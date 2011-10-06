<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

$config['api_key'] = "XXXXXXXXXXXXXXXXXXXXXXXXXXX";
$config['sender_name'] = "dnevnikam.net";
$config['debug_mode'] = true;  // Не отправляет смски, а только логирует их отправку
$config['begin_night_period'] = 4; // Не пытаться отправлять смски ночью с этого времени
$config['end_night_period'] = 10;   // Не пытаться отправлять смски ночью до этого времени