<h2>Список страниц</h2>

<table class="nicetable">
<thead>
<tr>
<th>Id</th>
<th>Заголовок</th>
<th></th>
</tr>
</thead>
<tbody>
<?php foreach($pages  as $page): ?>
<tr>
    <td>/<?= html_escape($page->link); ?></td>
    <td><?php echo anchor(array('pages', 'display', $page->id), html_escape($page->title)); ?></td>
    <td>
    <nobr>
    <?php if($page->id != 1) echo anchor(array('admin_pages', 'delete', $page->id),
            '<img src="' . base_url() . 'styles/icons/delete.png" />',
            array('class' => "btn tiny error", 'title' => "Удаление страницы", 'onclick' => "return confirm('Вы уверены, что хотите удалить эту страницу?')"));
          else echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
    ?>
    <?php echo anchor(array('admin_pages', 'edit', $page->id),
            '<img src="' . base_url() . 'styles/icons/page_edit.png" />',
            array('class' => "btn tiny", 'title' => "Редактирование страницы")); ?>
    </nobr>
    </td>
</tr>
<?php endforeach; ?>
</tbody>
</table>

<div class="actions">
<?php echo anchor('admin_pages/create', '<img src="' . base_url() . 'styles/icons/add.png" />Добавить страницу', array('class' => "btn success")); ?>
</div>