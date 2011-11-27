<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
$ci =& get_instance();

$config = array(    
    /* REGISTRATION */
    'registration' => array(
        array(
            'field' => 'name_f',
            'label' => 'Фамилия',
            'rules' => 'trim|required'
        ),
        array(
            'field' => 'name_i',
            'label' => 'Имя',
            'rules' => 'trim|required'
        ),
        array(
            'field' => 'name_o',
            'label' => 'Отчество',
            'rules' => 'trim|required'
        ),
        array(
            'field' => 'school',
            'label' => 'Школа',
            'rules' => 'trim|required'
        ),
        array(
            'field' => 'class',
            'label' => 'Класс',
            'rules' => 'trim|required'
        ),
        array(
            'field' => 'pname_f',
            'label' => 'Фамилия',
            'rules' => 'trim|required'
        ),
        array(
            'field' => 'pname_i',
            'label' => 'Имя',
            'rules' => 'trim|required'
        ),
        array(
            'field' => 'pname_o',
            'label' => 'Отчество',
            'rules' => 'trim|required'
        ),
        array(
            'field' => 'mail',
            'label' => 'EMail',
            'rules' => 'trim|valid_email'
        ),
        array(
            'field' => 'phone',
            'label' => 'Телефон',
            'rules' => 'required|trim|callback_valid_phone'
        ),
        array(
            'field' => 'password',
            'label' => 'пароль',
            'rules' => 'alpha_numeric'
        ),
        array(
            'field' => 'confirm',
            'label' => 'подтверждение пароля',
            'rules' => 'matches[password]'
        ),
        array(
            'field' => 'tariff',
            'label' => 'Тариффный план',
            'rules' => 'callback_tariff_required'
        ),
        array(
            'field' => 'agreement',
            'label' => 'Договор',
            'rules' => 'callback_agreement_required'
        ),
    ),
    
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
            'field' => 'new_username',
            'label' => 'логин',
            'rules' => 'alpha_numeric|callback_username_available'
        ),
        array(
            'field' => 'new_password',
            'label' => 'пароль',
            'rules' => 'callback_password_required|alpha_numeric'
        ),
        array(
            'field' => 'confirm',
            'label' => 'подтверждение пароля',
            'rules' => 'callback_password_required|matches[new_password]'
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
            'field' => 'new_username',
            'label' => 'логин',
            'rules' => 'callback_username_available|alpha_numeric'
        ),
        array(
            'field' => 'new_password',
            'label' => 'пароль',
            'rules' => 'callback_password_required|alpha_numeric'
        ),
        array(
            'field' => 'confirm',
            'label' => 'подтверждение пароля',
            'rules' => 'callback_password_required|matches[new_password]'
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
            'field' => 'new_username',
            'label' => 'логин',
            'rules' => 'required|alpha_numeric|callback_username_available'
        ),
        array(
            'field' => 'new_password',
            'label' => 'пароль',
            'rules' => 'required|alpha_numeric'
        ),
        array(
            'field' => 'confirm',
            'label' => 'подтверждение пароля',
            'rules' => 'required|matches[new_password]'
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
    ),
    
    /* SETTINGS */
    'settings' => array(
        array(
            'field' => 'email',
            'label' => 'e-mail',
            'rules' => 'trim|valid_email'
        ),
        array(
            'field' => 'phone',
            'label' => 'телефон',
            'rules' => 'trim|callback_valid_phone'
        ),
        array(
            'field' => 'old_password',
            'label' => 'старый пароль',
            'rules' => ''
        ),
        array(
            'field' => 'new_password',
            'label' => 'новый пароль',
            'rules' => 'callback_password_required|alpha_numeric'
        ),
        array(
            'field' => 'confirm',
            'label' => 'подтверждение пароля',
            'rules' => 'callback_password_required|matches[new_password]'
        )
    )
    

);