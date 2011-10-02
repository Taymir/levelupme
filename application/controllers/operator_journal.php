<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of operator_journal
 *
 * @author U7
 */
class operator_journal extends MY_Controller {
    public function __construct()
    {
        parent::__construct(array('operator', 'admin'));
        $this->load->model('grades_model');
    }
    
    public function index()
    {
        $time = time();
        if($this->input->post('updatedate'))
            $time = $this->strdate_2_timestamp ($this->input->post('date'));
        $date = date('d.m.Y', $time);
            
        $this->load->model('timetables_model');
        $this->load->model('classes_model');
        $this->load->model('user_profile_model');
        
        $class = $this->operator_class();
        $class_data = $this->classes_model->get_class_info($class);
        $schools_classes = $this->classes_model->get_schools_and_classes($this->user_profile_model->get_operators_school_list());
        $students = $this->user_profile_model->get_users_by_class($class);
        $subjects = $this->timetables_model->get_subjects_by_class_and_date($class, $date);
        
        $this->load_style('datepicker_vista/datepicker_vista');
        $this->load_scripts('mootools-core', 'mootools-more', 'datepicker/Locale.ru-RU.DatePicker',
        'datepicker/Picker', 'datepicker/Picker.Attach', 'datepicker/Picker.Date', 'showDialog', 'MUX.Dialog');
        $this->load_style('MUX.Dialog');
        $this->load_var('class', $class_data);
        $this->load_var('students', $students);
        $this->load_var('subjects', $subjects);
        $this->load_var('date', $date);
        $this->load_var('schools_classes', $schools_classes);
        
        return $this->load_view('operator_journal/index_view', "Журнал"); 
    }
    
    public function save()
    {
        if($this->input->post('submit'))
        {
            $date = date('Y-m-d', $this->strdate_2_timestamp($this->input->post('date')));
            
            $data = array();
            $data['students'] = $this->input->post('students');
            $data['subjects'] = $this->input->post('subjects');
            $data['grades'] = $this->input->post('grades');
            $data['comments'] = $this->input->post('comments');
            
            $this->grades_model->save_grades($date, $data);
            return $this->show_message('Оценки сохранены и отправлены.');
        }
        //$this->denyAccess();//@DEBUG
    }
    
    private function strdate_2_timestamp($strdate)
    {
        $strdate = str_replace(array(',-/'), '.', $strdate);
        return strtotime($strdate);
    }
}

?>
