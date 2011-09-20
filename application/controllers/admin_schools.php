<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of admin_schools
 *
 * @author U7
 */
class admin_schools extends MY_Controller {
    public function __construct()
    {
        parent::__construct('admin');
        
        $this->load->model('schools_model');
        $this->load->model('classes_model');
    }
    
    public function index()
    {
        $data = $this->classes_model->get_schools_and_classes();

        $this->load_var('schools', $data);
        
        return $this->load_view('admin_schools/list_view', "Школы и классы");
    }
    
    public function add_school()
    {
        $this->load->library('form_validation');
        
        if($this->form_validation->run())
        {
            $data = $this->get_post_params('school');
            $this->schools_model->add_school($data);
            return $this->redirect_message('admin_schools', "Школа добавлена");
        }
        
        return $this->load_view('admin_schools/add_school_view', "Добавление школы");
    }
    
    public function add_class($school_id)
    {
        $this->load->library('form_validation');
                
        //@BUGFIX: из-за 1 GET-параметра, form_validation не способен сам определить раздел конфига
        if($this->form_validation->run('admin_schools/add_class')) 
        {
            $data = $this->get_post_params('school_id', 'class');
            $this->classes_model->add_class($data);
            return $this->redirect_message('admin_schools', "Класс добавлен");
        } 
        
        $this->load_var('school_id', $school_id);
        return $this->load_view('admin_schools/add_class_view', "Добавление класса");
    }
    
    public function remove_class($class_id)
    {
        $this->classes_model->remove_class($class_id);
        
        return $this->redirect_message('admin_schools', "Класс удален");
    }
    
    public function remove_school($school_id)
    {
        $this->schools_model->remove_school($school_id);
        
        return $this->redirect_message('admin_schools', "Школа удалена");
    }
}

?>
