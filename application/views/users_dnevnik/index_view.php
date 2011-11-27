<h2>Дневник</h2>

<div class="usersDnevnik">
<!-- Информация о классе -->
<?php if(!empty($class->description)): ?>
<div class="classinfo">
<?= nl2br($class->description); ?>
</div>
<?php endif; ?>

<!-- Информация об оценках за неделю -->
<?php foreach($grades as $date => $unwanted_val): ?>
<div class="dayBlock">
<h3><?= russian_date($date)  ?></h3>
<table>
<col class="subjCol"/>
<col class="gradeCol"/>
<col class="commentCol"/>
<tbody>
<?php for($num = 1; $num <= $this->config->item('max_lessons'); ++$num): ?>
<tr>
<?php if(isset($grades[$date][$num])): $val = $grades[$date][$num]; ?>
<td <?= isset($val['grade']) ? '' : 'class="nogrades"' ?>><?= $num . '. ' . $val['subject'] ?></td>
    <?php if(isset($val['grade'])): ?>
    <td><?= $val['grade'] ?></td>
    <td><?= $val['comment'] ?></td>
    <?php else: ?>
    <td></td><td></td>
    <?php endif; ?>
<?php else: ?>
    <td class="nogrades"><?= $num . '. ' ?></td>
    <td></td><td></td>
<?php endif; ?>
</tr>
<?php endfor; ?>
</tbody>
</table>
</div>
<?php endforeach; ?>

</div>