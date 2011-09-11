<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Description of MY_Controller
 *
 * @author u7
 */
class MY_Controller extends CI_Controller {
    public function __construct($roles_allowed = null) {
        parent::__construct();

        if(ENVIRONMENT == 'development')
            $this->output->enable_profiler(TRUE);
        
        $this->authenticate();
        $this->allowAccessFor($roles_allowed);
    }
    
    private function authenticate()
    {
        $this->load->helper(array('form', 'url'));
        $this->load->library('form_validation');
        $this->load->library('security');
        $this->load->library('tank_auth');
        $this->lang->load('tank_auth');
        $this->load->model('user_profile_model');
        
        $data['AUTH_FORM'] = true;
        
        if ($this->tank_auth->is_logged_in()) {									// logged in
                $data['user_id']	= $this->tank_auth->get_user_id();
                $data['username']	= $this->tank_auth->get_username();
                $data['name']               = $this->user_profile_model->getName();
                $data['role']               = $this->user_profile_model->getRole();
                $this->load->vars($data);
        }  else {
                $data['login_by_username'] = ($this->config->item('login_by_username', 'tank_auth') AND
                                $this->config->item('use_username', 'tank_auth'));
                $data['login_by_email'] = $this->config->item('login_by_email', 'tank_auth');
                $data['use_recaptcha'] = $this->config->item('use_recaptcha', 'tank_auth');
                $data['show_captcha'] = FALSE;
                if ($this->tank_auth->is_max_login_attempts_exceeded('')) {//@BUGFIX: Выводим капчу не по логину, а по айпи
                        $data['show_captcha'] = TRUE;
                        if ($data['use_recaptcha']) {
                                $data['recaptcha_html'] = $this->_create_recaptcha();
                        } else {
                                $data['captcha_html'] = $this->_create_captcha();
                        }
                }
                        
                $this->load->vars($data);
        }
        
        
        
    }
    
    /**
     * Create CAPTCHA image to verify user as a human
     *
     * @return	string
     */
    function _create_captcha()
    {
            $this->load->helper('captcha');

            $cap = create_captcha(array(
                    'img_path'		=> './'.$this->config->item('captcha_path', 'tank_auth'),
                    'img_url'		=> base_url().$this->config->item('captcha_path', 'tank_auth'),
                    'font_path'		=> './'.$this->config->item('captcha_fonts_path', 'tank_auth'),
                    'font_size'		=> $this->config->item('captcha_font_size', 'tank_auth'),
                    'img_width'		=> $this->config->item('captcha_width', 'tank_auth'),
                    'img_height'	=> $this->config->item('captcha_height', 'tank_auth'),
                    'show_grid'		=> $this->config->item('captcha_grid', 'tank_auth'),
                    'expiration'	=> $this->config->item('captcha_expire', 'tank_auth'),
            ));

            // Save captcha params in session
            $this->session->set_flashdata(array(
                            'captcha_word' => $cap['word'],
                            'captcha_time' => $cap['time'],
            ));

            return $cap['image'];
    }
    
    protected function allowAccessFor($roles = null)
    {
        if(is_null($roles)) {
            return true;
        } elseif(is_array($roles) && !in_array ($this->user_profile_model->getRole(), $roles) ) {
             $this->denyAccess();
             return false;
        } elseif ($this->user_profile_model->getRole() != $roles)
        {
            $this->denyAccess();
            return false;
        }
        
        return true;
                    
    }

    protected function denyAccess()
    {
        // редирект на главную страницу в связи с отсутствием нужных
        // полномочий у пользователя
        //@TODO: выводить сообщение?
        redirect('');
        exit();
    }

    /*protected function redirectToLogin()
    {
        redirect('/auth/login/');
        exit();
    }*/

    protected function redirectToMain()
    {
        redirect('');
        exit();
    }

    protected function isLoggedIn()
    {
        // Проверка на то, залогинен ли пользователь (имеет id?)
        return ($this->user_profile_model->getId() !== null);//@BUGFIX: если false происходит бесконечная переадресация
    }

    protected function show_message($message, $message_extra = '')
    {
        $this->_show_message($message, $message_extra);
        $this->load->view('layout');
    }


    protected function redirect_message($redirect, $message)//@TODO: Возможно, потом поменять на редирект через несколько секунд
    {
        redirect($redirect);
        show_message($message);
    }

    private function _show_message($message, $message_extra = '')
    {
        $message_title = '';

        $this->config->load('system_messages', TRUE);
        if($this->config->item($message, 'system_messages'))
        {
            $message = $this->config->item($message, 'system_messages');
            if(is_array($message))
            {
                $message_title = $message['title'];
                $message = $message['message'];
            }
        }

        $this->load->vars(array(
            'message_title' => $message_title,
            'message' => $message . ' ' . $message_extra,
            'page_title' => $message_title,
            'content_template' => 'message'
        ));
    }
    
    protected function get_empty_arr()
    {
        $arr = array();
        $arguments = func_get_args();
        
        foreach($arguments as $argument)
        {
            $arr[$argument] = '';
        }
        
        return $arr;
    }
    
    protected function get_post_params()
    {
        $arr = array();
        $arguments = func_get_args();
        
        foreach($arguments as $argument)
        {
            $arr[$argument] = $this->input->post($argument);
        }
        
        return $arr;
    }
    
    protected function load_var($var_name, $var_value)
    {
        $this->load->vars(array($var_name => $var_value));
    }

    protected function load_view($content_template, $page_title = '', $data = null)
    {
        $this->_load_view_vars($content_template, $page_title, $data);
        $this->load->view('layout');
    }

    private function _load_view_vars($content_template, $page_title, $data = null)
    {
        if(!is_null($data))
            $this->load->vars($data);
        $this->load->vars(array(
            'page_title' => $page_title,
            'page_template' => $content_template
        ));
    }
}