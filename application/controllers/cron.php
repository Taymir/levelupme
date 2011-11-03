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

