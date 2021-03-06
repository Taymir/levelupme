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
        if(ENVIRONMENT != 'development')
            $this->denyAccess ();
    }
    
    public function index()
    {        
       return $this->load_view('Tests/index_view', "Tests");
    }
    
    public function jsdialogs()
    {
        return $this->load_view('Tests/jsdialogs_view', 'Диалоги');
    }
    
    public function jsautocomplete()
    {
        return $this->load_view('Tests/jsautocomplete_view', 'Автоподстановка');
    }
    
    public function jsdatepicker()
    {
        return $this->load_view('Tests/jsdatepicker_view', 'Выбор даты');
    }
    
    public function jswysiwyg()
    {
        return $this->load_view('Tests/jswysiwyg_view', 'Текстовый редактор');
    }
    
    public function smsmockup()
    {
        if($this->input->post('text'))
        {
            $this->load->library('sms');
            $this->sms->sendSms($this->input->post('to'), $this->input->post('text'));
            
            return $this->redirect_message('/Tests/smsmockup', "Сообщение отправлено");
        }
        return $this->load_view('Tests/smsmockup_view', 'Заглушка отправки СМС');
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
