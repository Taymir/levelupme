<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of newPHPClass
 *
 * @author U7
 */
class grades_sessions_model extends MY_Model {
    private $table_name = 'grades_sessions';
 
    public function store_session($user_id, $class_id, $date, $data)
    {
        $data_str = base64_encode(serialize($data));
        
        return $this->typical_insert($this->table_name, 
                array('user_id' => $user_id, 'class_id' => $class_id, 'date' => $date, 'data' => $data_str));
    }
    
    public function load_session($user_id, $class_id, $date)
    {
        $this->db->select('data');
        $this->db->from($this->table_name);
        
        $this->db->where('user_id', $user_id);
        $this->db->where('class_id', $class_id);
        $this->db->where('date', $date);
        
        $query = $this->db->get();
        if($query->num_rows() > 0) 
        {
            $row = $query->row();
            return unserialize(base64_decode($row->data));
        }
        return NULL;
    }
    
    public function clear_session($user_id, $class_id, $date)
    {
        $this->db->where('user_id', $user_id);
        $this->db->where('class_id', $class_id);
        $this->db->where('date', $date);
        $this->db->delete($this->table_name);
        
        return TRUE;
    }
    
    public function clear_old_sessions($older_then = 3)
    {
        $this->db->where('date <', date('Y-m-d', time() - 24 * 60 *60 * $older_then));
        $this->db->delete($this->table_name);
        
        return TRUE;
    }
    
}