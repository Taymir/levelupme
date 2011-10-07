<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of mailings_model
 *
 * @author U7
 */
class mailings_model extends MY_Model {
    private $table_name = 'mailings';
    private $packs_table_name = 'mailing_packs';
    private $profiles_table_name = 'user_profiles';
    private $classes_table_name = 'classes';
    private $schools_table_name = 'schools';
    
    public $total_mailings_found;
    
    private function _set_statuses($data)
    {
        if(!isset($data['email_text']) || $data['email_text'] == '')
            $data['email_status'] = 'empty';
        else
            $data['email_status'] = 'pending';
        
        if(!isset($data['sms_text']) || $data['sms_text'] == '')
            $data['sms_status'] = 'empty';
        else
            $data['sms_status'] = 'pending';
        
        return $data;
    }
    
    public function add_single_mailing($data)
    {
        $data = $this->_set_statuses($data);
        $data = $this->add_created_field($data);
        
        return $this->typical_insert($this->table_name, $data);
    }
    
    public function add_multi_mailing($type, $datas, $recipient = '')
    {
        $pack_id = $this->create_mailing_pack($type, $recipient);
        if($pack_id !== FALSE)
        {
            $batch_data = array();
            foreach($datas as $data)
            {
                $data = $this->_set_statuses($data);
                $data['pack_id'] = $pack_id;
                $data = $this->add_created_field($data);
                
                $batch_data[] = $data;
            }
            if(sizeof($batch_data) > 0)
                $this->db->insert_batch($this->table_name, $batch_data);
            
            return sizeof($batch_data);
        }
        return FALSE;
    }
    
    public function create_mailing_pack($type, $recipient= '')
    {
        $data = array('type' => $type, 'recipient' => $recipient);
        if($this->typical_insert($this->packs_table_name, $data))
            return $this->db->insert_id();
        return FALSE;
    }
    
    public function get_email_queue($last_days = 3)
    {
        $this->db->select('*');
        
        $this->db->from($this->table_name);
        $this->db->order_by($this->table_name . '.' .  'created DESC');
        $this->db->where_in('email_status', array('pending', 'tmp_error'));
        $this->db->where('created >', date('Y-m-d H:i:s', time() - 24 * 60 * 60 * $last_days));
        
        $query = $this->db->get();
        
        return $query->result();
    }
    
    public function mark_email_sent($mailing_id, $status = 'sent')
    {
        $data = array('email_status' => $status);
        
        return $this->typical_update($this->table_name, $data, $mailing_id);
    }
    
    public function mark_old_email_errored($older_then = 3)
    {
        $this->db->where_in('email_status', array('pending', 'tmp_error'));
        $this->db->where('created <', date('Y-m-d H:i:s', time() - 24 * 60 * 60 * $older_then));
        $this->db->set('email_status', 'pm_error');
        $this->db->update($this->table_name);
        
         return $this->db->affected_rows();
    }
    
    public function get_sms_queue($last_days = 3)
    {
        $this->db->select('*');
        
        $this->db->from($this->table_name);
        $this->db->order_by($this->table_name . '.' .  'created DESC');
        $this->db->where_in('sms_status', array('pending', 'tmp_error'));
        $this->db->where('created >', date('Y-m-d H:i:s', time() - 24 * 60 * 60 * $last_days));
        
        $query = $this->db->get();
        
        return $query->result();
    }
    
    public function mark_sms_sent($mailing_id, $status = 'sent')
    {
        $data = array('sms_status' => $status);
        
        return $this->typical_update($this->table_name, $data, $mailing_id);
    }
    
    public function mark_old_sms_errored($older_then = 3)
    {
        $this->db->where_in('sms_status', array('pending', 'tmp_error'));
        $this->db->where('created <', date('Y-m-d H:i:s', time() - 24 * 60 * 60 * $older_then));
        $this->db->set('sms_status', 'pm_error');
        $this->db->update($this->table_name);
        
        return $this->db->affected_rows();
    }
    
    public function get_mailing($mailing_id)
    {
        $this->db->select('*');
        $this->db->from($this->table_name);
        $this->db->join($this->packs_table_name, $this->packs_table_name . '.id = ' . $this->table_name . '.pack_id', 'left');
        $this->db->where($this->table_name . '.id', $mailing_id);
    
        $query = $this->db->get();

        return $query->row();
    }
    
    public function get_all_mailings($school_id = '*', $class_id = '*', $type = '*', $limit = 0, $offset = 0)
    {
        $prefix = '';
        if($limit > 0)
            $prefix = 'SQL_CALC_FOUND_ROWS ';
        $this->db->select($prefix .
                          $this->table_name . '.id AS mailing_id,'.
                          $this->table_name . '.*,'.
                          $this->packs_table_name . '.type,'.
                          $this->profiles_table_name . '.*,'.
                          $this->classes_table_name . '.id AS class_id,'.
                          $this->classes_table_name . '.class,'.
                          $this->schools_table_name . '.id AS school_id,'.
                          $this->schools_table_name . '.school', FALSE);
        
        $this->db->from($this->table_name);
        $this->db->order_by($this->table_name . '.' .  'created DESC');
        
        $this->db->join($this->packs_table_name, 
                $this->packs_table_name . '.id = ' . $this->table_name . '.pack_id', 'left');
        $this->db->join($this->profiles_table_name,
                $this->profiles_table_name . '.id = ' . $this->table_name . '.user_profile_id');
        $this->db->join($this->classes_table_name,
                $this->classes_table_name . '.id = ' . $this->profiles_table_name . '.class_id');
        $this->db->join($this->schools_table_name,
                $this->schools_table_name . '.id = ' . $this->classes_table_name . '.school_id');
        
        if($school_id != '*')
        {
            $this->db->where($this->schools_table_name . '.id', $school_id);
        }
        
        if($class_id != '*')
        {
            $this->db->where($this->classes_table_name . '.id', $class_id);
        }
        
        if($type != '*')
        {
            $this->db->where_in($this->packs_table_name . '.type', $type);
            if(in_array('user', $type))
                $this->db->or_where ($this->packs_table_name . '.type', NULL);
        }
        
        if($limit >0)
            $this->db->limit($limit, $offset);
        
        $query = $this->db->get();
        
        if($limit > 0)
        {
            $rows_found_result = $this->db->query('SELECT FOUND_ROWS() as rowcount')->result();
            $this->total_mailings_found = $rows_found_result[0]->rowcount;
        }
        
        return $query->result();
    }
    
}
