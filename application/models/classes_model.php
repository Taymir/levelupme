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
    private $schools_table_name = 'schools';
    
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
    
    public function get_class_info($class_id)
    {
        // получить полную информацию о классе, включая информацию о школе (JOIN)
        $this->db->select('*, ' . $this->table_name . '.id AS class_id');
        
        $this->db->from($this->table_name);
        $this->db->join($this->schools_table_name, $this->schools_table_name . '.id = ' . $this->table_name . '.school_id');
        $this->db->where($this->table_name . '.id', $class_id);
        
        $query = $this->db->get();
        if($query->num_rows() == 1) return $query->row();
        return NULL;
    }
    
    public function get_default_class_info($school_list = '*')
    {
        // получить полную информацию о первом попавшемся классе из списка школ, включая информацию о школе
        $this->db->select('*, ' . $this->table_name . '.id AS class_id');
                
        $this->db->from($this->table_name);
        $this->db->join($this->schools_table_name, $this->schools_table_name . '.id = ' . $this->table_name . '.school_id');
        $this->db->order_by('LENGTH(' . $this->schools_table_name . '.school)');
        $this->db->order_by($this->schools_table_name . '.school');
        $this->db->order_by('LENGTH(' . $this->table_name . '.class)');
        $this->db->order_by($this->table_name . '.class');
        $this->db->limit(1);
        
        if(($school_list) != '*')
            $this->db->where_in($this->schools_table_name . '.id', $school_list);
        $query = $this->db->get();
        
        if($query->num_rows() == 1)
            return $query->row();
        return NULL;
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
    
    public function get_schools_and_classes($school_list = '*')
    {
        $ci = & get_instance();
        $ci->load->model('schools_model');
        $schools = $ci->schools_model->get_schools($school_list);
        
        foreach($schools as $key=>$school)
        {
            $schools[$key]->classes = $this->get_classes_by_school($school->id);
        }
        
        return $schools;
    }
    
    
}

?>
