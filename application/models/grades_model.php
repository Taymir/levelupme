<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of grades_model
 *
 * @author U7
 */
class grades_model extends MY_Model {
    private $table_name = 'grades';
    
    public function save_grades($date, $data)
    {
        // Формат $data:
        // $data[subjects] = array(1=>История, 2=>Информатика...);
        // $data[grades][studentkey][subjectkey] = grade
        // $data[comments][studentkey][subjectkey] = comment
        //
        // Надо переформатировать в:
        // $batch_data = array(
        // array(
        // user_profile_id
        // grade
        // comment
        // subject
        // num
        // date
        // ),
        // ...
        // );
        $ci = & get_instance();
        $max_lessons = $ci->config->item('max_lessons');
        $students = $data['students'];
        $subjects = $data['subjects'];
        $grades   = $data['grades'];
        $comments = $data['comments'];
        
        $batch_data = array();
        foreach($students as $user_profile_id => $student)
        {
            for($num = 1; ($num - 1) < $max_lessons; ++$num)
            {
                $batch_row = array();
                $batch_row['user_profile_id'] = $user_profile_id;
                $batch_row['subject'] = $subjects[$num];
                $batch_row['num'] = $num;
                $batch_row['date'] = $date;
                $batch_row['grade'] = '';
                $batch_row['comment'] = '';
                
                if(isset($grades[$user_profile_id][$num]) && trim($grades[$user_profile_id][$num]) != '')//@TODO: можно очищать в filter_grades
                    $batch_row['grade'] = $grades[$user_profile_id][$num];
                if(isset($comments[$user_profile_id][$num]) && trim($comments[$user_profile_id][$num]) != '')//@TODO: можно очищать в filter_grades
                    $batch_row['comment'] = $comments[$user_profile_id][$num];
                
                if(trim($batch_row['grade']) != '' || trim($batch_row['comment']) != '')
                    $batch_data[] = $batch_row;
            }
        }
        
        // сохранение информации
        $this->db->trans_start();
        $this->clear_grades($date, array_keys($students));
        if(sizeof($batch_data) > 0)
            $this->db->insert_batch ($this->table_name, $batch_data);
        $this->db->trans_complete();
        
        return TRUE;
    }
    
    public function has_grades($date, $user_profiles_ids)
    {
        $this->db->select('COUNT(*) as count', FALSE);
        $this->db->from($this->table_name);
        $this->db->where('date', $date);
        $this->db->where_in('user_profile_id', $user_profiles_ids);
        $query = $this->db->get();
        
        $result = $query->row();
        if($result->count > 0)
            return TRUE;
        return FALSE;
    }
    
    public function clear_grades($date, $user_profiles_ids)
    {
        $this->db->where('date', $date);
        $this->db->where_in('user_profile_id', $user_profiles_ids);
        $this->db->delete($this->table_name);
        
        return true;
    }
    
    public function load_grades($date, $user_profile_ids)
    {
        //Формат таблицы:
        //$row = array(
        // user_profile_id
        // grade
        // comment
        // subject
        // num
        // date
        // )
        //
        // Надо переформатировать в:
        //
        // Формат $data:
        // $data[subjects] = array(1=>История, 2=>Информатика...);
        // $data[grades][studentkey][subjectkey] = grade
        // $data[comments][studentkey][subjectkey] = comment
        $this->db->select('*');
        $this->db->from($this->table_name);
        $this->db->where('date', $date);
        $this->db->where_in('user_profile_id', $user_profile_ids);
        $query = $this->db->get();
        $unformated_grades = $query->result();
        
        // Форматируем результат
        $grades = array();
        $subjects = array();
        $comments = array();
        foreach($unformated_grades as $grade)
        {
            $grades[$grade->user_profile_id][$grade->num] = $grade->grade;
            $comments[$grade->user_profile_id][$grade->num] = $grade->comment;
            
            if(!isset($subjects[$grade->num]))
                $subjects[$grade->num] = $grade->subject;
        }
        
        if(sizeof($subjects) > 0)
            return array(
                'grades' => $grades,
                'subjects' => $subjects,
                'comments' => $comments
                );
        return NULL;
    }
    
    public function get_grades($date_from, $date_to, $user_profile_ids)
    {
        $this->db->select('grade, subject, num, date, user_profile_id');
        $this->db->from($this->table_name);
        $this->db->where('date >=', $date_from);
        $this->db->where('date <=', $date_to);
        $this->db->where_in('user_profile_id', $user_profile_ids);
        $this->db->order_by('date');
        $this->db->order_by('num');
        
        $query = $this->db->get();
        return $query->result();
    }
    
    public function get_grades_by_week($user_profile_id, $week, $year)
    {
        $this->load->helper('common_helper');
        
        list($start_time, $end_time) = week2times($week, $year);
        $start_date = date('Y-m-d', $start_time);
        $end_date   = date('Y-m-d', $end_time);
        
        $this->db->select('*');
        $this->db->from($this->table_name);
        $this->db->where('date >=', $start_date);
        $this->db->where('date <=', $end_date);
        $this->db->where('user_profile_id', $user_profile_id);
        $this->db->order_by('date');
        $this->db->order_by('num');
        
        $query = $this->db->get();
        $unformated_grades = $query->result();
        // Форматируем результат
        $grades = array();
        foreach($unformated_grades as $grade)
        {
            $grades[$grade->date][$grade->num] = array(
                'subject' => $grade->subject,
                'grade'   => $grade->grade,
                'comment' => $grade->comment
            );
        }
        
        return array('grades' => $grades, 'start_date' => $start_date, 'end_date' => $end_date);
    }
}
