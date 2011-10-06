<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

require_once('smspilot.class.php');
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of sms
 *
 * @author U7
 */
class Sms {
    private $smsPilot;
    private $debug_mode;
    private $sms_model;
    
    private $_to = '';
    private $_text = '';
    
    private $_dont_send_at_night = true;
    private $_begin_night_period;
    private $_end_night_period;
    
    public function __construct()
    {
        $CI = & get_instance();
        
        $CI->load->model('sms_model');
        $CI->load->config('sms');
        $this->sms_model = $CI->sms_model;
        
        $this->debug_mode = $CI->config->item('debug_mode');
        $this->smsPilot = new SMSPilot($CI->config->item('api_key'), false, $CI->config->item('sender_name'));
        
        $this->_begin_night_period = $CI->config->item('begin_night_period');
        $this->_end_night_period = $CI->config->item('end_night_period');
    }
    
    public function to($to)
    {
        return $this->_to = $to;
    }
    
    public function text($text)
    {
        return $this->_text = $text;
    }
    
    public function dont_send_at_night($flag = true)
    {
        $this->_dont_send_at_night = $flag;
    }
    
    public function is_at_night_period()
    {
        $hour = (int)date('G');
        if($hour >= $this->_begin_night_period && $hour < $this->_end_night_period)
            return true;
        return false;
    }
    
    public function send()
    {
        //@TODO: сюда можно добавить проверку баланса и оповещение о критическом балансе
        if(trim($this->_to) == '' || trim($this->_text) == '')
            return FALSE;
        
        if($this->_dont_send_at_night && $this->is_at_night_period())
            return FALSE;
        
        if($this->debug_mode) {
            $result[0]['status'] = 2;
            $result[0]['server_id'] = -1;
        } else {
            $result = $this->smsPilot->send($this->_to, $this->_text);

            if($result == false)
            {
                log_message('error', "SMS to {$this->_to} error: {$this->smsPilot->error}");
                return FALSE;
            }

            foreach($result as $sms)
            {
                if($sms['status'] == -2)
                {
                    log_message ('error', "SMS to {$this->_to} errorcode: {$sms['error']}");
                    return FALSE;
                }
            }
        }
            
        $this->sms_model->add_sms(array(
            'to' => $this->_to,
            'text' => $this->_text,
            'status' => $result[0]['status'],
            'tmp_id' => $result[0]['server_id']
        ));
        return TRUE;
    }
}

?>
