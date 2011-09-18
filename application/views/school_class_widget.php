<?php
//@TMP
$ci = & get_instance();
$ci->load->model('classes_model');
$schools_and_classes = $ci->classes_model->get_schools_and_classes();
//@TODO
?>

<script type="text/javascript">    
    window.addEvent('domready', function()
    {
        var classes = new Array();
        var classIDs = new Array();
        classes[0] = ["Класс 1", "Класс 2", "Класс 3"];
        classIDs[0] = [0, 3, 5];
        classes[1] = ["Класс 4", "Класс 5", "Класс 6"];
        classIDs[1] = [8, 11, 56];
        classes[2] = ["Класс 7", "Класс 8", "Класс 9"];
        classIDs[2] = [111, 234, 2567];
        
        $('schoolselector').addEvent('change', function(){
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
        
        var submitDisabler = function(){
           if($('schoolselector').value == "Школа" || $('classselector').value == "Класс") 
               $('submit').disabled = true;
           else
               $('submit').disabled = false;
        }
        
        $('schoolselector').addEvent('change', submitDisabler);
        $('classselector').addEvent('change', submitDisabler);
        
    //$$('#schoolselector option')[0].set('style', 'font-weight: bold');
    //$$('#classselector option')[0].set('style', 'font-style: italic');
    });
</script>

<?php $this->load->helper('form'); ?>
<?= form_open(''); ?>

<p>
<?= form_dropdown('school', array("Школа", "Школа 1"), NULL, 'id="schoolselector"'); ?>&nbsp;
<?= form_dropdown('class', array("Класс"), NULL, 'id="classselector"'); ?>&nbsp;
<?= form_submit('submit', "OK", 'id="submit" disabled="true"'); ?>
</p>