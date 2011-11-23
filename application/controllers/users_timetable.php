<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of users_timetable
 *
 * @author U7
 */
class users_timetable extends MY_Controller {
    public function __construct()
    {
        parent::__construct();
        $this->load->model('timetables_model');
        $this->load->model('classes_model');
    }
    
    public function index()
    {//@TODO: Class info!
        $class_id = $this->user_profile_model->getProperty('class_id');
        $data = $this->timetables_model->get_timetable_by_class($class_id);
        $this->load_var('timetable', $data);
        
        return $this->load_view('users_timetable/index_view', "Расписание"); 
    }
}