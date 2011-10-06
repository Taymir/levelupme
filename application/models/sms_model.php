<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of sms_model
 *
 * @author U7
 */
class sms_model extends MY_Model {
    private $table_name = 'sms';
    
    public function add_sms($data)
    {
        $data = $this->add_created_field($data);
        return $this->typical_insert($this->table_name, $data);
    }
}
