<h2><?= isset($profile) ? 'Изменение пользователя' : 'Добавление пользователя' ?></h2>

<?php $this->load->helper('form'); ?>
<?= form_open('', 'class="niceform"');?>
<?php if(isset($profile)) echo form_hidden('profile_id', $profile->id); ?>

<?= form_fieldset('Данные ученика') ?>
<div class="clearfix">
<?= form_label('Имя ученика', 'name') ?>
    <div class="input">
    <?= form_input('name', set_value('name', isset($profile->name)?$profile->name : '')) ?>
    <?= form_error('name') ?>
    </div>
</div>

<div class="clearfix">
<label>Класс</label>
    <div class="input">
<?php 
$this->load->helper('widgets');
echo form_class_selector($schools_classes, "Выбрать класс", 'title="Выбор класса" class="btn"', $default_class);
echo form_error('class_id');
?>
    </div>
</div>

<div class="clearfix">
<?= form_label('Тариф', 'tariff') ?>
    <div class="input">
    <?= form_dropdown('tariff', $tariffs, set_value('tariff', (isset($profile->tariff)?$profile->tariff:1))); ?>    
    <?= form_error('tariff') ?>
    </div>
</div>
<?=form_fieldset_close() ?>

<?= form_fieldset('Контактные данные для рассылок') ?>
<div class="clearfix">
<?= form_label('Телефон', 'phone') ?>
    <div class="input">
    <?= form_input('phone', set_value('phone', isset($profile->phone)?$profile->phone : '')) ?>
    <?= form_error('phone') ?>
    </div>
</div>

<div class="clearfix">
<?= form_label('Email', 'email') ?>
    <div class="input">
    <?= form_input('email', set_value('email', isset($profile->email)?$profile->email : '')) ?>
    <?= form_error('email') ?>
    </div>
</div>
<?= form_fieldset_close() ?>

<?= form_fieldset("Данные для входа на сайт"); ?>
<div class="clearfix">
<?= form_label("Логин" , 'new_username') ?>
    <div class="input">
    <?php if(isset($user)): ?>
        <strong><?= $user->username ?><?= form_hidden('old_username', $user->username) ?></strong><br/>
    <?php else: ?>
    <?= form_input('new_username', set_value('new_username'), 'autocomplete="off"') ?>
    <?= form_error('new_username') ?>
    <?php endif; ?>
    </div>
</div>

<?php if(isset($user)): ?>
<div class="clearfix">
    <div class="input">
        <label>
        <?= form_checkbox('change_password', '1', set_checkbox('change_password', '1'), 'onChange="showHide($(\'passwordChange\'))"') ?>
        Сменить пароль
        </label>
    </div>
</div>
<div id="passwordChange" <?= (isset($_POST['change_password']) && $_POST['change_password'] == '1') ? '' : 'style="display:none"' ?>>
<?php else: ?>
<div>
<?php endif; ?>

<div class="clearfix">
<?= form_label("Новый пароль", 'new_password') ?>
    <div class="input">
    <?= form_password('new_password', set_value('new_password'), 'autocomplete="off"') ?>
    <?= form_error('new_password') ?>
    </div>
</div>

<div class="clearfix">
<?= form_label('Подтверждение пароля', 'confirm') ?>
    <div class="input">
    <?= form_password('confirm', set_value('confirm'), 'autocomplete="off"') ?>
    <?= form_error('confirm') ?>
    </div>
</div>
    
</div>
<?= form_fieldset_close(); ?>

<div class="actions">
<?= form_submit('submit', isset($profile)?"Сохранить" : "Добавить", 'class="btn primary"') ?></p>
</div>

<?= form_close() ?>