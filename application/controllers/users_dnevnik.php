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
            if(empty($week))
                $week = $cur_week;
            if(empty($year))
                $year = $cur_year;

            $next_week = $week + 1;
            $next_year = $year;
            if($next_week > max_weeks_in_year($year))
            {
                $next_year++;
                $next_week = 1;
            }
            
            $prev_week = $week - 1;
            $prev_year = $year;
            if($prev_week <= 0)
            {
                $prev_year--;
                $prev_week = max_weeks_in_year($prev_year);
            }
            
            $data = $this->grades_model->get_grades_by_week($this->user_profile_model->getProfileId(), $week, $year);
            $start_date = $data['start_date'];
            $end_date   = $data['end_date'];
            $vars = array(
                'start_date' => $start_date, 'end_date' => $end_date,
                'week' => $week, 'year' => $year,
                'next_week' => $next_week, 'next_year' => ($next_year == $cur_year) ? null : $next_year,
                'prev_week' => $prev_week, 'prev_year' => ($prev_year == $cur_year) ? null : $prev_year
                );
            $this->load->vars($vars);
            $data = $data['grades'];
            
            // Добавление пустых предметов
            if($add_empty_subjects && $year == $cur_year && ($cur_week - $week) <= $this->config->item('dnevnik_empty_subjs_weeks'))
            {
                $this->load->model('timetables_model');
                $timetable = $this->timetables_model->get_timetable_by_class($class->id);
                $start_time = strtotime($start_date);
                for($day = 1; $day <= 7; ++$day)
                {
                    $date = date('Y-m-d', $start_time + ($day - 1) * 24 * 60 * 60);
                    
                    for($num = 1; $num <= $this->config->item('max_lessons'); ++$num)
                    {
                        if( !isset($data[$date][$num]) &&
                             isset($timetable->timetable[$num][$day])
                           )
                            
                            $data[$date][$num]['subject'] = $timetable->timetable[$num][$day];
                    }
                    if(isset($data[$date]))
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