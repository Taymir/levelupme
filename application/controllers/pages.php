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

        $this->load->model('pages_model');
    }
    
    public function index()
    {
        return $this->display(7);//@TMP
    }
    
    public function display($id)
    {
        $data = $this->pages_model->get_page($id);
        
        if($data == null)
            return show_404 ();
        
        $this->load_var('page', $data);
        return $this->load_view('pages/display_view', $data->title);
    }
}

?>
