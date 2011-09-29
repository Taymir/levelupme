<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of sms_model
 *
 * @author U7
 */
class sms_model extends MY_Model {
    private $table_name = 'sms';
    
    public function add_sms($data)
    {
        $data = $this->add_created_field($data);
        return $this->typical_insert($this->table_name, $data);
    }
    
    public function get_sms()
    {
        return $this->typical_select($this->table_name, 'created DESC');
    }
    
    public function get_unsent_sms($for_last_days = 3)
    {
        $this->db->select('*');

        $this->db->from($this->table_name);
        $this->db->order_by($this->table_name . '.' .  'created DESC');
        $this->db->where('status <', '-2');
        $this->db->where('created >', date('Y-m-d H:i:s', time() - 24 * 60 * 60 * $for_last_days));

        $query = $this->db->get();

        return $query->result();
    }
    
    public function update_sms($id, $data)
    {
        return $this->typical_update($this->table_name, $data, $id);
    }
    
    
}
