<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of user_profile_model
 *
 * @author U7
 */
class user_profile_model extends MY_Model {
    private $table_name	= 'user_profiles';               // user profiles
    private $users_table_name = 'users';                 // users
    private $operators_table_name = 'operators_schools'; //Таблица разрешений для операторов
    private $classes_table_name = 'classes';               // Таблица классов
    
    const unloaded = -1;
    
    private $userData = self::unloaded;
    private $profileData = null;
    
    public function getId()
    {
        return $this->getProperty('user_id');
    }
    
    public function getUsername()
    {
        return $this->loadUserData($this->getProperty('user_id'))->username;
    }
    
    public function getEmail()
    {
        return $this->loadUserData($this->getProperty('user_id'))->email;
    }
    
    public function getName()
    {
        return $this->getProperty('name');
    }
    
    public function getPhone()
    {
        return $this->getProperty('phone');
    }
    
    public function getRole()
    {
        return $this->getProperty('role');
    }
    
    public function getTariff()
    {
        return $this->getProperty('tariff');
    }
    
    public function getClassName()
    {
        //@TODO
    }
    
    public function getSchoolName()
    {
        //@TODO
    }
    
    public function getProperty($propertyName)
    {
        if($this->profileData != null && isset($this->profileData->$propertyName))
                return $this->profileData->$propertyName;
        return null;
    }
    
    public function __construct()
    {
        parent::__construct();
        
        $this->loadProfileData();
    }
    
    public function loadProfileData()
    {
        $ci = & get_instance();
        $ci->load->library('tank_auth');
        
        if($ci->tank_auth->is_logged_in())
            $this->profileData = $this->get_user_profile_by_user_id($ci->tank_auth->get_user_id());//@TMP
    }
    
    public function loadUserData($id)
    {
        if($this->userData == self::unloaded)
            return $this->userData = $this->typical_find_obj($this->users_table_name, $id);
        else 
            return $this->userData;
    }
    
    public function get_user_profile($profile_id)
    {
        $this->db->select('*');

        $this->db->from($this->table_name);
        $this->db->where('id', $profile_id);

        $query = $this->db->get();
        if ($query->num_rows() == 1) return $query->row();
            return NULL;
    }
    
    public function get_user_profile_by_user_id($user_id)
    {
        $this->db->select('*');

        $this->db->from($this->table_name);
        $this->db->where('user_id', $user_id);

        $query = $this->db->get();
        if ($query->num_rows() == 1) return $query->row();
            return NULL;
    }
    
    public function get_user($id)
    {
        return $this->typical_find_obj($this->users_table_name, $id);
    }
    
    public function get_operators_school_list($operator = null)
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
    
    public function check_class_against_schoollist($class_id, $schoollist)
    {
        if($schoollist == '*')
            return true;
        elseif($schoolist == null)
            return false;
        
        // Делаем запрос на право доступа оператором к данному классу
        $this->db->select('COUNT(*) as count', FALSE);
        $this->db->from($this->classes_table_name);
        $this->db->where($this->classes_table_name . '.id', $class_id);
        $this->db->where_in($this->classes_table_name . '.school_id', $schoollist);
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
    
    public function get_users_by_class($class_id)
    {
        $this->db->select(
                $this->users_table_name . '.id,'.
                $this->users_table_name . '.username,'.
                $this->users_table_name . '.email,'.
                
                $this->table_name . '.id AS profile_id,'.
                $this->table_name . '.name,'.
                $this->table_name . '.phone,'.
                $this->table_name . '.tariff,'.
                $this->table_name . '.banned'
                );
        
        $this->db->from($this->table_name);
        $this->db->join($this->users_table_name, $this->table_name  . '.user_id = ' . $this->users_table_name . '.id', 'left');
        
        $this->db->where($this->table_name . '.class_id', $class_id);
        $this->db->order_by($this->table_name . '.name');
        
        $query = $this->db->get();
        return $query->result();
    }
    
    public function get_userlist_by_profile_ids($ids_list)
    {
        $this->db->select('*');
        $this->db->select('id AS user_profile_id');
        $this->db->from($this->table_name);
        $this->db->where_in('id', $ids_list);
        
        $query = $this->db->get();
        
        $res = array();
        foreach($query->result() as $row)
            $res[$row->user_profile_id] = $row;
        return $res;
    }
    
    public function get_userlist_by_class($class_id, $min_tariff = NULL)
    {
        $this->db->select('id AS user_profile_id, name' );
        $this->db->from($this->table_name);
        $this->db->where('class_id', $class_id);
        if($min_tariff != NULL)
            $this->db->where('tariff >=', $min_tariff);
        
        $this->db->order_by('name');
        
        $query = $this->db->get();
        return $this->Arr2List($query->result_array(), 'user_profile_id', 'name');
    }
    
    public function get_users_by_class_without_school($class_id, $min_tariff = NULL)
    {
        $this->db->select('*');
        $this->db->from($this->table_name);
        $this->db->where('class_id', $class_id);
        if($min_tariff != NULL)
            $this->db->where('tariff >=', $min_tariff);
        
        $this->db->order_by('name');
        
        $query = $this->db->get();
        return $query->result();
    }
    
    public function get_userlist_by_school($school_id, $min_tariff = NULL)
    {
        $ci = & get_instance();
        $ci->load->model('classes_model');
        $classes = $ci->classes_model->get_classlist_by_school($school_id);
        $classes = array_keys($classes);
        
        $this->db->select('id AS user_profile_id, name' );
        $this->db->from($this->table_name);
        $this->db->where_in('class_id', $classes);
        if($min_tariff != NULL)
            $this->db->where('tariff >=', $min_tariff);
        
        $this->db->order_by('name');
        
        $query = $this->db->get();
        return $this->Arr2List($query->result_array(), 'user_profile_id', 'name');
    }
    
    public function get_users_by_school($school_id, $min_tariff = NULL)
    {
        $ci = & get_instance();
        $ci->load->model('classes_model');
        $classes = $ci->classes_model->get_classlist_by_school($school_id);
        $classes = array_keys($classes);
        
        $this->db->select('*');
        $this->db->from($this->table_name);
        $this->db->where_in('class_id', $classes);
        if($min_tariff != NULL)
            $this->db->where('tariff >=', $min_tariff);
        
        $this->db->order_by('name');
        
        $query = $this->db->get();
        return $query->result();
    }
    
    public function batch_add_users($batch_data)
    {
        if(sizeof($batch_data) > 0)
            $this->db->insert_batch ($this->table_name, $batch_data);
        
        return sizeof($batch_data);
        
    }
    
    public function add_user_profile($data)
    {
        $profile_data = array(
            'name' => $data['name'],
            'tariff' => $data['tariff'],
            'phone' => $data['phone'],
            'role' => 'parent',
            'class_id' => $data['class_id'],
            'email' => $data['email']
        );
        $username = $data['username'];
        $email = $data['email'];
        $password = $data['password'];
        
        $ci = & get_instance();
        $ci->load->library('tank_auth');
        
        if($username != '')
        {
            $result = $ci->tank_auth->create_user($username, $email, $password, false);
            if($result)
            {
                return $this->typical_update($this->table_name, $profile_data, $result['user_id'], 'user_id');
            }
        } else {
            $profile_data['user_id'] = NULL;
            return $this->typical_insert($this->table_name, $profile_data);
        }
        
        return FALSE;
    }
    
    public function save_user_profile($profile_id, $data)
    {
        $result = true;
        if(isset($data['password']))
        {
            $result = false;
            $ci = & get_instance();
            $ci->load->library('tank_auth');
        
            $password = $data['password'];

            if(isset($data['username']))
            {
                $username = $data['username'];
                $result = $ci->tank_auth->create_user($username, $data['email'], $password, false, false);
                $data['user_id'] = $result['user_id'];
            } else {
                $profile = $this->typical_find($this->table_name, $profile_id);
                if($profile->user_id)
                    $result = $ci->tank_auth->set_new_password($profile->user_id, $password);
            }
        }
        unset($data['username']);
        unset($data['password']);
        
        if($result)
            return $this->typical_update($this->table_name, $data, $profile_id);
        return $result;
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
    
    public function ban_user($user_profile_id)
    {
        $ci = & get_instance();
        $ci->load->model('tank_auth/users');
        
        $this->typical_update($this->table_name, array('banned' => 1), $user_profile_id);
        $user_profile = $this->typical_find($this->table_name, $user_profile_id);
        
        return $ci->users->ban_user($user_profile->user_id);
    }
    
    public function unban_user($user_profile_id)
    {
        $ci = & get_instance();
        $ci->load->model('tank_auth/users');
        
        $this->typical_update($this->table_name, array('banned' => 0), $user_profile_id);
        $user_profile = $this->typical_find($this->table_name, $user_profile_id);
        
        return $ci->users->unban_user($user_profile->user_id);
    }
    
    public function delete_user($user_profile_id)
    {
        $ci = & get_instance();
        $ci->load->model('tank_auth/users');
        
        $user_profile = $this->typical_find($this->table_name, $user_profile_id);
        if($user_profile->user_id === null)
            return $this->typical_delete ($this->table_name, $user_profile_id);
        else 
            return $this->users->delete_user($user_profile->user_id);
    }

}