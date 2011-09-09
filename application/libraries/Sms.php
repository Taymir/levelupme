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
    
    public function __construct()
    {
        $CI = & get_instance();
        
        $CI->load->model('sms_model');
        $CI->load->config('sms');
        $this->sms_model = $CI->sms_model;
        
        $this->debug_mode = $CI->config->item('debug_mode');
        $this->smsPilot = new SMSPilot($CI->config->item('api_key'), false, $CI->config->item('sender_name'));
    }
    
    public function sendSms($to, $text)
    {
        $data = $this->send($to, $text);
        $this->sms_model->add_sms($data);
    }
    
    private function send($to, $text)
    {
        if($this->debug_mode)
        {
            $status = 2;
            $tmp_id = -1;
        }
        else
        {
            $result = $this->smsPilot->send($to, $text);
            if($result == false)
            {
                if($this->smsPilot->error == 'CONNECTION ERROR')
                    $status = -4;
                else
                    $status = -3;
                $tmp_id = -1;
                log_message('error', "SMS to $to error: {$this->smsPilot->error}");
            } else {
                $status = $result[0]['status'];
                $tmp_id = $result[0]['id'];
            }
        }
        
        
        return array(
            'to' => $to,
            'text' => $text,
            'status' => $status,
            'tmp_id' => $tmp_id
        );
    }
    
    public function resend_sms_queue()
    {
        $unsent = $this->sms_model->get_unsent_sms();
        foreach($unsent as $sms)
        {
            $result = $this->send($sms->to, $sms->text);
            $this->sms_model->update_sms($sms->id, $result);
        }
        return TRUE;
    }
   
    
}

?>
