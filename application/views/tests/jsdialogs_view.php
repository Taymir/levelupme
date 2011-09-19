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
$ci->load->model('classes_model');
$schools = $ci->classes_model->get_schools_and_classes();
?>
<?= class_selector_widget($schools, 'hiddenField', 'opendialog'); ?>
<h1>Диалоги</h1>
<em>Кликните по ссылке, чтобы открыть диалог. Такие диалоги будут использоваться для выбора из списка классов и школ.</em>
<input id="hiddenField" value="" />
<p><a href="#" id="opendialog">Открыть диалог</a></p>