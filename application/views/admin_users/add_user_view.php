<h2>Добавление пользователя</h2>

<?php $this->load->helper('form'); ?>
<?= form_open('admin_users/add_user', 'class="niceform"');?>

<div class="clearfix">
<?= form_label("Логин" , 'username') ?>
    <div class="input">
    <?= form_input('username', set_value('username')) ?>
    <?= form_error('username') ?>
    </div>
</div>


<div class="clearfix">
<?= form_label("Пароль", 'password') ?>
    <div class="input">
    <?= form_password('password', set_value('password')) ?>
    <?= form_error('password') ?>
    </div>
</div>

<div class="clearfix">
<?= form_label('Подтверждение пароля', 'confirm') ?>
    <div class="input">
    <?= form_password('confirm', set_value('confirm')) ?>
    <?= form_error('confirm') ?>
    </div>
</div>

<div class="clearfix">
<?= form_label('Имя ученика', 'name') ?>
    <div class="input">
    <?= form_input('name', set_value('name')) ?>
    <?= form_error('name') ?>
    </div>
</div>

<div class="clearfix">
<label>Класс</label>
    <div class="input">
<?php 
$this->load->helper('widgets');
echo form_class_selector($schools_classes, "Выбрать класс", 'title="Выбор класса" class="btn"');
echo form_error('class_id');
?>
    </div>
</div>

<div class="clearfix">
<?= form_label('Тариф', 'tariff') ?>
    <div class="input">
    <?= form_dropdown('tariff', $tariffs, set_value('tarriff')); ?>    
    <?= form_error('tariff') ?>
    </div>
</div>

<div class="clearfix">
<?= form_label('Телефон', 'phone') ?>
    <div class="input">
    <?= form_input('phone', set_value('phone')) ?>
    <?= form_error('phone') ?>
    </div>
</div>

<div class="clearfix">
<?= form_label('Email', 'email') ?>
    <div class="input">
    <?= form_input('email', set_value('email')) ?>
    <?= form_error('email') ?>
    </div>
</div>

<div class="actions">
<?= form_submit('submit', "Добавить", 'class="btn primary"') ?></p>
</div>

<?= form_close() ?>