<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

function tariff_widget($target, $tariffs, $default_tariff = NULL)
{
    $ci = & get_instance();
    $ci->load->helper('form');
    
    $out = form_open($target, 'class="tariff-widget"');
    
    $out .= "<label>Тариф:&nbsp;";
    $out .= form_dropdown('tariff', $tariffs, $default_tariff);
    $out .= "</label>\n";
    $out .= form_submit('updatetariff', 'OK', 'class="btn success"');
    $out .= form_close();
    
    return $out; 
}

function date_widget($target, $default_date = NULL)
{
    $ci = & get_instance();
    $ci->load->helper('form');
    
    $out = form_open($target, 'class="date-widget"');
    
    $out .= "
<script type=\"text/javascript\">
window.addEvent('domready', function()
{
    Locale.use('ru-RU');
    var picker = new Picker.Date($('datepicker'), {
        timePicker: false,
        positionOffset: {x: 0, y: 0},
        pickerClass: 'datepicker_vista',
        useFadeInOut: false,//!Browser.ie,
        toggle: $('datepicklink')
    });
});
</script>\n";
    
    $out .= "<label>Дата:&nbsp;<input type=\"text\" name=\"date\" id=\"datepicker\" value=\"$default_date\" size=\"10\" /></label>\n";
    $out .= "<a id=\"datepicklink\" class=\"datepickerlink\" onclick=\"return false;\" href=\"#\">&nbsp;</a>\n";
    $out .= form_submit('updatedate', 'OK', 'class="btn success"');
    $out .= form_close();
    
    return $out; 
}

function school_class_widget($schoolClassData, $target, $default_school = NULL, $default_class = NULL)
{
    $ci = & get_instance();
    $ci->load->helper('form');
    
    $out = form_open($target,  'class="school-class-widget"');
    
    $out .= "
<script type=\"text/javascript\">    
        var classes = new Array();\n";

    $schools = array();
    $classes = array();
    $default_school_classes = array();
    if(!empty($schoolClassData))
    {
        foreach($schoolClassData as $school)
        {
            $school_id = (int)$school->id;
            $schools[$school_id] = $school->school;

            $classes[$school_id] = array();
            $classesTmp          = array();
            foreach($school->classes as $class)
            {
                $class_id = (int)$class->id;
                $classes[$school_id][$class_id] = $class->class;

                $classesTmp[] = ' "' . $class->id . '":"' . addslashes($class->class) . '"';
            }
            $out .= "classes[$school->id] = ";
            $out .= '{' . implode(',', $classesTmp) . "};\n";
        }
        
        if(!isset($default_school))
        {
            $school_ids = array_keys($schools);
            $default_school = $school_ids[0];
        }
        $default_school_classes = $classes[$default_school];
    }
        
    $out .= "
    var updateClassListEx = function()
    {
        updateClassList(classes, $('schoolselector'), $('classselector'));
    }
</script>
        ";
    
    $out .= form_dropdown('school', $schools, $default_school, 'id="schoolselector" onchange="updateClassListEx()"');
    $out .= form_dropdown('class', $default_school_classes, $default_class, 'id="classselector"');
    $out .= form_submit('submit', "OK", 'class="btn success"');
    $out .= form_close();
    
    return $out;
}

function get_next_dialog_id()
{
    static $dialog_id = 0;
    return $dialog_id++;
}

function form_class_selector($schoolsData, $linkText, $extra='', $default = null)
{
    $dialog_id = get_next_dialog_id();
    $dialog_title = "Выбор класса";
    
    $ci = & get_instance();
    $ci->load->helper('form');
    
    $out  = "<div style=\"display:none;\">\n";
    $out .= "<div style=\"text-align: left;\" id=\"dialogForm$dialog_id\">\n";
    
    foreach($schoolsData as $school)
    {
        $out .= "<div>\n";
        
        $out .= "<p>{$school->school}</p>\n";
        foreach($school->classes as $class)
        {
            $out .= "<label>";
            $out .= form_radio('class_id', $class->id, set_radio('class_id', $class->id, $class->id == $default));
            $out .= "{$class->class}<br /></label>";
        }
        
        $out .= "</div>\n";
    }
    
    $out .= "</div>";
    $out .= "</div>\n";
    $out .= "<a href=\"#\" $extra onclick=\"launchTypicalDialog('$dialog_title', $('dialogForm$dialog_id'), true)\">$linkText</a>\n";
    
    return $out;
}

function form_schools_selector($schoolsData, $linkText, $extra='')
{
    $dialog_id = get_next_dialog_id();
    $dialog_title = "Выбор школ";
    
    $ci = & get_instance();
    $ci->load->helper('form');
    
    $out  = "<div style=\"display:none\">\n";
    $out .= "<div style=\"text-align: left;\" id=\"dialogForm$dialog_id\">\n";
    
    $out .= "<div>\n";
    foreach($schoolsData as $school)
    {
        $out .= "<label>";
        $out .= form_checkbox('schools[]', $school->id, set_checkbox('schools', $school->id));
        $out .= "{$school->school}<br /></label>";
    }
    $out .= "</div>\n";
    
    $out .= "</div>";
    $out .= "</div>\n";
    $out .= "<a href=\"#\" $extra onclick=\"launchTypicalDialog('$dialog_title', $('dialogForm$dialog_id'))\">$linkText</a>\n";
    
    return $out;
}

function form_ajax_schools_selector($schoolsData, $selectedData, $ajax_url, $operator_id, $linkText, $extra='')
{
    if(!is_array($selectedData)) return '';
    
    $dialog_id = get_next_dialog_id();
    $dialog_title = "Выбор школ";
    
    $ci = & get_instance();
    $ci->load->helper('form');
    
    $out = "<div style=\"display:none\">\n";
    $out .= form_open($ajax_url, "id=\"dialogForm$dialog_id\"", array('operator' => $operator_id));
    
    $out .= "<div style=\"text-align: left;\">\n";
    foreach($schoolsData as $school)
    {
        $value = in_array($school->id, $selectedData) ? true : false;
        $out .= "<label>";
        $out .= form_checkbox('schools[]', $school->id, set_checkbox('schools', $school->id, $value));
        $out .= "{$school->school}<br /></label>";
    }
    $out .= "</div>\n";
    
    $out .= "</div>";
    $out .= form_close();
    $out .= "<a href=\"#\" $extra onclick=\"launchAjaxDialog('$dialog_title', $('dialogForm$dialog_id'), '$ajax_url', $operator_id)\">$linkText</a>\n";
    
    return $out;
}

function form_comment($name, $comment, $linkText, $extra = '')
{
    $dialog_id = get_next_dialog_id();
    $dialog_title = "Замечание";
    
    $out  = "<div style=\"display:none\">\n";
    $out .= "<div id=\"dialogForm$dialog_id\">\n";
    
    $out .= form_textarea($name, $comment, "style=\"width: 300px\" id=\"txt$dialog_id\"");
    $out .= "<script type=\"text/javascript\">
            window.addEvent('domready', function(){
            $('txt$dialog_id').addEvent('change', function(){updateButtonColor('txt$dialog_id', 'dialogLauncher$dialog_id')});updateButtonColor('txt$dialog_id', 'dialogLauncher$dialog_id');
            });
            </script>\n";
    
    $out .= "</div>";
    $out .= "</div>\n";
    $out .= "<a href=\"#\" $extra id=\"dialogLauncher$dialog_id\" onclick=\"launchTypicalDialog('$dialog_title', $('dialogForm$dialog_id')); return false\">$linkText</a>";
    
    return $out;
}

