<h2>Школы и классы</h2>

<table class="nicetable">
<tbody>
<?php foreach($schools  as $school): ?>
<tr>
    <th style="text-align: left;" colspan="2"><?= html_escape($school->school) ?>&nbsp;&nbsp;&nbsp;
    <nobr>
        <?php echo anchor(array('admin_schools', 'remove_school', $school->id),
                '<img src="' . base_url() . 'styles/icons/delete.png" />',
                array('class' => "btn tiny error", 'title'=>"Удаление школы", 'onclick' => "return confirm('Вы уверены, что хотите удалить эту школу?')"));
        ?>
        <?php echo anchor(array('admin_schools', 'add_class', $school->id),
                '<img src="' . base_url() . 'styles/icons/class_add.png" />',
                'class="btn tiny success" title="Добавить класс"');
        ?>
    </nobr>
    </th>
</tr>
<?php foreach( $school->classes as $class): ?>
<tr>
    <td><?= anchor(array('admin_users', "?class={$class->id}"), html_escape($class->class)) ?></td>
    <td>
<nobr>
<?php echo anchor(array('admin_schools', 'remove_class', $class->id),
        '<img src="' . base_url() . 'styles/icons/delete.png" />',
        array('class' => "btn tiny error", 'title' => "Удаление класса", 'onclick' => "return confirm('Вы уверены, что хотите удалить этот класс?')"));
?> 
<?php echo anchor(array('operator_timetable', "?class={$class->id}"),
        '<img src="' . base_url() . 'styles/icons/timetable.png" />',
        'class="btn tiny" title="Расписание класса"');
?> 
<?php echo anchor('admin_schools/rename_class/' . $class->id,
        '<img src="' . base_url() . 'styles/icons/rename.png" />',
        'class="btn tiny" title="Смена названия класса"');
?>
</nobr>
</td>
</tr>
<?php endforeach; ?>
<?php endforeach; ?>
</tbody>
</table>

<div class="actions">
<?php echo anchor('admin_schools/add_school', '<img src="' . base_url() . 'styles/icons/add.png" />Добавить школу', 'class="btn success"') ?>
</div>