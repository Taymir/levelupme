<?php
$login = array(
	'name'	=> 'login',
	'id'	=> 'login',
	'value' => set_value('login'),
	'maxlength'	=> 80,
	'size'	=> 30,
);
if ($this->config->item('use_username', 'tank_auth')) {
	$login_label = 'Email или логин';
} else {
	$login_label = 'Email';
}
?>
<?php echo form_open($this->uri->uri_string()); ?>

<div class="clearfix">
<?php echo form_label($login_label, $login['id']); ?>
<div class="input">
<?php echo form_input($login); ?>
<?php echo form_error($login['name']); ?><?php echo isset($errors[$login['name']])?$errors[$login['name']]:''; ?>
</div>
</div>
    
<div class="actions">
<?php echo form_submit('reset', 'Получить новый пароль', 'class="btn primary"'); ?>
</div>
<?php echo form_close(); ?>