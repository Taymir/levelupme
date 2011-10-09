<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of admin_users
 *
 * @author U7
 */
class admin_users extends MY_Controller {
    public function __construct()
    {
        parent::__construct('admin');
        
        $this->load->model('user_profile_model');
    }
    
    public function index()
    {
        $this->load->model('classes_model');
        $class = $this->operator_class();
        
        $data = $this->user_profile_model->get_users_by_class($class);
        $schools_classes = $this->classes_model->get_schools_and_classes();
        
        $this->load_scripts('mootools-core', 'schoolClassWidget');
        $this->load_var('users', $data);
        $this->load_var('schools_classes', $schools_classes);
        return $this->load_view('admin_users/list_view', "Пользователи");
    }
    
    public function add_user()
    {
        $this->load->library('form_validation');
        
        if($this->form_validation->run())
        {
            $data = $this->get_post_params('username', 'password', 'name', 'class_id', 'tariff', 'phone', 'email');
            $data['phone'] = $this->clean_phone_number($data['phone']);
            $data['class_id'] = (int)$data['class_id'];
            
            $this->user_profile_model->add_user_profile($data);
            return $this->redirect_message(array('admin_users', '?class=' . $data['class_id']), "Пользователь добавлен");
        }
        $this->load->model('classes_model');
        $this->load->model('tariffs_model');
        $schools_classes = $this->classes_model->get_schools_and_classes();
        $tariffs = $this->tariffs_model->get_tariffs_for_selector();
        
        $this->load_scripts('mootools-core', 'mootools-more', 'MUX.Dialog', 'showDialog');
        $this->load_style('MUX.Dialog');
        $this->load_var('schools_classes', $schools_classes);
        $this->load_var('tariffs', $tariffs);
        return $this->load_view('admin_users/add_user_view', "Добавление пользователя");
    }
    
    public function ban_user($profile_id)
    {
        $this->user_profile_model->ban_user($profile_id);
        
        return $this->redirect_message('admin_users', "Пользователь заблокирован");
    }
    
    public function unban_user($profile_id)
    {
        $this->user_profile_model->unban_user($profile_id);
        
        return $this->redirect_message('admin_users', "Пользователь разблокирован");
    }
    
    public function remove_user($profile_id)
    {
        $this->user_profile_model->delete_user($profile_id);
        
        return $this->redirect_message('admin_users', "Пользователь удален");
    }
    
    private function clean_phone_number($phone)
    {
        $unwanted_symbols = array('(', ')', '+', '-', ' ', '.' );
        
        $phone = str_replace($unwanted_symbols, '', $phone);
        if(substr($phone, 0, 1) == '8')
        {
            $phone = substr_replace($phone, '7', 0, 1);
        } elseif(substr($phone, 0, 1) == '9') 
        {
            $phone = '7' . $phone;
        } elseif(substr($phone, 0, 1) != '7')
        {
            return NULL;
        }
        
        if(strlen($phone) == 11 && ctype_digit($phone))
            return $phone;
        else
            return NULL;
    }
    
    public function valid_phone($phone)
    {
        if($phone == '' || $this->clean_phone_number($phone))
            return TRUE;
        else
        {
            $this->form_validation->set_message('valid_phone', "В поле %s должен быть введен корретный телефонный номер сотового телефона");
            return FALSE;
        }
    }
    
}