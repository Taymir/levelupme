<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of statistics_model
 *
 * @author U7
 */
class statistics_model extends MY_Model {
    const N = 'N';    //0;
    const B = 'B';    //1;
    const P = 'P';    //2;
    
    const ATTENDANCE =     'ATTENDANCE';    //0;
    const GRADES =         'GRADES';        //1;
    const CLASSES =        'CLASSES';       //2;
    const SCHOOLS =        'SCHOOLS';       //3;
    const STUDENTS =       'STUDENTS';      //4;
    const CLASS_SUMS =     'CLASS_SUMS';    //5;
    const CLASS_NUMS =     'CLASS_NUMS';    //6;
    const PARALLEL_SUMS =  'PARALLEL_SUMS'; //7;
    const PARALLEL_NUMS =  'PARALLEL_NUMS'; //8;
    const PARALLELS =      'PARALLELS';     //9;
    const USERS_SUMS =     'USERS_SUMS';    //10;
    const USERS_NUMS =     'USERS_NUMS';    //11;
    const DATES =          'DATES';         //12;
    const COUNTER_DATE =   'COUNTER_DATE';  //13;
    
    private $data = null;
    private $class_id = null;
    private $school_id = null;
    private $parallel_id = null;
    
    private function setifnotset(&$var, $value = 0)
    {
        if(!isset($var))
            return $var = $value;
    }
    
    private function incvar(&$var, $value = 1)
    {
        if(!isset($var))
            return $var = $value;
        else
            return $var += $value;
    }
    
    public function set_user_n($date, $subject, $student)
    {
        $this->set_user_attendance($date, $subject, $student, self::N);
    }
    
    public function set_user_b($date, $subject, $student)
    {
        $this->set_user_attendance($date, $subject, $student, self::B);
    }
    
    public function set_user_attendance($date, $subject, $student, $attendance = self::P)
    {
        $student_id = $this->add_student($student);
        $this->incvar($this->data[self::ATTENDANCE][$student_id][$subject][$attendance]);
    }
    
    public function set_user_grade($date, $subject, $student, $grade)
    {
        //Заменить на set_user_grade
        $student_id = $this->add_student($student);
        $this->data[self::GRADES][$subject][$student_id][$this->get_date_id($date)] = $grade;
        
        $this->incvar($this->data[self::USERS_SUMS][$subject][$student_id], $grade);
        $this->incvar($this->data[self::USERS_NUMS][$subject][$student_id]);
        
        $this->add_class_grade($subject, $grade);
        $this->add_parallel_grade($subject, $grade);
    }
    
    public function set_user_nograde($date, $subject, $student)
    {
        $student_id = $this->add_student($student);
        $this->data[self::GRADES][$subject][$student_id][$this->get_date_id($date)] = null;//@TODO: подумать над целесообразностью кучи пустых данных
        $this->set_user_attendance($date, $subject, $student);
    }
    
    private function add_class_grade($subject, $grade)
    {
        $class_id = $this->class_id;
        $this->incvar($this->data[self::CLASS_SUMS][$class_id][$subject], $grade);
        $this->incvar($this->data[self::CLASS_NUMS][$class_id][$subject]);
    }
    
    private function add_parallel_grade($subject, $grade)
    {
        $parallel_id = $this->parallel_id;
        $this->incvar($this->data[self::PARALLEL_SUMS][$parallel_id][$subject], $grade);
        $this->incvar($this->data[self::PARALLEL_NUMS][$parallel_id][$subject]);
    }
    
    public function add_student($student)
    {
        $this->setifnotset($this->data[self::STUDENTS][$student->id], $student);
        return $student->id;
    }
    
    public function set_current_class($class)
    {
        $this->class_id = $class->id;
        $this->setifnotset($this->data[self::CLASSES][$this->class_id], $class);

        $this->set_current_parallel($class->parallel);
        return $this->class_id;
        
    }
    
    private function get_date_id($date, $add_dates = true)
    {
        if(isset($this->data[self::DATES][$date]))
        {
            return $this->data[self::DATES][$date];
        }
        else
        {
            if($add_dates)
                return $this->data[self::DATES][$date] = $this->incvar($this->data[self::COUNTER_DATE]);
            else
                return FALSE;
        }
    }
   
    private function get_current_class()
    {
        return $this->data[self::CLASSES][$this->class_id];
    }
    
    public function set_current_parallel($parallel)
    {
        $this->parallel_id = $this->school_id. '-' .  $parallel;
        $this->setifnotset($this->data[self::PARALLELS][$this->parallel_id], $parallel);
        return $this->parallel_id;
    }
    
    public function set_current_school($school)
    {
        $this->school_id = $school->id;
        $this->setifnotset($this->data[self::SCHOOLS][$this->school_id], $school);
        return $this->school_id;
    }
    
    
    private function get_current_school()
    {
        return $this->data[self::SCHOOLS][$this->school_id];
    }
    
    public function serialize_data()
    {
        echo strlen(base64_encode(gzcompress(serialize($this->data))));
        print_r($this->data);
    }
    
    public function unserialize_data()
    {
        
    }
}