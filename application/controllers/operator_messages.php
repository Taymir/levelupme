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
    }
    
    public function add($selected_student = NULL)
    {   
        $this->load->model('classes_model');
        $this->load->model('user_profile_model');
        $this->load->model('tariffs_model');
        
        $tariff = 1;
        if($selected_student != NULL)
        {
            $tariff = 1;
            $selected_studennt_profile = $this->user_profile_model->get_user_profile($selected_student);
            $class = $this->operator_class($selected_studennt_profile->class_id);
        } elseif($this->input->post('updatetariff'))
        {
            $tariff = $this->input->post('tariff');
            $this->input->set_cookie('tariff', $tariff, 3 * 30 * 24 * 60 * 60);
            $class = $this->operator_class();
        } elseif ($this->input->cookie('tariff'))
        {
            $tariff = $this->input->cookie('tariff');
            $class = $this->operator_class();
        }
        
        $class_data = $this->classes_model->get_class_info($class);//@TODO: ЗАЧЕМ ПОВТОРНО ПОЛУЧАЕМ ДАННЫЙ О КЛАССЕ??
        $schools_classes = $this->classes_model->get_schools_and_classes($this->user_profile_model->get_operators_school_list());
        $students = $this->user_profile_model->get_userlist_by_class($class, $tariff);
        $tariffs = $this->tariffs_model->get_tariffs_for_widget();
        
        $this->load_scripts('mootools-core', 'mootools-more', 'schoolClassWidget');
        $this->load_var('class', $class_data);
        $this->load_var('students', $students);
        $this->load_var('selected_student', $selected_student);
        $this->load_var('schools_classes', $schools_classes);
        $this->load_var('tariff_id',  $tariff);
        $this->load_var('tariffs',  $tariffs);
        
        return $this->load_view('operator_messages/add_view', "Рассылки");
    }
    
    public function send()
    {
        
        $this->load->model('user_profile_model');
        $this->load->model('tariffs_model');
        
        if($this->input->post('submit'))
        {
            $mailed = 0;
            $title = trim($this->input->post('title'));
            $text = trim($this->input->post('text'));
            $type = $this->input->post('recipient_type');
            $profile_id = (int)$this->input->post('user');
            $tariff = $this->input->post('tariff');
            $class_id = $this->input->post('class_id');
            $school_id = $this->input->post('school_id');
            
            if($text == '')
                return $this->show_message ("Ошибка: Не введено сообщение");
            
            if(!in_array($type, array('school','class','user')))
                return $this->show_message ("Ошибка: Не указан получатель");
            
            $data = array();
            $data['email_title'] = $title;
            $data['email_text'] = $text;
            $data['sms_text'] = $text;//@TODO: В будущем дать возможность пользователю редактировать отдельно текст смс
            
            if($type == 'user')
            {
                // Отправка одному пользователю
                $profile = $this->user_profile_model->get_user_profile($profile_id);
                $tariff = $profile->tariff;
                $data['user_profile_id'] = $profile->id;
                
                if(!$this->tariffs_model->rule_send_email($tariff) || empty($profile->email))
                {
                    unset($data['email_title']);
                    unset($data['email_text']);
                }

                if(!$this->tariffs_model->rule_send_sms($tariff) || empty($profile->phone))
                {
                    unset($data['sms_text']);
                }
                
                if( isset($data['email_text']) || isset($data['sms_text']) )
                    $mailed = $this->mailings_model->add_single_mailing($data);
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
                    $user_profile_id = $user_row->id;
                    $tmp_data = $data;
                    $tmp_data['user_profile_id'] = $user_profile_id;
                    
                    if(!$this->tariffs_model->rule_send_email($user_row->tariff) || empty($user_row->email))
                    {
                        unset($tmp_data['email_title']);
                        unset($tmp_data['email_text']);
                    }
                    
                    if(!$this->tariffs_model->rule_send_sms($user_row->tariff) || empty($user_row->phone))
                    {
                        unset($tmp_data['sms_text']);
                    }
                    
                    if( isset($data['email_text']) || isset($data['sms_text']) )
                    {
                        $batch_data[] = $tmp_data;
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
        
        $mailings_type = '*';
        if($this->input->post('filters'))
        {
            $mailings_type = $this->input->post('filters');
            if(in_array('other', $mailings_type))
            {
                $mailings_type[] = 'analytic';
                $mailings_type[] = 'grades';
            }
            $this->input->set_cookie('filters', serialize($mailings_type), 60 * 60 * 24 * 3);
        } elseif ($this->input->cookie('filters')) {
            $mailings_type = unserialize($this->input->cookie('filters'));
            $_POST['filters'] = $mailings_type; // for view
        }
        
        $paginator['base_url'] = base_url() . 'operator_messages/archive';
        $paginator['per_page'] = 10;
        $paginator['num_links'] = 5;
        $paginator['first_link'] = "Первая";
        $paginator['last_link'] = "Последняя";
        
        $class = $this->operator_class();
        $class_data = $this->classes_model->get_class_info($class);
        $schools_classes = $this->classes_model->get_schools_and_classes($this->user_profile_model->get_operators_school_list());
        $mailings = $this->mailings_model->get_all_mailings($class_data->school_id, $class_data->class_id, $mailings_type, $paginator['per_page'], $offset);
        
        $paginator['total_rows'] = $this->mailings_model->total_mailings_found;
        $this->pagination->initialize($paginator);
        
        $this->load_scripts('mootools-core', 'mootools-more', 'schoolClassWidget');
        $this->load_var('mailings', $mailings);
        $this->load_var('class', $class_data);
        $this->load_var('schools_classes', $schools_classes);
        return $this->load_view('operator_messages/archive_view', "Архив рассылок");
    }
    
    public function view($mailing_id)
    {
        $data = $this->mailings_model->get_mailing($mailing_id);
        $data->text = $data->email_text;
        if(empty($data->text))
             $data->text = '<pre>' . $data->sms_text . '</pre>';
        
        return $this->load_view('operator_messages/view_view', "Просмотр рассылки", $data);
    }
}