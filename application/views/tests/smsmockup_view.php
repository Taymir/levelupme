<h1>Заглушка отправки СМС</h1> 

<?php $this->load->helper('form'); ?>
<?= form_open('tests/smsmockup'); ?>
<p>
<?php echo form_label('Номер телефона:', 'to'); ?>
<?php echo form_input('to'); ?>
</p>

<p>
<?php echo form_textarea('text'); ?>
</p>


<p>
<?php echo form_submit('submit', "Отправить"); ?>
</p>
<?php echo form_close(); ?>
