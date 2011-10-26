<?php $this->load->helper('widgets'); ?>
<?= school_class_widget($schools_classes, '', $school_id, $class_id) ?>

<?php if($class): ?>
<h2>Пользователи</h2>

<p>
<em>Здесь перечислены счета родителей и имена детей, подключенных к системе в выбранном классе:</em><br />
<strong>Школа: <?= $class->school ?></strong><br />
<strong>Класс: <?= $class->class ?></strong><br />
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
 <?php $usernum = 0; foreach($users as $user): ?>
<tr>
    <td>
    <?php if($user->banned) echo "<strike>" ?>
    <?= $user->username ?>
    <?php if($user->banned) echo "</strike>" ?>
    </td>
    <td>
    <?php if($user->banned) echo "<strike>" ?>
    <?= ++$usernum . '. ' .$user->name ?>
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
    <?php echo anchor(array('admin_users', 'edit_user', $user->profile_id),
            '<img src="' . base_url() . 'styles/icons/user_edit.png" />',
            array('class' => "btn tiny", 'title'=>"Изменение пользователя"));
    ?>
    <?php if($usernum > 1): ?>
    <?php echo anchor(array('admin_users', 'reorder_user', $user->profile_id, $user->order_num - 1),
            '<img src="' . base_url() . 'styles/icons/arrow_up.png" />',
            array('class' => "btn tiny", 'title'=>"Переместить пользователя по списку вверх"));
    ?>
    <?php else: ?>
    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    <?php endif; ?>
    <?php if($usernum < sizeof($users)) : ?>
    <?php echo anchor(array('admin_users', 'reorder_user', $user->profile_id, $user->order_num + 1),
            '<img src="' . base_url() . 'styles/icons/arrow_down.png" />',
            array('class' => "btn tiny", 'title'=>"Переместить пользователя по списку вниз"));
    ?>
    <?php endif; ?>
    </nobr>
    </td>
</tr>
<?php endforeach; ?>
</tbody>
</table>

<div class="actions">
<?php echo anchor('admin_users/mass_add_user/'. $class_id, '<img src="' . base_url() . 'styles/icons/user_add.png" />Добавить много учеников', array('class' => "btn success", 'title' => "Добавление списка учеников")) ?>&nbsp;
<?php echo anchor('admin_users/add_user/' . $class_id, '<img src="' . base_url() . 'styles/icons/user_add.png" />Добавить ученика', array('class' => "btn success", 'title' => "Добавление одного ученика")) ?>&nbsp;
<?php echo anchor('admin_users/resort_users/' . $class_id, '<img src="' . base_url() . 'styles/icons/arrow_refresh.png" />&nbsp;', array('class' => "btn success", 'title' => "Пересортировать по именам")) ?>
</div>
<?php endif; ?>