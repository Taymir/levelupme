<script type="text/javascript" src="/scripts/addAutocompletion.js"></script> 
<script type="text/javascript">
window.addEvent('domready', function()
{
    addAutocompletion(
        $$('#scheduleForm input.subjectField'),
        $('submit'),
        '<?php echo base_url(); ?>index.php/ajax/autocomplete');
});
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