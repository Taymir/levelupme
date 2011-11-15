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
    protected $table_name	= 'user_profiles';               // user profiles
    protected $users_table_name = 'users';                 // users
    protected $classes_table_name = 'classes';               // Таблица классов
    
    const unloaded = NULL;
    protected $userData = self::unloaded;
    protected $profileData = self::unloaded;
    
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
        $profileData = $this->loadProfileData();
        if(isset($profileData->$propertyName))
                return $profileData->$propertyName;
        return null;
    }
    
    public function __construct()
    {
        parent::__construct();
        
        $this->loadProfileData();
    }
    
    public function loadProfileData()
    {
        if($this->profileData == self::unloaded) {
            $ci = & get_instance();
            $ci->load->library('tank_auth');

            if($ci->tank_auth->is_logged_in())
                return $this->profileData = $this->get_user_profile_by_user_id($ci->tank_auth->get_user_id());   
            else
                return $this->profileData = null;
        } else {
            return $this->profileData;
        }
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
                $this->table_name . '.banned,'.
                $this->table_name . '.order_num'
                );
        
        $this->db->from($this->table_name);
        $this->db->join($this->users_table_name, $this->table_name  . '.user_id = ' . $this->users_table_name . '.id', 'left');
        
        $this->db->where($this->table_name . '.class_id', $class_id);
        $this->db->order_by($this->table_name . '.order_num');
        //$this->db->order_by($this->table_name . '.name');
        
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
        
        $this->db->order_by($this->table_name . '.order_num');
        //$this->db->order_by('name');
        
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
        
        $this->db->order_by($this->table_name . '.order_num');
        //$this->db->order_by('name');
        
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
        
        $this->db->order_by($this->table_name . '.order_num');
        //$this->db->order_by('name');
        
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
        
        $this->db->order_by($this->table_name . '.order_num');
        //$this->db->order_by('name');
        
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
                $profile_id = $this->db->insert_id();
                $this->typical_update($this->table_name, $profile_data, $profile_id);//@TOTEST;
                return $profile_id;
            }
        } else {
            $profile_data['user_id'] = NULL;
            $this->typical_insert($this->table_name, $profile_data);
            $profile_id = $this->db->insert_id();
            return $profile_id;
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
    
    public function resort_users($class_id)
    {
        // начало транзакции
        $this->db->trans_start();
        // запрос на список юзеров в классе
        $this->db->select('id, name');
        $this->db->from($this->table_name);
        $this->db->where('class_id', $class_id);
        $users = $this->db->get()->result();
        
        // сортировка списка юзеров
        usort($users, array($this, 'name_cmp'));
        
        // сохранение ордер_нум-ов
        foreach($users as $key => $user)
        {
            $this->typical_update($this->table_name, array('order_num' => $key + 1), $user->id);
        }
        // конец транзакции
        $this->db->trans_complete();
    }
    
    public function name_cmp($a, $b)
    {
        return strcasecmp($a->name, $b->name);
    }
    
    public function update_user_order($user_profile_id, $order_num)
    {
        // начало транзакции
        $this->db->trans_start();
        // Получение информации о юзере
        $user_profile = $this->typical_find_obj($this->table_name, $user_profile_id);
        // Проверка на то, есть ли в данном классе юзер с таким ордер_нум-ом
        $this->db->select('id,order_num');
        $this->db->from($this->table_name);
        $this->db->where('class_id', $user_profile->class_id);
        $this->db->where('order_num', $order_num);
        $query = $this->db->get();
        
        if($query->num_rows() > 0)
        {
            // если есть, то два запроса на замену ордер-нумов между юзерами
            $other_user_profile = $query->row();
            
            $this->typical_update($this->table_name, array('order_num' => $user_profile->order_num), $other_user_profile->id);
        }
            // иначе один запрос на обновление ордер-нума
        $this->typical_update($this->table_name, array('order_num' => $order_num), $user_profile->id);
        // конец транзакции
        $this->db->trans_complete();
    }

}