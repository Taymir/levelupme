<?php $this->load->helper('widgets'); ?>
<?= school_class_widget($schools_classes, '', $school_id, $class_id) ?>
<?= date_widget('', $date) ?>

<h2>Журнал</h2>
<!-- //@REFACTOR -->
<script type="text/javascript">
window.addEvent('domready', function()
{
    var inputs = $$('#gradesBlock input');
    var i = 0;
    inputs.each(function(field){
        field.addEvent('keypress', function(e)
        {
            if(e.key == 'enter') {
                e.stop();
                if(inputs[i+1])
                    inputs[i+1].focus();
                if(i == inputs.length - 1)
                    $('submit').focus();
                i++;
            }
        });
    });
    

});
var setNto = function(profile_id)
{
    $$('.std' + profile_id).each(function(input){
       if(input.value == "Н")
           input.value = "";
       else
           input.value = "Н"; 
    });
    return false;
}
</script>

<?php $this->load->helper('form'); ?>
<?= form_open('operator_journal/save', 'id="journalForm" class="niceform journal-form"', array('class_id' => $class->id, 'date' => $date)); ?>

<div class="clearfix">
    <label>Школа</label>
    <div class="input">
        <strong><?= $class->school ?>, <?= $class->class ?></strong>
    </div>
</div>

<div class="clearfix">
<label>Дата</label>
    <div class="input">
        <strong><?= $date ?></strong>
    </div>
</div>

<?php if(sizeof($students)): ?>
<div id="gradesBlock" class="clearfix">
<table>
<col class="studentsCol" />
<col class="buttonsCol" />
<colgroup class="gradesCol" span="<?=$this->config->item('max_lessons')?>" />
<thead>
<tr class="subjectsRow">
<th class="studentField"></th><th></th>
<?php for($num = 1; ($num - 1) < $this->config->item('max_lessons'); $num++): ?>
<th><nobr><?= $num ?>. <?= form_input("subjects[$num]", isset($subjects[$num]) ? $subjects[$num] : '', 'class="subjectField"') ?></nobr></th>
<?php endfor; ?>
</tr>
</thead>
<tbody>
<?php foreach($students as $student): ?>
<tr>
    <th class="studentField"><nobr><a href="<?= base_url() . 'operator_messages/index/' . $student->profile_id ?>"><?= $student->name ?></a><?= form_hidden("students[{$student->profile_id}]", $student->name) ?></nobr></th>
<td><a href="#" class="btn tiny" title="Неприсутствовал весь день" onclick="return setNto(<?=$student->profile_id?>)">Н</a></td>
<?php for($num = 1; ($num - 1) < $this->config->item('max_lessons'); $num++): ?>
<td><nobr>
<?= form_comment("comments[{$student->profile_id}][$num]", '', '<img src="' . base_url() .'styles/icons/comment.png" />', 'class="btn tiny" title="Добавить комментарий"') ?>&nbsp;
<?= form_input("grades[{$student->profile_id}][$num]", '', 'class="gradeField std' . $student->profile_id . '"') ?>
</nobr></td>
<?php endfor; ?>
</tr>
<?php endforeach; ?>
</tbody>
</table>
</div>

<div class="actions">
    <?= form_submit('submit', 'Сохранить', 'class="btn primary" id="submit"') ?>
    <em>При сохранении, оценки будут разосланы родителям!</em>
</div>
<?= form_close() ?>
<?php else: ?>
<div class="clearfix">
<label></label>
    <div class="input">
        <strong>В выбранном классе не зарегистрировано ни одного ученика</strong>
    </div>
</div>
<?php endif; ?>
