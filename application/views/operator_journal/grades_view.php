<?php $this->load->helper('widgets'); $this->load->helper('common'); ?>
<?= school_class_widget($schools_classes, '', $school_id, $class_id) ?>
<?php if(isset($class)): ?>

<?= date_widget('', $date) ?>

<script type="text/javascript">
var Nhandler = function(e)
{
    if(e.target.value == "")
        e.target.value = "н";
    else if(e.target.value == "н")
        e.target.value = "";
}
var subjectClick = function(num)
{
    if($('subjects' + num).value != '') {
        $$('.gradesBlock').setStyle('display', 'none');
        $('gradesBlock' + num).setStyle('display', 'block');
        return true;
    } else {
        alert("Для того, чтобы перейти к заполнению журнала по предмету, вы должны ввести название предмета.")
        return false;
    }
}
var changeSubjectName = function(num)
{
    $('subjectName' + num).innerText = $('subjects' + num).value;
    $('hiddenSubjectName' + num).value = $('subjects' + num).value;
}
var gradeFields;
var commentFields;
window.addEvent('domready', function()
{
    $$('.N').addEvent('click', Nhandler);
    gradeFields = $$('.gradeField');
    commentFields = $$('.commentField');
    
    gradeFields.each(function(field, i)
    {
        field.addEvent('keydown', function(e)
        {
            if(e.code == 13)//enter
            {
                e.stop();
                if(gradeFields[i+5] != undefined)
                    gradeFields[i+5].focus();
            } else if(e.code == 37) {// <-
                if(gradeFields[i-1] != undefined)
                    gradeFields[i-1].focus();
            } else if(e.code == 39) {//->
                if(gradeFields[i+1] != undefined)
                    gradeFields[i+1].focus();
            } else if(e.code == 38) {//^
                if(gradeFields[i-5] != undefined)
                    gradeFields[i-5].focus();
            } else if(e.code == 40) {//V
                if(gradeFields[i+5] != undefined)
                    gradeFields[i+5].focus();
            }
        })
    });
    commentFields.each(function(field, i)
    {
        field.addEvent('keypress', function(e)
        {
            if(e.key == 'enter')
            {
                e.stop();
                if(commentFields[i+1] != undefined)
                    commentFields[i+1].focus();
            } 
        })
    });
});
</script>

<?php $this->load->helper('form'); ?>
<?= form_open('operator_journal/saveTODO', 'id="journalForm" class="niceform journal-form"', array('class_id' => $class->id, 'date' => $date)); ?>

<?= form_fieldset() ?>
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
<?= form_fieldset_close() ?>

<!-- Список предметов -->
<?= form_fieldset('Предметы') ?>
<ul>
<?php for($num = 1; ($num - 1) < $this->config->item('max_lessons'); $num++): ?>
<li><?= $num ?>. 
<?= form_input("subjects[$num]", isset($subjects[$num]) ? $subjects[$num] : '', "class=\"subjectField\" id=\"subjects$num\" onchange=\"changeSubjectName($num)\"") ?> 
<a href="#subject<?= $num ?>" class="btn tiny" onclick="return subjectClick(<?= $num ?>)"><img src="<?= base_url() ?>styles/icons/journal_edit.png" /></a>
</li>
<?php endfor; ?>
</ul>
<?= form_fieldset_close() ?>

<!-- формы журнала -->
<?php for($num = 1; ($num - 1) < $this->config->item('max_lessons'); $num++): ?>
<a name="subject<?= $num ?>" id="subject<?= $num ?>"></a>
<div class="gradesBlock" id="gradesBlock<?= $num ?>">
<h3><?= $num ?>. <span id="subjectName<?= $num ?>"><?= isset($subjects[$num]) ? $subjects[$num] : '' ?></span></h3>
<input type="hidden" value="<?= isset($subjects[$num]) ? $subjects[$num] : '' ?>" id="hiddenSubjectName<?= $num ?>" />
<table>
    <col />
    <col class="studentNameCol" />
    <col />
    <col />
    <col />
    <col />
    <col />
    <col />
<thead>
<tr>
    <th></th>
    <th>Имя</th>
    <th>Н</th>
    <th>Отв</th>
    <th><?= form_input("", 'К/р', 'class="gradeTypeField"') ?></th>
    <th><?= form_input("", 'С/р', 'class="gradeTypeField"') ?></th>
    <th><?= form_input("", 'Д/з', 'class="gradeTypeField"') ?></th>
    <th>Комментарий <a href="#" class="btn tiny"><img src="<?= base_url() ?>styles/icons/comment.png" /></a></th> 
</tr>
</thead>
<tbody>
<?php $student_num = 0;
foreach($students as $student): $student_num++;?>
<tr class="<?= $student_num % 2 == 0 ? '' : 'odd' ?>">
<td class="studentNum"><?= $student_num . '. ' ?></td>
<td><?= colorify_name($student->name) ?></td>
<td><?= form_input("NAME", "", 'class="N gradeField"') ?></td>
<td><?= form_input("NAME", "", 'class="gradeField"') ?></td>
<td><?= form_input("NAME", "", 'class="gradeField"') ?></td>
<td><?= form_input("NAME", "", 'class="gradeField"') ?></td>
<td><?= form_input("NAME", "", 'class="gradeField"') ?></td>
<td><?= form_input("NAME", "", 'class="commentField"') ?></td>
</tr>
<?php endforeach; ?>
</tbody>
</table>

<div class="clearfix">
    <div class="input">
        <a href="#" class="btn success">Временно сохранить</a>
    </div>
</div>
</div>
<?php endfor; ?>
<!-- Сохранение и отправка -->
<div class="actions">
    <?= form_submit('submit', 'Разослать', 'class="btn error" id="submit"') ?>
    <em>Убедитесь, что заполнили <strong>все предметы</strong>!</em>
</div>

<?= form_close(); ?>
<?php endif; ?>
