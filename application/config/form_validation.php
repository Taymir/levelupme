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
        )
    ),
    
    /* ADMIN_USERS */
    'admin_users/mass_add_user' => array(
        array(
            'field' => 'users',
            'label' => 'Список пользователей',
            'rules' => 'required'
        ),
        array(
            'field' => 'class_id',
            'label' => 'Класс',
            'rules' => 'required'
        ),
    ),
    'admin_users/add_user' => array(
        array(
            'field' => 'username',
            'label' => 'логин',
            'rules' => 'alpha_numeric|callback_username_available'
        ),
        array(
            'field' => 'password',
            'label' => 'пароль',
            'rules' => 'callback_password_required|alpha_numeric'
        ),
        array(
            'field' => 'confirm',
            'label' => 'подтверждение пароля',
            'rules' => 'callback_password_required|matches[password]'
        ),
        array(
            'field' => 'name',
            'label' => 'имя ученика',
            'rules' => 'trim|required'
        ),
        array(
            'field' => 'class_id',
            'label' => 'класс',
            'rules' => 'required'
        ),
        array(
            'field' => 'tariff',
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
    ),
    'admin_users/edit_user' => array(
        array(
            'field' => 'username',
            'label' => 'логин',
            'rules' => 'callback_username_available|alpha_numeric'
        ),
        array(
            'field' => 'password',
            'label' => 'пароль',
            'rules' => 'callback_password_required|alpha_numeric'
        ),
        array(
            'field' => 'confirm',
            'label' => 'подтверждение пароля',
            'rules' => 'callback_password_required|matches[password]'
        ),
        array(
            'field' => 'name',
            'label' => 'имя ученика',
            'rules' => 'trim|required'
        ),
        array(
            'field' => 'class_id',
            'label' => 'класс',
            'rules' => 'required'
        ),
        array(
            'field' => 'tariff',
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
        ),
        array(
            'field' => 'change_password',
            'label' => 'смена пароля',
            'rules' => ''
        )
    ),
    
    /* ADMIN_OPERATORS */
    'admin_operators/add_operator' => array(
        array(
            'field' => 'username',
            'label' => 'логин',
            'rules' => 'required|alpha_numeric|callback_username_available'
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
            'label' => 'имя',
            'rules' => 'trim'
        ),
        array(
            'field' => 'email',
            'label' => 'email',
            'rules' => 'trim|valid_email'
        ),
        array(
            'field' => 'schools',
            'label' => 'школы',
            'rules' => 'required'
        )
    )

    

);