<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of classes_model
 *
 * @author U7
 */
class classes_model extends MY_Model {
    private $table_name = 'classes';
    
    public function add_class($data)
    {
        $this->db->trans_start();
        $this->typical_insert($this->table_name, $data);
        
        $ci = & get_instance();
        $ci->load->model('timetables_model');
        $ci->timetables_model->add_timetable(array('class_id' => $ci->db->insert_id())); // Добавляем пустое расписание к классу
        $this->db->trans_complete();
        
        return TRUE;
    }
    
    public function remove_class($id)
    {
        return $this->typical_delete($this->table_name, $id);
        // MYSQL: CASCADE DELETE TIMETABLE
    }
    
    public function get_class($id)
    {
        return $this->typical_find_obj($this->table_name, $id);
    }
    
    public function save_class($id, $data)
    {
        return $this->typical_update($this->table_name, $data, $id);
    }
    
    public function get_classes_by_school($school_id)
    {
        $this->db->select('*');

        $this->db->from($this->table_name);
        $this->db->order_by('LENGTH(' . $this->table_name . '.class)');
        $this->db->order_by($this->table_name . '.class');
        
         $this->db->where_in('school_id', $school_id);

        $query = $this->db->get();

        return $query->result();
    }
    
    public function get_schools_and_classes()
    {
        $ci = & get_instance();
        $ci->load->model('schools_model');
        $schools = $ci->schools_model->get_schools();
        
        foreach($schools as $key=>$school)
        {
            $schools[$key]->classes = $this->get_classes_by_school($school->id);
        }
        
        return $schools;
    }
    
    
}

?>
