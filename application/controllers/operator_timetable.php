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
        $class_data = $this->classes_model->get_class(1); //@TMP
        $data = $this->timetables_model->get_timetable_by_class(1); //@TMP
        
        $this->load_scripts('mootools-core', 'mootools-more', 'Meio.Autocomplete');
        $this->load_style('autocomplete');
        $this->load_var('timetable', $data);
        $this->load_var('class', $class_data);
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