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
    const N = 0;
    const B = 1;
    const P = 2;
    
    const ATTENDANCE = 0;
    const GRADES = 1;
    const CLASSES = 2;
    const SCHOOLS = 3;
    const STUDENTS = 4;
    const COUNT_GRADES = 5;
    const CLASS_SUMS = 6;
    const CLASS_NUMS = 7;
    const PARALLEL_SUMS = 8;
    const PARALLEL_NUMS = 9;
    const PARALLELS = 10;
    
    private $data = null;
    private $class_id = null;
    private $school_id = null;
    private $parallel_id = null;
    
    private function setifnotset(&$var, $value = 0)
    {
        if(!isset($var))
            return $var = $value;
    }
    
    private function setvar(&$var, $value = 0)
    {
        $this->setifnotset($var, $value);
    }
    
    private function incvar(&$var, $value = 1)
    {
        if(!isset($var))
            $var = $value;
        else
            $var++;
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
        $this->incvar($this->data[self::ATTENDANCE][$subject][$student_id][$attendance]);
    }
    
    public function add_user_grade($date, $subject, $student, $grade)
    {
        $student_id = $this->add_student($student);
        $this->incvar($this->data[self::GRADES      ][$date][$subject][$student_id], $grade);
        $this->incvar($this->data[self::COUNT_GRADES][$date][$subject][$student_id]);
        
        $this->add_class_grade($subject, $grade);
        $this->add_parallel_grade($subject, $grade);
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
    
    public function set_user_nograde($date, $subject, $student)
    {
        $student_id = $this->add_student($student);//@TODO: подумать над целесообразностью кучи пустых данных
        $this->data[self::GRADES][$date][$subject][$student_id] = null;
        $this->data[self::COUNT_GRADES][$date][$subject][$student_id] = 1;
        $this->set_user_attendance($date, $subject, $student);
    }
    
    public function add_student($student)
    {
        $this->setvar($this->data[self::STUDENTS][$student->id], $student);
        return $student->id;
    }
    
    public function set_current_class($class)
    {
        $this->class_id = $class->id;
        $this->setvar($this->data[self::CLASSES][$this->class_id], $class);

        $this->set_current_parallel($class->parallel);
        return $this->class_id;
        
    }
   
    private function get_current_class()
    {
        return $this->data[self::CLASSES][$this->class_id];
    }
    
    public function set_current_parallel($parallel)
    {
        $this->parallel_id = $this->school_id. '-' .  $parallel;
        $this->setvar($this->data[self::PARALLELS][$this->parallel_id]);
        return $this->parallel_id;
    }
    
    public function set_current_school($school)
    {
        $this->school_id = $school->id;
        $this->setvar($this->data[self::SCHOOLS][$this->school_id]);
        return $this->school_id;
    }
    
    
    private function get_current_school()
    {
        return $this->data[self::SCHOOLS][$this->school_id];
    }
    
    public function serialize_data()
    {
        echo strlen(gzcompress(base64_encode(serialize($this->data))));
    }
    
    public function unserialize_data()
    {
        
    }
}