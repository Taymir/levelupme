<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of operator_messages
 *
 * @author U7
 */
class operator_messages extends MY_Controller {
    public function __construct()
    {
        parent::__construct(array('operator', 'admin'));
        $this->load->model('mailings_model');
        $this->load->model('operator_model');
    }
    
    public function add($selected_student = NULL)
    {   
        $this->load->model('classes_model');
        $this->load->model('user_profile_model');
        $this->load->model('tariffs_model');
        
        $tariff = 1;
        if($selected_student != NULL) {
            $tariff = 1;
            $selected_student_profile = $this->user_profile_model->get_user_profile($selected_student);
            $class = $this->operator_class($selected_student_profile->class_id);
        } elseif($this->input->post('updatetariff')) {
            $tariff = $this->input->post('tariff');
            $this->input->set_cookie('tariff', $tariff, 3 * 30 * 24 * 60 * 60);
            $class = $this->operator_class();
        } elseif ($this->input->cookie('tariff')) {
            $tariff = $this->input->cookie('tariff');
            $class = $this->operator_class();
        }
        
        $schools_classes = $this->classes_model->get_schools_and_classes($this->operator_model->get_operators_school_list());
        if(isset($class)) {
            $students = $this->user_profile_model->get_userlist_by_class($class->id, $tariff);
            $tariffs = $this->tariffs_model->get_tariffs_for_widget();

            $this->load_var('students', $students);
            $this->load_var('selected_student', $selected_student);

            $this->load_var('tariff_id',  $tariff);
            $this->load_var('tariffs',  $tariffs);
        }
        $this->load_var('schools_classes', $schools_classes);
        $this->load_scripts('mootools-core', 'mootools-more', 'schoolClassWidget');
        $this->load_scripts('Locale/Locale.ru-RU.MooEditable', 'MooEditable/MooEditable', 'MooEditable/MooEditable.UI.MenuList', 'MooEditable/MooEditable.Extras');
        $this->load_styles('MooEditable/MooEditable', 'MooEditable/MooEditable.Extras', 'MooEditable/MooEditable.SilkTheme');
        
        return $this->load_view('operator_messages/add_view', "Рассылки");
    }
    
    public function send()
    {
        
        $this->load->model('user_profile_model');
        $this->load->model('tariffs_model');
        
        if($this->input->post('submit'))
        {
            $mailed = 0;
            $email_title = trim($this->input->post('email_title'));
            $email_text = trim($this->input->post('email_text'));
            $sms_text = trim($this->input->post('sms_text'));
            $type = $this->input->post('recipient_type');
            $profile_id = (int)$this->input->post('user');
            $tariff = $this->input->post('tariff');
            $class_id = $this->input->post('class_id');
            $school_id = $this->input->post('school_id');
            
            if(empty($email_text) && empty($sms_text))
                return $this->show_message ("Ошибка: Не введено сообщение");
            
            if(!in_array($type, array('school','class','user')))
                return $this->show_message ("Ошибка: Не указан получатель");
            
            $data = array();
            $data['email_title'] = $email_title == '' ? NULL : $email_title;
            $data['email_text'] = (strip_tags($email_text) == '' ? NULL : "<h2>$email_title</h2> $email_text");
            $data['sms_text'] = $sms_text == '' ? NULL : $sms_text;
            
            if($type == 'user')
            {
                // Отправка одному пользователю
                $profile = $this->user_profile_model->get_user_profile($profile_id);
                
                if(!$profile->banned)
                {
                    $tariff = $profile->tariff;
                    $data['user_profile_id'] = $profile->id;

                    if(!$this->tariffs_model->rule_send_msg_to_email($tariff) || empty($profile->email))
                    {
                        $data['email_title'] = null;
                        $data['email_text'] = null;
                    }

                    if(!$this->tariffs_model->rule_send_msg_to_sms($tariff) || empty($profile->phone))
                    {
                        $data['sms_text'] = null;
                    }

                    if( isset($data['email_text']) || isset($data['sms_text']) )
                        $mailed = $this->mailings_model->add_single_mailing($data);
                }
            } else
            {
                if($type == 'class') 
                {
                    // получить список всех учеников в классе
                    $user_list = $this->user_profile_model->get_users_by_class_without_school($class_id, $tariff);
                    
                    $this->load->model('classes_model');
                    $recipient = $this->classes_model->get_class_info($class_id);
                    $recipient = $recipient->class;
                } else {
                    // получить список всех учеников в школе
                    $user_list = $this->user_profile_model->get_users_by_school($school_id, $tariff);
                    
                    $this->load->model('classes_model');
                    $recipient = $this->classes_model->get_class_info($class_id);
                    $recipient = $recipient->school;
                }
                
                $batch_data = array();
                foreach($user_list as  $user_row)
                {
                    if(!$user_row->banned)
                    {
                        $user_profile_id = $user_row->id;
                        $tmp_data = $data;
                        $tmp_data['user_profile_id'] = $user_profile_id;

                        if(!$this->tariffs_model->rule_send_msg_to_email($user_row->tariff) || empty($user_row->email))
                        {
                            $tmp_data['email_title'] = null;
                            $tmp_data['email_text']= null;
                        }

                        if(!$this->tariffs_model->rule_send_msg_to_sms($user_row->tariff) || empty($user_row->phone))
                        {
                            $tmp_data['sms_text'] = null;
                        }

                        if( isset($tmp_data['email_text']) || isset($tmp_data['sms_text']) )
                        {
                            $batch_data[] = $tmp_data;
                        }
                    }
                }
                
                if(sizeof($batch_data) > 0)
                    $mailed = $this->mailings_model->add_multi_mailing($type, $batch_data, $recipient);
            }
            
            if($mailed)
                return $this->show_message("Ваше сообщение отправлено. $mailed получателей.");
            else
                return $this->show_message ("Ни одного сообщения не отправлено. Проверьте, есть ли в выбранной школе и классе ученики с выбранным тарифом");
        } 
        $this->denyAccess();
    }
    
    public function index($offset = 0)
    {
        return $this->archive($offset);
    }
    
    public function archive($offset = 0)
    {
        $this->load->library('pagination');
        $this->load->model('classes_model');
        
        $mailings_type = array('analytic', 'grades', 'class', 'school', 'user');
        if($this->input->post('filters'))
        {
            $mailings_type = $this->input->post('filters');
            $this->input->set_cookie('filters', serialize($mailings_type), 60 * 60 * 24 * 3);
        } elseif ($this->input->cookie('filters')) {
            $mailings_type = unserialize($this->input->cookie('filters'));
        }
        
        $paginator['base_url'] = base_url() . 'operator_messages/archive';
        $paginator['per_page'] = 10;
        $paginator['num_links'] = 5;
        $paginator['first_link'] = "Первая";
        $paginator['last_link'] = "Последняя";
        
        $class = $this->operator_class();
        $schools_classes = $this->classes_model->get_schools_and_classes($this->operator_model->get_operators_school_list());
        if(isset($class)) {
            $mailings = $this->mailings_model->get_all_mailings($class->id, $mailings_type, $paginator['per_page'], $offset);

            $paginator['total_rows'] = $this->mailings_model->total_mailings_found;
            $this->pagination->initialize($paginator);

            $this->load_var('filters', $mailings_type);
            $this->load_var('mailings', $mailings);
        }
        $this->load_var('schools_classes', $schools_classes);
        $this->load_scripts('mootools-core', 'mootools-more', 'schoolClassWidget');
        
        return $this->load_view('operator_messages/archive_view', "Архив рассылок");
    }
    
    public function view($mailing_id)
    {
        $data = $this->mailings_model->get_mailing($mailing_id);
        if(!isset($data))
            show_404();
            
        return $this->load_view('operator_messages/view_view', "Просмотр рассылки", $data);
    }
}