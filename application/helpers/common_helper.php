<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

function colorify_name($name)
{
    $pos = strpos($name, ' ');
    if($pos !== false)
    {
        $name = substr_replace($name, '</span> <span class="nameColored">', $pos, 1);
    }
    $name = '<span class="surnameColored">' . $name . '</span>';
    return $name;
}

function encrypt($str){
  $ci = &get_instance();
  $key = $ci->config->item('passwords_crypt_key');
  $result = '';
  for($i=0; $i<strlen($str); $i++) {
     $char = substr($str, $i, 1);
     $keychar = substr($key, ($i % strlen($key))-1, 1);
     $char = chr(ord($char)+ord($keychar));
     $result.=$char;
  }
  return urlencode(base64_encode($result));
}


function decrypt($str){
  $str = base64_decode(urldecode($str));
  $result = '';
  $ci = &get_instance();
  $key = $ci->config->item('passwords_crypt_key');
  for($i=0; $i<strlen($str); $i++) {
    $char = substr($str, $i, 1);
    $keychar = substr($key, ($i % strlen($key))-1, 1);
    $char = chr(ord($char)-ord($keychar));
    $result.=$char;
  }
return $result;
}

function protect_password($password)
{
    $start = 'PASW<<';
    $end = '>>PASW';
    
    return $start . encrypt($password) . $end;
}

function unprotect_password($text)
{
    $start = 'PASW<<';
    $end = '>>PASW';
    
	$start_pos = strpos($text, $start);
	if ($start_pos === false) return $text;
	$end_pos = strpos($text, $end);
    if ($end_pos === false) return $text;
    
    $password = substr($text, $start_pos + strlen($start), $end_pos - $start_pos - strlen($start));
    $password = decrypt($password);
    $text = substr_replace($text, $password, $start_pos, $end_pos + strlen($end) - $start_pos);
    
	return $text;
}

function is_password_protected($text)
{
    $start = 'PASW<<';
    if(strpos($text, $start) !== FALSE)
        return true;
    return false;
}

function hide_password($text)
{
    $start = 'PASW<<';
    $end = '>>PASW';
    
	$start_pos = strpos($text, $start);
	if ($start_pos === false) return $text;
	$end_pos = strpos($text, $end);
    if ($end_pos === false) return $text;
    
    $password = substr($text, $start_pos + strlen($start), $end_pos - $start_pos - strlen($start));
    $password = "XXXXXX";
    $text = substr_replace($text, $password, $start_pos, $end_pos + strlen($end) - $start_pos);
    
	return $text;
}