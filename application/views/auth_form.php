<div>
 <?php if(isset($user_id)) : ?>
    <?php
    if($role == 'admin')
        echo "Администратор: ";
    elseif ($role == 'operator')
        echo "Оператор: ";
    ?>
    <strong><?php echo $name; ?></strong> <?php echo anchor('/auth/logout/', 'Выход'); ?>   
<?php else: ?>
<?php
$login = array(
	'name'	=> 'login',
	'id'	=> 'login',
//	'value' => set_value('login'),
	'maxlength'	=> 80,
	'size'	=> 30,
);
if ($login_by_username AND $login_by_email) {
	$login_label = 'Email или логин:';
} else if ($login_by_username) {
	$login_label = 'Логин:';
} else {
	$login_label = 'Email';
}
$password = array(
	'name'	=> 'password',
	'id'	=> 'password',
	'size'	=> 30,
);
$remember = array(
	'name'	=> 'remember',
	'id'	=> 'remember',
	'value'	=> 0,
	'checked'	=> set_value('remember'),
	'style' => 'margin:0;padding:0',
);
$captcha = array(
	'name'	=> 'captcha',
	'id'	=> 'captcha',
	'maxlength'	=> 8,
);
?>
<strong>Авторизация:</strong>
<?= form_open('auth/login') ?>

<?= form_label($login_label, $login['id']) ?>
<?= form_input($login); ?><br />

<?php echo form_label('Пароль', $password['id']); ?>
<?php echo form_password($password); ?><br />
    
<?php echo form_checkbox($remember); ?>
<?php echo form_label('Запомнить меня', $remember['id']); ?><br />
<?php echo anchor('/auth/forgot_password/', 'Забыл пароль'); ?><br />

<?php if ($show_captcha) {
        if ($use_recaptcha) { ?>
                <div id="recaptcha_image"></div>
                <a href="javascript:Recaptcha.reload()">Получить новый код</a><br />
                <div class="recaptcha_only_if_image"><a href="javascript:Recaptcha.switch_type('audio')">Аудио-код</a></div>
                <div class="recaptcha_only_if_audio"><a href="javascript:Recaptcha.switch_type('image')">Код-изображение</a></div>

                <div class="recaptcha_only_if_image">Введите слова, написанные выше</div>
                <div class="recaptcha_only_if_audio">Введите числа, которые услышали</div>

                <input type="text" id="recaptcha_response_field" name="recaptcha_response_field" /><br />
                <?php echo form_error('recaptcha_response_field'); ?><br />
                <?php echo $recaptcha_html; ?>
<?php } else { ?>
                <p>Введите код с изображения:</p>
                <?php echo $captcha_html; ?><br />

                <?php echo form_label('Код:', $captcha['id']); ?><br />
                <?php echo form_input($captcha); ?><br />
                <?php echo form_error($captcha['name']); ?><br />
<?php }
} ?>

<?php echo form_submit('submit', 'Войти'); ?><br />
<?php echo form_close(); ?>
    
<?php endif; ?>
</div>