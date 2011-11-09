<?php //if (!defined('CRON')) exit('No direct script access allowed');

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of cron
 *
 * @author U7
 */
class cron extends CI_Controller {
    public function __construct() {
        parent::__construct();
        
        $this->output->enable_profiler(TRUE);//@TMP
    }
    
    public function send_mailings()
    {
        //@TODO: возможно добавить прекращение попыток отправки sms, если несколько сообщений подряд не отправляются?
        $this->load->model('mailings_model');
        $this->load->model('user_profile_model');
        
        $this->load->library('email');

        // получить список email-сообщений
        $email_mailings = $this->mailings_model->get_email_queue();
        
        if(sizeof($email_mailings) > 0)
        {
            echo 'Найдено: ' . sizeof($email_mailings) . " новых email рассылок\n";
            $success_mailings = 0;
            foreach($email_mailings as $mailing)
            {
                // для каждого сообщения:
                $recipient = $this->user_profile_model->get_user_profile($mailing->user_profile_id);
                if(isset($recipient) && $recipient->email != '')
                {
                    $this->email->set_wordwrap(false);
                    $this->email->from($this->config->item('webmaster_email', 'tank_auth'), $this->config->item('website_name', 'tank_auth'));
                    $this->email->reply_to($this->config->item('webmaster_email', 'tank_auth'), $this->config->item('website_name', 'tank_auth'));
                    $this->email->to($recipient->email);
                    $this->email->subject($mailing->email_title);
                    $this->email->message($this->load->view('email/mailing-html', array('title' => $mailing->email_title, 'text' => $mailing->email_text), TRUE));
                    $this->email->set_alt_message($this->load->view('email/mailing-txt', array('title' => $mailing->email_title, 'text' => $mailing->email_text), TRUE));
                    // Попытаться отправить
                    if($this->email->send())
                    {
                        // Сохранить результат в бд
                        $this->mailings_model->mark_email_sent($mailing->id);
                        $success_mailings++;
                    } else {
                        // Сохранить результат в бд
                        $this->mailings_model->mark_email_sent($mailing->id, 'tmp_error');
                    }
                } else {
                    // Сохранить результат в бд
                    $this->mailings_model->mark_email_sent($mailing->id, 'pm_error');
                }
            }
            echo 'Успешно отправлено: ' . $success_mailings . " email рассылок\n";
        }
        
        $this->load->library('sms');
        if(!$this->sms->is_at_night_period()) {
            // получить список смс-сообщений
            $sms_mailings = $this->mailings_model->get_sms_queue();

            if(sizeof($sms_mailings) > 0)
            {
                echo 'Найдено: ' . sizeof($sms_mailings) . " новых sms рассылок\n";
                $success_mailings = 0;
                foreach($sms_mailings as $mailing)
                {
                    // для каждого сообщения:
                    $recipient = $this->user_profile_model->get_user_profile($mailing->user_profile_id);
                    if(isset($recipient) && $recipient->phone != '')
                    {
                        $this->sms->to($recipient->phone);
                        $this->sms->text($mailing->sms_text);
                        // Попытаться отправить
                        if($this->sms->send())
                        {
                            // Сохранить результат в бд
                            $this->mailings_model->mark_sms_sent($mailing->id);
                            $success_mailings++;
                        } else {
                            // Сохранить результат в бд
                            $this->mailings_model->mark_sms_sent($mailing->id, 'tmp_error');
                        }
                    } else {
                        // Сохранить результат в бд
                        $this->mailings_model->mark_sms_sent($mailing->id, 'pm_error');
                    }
                }
                echo 'Успешно отправлено: ' . $success_mailings . " sms рассылок\n";
            }
        }

        // Пометить оставшиеся рассылки старыми
        $old_mailings = $this->mailings_model->mark_old_email_errored();
        if($old_mailings > 0)
            echo 'Удалено: ' . $old_mailings . " старых email рассылок\n";
                
        $old_mailings = $this->mailings_model->mark_old_sms_errored();
        if($old_mailings > 0)
            echo 'Удалено: ' . $old_mailings . " старых sms рассылок\n";
    }
    
    private function is_required_subject($subject)
    {
         $subject = mb_strtolower(trim($subject));
         $required_subjects = $this->config->item('required_subjects');
         
         
         foreach($required_subjects as $subject_name => $variations)
         {
             if($subject_name == $subject || in_array($subject, $variations))
                     return $subject_name;
         }
         return FALSE;
    }
    
    public function render_statistics($classes_num = 1)
    {
        set_time_limit(0); //@DEBUG
        define('PCHART_DIRECTORY', 'application/third_party/pchart/');
        include(PCHART_DIRECTORY . 'class/pData.class.php');
        include(PCHART_DIRECTORY . 'class/pDraw.class.php');
        include(PCHART_DIRECTORY . 'class/pPie.class.php');
        include(PCHART_DIRECTORY . 'class/pImage.class.php');
        
        $this->load->model('statistics_model');
        $this->load->helper('common_helper');
        
        $this->statistics_model->import_data($classes_num);
        //print_r($this->data);
        while($class = $this->statistics_model->get_next_class())
        {
            var_dump($class);//@TMP
            while($user = $this->statistics_model->get_next_user($class['id']))
            {
                echo "<b>{$user['user']->name}</b><br/>";//@TMP
                
                foreach($user['subjects'] as $subject)
                {
                    echo "$subject: <br/>"; //@TMP
                    $subject_path = sanitize(rus2translit($subject), true, true);
                    
                    /* 1. ATTENDANCE */
                    //echo " $N \ $B  \ $P <br/>";
                    /*extract($this->statistics_model->get_user_attendance($user['id'], $subject));
                    $T = $N + $B + $P;
                    $N = round(100 * $N / $T);
                    $B = round(100 * $B / $T);
                    $P = round(100 * $P / $T);
                    
                    $MyData = new pData();   
                    $MyData->addPoints(array($P, $N, $B),"ScoreA");  
                    $MyData->setSerieDescription("ScoreA","Application A");
                    $MyData->loadPalette("palettes/green.color", TRUE);
                    
                    $MyData->addPoints(array("Присутствовал","Отсутствовал","Болел"),"Labels");
                    $MyData->setAbscissa("Labels");
                    
                     $myPicture = new pImage(700,700,$MyData,TRUE);
                     $myPicture->DrawFromPNG(0,0, PCHART_DIRECTORY . 'back.png');
                     $myPicture->setFontProperties(array("FontName"=>"C:/Windows/Fonts/arial.ttf","FontSize"=>16));
                     $PieChart = new pPie($myPicture,$MyData);
                     $PieChart->draw3DPie(320,300,array("WriteValues"=>TRUE,"DataGapAngle"=>20,
 "DataGapRadius"=>12,"Border"=>TRUE, 'Radius'=> 200, 'SkewFactor' => 0.7, 'SecondPass' => true, 'SliceHeight' => 30
 ));
                      $myPicture->setFontProperties(array("FontName"=>"C:/Windows/Fonts/arial.ttf","FontSize"=>12));
                      $PieChart->drawPieLegend(300,500,array("Style"=>LEGEND_ROUND ,"Mode"=>LEGEND_VERTICAL , 'R'=> 240, 'G' => 247, 'B' => 241, 'Margin' => 10, 'BorderR' => 224, 'BorderG' => 235, 'BorderB' => 241
 ));
                      
                      
                      $path = "charts/att/{$class['school']->id}/{$class['id']}/$subject_path/";
                      if(!is_dir($path))
                          mkdir ($path, 0777, true);
                      $myPicture->Render("$path{$user['id']}.png");*/
                      
                      /* 2. GRADES */
                      //foreach($this->statistics_model->get_user_grades($user['id'], $subject) as $date => $grade)
                      //      echo "$date: $grade<br/>";
                      $MyData = new pData();
                      $MyData->setAxisName(0,"Оценки");
                      $MyData->setAxisName(1,"Даты");  
                      foreach($this->statistics_model->get_user_grades($user['id'], $subject) as $date => $grade)
                      {
                          $day = (int)date('j', strtotime($date));
                          $grade = round($grade, 1);
                          if($grade == null)
                          {
                              $MyData->addPoints(VOID, "grades");
                              $MyData->addPoints($day, "dates");
                          } else {
                              $MyData->addPoints($grade, "grades");
                              $MyData->addPoints($day, "dates");
                          }
                      }
                      $MyData->setSerieDescription("dates","Дни месяца....");
                      $MyData->setAbscissa("dates");
                      $MyData->loadPalette("palettes/green.color", TRUE);
                      $MyData->setAbscissa("Дата");
                      $myPicture = new pImage(700,700,$MyData,TRUE);
                      $myPicture->DrawFromPNG(0,0, PCHART_DIRECTORY . 'back.png');
                      $myPicture->setFontProperties(array("FontName"=>"C:/Windows/Fonts/arial.ttf","FontSize"=>12));
                      $myPicture->setGraphArea(70,150,600,450); 
                      $myPicture->drawText(350,120,"Оценки за октябрь",array("FontSize"=>20,"Align"=>TEXT_ALIGN_BOTTOMMIDDLE));
                      $AxisBoundaries = array(0=>array("Min"=>1,"Max"=>5));
                      $ScaleSettings = array("XMargin"=>5,"YMargin"=>5,"Mode"=>SCALE_MODE_MANUAL,"ManualScale"=>$AxisBoundaries,"DrawSubTicks"=>FALSE,"AxisR"=>99,"AxisG"=>99,"AxisB"=>99,"CycleBackground"=> TRUE, "DrawArrows" => TRUE, "MinDivHeight" => 40, "LabelSkip" => 0);
                      $myPicture->drawScale($ScaleSettings); 
                      $myPicture->setShadow(TRUE,array("X"=>1,"Y"=>1,"R"=>0,"G"=>0,"B"=>0,"Alpha"=>20));
                      $myPicture->drawBarChart(array('DisplayValues' => TRUE, 'DisplayOffset' => -25, 'DisplayShadow' => TRUE, 'Rounded' => TRUE));
                      //$myPicture->drawFilledSplineChart(array("DisplayValues"=>TRUE, 'DisplayOffset' => -25));
                      //$myPicture->drawSplineChart(array( 'BreakVoid' => FALSE));
                      $myPicture->setShadow(FALSE);
                      $myPicture->setFontProperties(array("FontName"=>"C:/Windows/Fonts/arial.ttf","FontSize"=>16));
                      
                      $path = "charts/gra/{$class['school']->id}/{$class['id']}/$subject_path/";
                      if(!is_dir($path))
                          mkdir ($path, 0777, true);
                      $myPicture->Render("$path{$user['id']}.png");
                      /* 3. AVERAGES */
                      //echo 
                      //      round($this->statistics_model->get_user_average($user['id'], $subject), 1) . ' / ' .
                      //      round($this->statistics_model->get_class_average($class['id'], $subject), 1) . ' / ' . 
                      //      round($this->statistics_model->get_parallel_average($class['parallel'], $subject), 1) . " <br/>";
                      /*$u_avg = round($this->statistics_model->get_user_average($user['id'], $subject), 1);
                      $c_avg = round($this->statistics_model->get_class_average($class['id'], $subject), 1);
                      $p_avg = round($this->statistics_model->get_parallel_average($class['parallel'], $subject), 1);
                      
                      $MyData = new pData();   
                      $MyData->addPoints(array($u_avg, $c_avg, $p_avg), "avg");
                      $MyData->addPoints(array("Средний бал","Средний бал класса","Средний бал паралели"),"par"); 
                      $MyData->setAbscissa("par");
                      $MyData->loadPalette("palettes/green.color", TRUE);
                      $MyData->setAbscissa("Дата");
                      $myPicture = new pImage(700,700,$MyData,TRUE);
                      $myPicture->DrawFromPNG(0,0, PCHART_DIRECTORY . 'back.png');
                      $myPicture->setFontProperties(array("FontName"=>"C:/Windows/Fonts/arial.ttf","FontSize"=>12));
                      $myPicture->setGraphArea(200,200,650,450); 
                      $myPicture->drawText(350,120,"Средний бал",array("FontSize"=>20,"Align"=>TEXT_ALIGN_BOTTOMMIDDLE));
                      $AxisBoundaries = array(0=>array("Min"=>1,"Max"=>5));
                      $myPicture->drawScale(array("CycleBackground"=>TRUE,"DrawSubTicks"=>TRUE,"GridR"=>0,"GridG"=>0,"GridB"=>0,"GridAlpha"=>10,"Pos"=>SCALE_POS_TOPBOTTOM, 'MinDivHeight' => 50, "Mode"=>SCALE_MODE_MANUAL,"ManualScale"=>$AxisBoundaries)); 
                      $Palette = array("0"=>array("R"=>146,"G"=>200,"B"=>0,"Alpha"=>100),
                         "1"=>array("R"=>200,"G"=>50,"B"=>0,"Alpha"=>100),
                         "2"=>array("R"=>0,"G"=>75,"B"=>153,"Alpha"=>100),
                         "3"=>array("R"=>235,"G"=>171,"B"=>0,"Alpha"=>100),
                         "4"=>array("R"=>188,"G"=>0,"B"=>107,"Alpha"=>100));
                      $myPicture->drawBarChart(array("DisplayPos"=>LABEL_POS_INSIDE, "DisplayValues"=>TRUE, "DisplayR"=>255, "DisplayG"=>255, "DisplayB"=>255, "Rounded"=>TRUE,"Surrounding"=>30,"OverrideColors"=>$Palette)); 
                      
                      $path = "charts/avg/{$class['school']->id}/{$class['id']}/$subject_path/";
                      if(!is_dir($path))
                          mkdir ($path, 0777, true);
                      $myPicture->Render("$path{$user['id']}.png");*/
                }
                
            }
        }
        echo 'Память: ' . memory_get_peak_usage(true);
        
    }
    
    public function generate_statistics($schools = '*', $last_days = 14)//@DEBUG /* '*' */
    {
        set_time_limit(0); //@DEBUG
        
        $this->load->model('user_profile_model');
        $this->load->model('classes_model');
        $this->load->model('grades_model');
        $this->load->model('statistics_model');
        $this->config->load('statistics');
        
        $max_subjects =      $this->config->item('max_subjects');

        $date_end   = date('Y-m-d', time());
        $date_start = date('Y-m-d', time() - 60*60*24*$last_days);
        
        $schools_classes = $this->classes_model->get_schools_and_classes($schools, true);
        
        foreach($schools_classes as $school)
        {
            // Для каждой школы
            for($class_key = 0; $class_key < sizeof($school->classes); $class_key++)
            {
                // Для каждого класса
                // Получение исходных данных из БД
                $class    = $school->classes[$class_key];
                $class_id = $class->id;
                
                $users = $this->user_profile_model->get_users_by_class_without_school($class_id);//@TODO: min-tariff!!
                if(sizeof($users) < 1)
                    continue; // Пропускаем пустые классы
                
                $user_profile_ids = user_profile_model::extract_ids_from_students($users);
                $grades_raw = $this->grades_model->get_grades($date_start, $date_end, $user_profile_ids);
                
                $grades = array();
                $subjects = array();
                // Начальное заполнение матрицы оценок
                foreach($grades_raw as $grade_row)
                {
                    $subject = $this->is_required_subject($grade_row->subject);
                    
                    if($subject !== FALSE)
                    {
                        $known_subject = in_array($subject, $subjects);
                        if(!$known_subject && sizeof($subjects) <= $max_subjects)
                        {
                            $subjects[] = $subject;
                            $grades[$grade_row->date][$subject][$grade_row->user_profile_id] =
                                $grade_row->grade;
                        } elseif($known_subject) {
                            $grades[$grade_row->date][$subject][$grade_row->user_profile_id] =
                                $grade_row->grade;
                        }
                        
                    }
                }
                
                // Анализируем загруженные данные
                if(sizeof($grades) > 0)
                {
                    //Если есть оценки
                    $this->statistics_model->set_current_school($school);
                    $this->statistics_model->set_current_class($class);

                    foreach($grades as $date=>$val)
                    {
                        // Для каждой даты
                        foreach($subjects as $subject)
                        {
                            // Для каждого предмета
                            foreach($users as $user)
                            {
                                // Для каждого студента
                                $user_profile_id = $user->profile_id;

                                if(isset($grades[$date][$subject][$user_profile_id])) {
                                    // Если поле с оценкой заполнено
                                    $grade = $grades[$date][$subject][$user_profile_id];

                                    // Распарсить grade на составляющие
                                    str_replace(array(',',';',':'), ' ', $grade);
                                    $sub_grades = explode(' ', $grade);

                                    $sum_grades = 0.0;
                                    $num_grades = 0;
                                    foreach($sub_grades as $sub_grade)
                                    {
                                        // Для каждой части оценки
                                        $sub_grade = mb_strtolower(trim($sub_grade));
                                        if($sub_grade == 'н')
                                        {
                                            // Ученик отсутствовал
                                            $this->statistics_model->set_user_n($date, $subject, $user);
                                        } elseif ($sub_grade == 'б')
                                        {
                                            // Ученик болел
                                            $this->statistics_model->set_user_b($date, $subject, $user);
                                        } elseif ((int)$sub_grade > 0) {
                                            // Ученик получил оценку (оценки)
                                            $sum_grades += (int)$sub_grade;
                                            $num_grades ++;
                                        }
                                    }
                                    if($num_grades)
                                        $this->statistics_model->set_user_grade($date, $subject, $user, $sum_grades / $num_grades);
                                } else {
                                    // Если поле с оценкой пусто
                                    $this->statistics_model->set_user_nograde($date, $subject, $user);
                                } // Конец: Если поле с оценкой пусто
                            } // Конец: Для каждого студента
                        } //Конец: Для каждого предмета
                    } //Конец: Для каждой даты
                } // Конец: Если есть оценки
            } //Конец: Для каждого класса
        } // Конец: Для каждой школы
        
        /////////////////////////////////////////////////
        $this->statistics_model->serialize_data();

    }
}

