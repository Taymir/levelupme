<h2>Операторы</h2>

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
    <?php echo anchor(array('admin_operators', 'remove_operator', $operator->id),
            '<img src="' . base_url() . 'styles/icons/user_delete.png" />',
            array('class' => "btn tiny error", 'title'=>"Удаление оператора", 'onclick' => "return confirm('Вы уверены, что хотите удалить оператора?')"));
    ?>
    <?php echo form_ajax_schools_selector($schools, $operator->schools, base_url() . 'index.php/ajax/save_op_schools',
            $operator->id, '<img src="' . base_url() . 'styles/icons/operator_edit.png" />', 'title="Выбор школ для оператора" class="btn tiny"')
    ?>
    </nobr>
    </td>
</tr>
<?php endforeach; ?>
</tbody>
</table>

<div class="actions">
<?php echo anchor('admin_operators/add_operator', '<img src="' . base_url() . 'styles/icons/user_add.png" />Добавить оператора', array('class' => "btn success")) ?>
</div>