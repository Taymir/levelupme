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
<?= form_checkbox('admin', 'admin', set_radio('admin')) ?>
Администратор</label>
<?= form_error('admin') ?>
</p>

<p>
<script type="text/javascript" src="/scripts/mootools-core.js"></script> 
<script type="text/javascript" src="/scripts/mootools-more.js"></script> 
<script type="text/javascript" src="/scripts/MUX.Dialog.js"></script>
<script type="text/javascript" src="/scripts/showDialog.js"></script>
<link rel="stylesheet" href="/styles/MUX.Dialog.css"> 

<style type="text/css">
     .link {
	cursor: pointer;
	text-decoration: none;
	color: inherit;
	font-size: inherit;
     }
 </style>
<?php $this->load->helper('widgets');
$ci = & get_instance();
$ci->load->model('schools_model');
$schools = $ci->schools_model->get_schools();
echo schools_selector_widget($schools, 'schools', 'opendialog');
echo '<input type="hidden" id="schools" name="schools" />';
echo "<a href=\"#\" id=\"opendialog\">Выбрать школы</a>\n";
echo form_error('schools');
?>
</p>

<p>
<?= form_submit('submit', "Добавить", 'class="submit"') ?>
</p>

<?= form_close() ?>