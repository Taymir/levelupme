<?php $this->load->helper('widgets'); ?>
<?= school_class_widget($schools_classes, '', $school_id, $class_id) ?>

<script type="text/javascript">
window.addEvent('domready', function()
{
    addAutocompletion(
        $$('#scheduleForm input.subjectField'),
        $('submit'),
        '<?php echo base_url(); ?>index.php/ajax/autocomplete');
});
</script>

<h2>Расписание</h2>

<?php $this->load->helper('form'); ?>
<?= form_open('operator_timetable/save', 'id="scheduleForm" class="timetable-form"', array('class_id' => $class->id, 'id' => $timetable->id)); ?>
<div class="clearfix">
<?= form_label("Информация о классе:", 'class_description') ?>
    <div class="input">
    <?= form_textarea('class_description', set_value('class_description', $class->description)) ?>
    </div>
</div>

<table >
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
<?php for($day = 1; $day -1 < 7; $day++): 
$value = isset($timetable->timetable[$num][$day]) ? $timetable->timetable[$num][$day] : '';
?>
<td><?= form_input("subject[$num][$day]", $value, 'size="12" tabindex="' . ($num + $day * $this->config->item('max_lessons')) . '" class="subjectField"'); ?></td>
<?php endfor; ?>
</tr>
<?php endfor; ?>
</tbody>
</table>
<p>
<?= form_label("Комментарий к расписанию:", 'description') ?>
<?= form_textarea('description', set_value('description', $timetable->description)) ?>
</p>

<div class="actions">
<?= form_submit('submit', "Сохранить", 'class="btn primary" id="submit"'); ?>
</div>
<?= form_close(); ?>

