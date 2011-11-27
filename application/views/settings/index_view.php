<h2>Настройки</h2>

<?php $this->load->helper('form'); ?>
<?= form_open('', 'class="niceform"'); ?>

<?php if($profile->role == 'parent'): ?>

<?= form_fieldset('Тариф'); ?>
<div class="clearfix">
    <?= form_label('Ваш тариф:'); ?>
    <div class="input">
    <strong><?= isset($profile->tariff_name) ? $profile->tariff_name:'' ?></strong>
    </div>
</div>
<?= form_fieldset_close(); ?>

<?php endif; ?>

<?= form_fieldset('Контакты'); ?>
<div class="clearfix">
    <?= form_label("E-mail:", 'mail'); ?>
    <div class="input">
        <?= form_input('email', set_value('email', isset($profile->email) ? $profile->email:'')) ?>
        <?= form_error('email') ?>
    </div>
</div>

<?php if($profile->role == 'parent'): ?>
<div class="clearfix">
    <?= form_label("Телефон:<br/>(для sms)", 'phone'); ?>
    <div class="input">
        <?= form_input('phone', set_value('phone', isset($profile->phone) ? $profile->phone:'')) ?>
        <?= form_error('phone') ?>
    </div>
</div>
<?= form_fieldset_close(); ?>
<?php endif; ?>

<?= form_fieldset('Смена пароля'); ?>
<div class="clearfix">
    <?= form_label('Ваш логин:'); ?>
    <div class="input">
    <strong><?= isset($user->username) ? $user->username:'' ?></strong>
    </div>
</div>

<div class="clearfix">
    <?= form_label("Старый пароль:", 'old_password'); ?>
    <div class="input">
        <?= form_password('old_password') ?> 
        <?= form_error('old_password') ?> 
        <?= isset($password_errors) ? '<span class="validation-err">' . implode(' ', $password_errors) . '</span>' : '' ?>
    </div>
</div>

<div class="clearfix">
    <?= form_label("Новый пароль:", 'new_password'); ?>
    <div class="input">
        <?= form_password('new_password') ?>
        <?= form_error('new_password') ?>
    </div>
</div>

<div class="clearfix">
    <?= form_label("Новый пароль:<br/>(повторите)", 'confirm'); ?>
    <div class="input">
        <?= form_password('confirm') ?>
        <?= form_error('confirm') ?>
    </div>
</div>
<?= form_fieldset_close(); ?>

<div class="actions">
<?= form_reset('', "Отменить", 'class="btn"') ?> 
<?= form_submit('submit', "Сохранить", 'class="btn success"') ?> 
</div>