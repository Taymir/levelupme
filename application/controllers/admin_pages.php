<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of admin_pages
 *
 * @author U7
 */
class Admin_pages extends MY_Controller {
    public function __construct()
    {
        parent::__construct('admin');
        
        $this->load->model('pages_model');
    }
    
    public function index()
    {
        $data = $this->pages_model->get_pages();
        
        $this->load_var('pages', $data);
        
        return $this->load_view('admin_pages/list_view', "Страницы");
    }
    
    public function create()
    {
        return $this->edit();
    }
    
    public function edit($id = null)
    {
        $id = $id ? $id : $this->input->post('id');
        $this->load->library('form_validation');
        
        if($this->form_validation->run()) 
        {
            // Если форма засабмичена
            $data = $this->get_post_params('link', 'title', 'text');
            
            if($id) {
                if($id == 1)
                    unset($data['link']);
                $result = $this->pages_model->save_page($id, $data);
            } else
                $result = $this->pages_model->create_page($data);
            
            $this->save_routes();
            
            return $this->redirect_message('admin_pages', "Страница сохранена");
        } else {
            // Если форма ещё не засабмичена
            if($id)
                $data = $this->pages_model->get_page($id);
            else
                $data = $this->get_empty_arr ('link', 'title', 'text');

            $this->load_scripts('mootools-core', 'mootools-more');
            $this->load_scripts('Locale/Locale.ru-RU.MooEditable', 'MooEditable/MooEditable', 'MooEditable/MooEditable.UI.MenuList', 'MooEditable/MooEditable.Extras');
            $this->load_styles('MooEditable/MooEditable', 'MooEditable/MooEditable.Extras', 'MooEditable/MooEditable.SilkTheme');
            return $this->load_view('admin_pages/edit_view', "Добавление страницы", $data);
        }
        
    }
    
    public function delete($id)
    {
        if($id == 1)
            return $this->show_message ("Нельзя удалять страницу со ссылкой home (главную страницу)");
        $this->pages_model->delete_page($id);
        $this->save_routes();
        
        return $this->redirect_message('/admin_pages', "Страница удалена");
    }
    
    
    private function save_routes()
    {
        // this simply returns all the pages from my database
        $routes = $this->pages_model->get_pages();

        // for every page in the database, get the route using the recursive function - _get_route()
        $data = array();
        foreach( $routes as $route )
        {
            $data[] = '$route["' . $route->link . '"] = "' . "pages/display/{$route->id}/{$route->link}" . '";';
        }

        $output = "<?php\n" . implode("\n", $data);

        $this->load->helper('file');
            
        write_file(APPPATH . "cache/routes.php", $output);
    }
    
    public function link_check($link)
    {
        $id = $this->input->post('id');
        $reserved_names = array('admin_operators', 'admin_schools', 'admin_users', 'ajax', 'auth', 'cron', 'operator_journal',
            'operator_messages', 'pages', 'tests', 'welcome', 'styles', 'scripts', 'join', 'schedule');
        if (in_array($link, $reserved_names)) {
            $this->form_validation->set_message('link_check', 'Поле %s не может содержать зарезервированные ссылки: ' . implode(", ", $reserved_names));
            return false;
        } else if($this->pages_model->link_exists($link, $id)){
            $this->form_validation->set_message('link_check', '%s уже используется');
            return false;
        } else {
            return true;
        }
        
    }
    
}
