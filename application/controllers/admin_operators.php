<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of admin_operators
 *
 * @author U7
 */
class admin_operators extends MY_Controller {
    public function __construct()
    {
        parent::__construct('admin');
        
        $this->load->model('user_profile_model');
    }
    
    public function index()
    {
        $this->load->model('schools_model');
        $schools = $this->schools_model->get_schools();
        $data = $this->user_profile_model->get_operators(true);
        
        $this->load_scripts('mootools-core', 'mootools-more', 'MUX.Dialog', 'showDialog');
        $this->load_style('MUX.Dialog');
        $this->load_var('schools', $schools);
        $this->load_var('operators', $data);
        return $this->load_view('admin_operators/list_view', "Операторы");
    }
    
    public function add_operator()
    {
        $this->load->library('form_validation');
        
        if($this->form_validation->run())
        {
            $data = $this->get_post_params('username', 'password', 'name', 'email', 'admin', 'schools');
            if(
                (is_array($data['schools']) && in_array('*', $data['schools'])) ||
                (is_string($data['schools']) && '*' == $data['schools'])
              )
            {
                $data['role'] = 'admin';
                unset($data['schools']);
            } else
            {
                $data['role'] = 'operator';
            }
            
            $this->user_profile_model->add_operator_profile($data);
            return $this->redirect_message('admin_operators', "Оператор добавлен");
        }
        $this->load->model('schools_model');
        $schools = $this->schools_model->get_schools();
        
        $this->load_scripts('mootools-core', 'mootools-more', 'MUX.Dialog', 'showDialog');
        $this->load_style('MUX.Dialog');
        $this->load_var('schools', $schools);
        return $this->load_view('admin_operators/add_operator', "Добавление оператора");
    }
    
    public function remove_operator($id)
    {
        $this->load->model('tank_auth/users');
        
        $this->users->delete_user($id);
        
        return $this->redirect_message('admin_operators', "Оператора удален");
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
}

?>
