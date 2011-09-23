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
<?php $this->load->helper('widgets') ?>
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
    <?php echo form_ajax_schools_selector($schools, $operator->schools, base_url() . 'index.php/ajax/save_op_schools', $operator->id, "[...]") ?>
    </nobr>
    </td>
</tr>
<?php endforeach; ?>
</tbody>
</table>

<p>
<?php echo anchor('admin_operators/add_operator', "Добавить оператора", array('class' => "newbutton")) ?>
</p>