<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of timetables_model
 *
 * @author U7
 */
class timetables_model extends MY_Model {
    private $table_name = 'timetables';
    private $subjects_table_name = 'timetable_subjects';
    
    public function add_timetable($data)
    {
        return $this->typical_insert($this->table_name, $data);
    }
    
    public function remove_timetable($id)
    {
        return $this->typical_delete($this->table_name, $id);
        // MYSQL: CASCADE DELETE TIMETABLE SUBJECTS
    }
    
    public function get_timetable_by_class($class_id)
    {
        // запрос на описание расписания
        $this->db->select('*');
        $this->db->from($this->table_name);
        $this->db->where('class_id', $class_id);
        $query = $this->db->get();
        $data = $query->row();
         
        // Запрос на список предметов
        $this->db->select('num, day, subject');
        $this->db->from($this->subjects_table_name);
        $this->db->where('timetable_id', $data->id);
        $query = $this->db->get();
        $unformatted_timetable = $query->result();
        
        // форматируем результат
        $formatted_timetable = array();
        foreach($unformatted_timetable as $row)
        {
            $num = $row->num;
            $day = $row->day;
            $subject = $row->subject;
            
            $formatted_timetable[$num][$day] = $subject;
        }
        $data->timetable = $formatted_timetable;
        
        return $data;
    }
    
    public function save_timetable($id, $description, $data)
    {
        //Формат $data:
        // $data[num][day] = subject
        // надо переформатировать в:
        // $batch_data = array(
        // array(
        // 'num' => num,
        // 'day' => day,
        // 'subject' => subject,
        // 'timetable_id' => id
        // ),
        // ....
        // );
        $batch_data = array();
        for($num = 1; ($num-1) < sizeof($data); ++$num)
        {
            for($day = 1; ($day-1) < sizeof($data[$num]); ++$day)
            {
                if($data[$num][$day] != '')
                {
                    $batch_data[] = array(
                        'num' => $num,
                        'day' => $day,
                        'subject' => $data[$num][$day],
                        'timetable_id' => $id
                    );
                }
            }
        }
        
        $this->db->trans_start();
        // сохранить описание
        $this->typical_update($this->table_name, array('description' => $description), $id);
        //  очистить расписание
        $this->clear_timetable($id);
        //  сохранить все ячейки нового расписания
        $this->db->insert_batch($this->subjects_table_name, $batch_data);
        $this->db->trans_complete();
        
        return TRUE;
    }
    
    public function clear_timetable($id)
    {
        $this->db->where('timetable_id', $id);
        $this->db->delete($this->subjects_table_name);

        return TRUE;
    }
    
    public function get_subjects($like = null)
    {
        $this->db->select('subject');

        $this->db->from($this->subjects_table_name);
        $this->db->order_by('subject');
        $this->db->group_by('subject');
        
         if($like != NULL)
             $this->db->like('subject', $like);

        $query = $this->db->get();

        return $this->Arr2List($query->result_array(), 'subject');
    }
    
    
}

?>
