<?php $this->load->helper('widgets'); ?>
<?= school_class_widget($schools_classes, '', $school_id, $class_id) ?>

<h2>Пользователи</h2>

<p>
<em>Здесь перечислены счет родителей и имена детей, подключенных к системе в выбранном классе:</em><br />
<strong>Школа: <?= $school ?></strong><br />
<strong>Класс: <?= $class ?></strong><br />
</p>

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
    <td>
    <?php if($user->banned) echo "<strike>" ?>
    <?= $user->username ?>
    <?php if($user->banned) echo "</strike>" ?>
    </td>
    <td>
    <?php if($user->banned) echo "<strike>" ?>
    <?= $user->name ?>
    <?php if($user->banned) echo "</strike>" ?>
    </td>
    <td>
    <nobr>
    <?php echo anchor(array('admin_users', 'remove_user', $user->profile_id),
            '<img src="' . base_url() . 'styles/icons/user_delete.png" />',
            array('class' => "btn tiny error", 'title'=>"Удалить пользователя", 'onclick' => "return confirm('Вы уверены, что хотите удалить пользователя?')"));
    ?>
    <?php if(!$user->banned): ?>
    <?php echo anchor(array('admin_users', 'ban_user', $user->profile_id),
            '<img src="' . base_url() . 'styles/icons/user_ban.png" />',
            array('class' => "btn tiny error", 'title'=>"Временная блокировка пользователя"));
    ?>
   <?php else: ?>
   <?php echo anchor(array('admin_users', 'unban_user', $user->profile_id),
           '<img src="' . base_url() . 'styles/icons/user_unban.png" />',
           array('class' => "btn tiny success",  'title'=>"Разблокировка пользователя"));
   ?>     
   <?php endif; ?>
    </nobr>
    </td>
</tr>
<?php endforeach; ?>
</tbody>
</table>

<div class="actions">
<?php echo anchor('admin_users/add_user', '<img src="' . base_url() . 'styles/icons/user_add.png" />Добавить пользователя', array('class' => "btn success")) ?>
</div>