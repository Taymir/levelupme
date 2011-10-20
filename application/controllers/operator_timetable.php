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
class operator_timetable extends MY_Controller {
    public function __construct()
    {
        parent::__construct(array('operator', 'admin'));
        $this->load->model('timetables_model');
        $this->load->model('classes_model');
    }
    
    public function index()
    {
        $class = $this->operator_class();
        $schools_classes = $this->classes_model->get_schools_and_classes($this->user_profile_model->get_operators_school_list());
        if(isset($class)) {
            $data = $this->timetables_model->get_timetable_by_class($class->id);

            $this->load_style('autocomplete');
            $this->load_var('timetable', $data);
        }
        $this->load_var('schools_classes', $schools_classes);
        $this->load_scripts('mootools-core', 'mootools-more', 'Meio.Autocomplete', 'addAutocompletion', 'schoolClassWidget');
        
        return $this->load_view('operator_timetable/index_view', "Расписание"); 
    }
    
    public function save()
    {
        if($this->input->post('submit'))
        {
            $class_id = $this->input->post('class_id');
            $class = array('description' => $this->input->post('class_description'));
            
            $id = $this->input->post('id');
            $description = $this->input->post('description');
            $subjects = $this->input->post('subject');

            $this->classes_model->save_class($class_id, $class);
            $this->timetables_model->save_timetable($id, $description, $subjects);
            
            return $this->redirect_message('operator_timetable', "Расписание сохранено");
        }
        $this->denyAccess();
    }
}