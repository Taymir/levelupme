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
        $this->load->helper('common_helper');
        $add_empty_subjects = $this->config->item('dnevnik_empty_subjs_show');
        $class_id = $this->user_profile_model->getProperty('class_id');
        $class = $this->classes_model->get_class_info($class_id);
        
        if(isset($class))
        {
            $cur_week = date('W');
            $cur_year = date('Y');
            if(is_null($week))
                $week = $cur_week;
            if(is_null($year))
                $year = $cur_year;
                
            $data = $this->grades_model->get_grades_by_week($this->user_profile_model->getProfileId(), $week, $year);
            
            // Добавление пустых предметов
            if($add_empty_subjects && $year == $cur_year && ($cur_week - $week) <= $this->config->item('dnevnik_empty_subjs_weeks'))
            {
                $this->load->model('timetables_model');
                $timetable = $this->timetables_model->get_timetable_by_class($class->id);
                foreach($data as $date => $val)
                {
                    for($num = 1; $num <= $this->config->item('max_lessons'); ++$num)
                    {
                        $day = date2day($date);
                        if( !isset($data[$date][$num]) &&
                             isset($timetable->timetable[$num][$day])
                           )
                            
                            $data[$date][$num]['subject'] = $timetable->timetable[$num][$day];
                    }
                    ksort($data[$date]);
                }
            }
            
            $this->load_var('class', $class);
            $this->load_var('grades', $data);

            return $this->load_view('users_dnevnik/index_view', "Дневник"); 
        } else
        {
            $this->denyAccess();
        }
    }
}