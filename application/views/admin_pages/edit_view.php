<h1>Добавление страницы</h1>

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
<?= form_label('Название:', 'title'); ?>
<?= form_input('title', set_value('title', $title)); ?>
<?= form_error('title'); ?>
</p>

<p>
<?= form_textarea('text', set_value('text', $text), 'id="wysiwyg"'); ?>
</p>
<p>
<?= form_error('text'); ?>
</p>

<p>
<?= form_label('Ссылка:', 'link'); ?>
<?= form_input('link', set_value('link', $link)); ?>
<?= form_error('link'); ?>
</p>

<p>
<?= form_submit('submit', "Отправить", 'class="submit"'); ?>
</p>

<?= form_close(); ?>
