<h2>Редактор страницы</h2>

<script type="text/javascript"> 
    window.addEvent('domready', function(){
        $('wysiwyg').mooEditable({
                actions: 'bold italic underline strikethrough | formatBlock justifyleft justifyright justifycenter justifyfull | insertunorderedlist insertorderedlist indent outdent | undo redo | createlink unlink | urlimage | toggleview'
        });
    });
</script> 


<?php $this->load->helper('form'); ?>
<?= form_open('admin_pages/edit', 'class="niceform"', isset($id) ? array('id' => $id) : null); ?>

<div class="clearfix">
<?= form_label('Название:', 'title'); ?>
    <div class="input">
    <?= form_input('title', set_value('title', $title)); ?>
    <?= form_error('title'); ?>
    </div>
</div>

<div class="clearfix">
<?= form_label('Ссылка:', 'link'); ?>
    <div class="input">
    <?= form_input('link', set_value('link', $link), (isset($id) && $id == 1) ? 'disabled="disabled"' : ''); ?>
    <?= form_error('link'); ?>
    </div>
</div>

<div class="clearfix">
<?= form_textarea('text', set_value('text', $text), 'id="wysiwyg"'); ?>
<?= form_error('text'); ?>
</div>

<div class="actions">
<?= form_submit('submit', "Сохранить", 'class="btn primary"'); ?>
</div>

<?= form_close(); ?>
