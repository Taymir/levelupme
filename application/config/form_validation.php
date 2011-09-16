<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
$ci =& get_instance();

$config = array(
    /* ADMIN_PAGES */
    'admin_pages/edit' => array(
        array(
            'field' => 'title',
            'label' => 'название',
            'rules' => 'trim|required'
        ),
        array(
            'field' => 'text',
            'label' => 'содержимое',
            'rules' => 'trim|required|xss_clean'
        ),
        array(
            'field' => 'link',
            'label' => 'ссылка',
            'rules' => 'trim|alpha_dash|callback_link_check'
        ),
    ),
    
    /* ADMIN_SCHOOLS */
    'admin_schools/add_school' => array(
        array(
            'field' => 'school',
            'label' => 'название школы',
            'rules' => 'trim|required'
        )
    ),
    'admin_schools/add_class' => array(
        array(
            'field' => 'class',
            'label' => 'название класса',
            'rules' => 'trim|required'
        ),
        array(
            'field' => 'school_id',
            'label' => 'идентификатор школы',
            'rules' => 'required'
        )
    ),
    
    /* ADMIN_USERS */
    'admin_users/add_user' => array(
        array(
            'field' => 'username',
            'label' => 'логин',
            'rules' => 'required|alpha_numeric'
        ),
        array(
            'field' => 'password',
            'label' => 'пароль',
            'rules' => 'required|alpha_numeric'
        ),
        array(
            'field' => 'confirm',
            'label' => 'подтверждение пароля',
            'rules' => 'required|matches[password]'
        ),
        array(
            'field' => 'name',
            'label' => 'имя ученика',
            'rules' => 'trim|required'
        ),
        array(
            'field' => 'class_id',
            'label' => 'класс',
            'rules' => 'required|integer'
        ),
        array(
            'field' => 'acc_type',
            'label' => 'тариф',
            'rules' => 'trim'
        ),
        array(
            'field' => 'phone',
            'label' => 'телефон',
            'rules' => 'trim|callback_valid_phone'
        ),
        array(
            'field' => 'email',
            'label' => 'email',
            'rules' => 'trim|valid_email'
        )
    )

    

);