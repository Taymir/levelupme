<?php
$new_password = array(
	'name'	=> 'new_password',
	'id'	=> 'new_password',
	'maxlength'	=> $this->config->item('password_max_length', 'tank_auth'),
	'size'	=> 30,
);
$confirm_new_password = array(
	'name'	=> 'confirm_new_password',
	'id'	=> 'confirm_new_password',
	'maxlength'	=> $this->config->item('password_max_length', 'tank_auth'),
	'size' 	=> 30,
);
?>
<?php echo form_open($this->uri->uri_string()); ?>

<div class="clearfix">
<?php echo form_label('Новый пароль', $new_password['id']); ?>
<div class="input">
<?php echo form_password($new_password); ?>
<?php echo form_error($new_password['name']); ?><?php echo isset($errors[$new_password['name']])?$errors[$new_password['name']]:''; ?>
</div>
</div>

<div class="clearfix">
<?php echo form_label('Подтверждение пароля', $confirm_new_password['id']); ?></td>
<div class="input">
<?php echo form_password($confirm_new_password); ?></td>
<?php echo form_error($confirm_new_password['name']); ?><?php echo isset($errors[$confirm_new_password['name']])?$errors[$confirm_new_password['name']]:''; ?>
</div>
</div>

<div class="actions">
<?php echo form_submit('change', 'Сменить пароль','class="btn primary"'); ?>
</div>
<?php echo form_close(); ?>