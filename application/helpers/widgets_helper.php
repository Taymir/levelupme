<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

function school_class_widget($schoolClassData, $target)
{
    $ci = & get_instance();
    $ci->load->helper('form');
    
    $out = form_open($target);
    
    $out .= "
<script type=\"text/javascript\">    
        var classes = new Array();\n";

    $schools = array();
    $classes = array();
    foreach($schoolClassData as $school)
    {
        $school_id = (int)$school->id;
        $schools[$school_id] = $school->school;
        
        $classes[$school_id] = array();
        $classesTmp          = array();
        foreach($school->classes as $class)
        {
            $class_id = (int)$class->id;
            $classes[$school_id][] = $class->class;
            
            $classesTmp[] = ' "' . $class->id . '":"' . addslashes($class->class) . '"';
        }
        $out .= "classes[$school->id] = ";
        $out .= '{' . implode(',', $classesTmp) . "};\n";
    }
        
    $out .= "
    var updateClassListEx = function()
    {
        updateClassList(classes, $('schoolselector'), $('classselector'));
    }
</script>
        ";
    $school_ids = array_keys($schools);
    $first_school_classes = $classes[$school_ids[0]];
    
    $out .= form_dropdown('school', $schools, NULL, 'id="schoolselector" onchange="updateClassListEx()"');
    $out .= form_dropdown('class', $first_school_classes, NULL, 'id="classselector"');//@TODO cookies
    $out .= form_submit('submit', "OK", 'id="submit"');
    $out .= form_close();
    
    return $out;
}

function get_next_dialog_id()
{
    static $dialog_id = 0;
    return $dialog_id++;
}

function form_class_selector($schoolsData, $linkText)
{
    $dialog_id = get_next_dialog_id();
    $dialog_title = "Выбор класса";
    
    $ci = & get_instance();
    $ci->load->helper('form');
    
    $out  = "<div style=\"display:none\">\n";
    $out .= "<div id=\"dialogForm$dialog_id\">\n";
    
    foreach($schoolsData as $school)
    {
        $out .= "<div>\n";
        
        $out .= "<p>{$school->school}</p>\n";
        foreach($school->classes as $class)
        {
            $out .= "<label>";
            $out .= form_radio('class', $class->id, set_radio('class', $class->id));
            $out .= "{$class->class}<br /></label>";
        }
        
        $out .= "</div>\n";
    }
    
    $out .= "</div>";
    $out .= "</div>\n";
    $out .= "<a href=\"#\" onclick=\"launchTypicalDialog('$dialog_title', $('dialogForm$dialog_id'), true)\">$linkText</a>\n";
    
    return $out;
}

function form_schools_selector($schoolsData, $linkText)
{
    $dialog_id = get_next_dialog_id();
    $dialog_title = "Выбор школ";
    
    $ci = & get_instance();
    $ci->load->helper('form');
    
    $out  = "<div style=\"display:none\">\n";
    $out .= "<div id=\"dialogForm$dialog_id\">\n";
    
    $out .= "<div>\n";
    foreach($schoolsData as $school)
    {
        $out .= "<label>";
        $out .= form_checkbox('schools[]', $school->id, set_checkbox('schools[]', $school->id));
        $out .= "{$school->school}<br /></label>";
    }
    $out .= "</div>\n";
    
    $out .= "</div>";
    $out .= "</div>\n";
    $out .= "<a href=\"#\" onclick=\"launchTypicalDialog('$dialog_title', $('dialogForm$dialog_id'))\">$linkText</a>\n";
    
    return $out;
}

function form_comment($comment, $linkText)
{
    $dialog_id = get_next_dialog_id();
    $dialog_title = "Замечание";
    
    $out  = "<div style=\"display:none\">\n";
    $out .= "<div id=\"dialogForm$dialog_id\">\n";
    
    $out .= form_textarea('comment', $comment);//@BUG: Здесь явно ошибка, которая проявится при большом количестве полей
    
    $out .= "</div>";
    $out .= "</div>\n";
    $out .= "<a href=\"#\" onclick=\"launchTypicalDialog('$dialog_title', $('dialogForm$dialog_id'))\">$linkText</a>\n";
    
    return $out;
}

