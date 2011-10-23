<h2>Добавление оператора</h2>

<?php $this->load->helper('form'); ?>
<?= form_open('admin_operators/add_operator"', 'class="niceform"');?>

<div class="clearfix">
<?= form_label("Логин" , 'new_username') ?>
    <div class="input">
    <?= form_input('new_username', set_value('new_username'), 'autocomplete="off"') ?>
    <?= form_error('new_username') ?>
    </div>
</div>

<div class="clearfix">
<?= form_label("Пароль", 'new_password') ?>
    <div class="input">
    <?= form_password('new_password', set_value('new_password'), 'autocomplete="off"') ?>
    <?= form_error('new_password') ?>
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
<?= form_label('Имя', 'name') ?>
    <div class="input">
    <?= form_input('name', set_value('name')) ?>
    <?= form_error('name') ?>
    </div>
</div>

<div class="clearfix">
<?= form_label('Email', 'email') ?>
    <div class="input">
    <?= form_input('email', set_value('email')) ?>
    <?= form_error('email') ?>
    </div>
</div>

<div class="clearfix">
    <div class="input">
    <label>
    <?= form_checkbox('schools', '*', set_checkbox('schools', '*'), 'onChange="showHide($(\'schoolSelectorBlock\'))"') ?>
    Администратор</label>
    <?= form_error('admin') ?>
    </div>
</div>

<div class="clearfix" id="schoolSelectorBlock" <?= (isset($_POST['schools']) && $_POST['schools'] == '*') ? 'style="display:none"' : '' ?>>
    <label>Школы</label>
    <div class="input">
    <?php 
    $this->load->helper('widgets');
    echo form_schools_selector($schools, "Выбрать школы", 'title="Выбор школ для оператора" class="btn tiny"');
    echo form_error('schools');
    ?>
    </div>
</div>

<div class="actions">
<?= form_submit('submit', "Добавить оператора", 'class="btn primary"') ?>
</div>

<?= form_close() ?>