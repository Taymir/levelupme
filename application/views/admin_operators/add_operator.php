<h1>Добавление оператора</h1>

<?php $this->load->helper('form'); ?>
<?= form_open('admin_operators/add_operator"', 'class="niceform"');?>

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
<?= form_label('Имя', 'name') ?>
<?= form_input('name', set_value('name')) ?>
<?= form_error('name') ?>
</p>

<p>
<?= form_label('Email', 'email') ?>
<?= form_input('email', set_value('email')) ?>
<?= form_error('email') ?>
</p>

<p>
<label>
<?= form_checkbox('admin', set_value('admin')) ?>
Администратор</label>
<?= form_error('admin') ?>
</p>

<p>
<?php echo anchor('admin_operators/select_schools', "Выбрать школы") ?>
</p>

<p>
<?= form_submit('submit', "Добавить", 'class="submit"') ?>
</p>

<?= form_close() ?>