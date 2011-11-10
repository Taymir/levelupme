<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of pages
 *
 * @author U7
 */
class pages extends MY_Controller {
    public function __construct() {
        parent::__construct();
        
        $this->use_short_view(false);
        $this->load->model('pages_model');
    }
    
    public function index()
    {
        return $this->display(1, "home");//@HARDFIX
    }
    
    public function display($id, $url = null)
    {
        $data = $this->pages_model->get_page($id);
        
        if(!isset($data))
            return show_404 ();
        
        return $this->load_view('pages/display_view', $data->title, array('page' => $data, 'page_name' => $url));
    }
    
    public function registration()
    {
        $this->load->library('form_validation');
        
        if($this->form_validation->run('registration'))
        {
            //@TODO: Составление письма
            //@TODO: Отправка письма
            
            return $this->show_message("Ваша заявка отправлена на рассмотрение.");
        }
        
        return $this->load_view('pages/registration_view', "Подключение");
    }
    
    public function password_required($password)
    {
        if($this->input->post('username') != '' && $password == '')
        {
            $this->form_validation->set_message('password_required', "Введите пароль");
            return false;
        }
        return true;
    }
    
    public function tariff_required($var)
    {
        if(empty($var))
        {
            $this->form_validation->set_message('tariff_required', "Необходимо выбрать один из тарифных планов");
            return false;
        }
        return true;
    }
    
    public function agreement_required($var)
    {
        if(empty($var))
        {
            $this->form_validation->set_message('agreement_required', "Для регистрации, вам необходимо принять условия договора");
            return false;
        }
        return true;
    }
    
    public function username_available($username)
    {
        $this->load->model('tank_auth/users');
        
        if(!$this->users->is_username_available($username))
        {
            $this->form_validation->set_message('username_available', "Данный логин уже используется");
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