<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of operator_users
 *
 * @author U7
 */
class operator_users extends MY_Controller {
    public function __construct($roles_allowed = null) {
        parent::__construct(array('admin', 'operator'));
        
        $this->load->model('user_profile_model');
    }
    
    public function index()
    {
        
    }
    
    public function add_user()
    {
        
    }
    
    public function mass_add_users()
    {
        
    }
    
    public function ban_user($id)
    {
        
    }
    
    public function unban_user($id)
    {
        
    }
    
    public function remove_user($id)
    {
        
    }
    
    private function clean_phone_number($phone)
    {
        
    }
    
    public function valid_phone($phone)
    {
        
    }
}

?>
