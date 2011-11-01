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
    
    public function generate_statistics($schools = array(8), $last_days = 14)//@DEBUG /* '*' */
    {
        set_time_limit(0); //@DEBUG
        
        $this->load->model('user_profile_model');
        $this->load->model('classes_model');
        $this->load->model('grades_model');
        $this->config->load('statistics');
        
        $max_subjects =      $this->config->item('max_subjects');

        $date_end   = date('Y-m-d', time());
        $date_start = date('Y-m-d', time() - 60*60*24*$last_days);
        
        $schools_classes = $this->classes_model->get_schools_and_classes($schools, true);
        
        foreach($schools_classes as $school)
        {
            for($class_key = 0; $class_key < sizeof($school->classes); $class_key++)
            {
                $class_id = $school->classes[$class_key]->id;
                
                $users = $this->user_profile_model->get_userlist_by_class($class_id);
                if(sizeof($users) < 1)continue; // Чтобы не стопориться на пустых классах
                $user_profile_ids = array_keys($users);
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
                
                ////////////////////////
                $Ns = array();
                $Bs = array();
                $Ps = array();
                $grades_out = array();
                $sum_class = array();
                $num_class = array();
                $avg_class = array();
                
                foreach($grades as $date=>$val)
                {
                    foreach($subjects as $subject)
                    {
                        foreach($users as $user_profile_id => $name)
                        {
                            if(isset($grades[$date][$subject][$user_profile_id])) {
                                $grade = $grades[$date][$subject][$user_profile_id];

                                // Распарсить grade на составляющие
                                str_replace(array(',',';',':'), ' ', $grade);
                                $sub_grades = explode(' ', $grade);
                                $absent = false;
                                $grades_num = 0;
                                foreach($sub_grades as $sub_grade)
                                {
                                    $sub_grade = mb_strtolower(trim($sub_grade));
                                    if($sub_grade == 'н')
                                    {
                                        // Ученик отсутствовал
                                        $absent = true;
                                        if(!isset($Ns[$subject][$user_profile_id]))
                                            $Ns[$subject][$user_profile_id] = 0;
                                        $Ns[$subject][$user_profile_id]++;
                                    } elseif ($sub_grade == 'б')
                                    {
                                        // Ученик болел
                                        $absent = true;
                                        if(!isset($Bs[$subject][$user_profile_id]))
                                            $Bs[$subject][$user_profile_id] = 0;
                                        $Bs[$subject][$user_profile_id]++;
                                    } elseif ((int)$sub_grade > 0) {
                                        // Ученик получил оценку (оценки)
                                        $grades_num++;
                                        if(!isset($grades_out[$date][$subject][$user_profile_id]))
                                            $grades_out[$date][$subject][$user_profile_id] = 0;
                                        $grades_out[$date][$subject][$user_profile_id] += (int)$sub_grade;
                                    }
                                }
                                // Усреднить оценки
                                if($grades_num > 0)
                                    $grades_out[$date][$subject][$user_profile_id] /= $grades_num;

                                // Ученик присутствовал
                                if(!isset($Ps[$subject][$user_profile_id]))
                                    $Ps[$subject][$user_profile_id] = 0;
                                if(!$absent)
                                    $Ps[$subject][$user_profile_id]++;
                            } else {
                                $grade = '';
                                $grades_out[$date][$subject][$user_profile_id] = '';
                                    //@TODO: avg class, avg parallel //@TODO: export??? //@TODO:???????????
                                // Оценки нет, ученик присутствовал
                                if(!isset($Ps[$subject][$user_profile_id]))
                                    $Ps[$subject][$user_profile_id] = 0;
                                $Ps[$subject][$user_profile_id]++;
                            }
                            if(!isset($sum_class[$date][$subject]))$sum_class[$date][$subject] = 0;
                            $sum_class[$date][$subject] += (int)isset($grades_out[$date][$subject][$user_profile_id]) ? $grades_out[$date][$subject][$user_profile_id] : 0;
                            if(!isset($num_class[$date][$subject]))$num_class[$date][$subject] = 0;
                            if(isset($grades_out[$date][$subject][$user_profile_id]) && $grades_out[$date][$subject][$user_profile_id] > 0)
                                $num_class[$date][$subject]++;
                            $avg_class[$date][$subject] = $sum_class[$date][$subject] / $num_class[$date][$subject];//@TMP
                        }
                    }
                }
                var_dump($avg_class);
            }
        }
        
/////////////////////////////////////////////////
        /*
        // Пример вывода пропусков
        $subject = "английский язык";
        $upid = 109;
        foreach($grades_out as $date=>$val)
        {
            if(isset($grades_out[$date][$subject][$upid]))
            {
                $grade = $grades_out[$date][$subject][$upid];
            } else {
                $grade = "&nbsp;";
            }
            echo $grade . ' ';
        }*/
    }
}

