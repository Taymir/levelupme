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

/**
 * array_merge_recursive does indeed merge arrays, but it converts values with duplicate
 * keys to arrays rather than overwriting the value in the first array with the duplicate
 * value in the second array, as array_merge does. I.e., with array_merge_recursive,
 * this happens (documented behavior):
 * 
 * array_merge_recursive(array('key' => 'org value'), array('key' => 'new value'));
 *     => array('key' => array('org value', 'new value'));
 * 
 * array_merge_recursive_distinct does not change the datatypes of the values in the arrays.
 * Matching keys' values in the second array overwrite those in the first array, as is the
 * case with array_merge, i.e.:
 * 
 * array_merge_recursive_distinct(array('key' => 'org value'), array('key' => 'new value'));
 *     => array('key' => array('new value'));
 * 
 * Parameters are passed by reference, though only for performance reasons. They're not
 * altered by this function.
 * 
 * @param array $array1
 * @param mixed $array2
 * @return array
 * @author daniel@danielsmedegaardbuus.dk
 */
function &array_merge_recursive_distinct(array &$array1, &$array2 = null)
{
  $merged = $array1;
  
  if (is_array($array2))
    foreach ($array2 as $key => $val)
      if (is_array($array2[$key]))
        $merged[$key] = (isset($merged[$key]) && is_array($merged[$key])) ? array_merge_recursive_distinct($merged[$key], $array2[$key]) : $array2[$key];
      else
        $merged[$key] = $val;
  
  return $merged;
}

function sortArrayByArray($array,$orderArray) {
    $ordered = array();
    foreach($orderArray as $val) {
        if(in_array($val, $array)) {
                $ordered[] = $val;
                array_remove_value($array, $val);
        }
    }
    return $ordered + $array;
}

# remove by key:
function array_remove_key ()
{
  $args  = func_get_args();
  return array_diff_key($args[0],array_flip(array_slice($args,1)));
}
# remove by value:
function array_remove_value ()
{
  $args = func_get_args();
  return array_diff($args[0],array_slice($args,1));
}

function rus2translit($string)
{
    $converter = array(
        'а' => 'a',   'б' => 'b',   'в' => 'v',
        'г' => 'g',   'д' => 'd',   'е' => 'e',
        'ё' => 'e',   'ж' => 'zh',  'з' => 'z',
        'и' => 'i',   'й' => 'y',   'к' => 'k',
        'л' => 'l',   'м' => 'm',   'н' => 'n',
        'о' => 'o',   'п' => 'p',   'р' => 'r',
        'с' => 's',   'т' => 't',   'у' => 'u',
        'ф' => 'f',   'х' => 'h',   'ц' => 'c',
        'ч' => 'ch',  'ш' => 'sh',  'щ' => 'sch',
        'ь' => "'",  'ы' => 'y',   'ъ' => "'",
        'э' => 'e',   'ю' => 'yu',  'я' => 'ya',
 
        'А' => 'A',   'Б' => 'B',   'В' => 'V',
        'Г' => 'G',   'Д' => 'D',   'Е' => 'E',
        'Ё' => 'E',   'Ж' => 'Zh',  'З' => 'Z',
        'И' => 'I',   'Й' => 'Y',   'К' => 'K',
        'Л' => 'L',   'М' => 'M',   'Н' => 'N',
        'О' => 'O',   'П' => 'P',   'Р' => 'R',
        'С' => 'S',   'Т' => 'T',   'У' => 'U',
        'Ф' => 'F',   'Х' => 'H',   'Ц' => 'C',
        'Ч' => 'Ch',  'Ш' => 'Sh',  'Щ' => 'Sch',
        'Ь' => "'",  'Ы' => 'Y',   'Ъ' => "'",
        'Э' => 'E',   'Ю' => 'Yu',  'Я' => 'Ya',
    );
    return strtr($string, $converter);
}

/**
 * Function: sanitize
 * Returns a sanitized string, typically for URLs.
 *
 * Parameters:
 *     $string - The string to sanitize.
 *     $force_lowercase - Force the string to lowercase?
 *     $anal - If set to *true*, will remove all non-alphanumeric characters.
 */
function sanitize($string, $force_lowercase = true, $anal = false) {
    $strip = array("~", "`", "!", "@", "#", "$", "%", "^", "&", "*", "(", ")", "_", "=", "+", "[", "{", "]",
                   "}", "\\", "|", ";", ":", "\"", "'", "&#8216;", "&#8217;", "&#8220;", "&#8221;", "&#8211;", "&#8212;",
                   "â€”", "â€“", ",", "<", ".", ">", "/", "?");
    $clean = trim(str_replace($strip, "", strip_tags($string)));
    $clean = preg_replace('/\s+/', "-", $clean);
    $clean = ($anal) ? preg_replace("/[^a-zA-Z0-9]/", "", $clean) : $clean ;
    return ($force_lowercase) ?
        (function_exists('mb_strtolower')) ?
            mb_strtolower($clean, 'UTF-8') :
            strtolower($clean) :
        $clean;
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


function decrypt($str)
{
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

function week2times($week, $year)
{
    $start = strtotime($year . 'W' . $week . '1');
    $end   = strtotime($year . 'W' . $week . '7');
    
    return array($start, $end);
}

function date2day($datestr)
{
    // Получение дня недели
    $time = strtotime(str_replace(array(',-/'), '.', $datestr));
    $day  = (int)date('w', $time);
    // FIX for dates starting with saturday
    if($day == 0)
        $day = 7;
    
    return $day;
}

function russian_date($datestr)
{
    $time = strtotime(str_replace(array(',-/'), '.', $datestr));
    $format = 'l, j F';
    
    if(date('Y') != date('Y', $time))
        $format .= ', Y г.';

    // russify days
    switch(date('l', $time)) {
        case "Monday" : $day_replacement = "Понедельник"; break;
        case "Tuesday" : $day_replacement = "Вторник"; break;
        case "Wednesday" : $day_replacement = "Среда"; break;
        case "Thursday" : $day_replacement = "Четверг"; break;
        case "Friday" : $day_replacement = "Пятница"; break;
        case "Saturday" : $day_replacement = "Суббота"; break;
        case "Sunday" : $day_replacement = "Воскресенье"; break;                        
    };
    $format = str_replace('l', $day_replacement, $format);
    
    // russify months
    switch(date('m', $time)) {
        case 1: $month_replacement='января'; break;
        case 2: $month_replacement='февраля'; break;
        case 3: $month_replacement='марта'; break;
        case 4: $month_replacement='апреля'; break;
        case 5: $month_replacement='мая'; break;
        case 6: $month_replacement='июня'; break;
        case 7: $month_replacement='июля'; break;
        case 8: $month_replacement='августа'; break;
        case 9: $month_replacement='сентября'; break;
        case 10: $month_replacement='октября'; break;
        case 11: $month_replacement='ноября'; break;
        case 12: $month_replacement='декабря'; break;
    }
    $format = str_replace('F', $month_replacement, $format);
    
    return date($format, $time);
}