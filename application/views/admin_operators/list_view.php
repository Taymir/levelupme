<h1>Операторы</h1>

<table class="nicetable">
<thead>
<tr>
    <th>Логин</th>
    <th>Имя</th>
    <th></th>
</tr>
</thead>
<tbody>
 <?php foreach($operators as $operator): ?>
<tr>
    <td>
    <?= $operator->role == 'admin' ? '<strong>' : '' ?>
    <?= $operator->username ?>
    <?= $operator->role == 'admin' ? '</strong>' : '' ?>
    </td>
    <td><?= $operator->name ?></td>
    <td>
    <nobr>
    <?php echo anchor(array('admin_operators', 'remove_operator', $operator->id), "[X]", array('class' => "editbutton", 'onclick' => "return confirm('Вы уверены, что хотите удалить оператора?')")); ?>
    <?php echo anchor(array('admin_operators', 'select_schools', $operator->id), "[...]", array('class' => "editbutton")); ?>
    </nobr>
    </td>
</tr>
<?php endforeach; ?>
</tbody>
</table>

<p>
<?php echo anchor('admin_operators/add_operator', "Добавить оператора", array('class' => "newbutton")) ?>
</p>