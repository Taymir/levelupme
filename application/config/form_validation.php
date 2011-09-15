<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
$ci =& get_instance();

$config = array(
    /* ADMIN_PAGES */
    'admin_pages/edit' => array(
        array(
            'field' => 'title',
            'label' => 'Название',
            'rules' => 'trim|required'
        ),
        array(
            'field' => 'text',
            'label' => 'Содержимое',
            'rules' => 'trim|required|xss_clean'
        ),
        array(
            'field' => 'link',
            'label' => 'Ссылка',
            'rules' => 'trim|alpha_dash|callback_link_check'
        ),
    ),
    
    /* ADMIN_SCHOOLS */
    'admin_schools/add_school' => array(
        array(
            'field' => 'school',
            'label' => 'Название школы',
            'rules' => 'trim|required'
        )
    ),
    'admin_schools/add_class' => array(
        array(
            'field' => 'class',
            'label' => 'Название класса',
            'rules' => 'trim|required'
        ),
        array(
            'field' => 'school_id',
            'label' => 'Идентификатор школы',
            'rules' => 'required'
        )
    ),

    

);