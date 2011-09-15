<?php

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
    private $table_name	= 'user_profiles';	// user profiles
    private $users_table_name = 'users';                     // users
    private $operators_table_name = 'operators_schools'; //Таблица разрешений для операторов
    
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
    
    public function getAccType()
    {
        return $this->getProperty('acc_type');
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
            $this->profileData = $this->get_user_profile($ci->tank_auth->get_user_id());//@TMP
    }
    
    public function loadUserData($id)
    {
        if($this->userData == self::unloaded)
            return $this->userData = $this->typical_find_obj($this->users_table_name, $id);
        else 
            return $this->userData;
    }
    
    public function get_user_profile($user_id)
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
    
    public function get_operators_school_list()
    {
        if($this->getRole() == 'operator')
        {
            $this->db->select('class_id');
            $this->db->from($this->operators_table_name);
            $this->db->where('user_id', $this->getId());
            $this->db->group_by('class_id');
            $query = $this->db->get();
            
            return $this->Arr2List($query->result_array(), 'class_id');
        }
        elseif ($this->getRole() == 'admin')
        {
            return '*';
        }
        else
            return null;
            
    }
}

?>
