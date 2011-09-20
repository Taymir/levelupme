<h1>Школы и классы</h1>

<table class="nicetable">
<tbody>
<?php foreach($schools  as $school): ?>
<tr>
    <th style="text-align: left;"><?= html_escape($school->school) ?></th>
    <td>
    <nobr>
        <?php echo anchor(array('admin_schools', 'remove_school', $school->id), "[X]", array('class' => "editbutton", 'onclick' => "return confirm('Вы уверены, что хотите удалить эту школу?')")); ?>
        <?php echo anchor(array('admin_schools', 'add_class', $school->id), "[+]"); ?>
    </nobr>
    </td>
</tr>
<?php foreach( $school->classes as $class): ?>
<tr>
    <td><?= html_escape($class->class) ?></td>
    <td>
<nobr>
<?php echo anchor(array('admin_schools', 'remove_class', $class->id), "[X]", array('class' => "editbutton", 'onclick' => "return confirm('Вы уверены, что хотите удалить этот класс?')")); ?>
<?php echo anchor(array('admin_schools', 'edit_class', $class->id), "[O]", array('class' => "editbutton")); ?>
</nobr>
</td>
</tr>
<?php endforeach; ?>
<?php endforeach; ?>
</tbody>
</table>

<p>
<?php echo anchor('admin_schools/add_school', "Добавить школу", array('class' => "newbutton")) ?>
</p>