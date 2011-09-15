<h1>Список страниц</h1>

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
    <?php echo anchor(array('admin_pages', 'edit', $page->id), "РЕД", array('class' => "editbutton")); ?>&nbsp;
    <?php echo anchor(array('admin_pages', 'delete', $page->id), "X", array('class' => "editbutton", 'onclick' => "return confirm('Вы уверены, что хотите удалить эту страницу?')")); ?>
    </nobr>
    <!-- TODO: Заменить на Mux.Dialog -->
    </td>
</tr>
<?php endforeach; ?>
</tbody>
</table>

<?php echo anchor('admin_pages/create', "Добавить страницу", array('class' => "newbutton"));//@TODO: для этих кнопок надо сделать хелпер, а можно также сделать хелпер проверки админских полномочий через user_model ?>
