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
    $ordered = array();// БАГ - не то что надо
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