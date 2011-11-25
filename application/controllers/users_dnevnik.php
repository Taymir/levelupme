<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of users_dnevnik
 *
 * @author U7
 */
class users_dnevnik extends MY_Controller {
    public function __construct()
    {
        parent::__construct();
        $this->load->model('grades_model');
        $this->load->model('classes_model');
    }
    
    public function index($week = null, $year = null)
    {
        $class_id = $this->user_profile_model->getProperty('class_id');
        $class = $this->classes_model->get_class_info($class_id);
        
        if(isset($class))
        {
            if(is_null($week))
                $week = date('W');
            if(is_null($year))
                $year = date('Y');
            $data = $this->grades_model->get_grades_by_week($this->user_profile_model->getProfileId(), $week, $year);
            $this->load_var('class', $class);
            $this->load_var('grades', $data);

            return $this->load_view('users_dnevnik/index_view', "Дневник"); 
        } else
        {
            $this->denyAccess();
        }
    }
}