<script type="text/javascript">
window.addEvent('domready', function()
{
    var meioList = new Meio.Element.List();
    
    var subjectFields = $$('#scheduleForm input.subjectField')
   subjectFields .each(function(field, i)
    {
        new Meio.Autocomplete.Select(field, '<?php echo base_url(); ?>index.php/ajax/autocomplete', {
            delay: 200,
            minChars: 0,
            cacheLenght: 20,
            cacheType: 'shared',
            selectOnTab: true,
            maxVisibleItems: 10,
            requestOptions: { 
            formatResponse: function(jsonResponse){ // this function should return the array of autocomplete data from your jsonResponse 
                return jsonResponse; 
            }, 
            noCache: true  // nocache is setted by default to avoid cache problem on ie 
            // you can pass any of the Request.JSON options here -> http://mootools.net/docs/core/Request/Request.JSON 
            },
            urlOptions: {
                queryVarName: 'q',
                max:20
            }
        }, meioList );
        field.addEvent('keypress', function(e)
        {
            if(e.key == 'enter') {
                e.stop();
                if(subjectFields[i+1])
                    subjectFields[i+1].focus();
                if(i == subjectFields.length - 1)
                    $('submit').focus();
            }
        }
    )
    });
})
</script>

<h1>Расписание</h1>

<?php $this->load->helper('form'); ?>
<?= form_open('operator_timetable/save', 'id="scheduleForm"', array('class_id' => $class->id, 'id' => $timetable->id)); ?>
<p>
<?= form_label("Информация о классе:", 'class_description') ?>
<?= form_textarea('class_description', set_value('class_description', $class->description)) ?>
</p>

<table class="nicetable">
<thead>
<th></th>
<th>Пн</th>
<th>Вт</th>
<th>Ср</th>
<th>Чт</th>
<th>Пт</th>
<th>Сб</th>
<th>Вс</th>
</thead>
<tbody>
<?php for($num = 1; $num - 1 < $this->config->item('max_lessons'); $num++): ?>
<tr>
<th><?= $num?></th>
<?php for($day = 1; $day -1 < 7; $day++): ?>
<td><?= form_input("subject[$num][$day]", @$timetable->timetable[$num][$day], 'size="12" tabindex="' . ($num + $day * $this->config->item('max_lessons')) . '" class="subjectField"'); ?></td>
<?php endfor; ?>
</tr>
<?php endfor; ?>
</tbody>
</table>

<p>
<?= form_label("Комментарий к расписанию:", 'description') ?>
<?= form_textarea('description', set_value('description', $timetable->description)) ?>
</p>

<p>
<?= form_submit('submit', "Сохранить", 'class="submit" id="submit"'); ?>
</p>
<?= form_close(); ?>