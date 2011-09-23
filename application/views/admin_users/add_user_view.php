<h1>Добавление пользователя</h1>

<?php $this->load->helper('form'); ?>
<?= form_open('admin_users/add_user', 'class="niceform"');?>

<p>
<?= form_label("Логин" , 'username') ?>
<?= form_input('username', set_value('username')) ?>
<?= form_error('username') ?>
</p>

<p>
<?= form_label("Пароль", 'password') ?>
<?= form_password('password', set_value('password')) ?>
<?= form_error('password') ?>
</p>

<p>
<?= form_label('Подтверждение пароля', 'confirm') ?>
<?= form_password('confirm', set_value('confirm')) ?>
<?= form_error('confirm') ?>
</p>

<p>
<?= form_label('Имя ученика', 'name') ?>
<?= form_input('name', set_value('name')) ?>
<?= form_error('name') ?>
</p>

<p>
<?php 
$this->load->helper('widgets');
echo form_class_selector($schools_classes, "Выбрать класс");
echo form_error('class_id');
?>
</p>

<p>
<?= form_label('Тариф', 'acc_type') /*@TMP */ ?>
<?= form_input('acc_type', set_value('acc_type')) ?>
<?= form_error('acc_type') ?>
</p>

<p>
<?= form_label('Телефон', 'phone') ?>
<?= form_input('phone', set_value('phone')) ?>
<?= form_error('phone') ?>
</p>

<p>
<?= form_label('Email', 'email') ?>
<?= form_input('email', set_value('email')) ?>
<?= form_error('email') ?>
</p>

<p>
<?= form_submit('submit', "Добавить", 'class="submit"') ?>
</p>

<?= form_close() ?>