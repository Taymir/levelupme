<h1>Добавление страницы</h1>

<style type="text/css"> 
        body{
                font-family: sans-serif;
                font-size: .9em;
        }
        #wysiwyg{
                width: 720px;
                height: 200px;
                padding: 10px;
                border: 2px solid #ddd;
        }
</style> 

<link rel="stylesheet" type="text/css" href="/styles/MooEditable/MooEditable.css"> 
<link rel="stylesheet" type="text/css" href="/styles/MooEditable/MooEditable.Extras.css"> 
<link rel="stylesheet" type="text/css" href="/styles/MooEditable/MooEditable.SilkTheme.css"> 

<script type="text/javascript" src="/scripts/mootools-core.js"></script> 
<script type="text/javascript" src="/scripts/mootools-more.js"></script> 

<script type="text/javascript" src="/scripts/Locale/Locale.ru-RU.MooEditable.js"></script>
<script type="text/javascript" src="/scripts/MooEditable/MooEditable.js"></script>
<script type="text/javascript" src="/scripts/MooEditable/MooEditable.UI.MenuList.js"></script> 
<script type="text/javascript" src="/scripts/MooEditable/MooEditable.Extras.js"></script> 
<script type="text/javascript"> 
    window.addEvent('domready', function(){
        $('wysiwyg').mooEditable({
                actions: 'bold italic underline strikethrough | formatBlock justifyleft justifyright justifycenter justifyfull | insertunorderedlist insertorderedlist indent outdent | undo redo | createlink unlink | urlimage | toggleview'
        });
    });
</script> 


<?php $this->load->helper('form'); ?>
<?= form_open('admin_pages/edit', 'class="niceform"', isset($id) ? array('id' => $id) : null); ?>
<p>
<?php echo form_label('Название:', 'title'); ?>
<?php echo form_input('title', set_value('title', $title)); ?>
<?php echo form_error('title'); ?>
</p>

<p>
<?php echo form_textarea('text', set_value('text', $text), 'id="wysiwyg"'); ?>
</p>
<p>
<?php echo form_error('text'); ?>
</p>

<p>
<?php echo form_label('Ссылка:', 'link'); ?>
<?php echo form_input('link', set_value('link', $link)); ?>
<?php echo form_error('link'); ?>
</p>

<p>
<?php echo form_submit('submit', "Отправить", 'class="submit"'); ?>
</p>

<?php echo form_close(); ?>
