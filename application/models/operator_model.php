<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of operator_model
 *
 * @author U7
 */
class operator_model extends user_profile_model {
    protected $operators_table_name = 'operators_schools'; //Таблица разрешений для операторов
    
    const unloaded = -1;
    protected $schoolList = self::unloaded;
    
    public function get_operators_school_list($operator = null)
    {
       if(isset($operator)) {
           return $this->load_operators_school_list($operator);
       } else {
           if($this->schoolList == self::unloaded)
               $this->schoolList = $this->load_operators_school_list($operator);
           
           return $this->schoolList;
       }
    }
    
    private function load_operators_school_list($operator = null)
    {
        if(is_null($operator))
        {
            if ($this->getRole() == 'admin')
                return '*';
            elseif ($this->getRole() != 'operator')
                return null;
            else
                $operator = $this->getId();
        }
            
        $this->db->select('school_id');
        $this->db->from($this->operators_table_name);
        $this->db->where('user_id', $operator);
        $query = $this->db->get();
        
        return $this->Arr2List($query->result_array(), 'school_id');
    }
    
    public function check_class_against_school_list($class_id, $schoolList = null)
    {
        if(!isset($schoolList))
            $schoolList = $this->get_operators_school_list();
        
        if($schoolList == '*')
            return true;
        elseif($schoolList == null)
            return false;
        
        // Делаем запрос на право доступа оператором к данному классу
        $this->db->select('COUNT(*) as count', FALSE);
        $this->db->from($this->classes_table_name);
        $this->db->where($this->classes_table_name . '.id', $class_id);
        $this->db->where_in($this->classes_table_name . '.school_id', $schoolList);
        $query = $this->db->get();
        
        $res = $query->row();
        return ($res->count > 0);
    }
    
    public function save_operators_school_list($operator, $schools)
    {
        $batch_data = array();
        if(is_array($schools)){
            foreach($schools as $school)
            {
                $batch_data[] = array('user_id' => (int)$operator,  'school_id' => (int)$school);
            }
        }
        
        $this->db->trans_start();
        $result = $this->clear_operators_school_list($operator);
        if(sizeof($batch_data) > 0)
            $result = $this->db->insert_batch($this->operators_table_name, $batch_data);
        $this->db->trans_complete();
        
        return $result;
    }

    public function clear_operators_school_list($operator)
    {
        $this->db->where('user_id', $operator);
        return $this->db->delete($this->operators_table_name);
    }
    
    public function add_operator_profile($data)
    {
        $profile_data = array(
            'name' => $data['name'],
            'role' => $data['role'],
            'class_id' => -1
        );
        $username = $data['username'];
        $email = $data['email'];
        $password = $data['password'];
        
        $ci = & get_instance();
        $ci->load->library('tank_auth');
        
        $result = $ci->tank_auth->create_user($username, $email, $password, false);
        if($result)
        {
            $this->typical_update($this->table_name, $profile_data, $result['user_id'], 'user_id');
            if($data['role'] != 'admin')
                $this->save_operators_school_list($result['user_id'], $data['schools']);
            return TRUE;
        }
        else
        {
            return FALSE;
        }
    }
    
    public function get_operators($with_school_lists = false)
    {
        $this->db->select(
                $this->users_table_name . '.id,'.
                $this->users_table_name . '.username,'.
                $this->users_table_name . '.email,'.
                $this->users_table_name . '.banned,'.
                
                $this->table_name . '.name,' .
                $this->table_name . '.role'
                );
        
        $this->db->from($this->table_name);
        $this->db->join($this->users_table_name, $this->table_name  . '.user_id = ' . $this->users_table_name . '.id');
        
        $this->db->where_in('role', array('operator', 'admin'));
        $this->db->order_by($this->table_name . '.name');
        
        $query = $this->db->get();
        $operators = $query->result();
        
        if($with_school_lists)
        {
            foreach($operators as $key=>$operator)
            {
                if($operator->role == 'operator')
                    $operators[$key]->schools = $this->get_operators_school_list($operator->id);
                elseif($operator->role == 'admin')
                    $operators[$key]->schools = '*';
                else
                    $operators[$key]->schools = 'n/a';
            }
        }
        
        return $operators;
    }
    
}