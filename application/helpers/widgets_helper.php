<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
$dialog_id = 0;

function last_dialog_id()
{
    global $dialog_id;
        
    return "showDialogEx$dialog_id";
}

function schools_selector_widget($schoolsData, $target, $caller)
{
    global $dialog_id;
    
    $dialog_title = "Выбор школ";
    $dialog_id++;
    
    $out = '';
    $out .= "<script type=\"text/javascript\">\n";
    $out .= "window.addEvent('domready', function()\n{\n";
    
    $schools = array();
    $schoolIDs = array();
    foreach($schoolsData as $school)
    {
        $schools[] = '"' . htmlspecialchars($school->school) . '"';
        $schoolIDs[] = (int)$school->id;        
    }
    $schools = implode(', ', $schools);
    $schoolIDs = implode(', ', $schoolIDs);
    
    $out .= "var schools$dialog_id = [$schools];\n";
    $out .= "var schoolIDs$dialog_id = [$schoolIDs];\n";
    
    $out .= "var content$dialog_id = new Array();\n
    schools$dialog_id.each(function(schoolEl, schoolKey){
       content{$dialog_id}[schoolKey] = new Element('div').grab(
           new Element('label', {
              html: schoolEl
           }).grab(new Element('input', {
              type: 'checkbox',
              name: 'class',
              value: schoolKey
           }), 'top').grab(new Element('br'))
           );
       });
    content$dialog_id = new Element('form', {id: 'dialogForm$dialog_id'}).adopt(content$dialog_id);
    
    var showDialogEx$dialog_id = function()
    {
        showDialog(\"$dialog_title\", content$dialog_id, onSubmit$dialog_id);
    }
    
    var onSubmit$dialog_id = function()
    {
        var selected = '';

        $$('#dialogForm$dialog_id input').each(function(el)
        {
            if(el.checked)
                selected += el.value + ',';
        });
    ";
    
    if(substr($target, 0, 4) == 'http')
        $out .= "var mJax = new Request.JSON(
            {
               url: '$target',
               method: 'post',
               async: true,
               data: {
                'operator' : \"11\",
                'classes' : selected
                }
            });
            mJax.send();
        ";
    else
        $out .= "
            $('$target').value = selected;";
    
    $out .= "
        this.close();
    }
    ";
    
    $out .= "$('$caller').addEvent('click', showDialogEx$dialog_id);";
    
    $out .= "});\n</script>";
    
    return $out;
}

function class_selector_widget($schoolClassData, $target, $caller)
{
    global $dialog_id;
    
    $dialog_title = "Выбор класса";
    $dialog_id++;
    
    $out = '';
    $out .= "<script type=\"text/javascript\">\n";
    $out .= "window.addEvent('domready', function()\n{";
    
    $out .= 
    "var classes$dialog_id = new Array();
     var classIDs$dialog_id = new Array();\n";
    $schools = array();
    foreach($schoolClassData as $key => $school)
    {
        $schools[] = '"' . htmlspecialchars($school->school) . '"';
        
        $classes = array();
        $classIDs = array();
        foreach($school->classes as $class)
        {
            $classes[] = '"' . htmlspecialchars($class->class) . '"';
            $classIDs[] = (int)$class->id;
        }
        $classes = implode(', ', $classes);
        $classIDs= implode(', ', $classIDs);
    
        $out .= "classes{$dialog_id}[$key] = [$classes];\n";
        $out .= "classIDs{$dialog_id}[$key] = [$classIDs];\n";
    }
    $schools = implode(', ', $schools);
    
    $out .= "var schools$dialog_id = [$schools];\n\n";
    
    $out .= "var content$dialog_id = new Array();
    schools$dialog_id.each(function(schoolEl, schoolKey){
    content{$dialog_id}[schoolKey] = new Element('div').grab(
    new Element('p', {html: schoolEl}) );

    classes{$dialog_id}[schoolKey].each(function(classEl, classKey){
       content{$dialog_id}[schoolKey].grab(
           new Element('label', {
              html: classes{$dialog_id}[schoolKey][classKey]
           }).grab(new Element('input', {
              type: 'radio',
              name: 'class',
              value: classIDs{$dialog_id}[schoolKey][classKey]
           }), 'top').grab(new Element('br'))
           );
       });
    });
    content$dialog_id = new Element('form', {id: 'dialogForm$dialog_id'}).adopt(content$dialog_id);
    
    var showDialogEx$dialog_id = function()
    {
        showDialog(\"$dialog_title\", content$dialog_id, onSubmit$dialog_id);
    }
    
    var onSubmit$dialog_id = function()
    {
        var selected = '';

        $$('#dialogForm$dialog_id input').each(function(el)
        {
            if(el.checked)
                selected += el.value + ',';
        });
        $('$target').value = selected;

        this.close();
    }
    ";
    
    $out .= "$('$caller').addEvent('click', showDialogEx$dialog_id);";
    
    $out .= "});\n</script>";
    
    return $out;
}

function comment_widget($caller)
{
    global $dialog_id;
        
    $dialog_title = "Замечание";
    $dialog_id++;
    
    $out = '';
    $out .= "<script type=\"text/javascript\">\n";
    $out .= "window.addEvent('domready', function()\n{";
    
    $out .= "var content$dialog_id = new Array();\n
    content = new Element('textarea', {id: 'commentField$dialog_id', style: 'width: 350px; height: 400px'});

    var showDialogEx$dialog_id = function()
    {
        showDialog(\"$dialog_title\", content$dialog_id, onSubmit$dialog_id);
    }
    
    var onSubmit$dialog_id = function()
    {
        var selected = $('commentField$dialog_id').value;
        $('comment').value = selected;
        this.close();
    }
    ";
    
    $out .= "});\n</script>";
    
    return $out;
}

function school_class_widget($schoolClassData, $target)
{
    $this->load->helper('form');
    
    $out = form_open($target);
    
    $out .= "
<script type=\"text/javascript\">    
    window.addEvent('domready', function()
    {
        var classes = new Array();
        var classIDs = new Array();\n";
    
    $schools = array();
    foreach($schoolClassData as $key => $school)
    {
        $schools[$key] = $school->school;
        
        $classes[$key] = array();
        $classIDs[$key] = array();
        foreach($school->classes as $class)
        {
            $classes[$key][] = htmlspecialchars($class->class);
            $classIDs[$key][] = (int)$class->id;
        }
        $classes[$key] = implode(', ', $classes[$key]);
        $classIDs[$key] = implode(', ', $classIDs[$key]);
        
        $out .= "classes[{$school->id}] = [$classes[$key]]\n";
        $out .= "classIDs[{$school->id}] = [$classIDs[$key]]\n";
    }
        
    $out .= "$('schoolselector').addEvent('change', function(){
            $('classselector').set('html', '');
            var schoolID = $('schoolselector').value;
            classes[schoolID].each(function(item, key)
            {
                new Element('option',
                {
                        'value': classIDs[schoolID][key],
                        'text' : item
                }).inject($('classselector'));
            });
        });
    });
</script>
        ";
    //@TODO: cookies!
    $out .= form_dropdown('school', $schools, NULL, 'id="schoolselector"');
    $out .= form_dropdown('class', array_combine($classes[0], $classIDs[0]), NULL, 'id="classselector"');//@TODO
    $out .= form_submit('submit', "OK", 'id="submit"');
    $out .= form_close();
    
    return $out;
}