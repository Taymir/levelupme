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
    
    const ATTENDANCE =       0;///'ATTENDANCE';        //0;///
    const GRADES =           1;///'GRADES';            //1;///
    const CLASS_INFO =       2;///'CLASS_INFO';        //2;///
    const SCHOOL_INFO =      3;///'SCHOOL_INFO';       //3;///
    const STUDENTS =         4;///'STUDENTS';          //4;///
    const CLASS_SUMS =       5;///'CLASS_SUMS';        //5;///
    const CLASS_NUMS =       6;///'CLASS_NUMS';        //6;///
    const PARALLEL_SUMS =    7;///'PARALLEL_SUMS';     //7;///
    const PARALLEL_NUMS =    8;///'PARALLEL_NUMS';     //8;///
    const PARALLEL_INFO =    9;///'PARALLEL_INFO';     //9;///
    const USERS_SUMS =       10;//'USERS_SUMS';        //10;//
    const USERS_NUMS =       11;//'USERS_NUMS';        //11;//
    const DATES =            12;//'DATES';             //12;//
    const COUNTER_DATE =     13;//'COUNTER_DATE';      //13;//
    const META =             14;//'META';              //14;//
    const SUBJECTS =         15;//'SUBJECTS';          //15;//
    const COUNTER_SUBJECTS = 16;//'COUNTER_SUBJECTS';  //16;//
    
    private $table_name = 'statistics_queue';
    
    private $data = null;
    private $class_id = null;
    private $school_id = null;
    private $parallel_id = null;
    private $user_id = null;
    
    private function ifset(&$var, $default = null)
    {
        if(isset($var))
            return $var;
        return $default;
    }
    
    private function issetand(&$var, $value = true)
    {
        return isset($var) && $var == $value;
    }
    
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
        $school_id = $this->school_id;
        $parallel_id = $this->parallel_id;
        $class_id = $this->class_id;
        $student_id = $this->add_student($student);
        $subject_id = $this->get_subject_id($subject);
        $this->incvar($this->data[$school_id][$parallel_id][$class_id][self::STUDENTS][$student_id][$subject_id][self::ATTENDANCE][$attendance]);
    }
    
    public function set_user_grade($date, $subject, $student, $grade)
    {
        $school_id = $this->school_id;
        $parallel_id = $this->parallel_id;
        $class_id = $this->class_id;
        $student_id = $this->add_student($student);
        $subject_id = $this->get_subject_id($subject);
        $date_id = $this->get_date_id($date);
        $this->data[$school_id][$parallel_id][$class_id][self::STUDENTS][$student_id][$subject_id][self::GRADES][$date_id] = $grade;
        
        $this->incvar($this->data[$school_id][$parallel_id][$class_id][self::STUDENTS][$student_id][$subject_id][self::USERS_SUMS], $grade);
        $this->incvar($this->data[$school_id][$parallel_id][$class_id][self::STUDENTS][$student_id][$subject_id][self::USERS_NUMS]);
        
        $this->add_class_grade($subject, $grade);
        $this->add_parallel_grade($subject, $grade);
    }
    
    public function set_user_nograde($date, $subject, $student)
    {
        $school_id = $this->school_id;
        $parallel_id = $this->parallel_id;
        $class_id = $this->class_id;
        $student_id = $this->add_student($student);
        $subject_id = $this->get_subject_id($subject);
        $date_id = $this->get_date_id($date);
        $this->data[$school_id][$parallel_id][$class_id][self::STUDENTS][$student_id][$subject_id][self::GRADES][$date_id] = null;//@TODO: подумать над целесообразностью кучи пустых данных
        $this->set_user_attendance($date, $subject, $student);
    }
    
    private function add_class_grade($subject, $grade)
    {
        $school_id = $this->school_id;
        $parallel_id = $this->parallel_id;
        $class_id = $this->class_id;
        $subject_id = $this->get_subject_id($subject);
        $this->incvar($this->data[$school_id][$parallel_id][$class_id][self::META][self::CLASS_SUMS][$subject_id], $grade);
        $this->incvar($this->data[$school_id][$parallel_id][$class_id][self::META][self::CLASS_NUMS][$subject_id]);
    }
    
    private function add_parallel_grade($subject, $grade)
    {
        $school_id = $this->school_id;
        $parallel_id = $this->parallel_id;
        $subject_id = $this->get_subject_id($subject);
        $this->incvar($this->data[$school_id][$parallel_id][self::META][self::PARALLEL_SUMS][$subject_id], $grade);
        $this->incvar($this->data[$school_id][$parallel_id][self::META][self::PARALLEL_NUMS][$subject_id]);
    }
    
    public function add_student($student)
    {
        $school_id = $this->school_id;
        $parallel_id = $this->parallel_id;
        $class_id = $this->class_id;
        $this->setifnotset($this->data[$school_id][$parallel_id][$class_id][self::STUDENTS][$student->id][self::META], $student);
        return $student->id;
    }
    
    public function set_current_class($class)
    {
        $this->set_current_parallel($class->parallel);
        
        $school_id = $this->school_id;
        $parallel_id = $this->parallel_id;
        $this->class_id = $class->id;
        $class_id = $this->class_id;
        $this->setifnotset($this->data[$school_id][$parallel_id][$class_id][self::META][self::CLASS_INFO], $class);
        
        return $this->class_id;
        
    }
    
    private function get_date_id($date, $add_dates = true)
    {
        //return $date;//@DEBUG
        if(isset($this->data[self::META][self::DATES][$date]))
        {
            return $this->data[self::META][self::DATES][$date];
        }
        else
        {
            if($add_dates)
                return $this->data[self::META][self::DATES][$date] = $this->incvar($this->data[self::META][self::COUNTER_DATE]);
            else
                return FALSE;
        }
    }
    
    private function get_date_from_id($date_id)
    {
        return array_search($date_id, $this->data[self::META][self::DATES]);
    }
    
    private function get_subject_id($subject, $add_subjects = true)
    {
        //return $subject;//@DEBUG
        if(isset($this->data[self::META][self::SUBJECTS][$subject]))
        {
            return $this->data[self::META][self::SUBJECTS][$subject];
        }
        else
        {
            if($add_subjects)
                return $this->data[self::META][self::SUBJECTS][$subject] = $this->incvar($this->data[self::META][self::COUNTER_SUBJECTS]);
            else
                return FALSE;
        }
    }
    
    private function get_subject_from_id($subject_id)
    {
        return array_search($subject_id, $this->data[self::META][self::SUBJECTS]);
    }
    
    public function set_current_parallel($parallel_id)
    {
        $school_id = $this->school_id;
        $this->parallel_id = $parallel_id;
        $this->setifnotset($this->data[$school_id][$parallel_id][self::META][self::PARALLEL_INFO], $parallel_id);
        return $this->parallel_id;
    }
    
    public function set_current_school($school)
    {
        $this->school_id = $school->id;
        $school_id = $this->school_id;
        $this->setifnotset($this->data[$school_id][self::META][self::SCHOOL_INFO], $school);
        return $this->school_id;
    }
    
    public function serialize_data()
    {
        //echo memory_get_peak_usage(true); echo "\n";
        //$d1 = $this->data;
        //$this->export_data();
        //$this->data = null;
        //$this->import_data(1);//@BUGSSSS
        //print_r($this->data);
        //while($class = $this->get_next_class())
        //{
        //    var_dump($class);
        //    while($user = $this->get_next_user($class['id']))
        //    {
        //        foreach($user['subjects'] as $subject)
        //            echo $this->get_user_average($user['id'], $subject);
        //    }
        //}
        //$d2 = $this->data;
        /*echo strlen(base64_encode(gzcompress(serialize($this->data))));echo "\n";
        echo strlen(base64_encode(serialize($this->data)));echo "\n";
        print_r($this->data);*/
    }
    
    public function unserialize_data()
    {
        $this->import_data(2);
        //print_r($this->data);
        while($class = $this->get_next_class())
        {
            var_dump($class);
            while($user = $this->get_next_user($class['id']))
            {
                echo "<b>{$user['user']->name}</b><br/>";
                
                /*foreach($user['subjects'] as $subject)
                {
                    extract($this->get_user_attendance($user['id'], $subject));
                    $T = $N + $B + $P;
                    $N = round(100 * $N / $T);
                    $B = round(100 * $B / $T);
                    $P = round(100 * $P / $T);
                    echo "$subject: $N \ $B  \ $P <br/>"; 
                }*/
                
                ///*
                foreach($user['subjects'] as $subject)
                {
                    echo "$subject: <br/>"; 
                    foreach($this->get_user_grades($user['id'], $subject) as $date => $grade)
                            echo "$date: $grade<br/>";
                }
                 //*/
                
                /*
                foreach($user['subjects'] as $subject)
                {
                    echo "$subject: " .
                            round($this->get_user_average($user['id'], $subject), 1) . ' / ' .
                            round($this->get_class_average($class['id'], $subject), 1) . ' / ' . 
                            round($this->get_parallel_average($class['parallel'], $subject), 1) . " <br/>";
                }
                */
                
            }
        }
    }
    
    public function export_data()
    {
        $batch_data = array();
        
        for(reset($this->data); list($school_id) = each($this->data);)
        {
            if($school_id == self::META)
                continue;
            for(reset($this->data[$school_id]); list($parallel_id) = each($this->data[$school_id]);)
            {
                if($parallel_id == self::META)
                    continue;
                for(reset($this->data[$school_id][$parallel_id]); list($class_id) = each($this->data[$school_id][$parallel_id]);)
                {
                    if($class_id == self::META)
                        continue;
                    
                    if($this->issetand($this->data[$school_id][$parallel_id][$class_id][self::META][self::CLASS_NUMS]))
                    {
                        $class_data = array();
                        $class_data[self::META] = $this->data[self::META];
                        $class_data[$school_id][self::META] = $this->data[$school_id][self::META];
                        $class_data[$school_id][$parallel_id][self::META] = $this->data[$school_id][$parallel_id][self::META];
                        $class_data[$school_id][$parallel_id][$class_id] = $this->data[$school_id][$parallel_id][$class_id];

                        $package = base64_encode(gzcompress(serialize($class_data)));
                        //$package = $class_data;//@DEBUG

                        $batch_data[] = array(
                            'class_id' => $class_id,
                            'data' => $package
                        );
                    }
                }
            }
        }
        
        $this->db->insert_batch($this->table_name, $batch_data);
    }
    
    public function import_data($limit = 1)
    {
        $ci = &get_instance();
        $ci->load->helper('common_helper');
        
        $this->db->select('*');
        $this->db->from($this->table_name);
        $this->db->limit($limit);
        
        $query = $this->db->get();
        $data = $query->result();
        
        foreach($data as $row)
        {
            $class_data = unserialize(gzuncompress(base64_decode($row->data)));
            
            if(!is_array($this->data))
                $this->data = $class_data;
            else
                $this->data = array_merge_recursive_distinct($this->data, $class_data);
        }
        $this->reset_iterator();
    }
    
    private function next_arr(&$arr)
    {
        do {
            list($key, $value) = each($arr);
        } while ($key == self::META);
        
        return $key;
        
    }
    
    public function reset_iterator()
    {
        reset($this->data);
        $this->get_next_school();
        $this->get_next_parallel();
        /*$this->school_id = $this->reset_and_next_arr($this->data);
        $this->parallel_id = $this->reset_and_next_arr($this->data[$this->school_id]);
        $this->class_id = $this->reset_and_next_arr($this->data[$this->school_id][$this->parallel_id]);
        $this->user_id = $this->reset_and_next_arr($this->data[$this->school_id][$this->parallel_id][$this->class_id][self::STUDENTS]);*/
    }
    
    private function get_next_school()
    {
        $this->school_id = $this->next_arr($this->data);
        if($this->school_id != null)
            reset($this->data[$this->school_id]);
        return $this->school_id;
    }
    
    private function get_next_parallel()
    {
        $this->parallel_id = $this->next_arr($this->data[$this->school_id]);
        if($this->parallel_id != null)
            reset($this->data[$this->school_id][$this->parallel_id]);
        return $this->parallel_id;
    }
    
    public function get_next_class()
    {
        $this->class_id = $this->next_arr($this->data[$this->school_id][$this->parallel_id]);
        
        if($this->class_id == null) {
            $this->get_next_parallel();
            
            if($this->parallel_id == null) {
                $this->get_next_school();
                
                if($this->school_id == null) {
                    return null;
                }
            }
            
            $this->class_id = $this->next_arr($this->data[$this->school_id][$this->parallel_id]);
        }
        
        $class = array(
            'id' => $this->class_id,
            'class' => $this->data[$this->school_id][$this->parallel_id][$this->class_id][self::META][self::CLASS_INFO],
            'parallel' => $this->data[$this->school_id][$this->parallel_id][self::META][self::PARALLEL_INFO],
            'school' => $this->data[$this->school_id][self::META][self::SCHOOL_INFO]
        );
        
        return $class;
    }
    
    public function get_next_user($class_id)
    {
        $class_id = $this->class_id;
        $parallel_id = $this->parallel_id;
        $school_id = $this->school_id;
        $user_id = $this->user_id = $this->next_arr($this->data[$school_id][$parallel_id][$class_id][self::STUDENTS]);
        
        if($user_id == null)
            return null;
            
        $user = array(
            'id' => $user_id,
            'user' => $this->data[$school_id][$parallel_id][$class_id][self::STUDENTS][$user_id][self::META],
            'subjects' => $this->get_user_subjects($user_id)
        );
        
        return $user;
    }
    
    private function get_user_subjects($user_id, $sort_on_config = true)
    {
        $class_id = $this->class_id;
        $parallel_id = $this->parallel_id;
        $school_id = $this->school_id;
        
        $subjects = array();
        foreach($this->data[$school_id][$parallel_id][$class_id][self::STUDENTS][$user_id] as $subject_id => $val)
        {
            if($subject_id == self::META)
                continue;
            $subjects[] = $this->get_subject_from_id($subject_id);
        }
        
        if($sort_on_config)
        {
            $this->config->load('statistics');
            $ci = &get_instance();
            $ci->load->helper('common_helper');
            $sortOn = array_keys($this->config->item('required_subjects'));
            $subjects = sortArrayByArray($subjects, $sortOn);
        }
        
        return $subjects;
    }
    
    public function get_user_grades($student_id, $subject)
    {
        $class_id = $this->class_id;
        $parallel_id = $this->parallel_id;
        $school_id = $this->school_id;
        $subject_id = $this->get_subject_id($subject, false);
        
        $grades = array();
        foreach($this->data[$school_id][$parallel_id][$class_id][self::STUDENTS][$student_id][$subject_id][self::GRADES] as $date_id => $grade)
        {
            $grades[$this->get_date_from_id($date_id)] = $grade;
        }
        
        return $grades;
    }
    
    public function get_user_attendance($student_id, $subject)
    {
        $class_id = $this->class_id;
        $parallel_id = $this->parallel_id;
        $school_id = $this->school_id;
        $subject_id = $this->get_subject_id($subject, false);
        
        $n = $this->ifset($this->data[$school_id][$parallel_id][$class_id][self::STUDENTS][$student_id][$subject_id][self::ATTENDANCE][self::N]);
        $p = $this->ifset($this->data[$school_id][$parallel_id][$class_id][self::STUDENTS][$student_id][$subject_id][self::ATTENDANCE][self::P]);
        $b = $this->ifset($this->data[$school_id][$parallel_id][$class_id][self::STUDENTS][$student_id][$subject_id][self::ATTENDANCE][self::B]);
        return array(
            'N' => $n,
            'B' => $b,
            'P' => $p
        );
    }
    
    public function get_user_average($student_id, $subject)
    {
        $class_id = $this->class_id;
        $parallel_id = $this->parallel_id;
        $school_id = $this->school_id;
        $subject_id = $this->get_subject_id($subject, false);
        
        return $this->ifset($this->data[$school_id][$parallel_id][$class_id][self::STUDENTS][$student_id][$subject_id][self::USERS_SUMS], 0)
                  /
               $this->ifset($this->data[$school_id][$parallel_id][$class_id][self::STUDENTS][$student_id][$subject_id][self::USERS_NUMS], 1);
    }
    
    public function get_class_average($class_id, $subject)
    {
        $class_id = $this->class_id;
        $parallel_id = $this->parallel_id;
        $school_id = $this->school_id;
        $subject_id = $this->get_subject_id($subject, false);
        
        return $this->ifset($this->data[$school_id][$parallel_id][$class_id][self::META][self::CLASS_SUMS][$subject_id], 0)
                 /
               $this->ifset($this->data[$school_id][$parallel_id][$class_id][self::META][self::CLASS_NUMS][$subject_id], 1);
    }
    
    public function get_parallel_average($parallel_id, $subject)
    {
        $parallel_id = $this->parallel_id;
        $school_id = $this->school_id;
        $subject_id = $this->get_subject_id($subject, false);
        
        return $this->ifset($this->data[$school_id][$parallel_id][self::META][self::PARALLEL_SUMS][$subject_id], 0)
                 /
               $this->ifset($this->data[$school_id][$parallel_id][self::META][self::PARALLEL_NUMS][$subject_id], 1);
    }
    
    public function remove_class_from_queue($class_id)
    {
        $this->typical_delete($this->table_name, $class_id, 'class_id');
    }
}