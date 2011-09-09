<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
$ci =& get_instance();

$config = array(
    /* ADMIN_PAGES */
    'admin_pages/edit' => array(
        array(
            'field' => 'title',
            'label' => 'Название',
            'rules' => 'trim|required|xss_clean'
        ),
        array(
            'field' => 'text',
            'label' => 'Содержимое',
            'rules' => 'trim|required|xss_clean'
        ),
        array(
            'field' => 'link',
            'label' => 'Ссылка',
            'rules' => 'trim|required|xss_clean|alpha_dash|callback_link_check'
        ),
    ),

);