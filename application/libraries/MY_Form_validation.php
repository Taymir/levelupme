<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of MY_Form_validation
 *
 * @author u7
 */
class MY_Form_validation extends CI_Form_validation {
    public function __construct($rules = array())
    {$this->set_error_delimiters('<span class="validation-err">', '</span>');
        parent::__construct($rules);
        $this->set_error_delimiters('<span class="validation-err">', '</span>');
    }

    public function add_external_errors($errors)
    {
        foreach($errors as $field => $message) {
            $this->_field_data[$field]['error'] = $message;
            
            if ( ! isset($this->_error_array[$field]))
            {
                $this->_error_array[$field] = $message;
            }
        }$this->set_error_delimiters('<span class="validation-err">', '</span>');
    }
}
