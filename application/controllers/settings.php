<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of settings
 *
 * @author U7
 */
class settings extends MY_Controller {
    public function __construct()
    {
        parent::__construct();
        
        $this->load->model('user_profile_model');
    }
    
    public function index()
    {
        $this->load->library('tank_auth');
        $this->load->model('tariffs_model');
        $this->load->library('form_validation');
        if($this->form_validation->run('settings'))
        {
            $continue_flag = true;
            $data['email'] = $this->input->post('email');
            $data['phone'] = $this->clean_phone_number($this->input->post('phone'));
            
            if($this->input->post('old_password') != '')
            {
                
                // Попытка смены пароля
                if(!$this->tank_auth->change_password(
                    $this->form_validation->set_value('old_password'),
                    $this->form_validation->set_value('new_password'))) {
                    
                    $continue_flag = false;
                    $errors_unformatted = $this->tank_auth->get_error_message();
					foreach ($errors_unformatted as $k => $v)	
                        $errors_formatted[$k] = $this->lang->line($v);
                    $this->load_var('password_errors', $errors_formatted);
                }
            }
            
            if($continue_flag)
            {
                // Сохраняем профиль
                $this->user_profile_model->save_user_profile($this->user_profile_model->getProfileId(), $data);
                
                return $this->show_message("Настройки сохранены.");
            }
        }
        
        $profile = $this->user_profile_model->get_user_profile($this->user_profile_model->getProfileId());
        $user = $this->user_profile_model->get_user($profile->user_id);
        $profile->tariff_name = $this->tariffs_model->get_tariff($profile->tariff);
        
        return $this->load_view('settings/index_view', "Настройки", array('profile' => $profile, 'user' => $user));
    }
    
    public function password_required($password)
    {
        if($this->input->post('change_password') != '' && $password == '')
        {
            $this->form_validation->set_message('password_required', "Введите новый пароль");
            return false;
        }
        return true;
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