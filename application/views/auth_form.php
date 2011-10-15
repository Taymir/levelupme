<div class="auth-form">
<div class="top-auth-form">
<?php if(isset($user_id)) : ?>
    <?php
    if($role == 'admin')
        echo "Администратор: ";
    elseif ($role == 'operator')
        echo "Оператор: ";
    ?>
    <label><?= $name; ?></label>
    
    </div>
    <div class="bottom-auth-form">
        
    <?= anchor('/auth/logout/', 'Выход', 'class="btn success"'); ?>
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

<?= form_open('auth/login') ?>


<?= form_label($login_label, $login['id']) ?>
<?= form_input($login); ?>

<span class="forgot-password"><?= anchor('/auth/forgot_password/', 'Забыли пароль?'); ?></span>
<?= form_label('Пароль', $password['id']); ?>
<?= form_password($password); ?>

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
                <label>Введите код с изображения:</label>
                <?php echo $captcha_html; ?>

                <?php echo form_label('Код:', $captcha['id']); ?>
                <?php echo form_input($captcha); ?>
                <?php echo form_error($captcha['name']); ?>
<?php }
} ?>
</div>
<div class="bottom-auth-form">
<label class="remember-me"><?= form_checkbox($remember); ?>Запомнить меня</label>
<?php echo form_submit('submit', 'Войти', 'class="btn success"'); ?>
<?php echo form_close(); ?>
<?php endif; ?>
</div>
</div>