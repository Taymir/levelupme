<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of pages
 *
 * @author U7
 */
class pages extends MY_Controller {
    public function __construct() {
        parent::__construct();
        
        $this->use_short_view(false);
        $this->load->model('pages_model');
    }
    
    public function index()
    {
        return $this->display(1, "home");//@HARDFIX
    }
    
    public function display($id, $url = null)
    {
        $data = $this->pages_model->get_page($id);
        
        if($data == null)
            return show_404 ();
        
        return $this->load_view('pages/display_view', $data->title, array('page' => $data, 'page_name' => $url));
    }
}

?>
