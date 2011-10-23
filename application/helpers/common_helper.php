<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

function colorify_name($name)
{
    $pos = strpos($name, ' ');
    if($pos !== false)
    {
        $name = substr_replace($name, ' <span class="nameColored">', $pos, 1);
        $name .= "</span>";
    }
    return $name;
}