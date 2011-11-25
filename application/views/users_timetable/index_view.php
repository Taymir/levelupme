<h2>Расписание</h2>

<div class="usersTimetable">
<!-- Информация о классе -->
<?php if(!empty($class->description)): ?>
<div class="classinfo">
<?= nl2br($class->description); ?>
</div>
<?php endif; ?>

<!-- Расписание -->

<?php 
function no_subjects_on_day($timetable, $day)
{
    $ci = &get_instance();
    for($num = 1; $num - 1 < $ci->config->item('max_lessons'); $num++) {
        if(isset($timetable[$num][$day]))
            return false;
    }
    return true;
}
?>
<table >
<thead>
<tr class="first">
<th></th>
<th <? if(no_subjects_on_day($timetable->timetable, 1)) echo 'class="holiday"'; ?>>Пн</th>
<th <? if(no_subjects_on_day($timetable->timetable, 2)) echo 'class="holiday"'; ?>>Вт</th>
<th <? if(no_subjects_on_day($timetable->timetable, 3)) echo 'class="holiday"'; ?>>Ср</th>
<th <? if(no_subjects_on_day($timetable->timetable, 4)) echo 'class="holiday"'; ?>>Чт</th>
<th <? if(no_subjects_on_day($timetable->timetable, 5)) echo 'class="holiday"'; ?>>Пт</th>
<th <? if(no_subjects_on_day($timetable->timetable, 6)) echo 'class="holiday"'; ?>>Сб</th>
<th <? if(no_subjects_on_day($timetable->timetable, 7)) echo 'class="holiday"'; ?>>Вс</th>
</tr>
</thead>
<tbody>
<?php for($num = 1; $num - 1 < $this->config->item('max_lessons'); $num++): ?>
<tr>
<th><?= $num?></th>
<?php for($day = 1; $day -1 < 7; $day++): 
$value = isset($timetable->timetable[$num][$day]) ? $timetable->timetable[$num][$day] : '';
?>
<td><?= $value ?></td>
<?php endfor; ?>
</tr>
<?php endfor; ?>
</tbody>
</table>

<!-- Доп. информация -->
<? if(!empty($timetable->description)): ?>
<p><?= nl2br($timetable->description) ?></p>
<? endif; ?>
</div>