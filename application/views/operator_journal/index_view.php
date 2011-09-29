<?php $this->load->helper('widgets'); ?>
<?= school_class_widget($schools_classes, '', $school_id, $class_id) ?>

<h2>Журнал</h2>

<script type="text/javascript">
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
    var loadSubjects = function()
    {
        var myJson = new Request.JSON(
        {
            url: '<?= base_url() ?>index.php/ajax/get_timetable',
            data: {'date': $('datepicker').value, 'class': <?=$class_id ?>},
            method: 'post',
            async: true,
            onSuccess: updateSubjects
        });
        
        myJson.send();
    }
    var updateSubjects = function(data) 
    {
        $$('.subjectField').each(function(subject, num)
        {
           subject.value = '';
           if(data[num+1] != undefined && data[num+1] != '')
               subject.value = data[num+1];
        });
    }
    picker.addEvent('close', loadSubjects);
});
</script>

<?php $this->load->helper('form'); ?>
<?= form_open('operator_journal/save', 'id="journalForm" class="niceform journal-form"', array('class_id' => $class->id)); ?>

<div class="clearfix">
    <label>Школа</label>
    <div class="input">
        <strong><?= $class->school ?>, <?= $class->class ?></strong>
    </div>
</div>

<div class="clearfix">
<label>Дата</label>
    <div class="input">
    <input type="text" id="datepicker" value="<?= $date ?>" size="10" onchange="loadSubjects()" />
    <a id="datepicklink" class="datepickerlink" onclick="return false;" href="#">&nbsp;</a>
    </div>
</div>

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
<th class="studentField"><nobr><a href="#"><?= $student->name ?></a></nobr></th>
<td><a href="#" class="btn tiny">Н</a></td>
<?php for($num = 1; ($num - 1) < $this->config->item('max_lessons'); $num++): ?>
<td><nobr><a href="#" class="btn tiny" title="Добавить комментарий"><img src="<?=base_url()?>styles/icons/comment.png" /></a>&nbsp;<?= form_input("grades[{$student->profile_id}][$num]", '', 'class="gradeField"') ?></nobr></td>
<?php endfor; ?>
</tr>
<?php endforeach; ?>
</tbody>
</table>
</div>

<div class="actions">
    <?= form_submit('submit', 'Сохранить', 'class="btn primary"') ?>
    <em>При сохранении, оценки будут разосланы родителям!</em>
</div>