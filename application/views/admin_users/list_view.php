<h1>Пользователи</h1>

<em>Здесь перечислены счет родителей и имена детей, подключенных к системе в выбранном классе выбранной школы</em>

<table class="nicetable">
<thead>
<tr>
    <th>Логин</th>
    <th>Имя ученика</th>
    <th></th>
</tr>
</thead>
<tbody>
 <?php foreach($users as $user): ?>
<tr>
    <td><?= $user->username ?></td>
    <td><?= $user->name ?></td>
    <td>
    <nobr>
    <?php echo anchor(array('admin_users', 'remove_user', $user->id), "[X]", array('class' => "editbutton", 'onclick' => "return confirm('Вы уверены, что хотите удалить пользователя?')")); ?>
    <?php if(!$user->banned): ?>
    <?php echo anchor(array('admin_users', 'ban_user', $user->id), "[-]", array('class' => "editbutton")); ?>
   <?php else: ?>
   <?php echo anchor(array('admin_users', 'unban_user', $user->id), "[+]", array('class' => "editbutton")); ?>     
   <?php endif; ?>
    </nobr>
    </td>
</tr>
<?php endforeach; ?>
</tbody>
</table>

<p>
<?php echo anchor('admin_users/add_user', "Добавить пользователя", array('class' => "newbutton")) ?>
</p>