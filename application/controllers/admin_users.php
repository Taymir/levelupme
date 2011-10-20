<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of admin_users
 *
 * @author U7
 */
class admin_users extends MY_Controller {
    public function __construct()
    {
        parent::__construct('admin');
        
        $this->load->model('user_profile_model');
    }
    
    public function index()
    {
        $this->load->model('classes_model');
        $class = $this->operator_class();
        $schools_classes = $this->classes_model->get_schools_and_classes();
        
        if(isset($class)) {
            $data = $this->user_profile_model->get_users_by_class($class->id);
            $this->load_var('users', $data);
        }
        
        $this->load_var('schools_classes', $schools_classes);
        $this->load_scripts('mootools-core', 'schoolClassWidget');
        
        return $this->load_view('admin_users/list_view', "Пользователи");
    }
    
    public function add_user($class_id = null)
    {
        $this->load->library('form_validation');
        if($class_id == null)
            $class_id = $this->input->post('class_id');
        
        if($this->form_validation->run('admin_users/add_user'))
        {
            $data = $this->get_post_params('name', 'class_id', 'tariff', 'phone', 'email');
            $data['username'] = $this->input->post('new_username');
            $data['password'] = $this->input->post('new_password');
            $data['phone'] = $this->clean_phone_number($data['phone']);
            $data['class_id'] = (int)$data['class_id'];
            
            $this->user_profile_model->add_user_profile($data);
            return $this->redirect_message('/admin_users?class=' . $data['class_id'], "Пользователь добавлен");//@TODO: заменить на /admin_users/class_id
        }
        $this->load->model('classes_model');
        $this->load->model('tariffs_model');
        $schools_classes = $this->classes_model->get_schools_and_classes();
        $tariffs = $this->tariffs_model->get_tariffs_for_selector();
        
        $this->load_scripts('mootools-core', 'mootools-more', 'MUX.Dialog', 'showDialog');
        $this->load_style('MUX.Dialog');
        $this->load_var('schools_classes', $schools_classes);
        $this->load_var('default_class', $class_id);
        $this->load_var('tariffs', $tariffs);
        return $this->load_view('admin_users/add_user_view', "Добавление пользователя");
    }
    
    public function edit_user($profile_id = null)
    {
        $this->load->library('form_validation');
        if($profile_id == null)
            $profile_id = $this->input->post('profile_id');
        
        if($this->form_validation->run('admin_users/edit_user'))
        {
            $data = $this->get_post_params('name', 'class_id', 'tariff', 'phone', 'email');
            $data['phone'] = $this->clean_phone_number($data['phone']);
            $data['class_id'] = (int)$data['class_id'];
            
            if($this->input->post('new_username')) {
                // Зарегистрировать пользователя
                $data['username'] = $this->input->post('new_username');
                $data['password'] = $this->input->post('new_password');
                
            } elseif ($this->input->post('change_password') == '1') {
                // Сменить пароль пользователя
                $data['password'] = $this->input->post('new_password');
            }
            
            $this->user_profile_model->save_user_profile($profile_id, $data);

            return $this->redirect_message('/admin_users?class=' . $data['class_id'], "Пользователь обновлен");
        }
        
        $this->load->model('classes_model');
        $this->load->model('tariffs_model');
        $schools_classes = $this->classes_model->get_schools_and_classes();
        $tariffs = $this->tariffs_model->get_tariffs_for_selector();

        $profile = $this->user_profile_model->get_user_profile($profile_id);
        if($profile->user_id)
        {
            $user = $this->user_profile_model->get_user($profile->user_id);
            $this->load_var('user', $user);
        }
        
        $this->load_scripts('mootools-core', 'mootools-more', 'MUX.Dialog', 'showDialog');
        $this->load_style('MUX.Dialog');
        $this->load_var('schools_classes', $schools_classes);
        $this->load_var('profile', $profile);
        $this->load_var('default_class', $profile->class_id);
        $this->load_var('tariffs', $tariffs);
        return $this->load_view('admin_users/add_user_view', "Изменение пользователя");
    }
    
    public function password_required($password)
    {
        if(($this->input->post('change_password') == '1' || $this->input->post('username') != '') && $password == '')
        {
            $this->form_validation->set_message('password_required', "Введите новый пароль");
            return false;
        }
        return true;
    }
    
    public function username_available($username)
    {
        $this->load->model('tank_auth/users');
        
        if(!$this->users->is_username_available($username))
        {
            $this->form_validation->set_message('username_available', "Данный логин уже используется");
            return false;
        }
        
        return true;
    }
    
    public function mass_add_user($class_id = null)
    {
        $this->load->library('form_validation');
        $this->load->model('tariffs_model');
        if($class_id == null)
            $class_id = $this->input->post('class_id');
        
        if($this->form_validation->run())
        {
            $data = $this->input->post('users');
            // 1. Чистка данных
            $data = str_replace(array("\t", ',', ';'), ' ', $data);
            $data = str_replace(array("\r"), '', $data);
            $data = preg_replace('/ +/', ' ', $data);
            
            // 2. Выделение строк
            $rows = explode("\n", $data);
            
            // 3. Выделение полей
            $batch_data = array();
            foreach($rows as $row)
            {
                $row = trim($row);
                if($row == '')
                    continue;
                
                $batch_row = $this->get_empty_arr('name', 'tariff', 'phone', 'email', 'class_id');
                $fields = explode(' ', $row);
                for($i = sizeof($fields) - 1; $i >= 0; $i--)
                {
                    $val = $fields[$i];
                    if($this->valid_phone($val)) {
                        $batch_row['phone'] = $this->clean_phone_number($val);
                        unset($fields[$i]);
                    } elseif($this->form_validation->valid_email($val)) {
                        $batch_row['email'] = $val;
                        unset($fields[$i]);
                    } elseif($this->tariffs_model->is_valid_tariff_shortname($val)) {
                        $batch_row['tariff'] = $this->tariffs_model->get_tariff_by_shortname($val);
                        unset($fields[$i]);
                    } elseif(in_array($val, array('руб', 'руб.', 'р', 'р.'))) {
                        unset($fields[$i]);
                    }
                }
                $batch_row['name'] = trim(implode(' ', $fields));
                $batch_row['class_id'] = $class_id;
                
                if($batch_row['name'] == '')
                    continue;
                
                $batch_data[] = $batch_row;
            }
            
            $this->user_profile_model->batch_add_users($batch_data);
            
            return $this->redirect_message(array('admin_users', '?class=' . $class_id), "Ученики добавлены");//@TODO: заменить на /admin_users/class_id
        }
        $this->load->model('classes_model');
        $schools_classes = $this->classes_model->get_schools_and_classes();
        
        $this->load_scripts('mootools-core', 'mootools-more', 'MUX.Dialog', 'showDialog');
        $this->load_style('MUX.Dialog');
        $this->load_var('schools_classes', $schools_classes);
        $this->load_var('default_class', $class_id);
        return $this->load_view('admin_users/mass_add_user', "Массовое добавление учеников");
    }
    
    public function ban_user($profile_id)
    {
        $this->user_profile_model->ban_user($profile_id);
        
        return $this->redirect_message('admin_users', "Пользователь заблокирован");
    }
    
    public function unban_user($profile_id)
    {
        $this->user_profile_model->unban_user($profile_id);
        
        return $this->redirect_message('admin_users', "Пользователь разблокирован");
    }
    
    public function remove_user($profile_id)
    {
        $this->user_profile_model->delete_user($profile_id);
        
        return $this->redirect_message('admin_users', "Пользователь удален");
    }
    
    private function clean_phone_number($phone)
    {
        $unwanted_symbols = array('(', ')', '+', '-', ' ', '.' );
        
        $phone = str_replace($unwanted_symbols, '', $phone);
        if(substr($phone, 0, 1) == '8')
        {
            $phone = substr_replace($phone, '7', 0, 1);
        } elseif(substr($phone, 0, 1) == '9') 
        {
            $phone = '7' . $phone;
        } elseif(substr($phone, 0, 1) != '7')
        {
            return NULL;
        }
        
        if(strlen($phone) == 11 && ctype_digit($phone))
            return $phone;
        else
            return NULL;
    }
    
    public function valid_phone($phone)
    {
        if($phone == '' || $this->clean_phone_number($phone))
            return TRUE;
        else
        {
            $this->form_validation->set_message('valid_phone', "В поле %s должен быть введен корретный телефонный номер сотового телефона");
            return FALSE;
        }
    }
    
}