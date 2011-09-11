<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of MY_Auth_Controller
 *
 * @author U7
 */
class MY_Auth_Controller extends MY_Controller {
    public function __construct($logged_in_only = false) {
        parent::__construct();
        
        $this->authenticate();
    }
    
    private function authenticate()
    {
        $this->load->helper(array('form', 'url'));
        $this->load->library('form_validation');
        $this->load->library('security');
        $this->load->library('tank_auth');
        $this->lang->load('tank_auth');
        
        $data['AUTH_FORM'] = true;
        
        if ($this->tank_auth->is_logged_in()) {									// logged in
                $data['user_id']	= $this->tank_auth->get_user_id();
                $data['username']	= $this->tank_auth->get_username();
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
}

?>
