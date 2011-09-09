<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Description of MY_Controller
 *
 * @author u7
 */
class MY_Controller extends CI_Controller {
    public function __construct($logged_in_only = false) {
        parent::__construct();

        /*$this->load->model('users_model');
        $this->users_model->loadUserData();

        if($logged_in_only && !$this->isLoggedIn())
                $this->redirectToLogin();

        $this->load->vars(array(
            'username' => $this->users_model->getUsername(),
        ));*/

        if(ENVIRONMENT == 'development')
        $this->output->enable_profiler(TRUE);
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

    /*protected function isLoggedIn()
    {
        // Проверка на то, залогинен ли пользователь (имеет id?)
        return ($this->users_model->getId() !== null);//@BUGFIX: если false происходит бесконечная переадресация
    }*/

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