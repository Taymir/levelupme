<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of schools_model
 *
 * @author U7
 */
class schools_model extends MY_Model {
    private $table_name = 'schools';
    
    public function add_school($data)
    {
        return $this->typical_insert($this->table_name, $data);
    }
    
    public function remove_school($id)
    {
        return $this->typical_delete($this->table_name, $id);
        // MYSQL: CASCADE DELETE CLASSES
    }
    
    public function get_schools($schools_list = '*')
    {
        if($schools_list == NULL)
            return NULL;
        
        $this->db->select('*');

        $this->db->from($this->table_name);
        $this->db->order_by('LENGTH(' . $this->table_name . '.school)');
        $this->db->order_by($this->table_name . '.school');
        
         if($schools_list != '*')
             $this->db->where_in('id', $schools_list);

        $query = $this->db->get();

        return $query->result();
    }
}