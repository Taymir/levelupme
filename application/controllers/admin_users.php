<?php

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
        $data = $this->user_profile_model->get_users_by_class(1);//@TMP: номер класса должен браться из виджета  выбора школы и класса
        
        $this->load_var('users', $data);
        return $this->load_view('admin_users/list_view', "Пользователи");
    }
    
    public function add_user()
    {
        $this->load->library('form_validation');
        
        if($this->form_validation->run())
        {
            $data = $this->get_post_params('username', 'password', 'name', 'class_id', 'acc_type', 'phone', 'email');
            $data['phone'] = $this->clean_phone_number($data['phone']);
            
            $this->user_profile_model->add_user_profile($data);
            return $this->redirect_message('admin_users', "Пользователь добавлен");
        }
        
        return $this->load_view('admin_users/add_user_view', "Добавление пользователя");
    }
    
    public function ban_user($id)
    {
        $this->load->model('tank_auth/users');
        
        $this->users->ban_user($id);
        
        return $this->redirect_message('admin_users', "Пользователь заблокирован");
    }
    
    public function unban_user($id)
    {
        $this->load->model('tank_auth/users');
        
        $this->users->unban_user($id);
        
        return $this->redirect_message('admin_users', "Пользователь разблокирован");
    }
    
    public function remove_user($id)
    {
        $this->load->model('tank_auth/users');
        
        $this->users->delete_user($id);
        
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
            return FALSE;
        }
        
        if(strlen($phone) == 11 && ctype_digit($phone))
            return $phone;
        else
            return FALSE;
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