<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Description of Tests
 *
 * @author U7
 */
class Tests extends MY_Controller {
    public function __construct()
    {
        parent::__construct();
        
        //$this->load->model('name_model');
    }
    
    public function index()
    {
        return $this->load_view('Tests/index_view', "Tests");
    }
    
    public function jsdialogs()
    {
        
    }
    
    public function jsautocomplete()
    {
        
    }
    
    public function jsdatepicker()
    {
        
    }
    
    /*
     * public function action()
     * {
     * //@TODO
     * return $this->load_view('controller/action_view', "Page Title");
     * }
     */
}

?>
