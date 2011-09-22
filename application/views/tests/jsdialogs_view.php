<script type="text/javascript" src="/scripts/mootools-core.js"></script> 
<script type="text/javascript" src="/scripts/mootools-more.js"></script> 
<script type="text/javascript" src="/scripts/MUX.Dialog.js"></script>
<link rel="stylesheet" href="/styles/MUX.Dialog.css"> 


<form action="http://form-data.appspot.com/">
    <script type="text/javascript" src="/scripts/showDialog.js"></script>
    <?php 
    $this->load->helper('widgets');
    $ci = & get_instance();
    $ci->load->model('classes_model');
    $schoolClassData = $ci->classes_model->get_schools_and_classes();
    echo form_schools_selector($schoolClassData, "Выбрать школы"); ?>
    <input type="submit" />
</form>