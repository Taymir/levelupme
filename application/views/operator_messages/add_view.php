<?php $this->load->helper('widgets'); ?>
<?= school_class_widget($schools_classes, '/operator_messages/add', $school_id, $class_id) ?>
<?= tariff_widget('/operator_messages/add', $tariffs, $tariff_id) ?>

<h2>Рассылки</h2>
<?php $this->load->helper('form'); ?>
<script type="text/javascript"> 
    window.addEvent('domready', function(){
        $('email_text').mooEditable({
                actions: 'bold italic underline strikethrough | formatBlock justifyleft justifyright justifycenter justifyfull | insertunorderedlist insertorderedlist indent outdent | undo redo | createlink unlink | urlimage | toggleview'
        });
    });
</script> 
<?= form_open('operator_messages/send', 'id="messagesForm" class="niceform journal-form"', array('class_id' => $class_id, 'school_id' => $school_id, 'tariff' => $tariff_id)); ?>

<?= form_fieldset("Адресат"); ?>
<div class="clearfix">
    <label>Тариф:</label>
    <div class="input">
        <strong><?= $tariffs[$tariff_id] ?></strong> <em>(при выборе школы или класса, рассылку получат только пользователи этого тарифа)</em>
    </div>
</div>
<div class="clearfix">
    <label>Кому отправить:</label>
    <div class="input">
        <ul class="inputs-list">
            <li><label><?= form_radio('recipient_type', 'school', set_radio('recipient_type', 'school', sizeof($students) == 0)) ?> <span>Школе "<strong><?= $class->school; ?></strong>"</span></label></li>
            <?php if(sizeof($students) > 0): ?>
            <li><label><?= form_radio('recipient_type', 'class', set_radio('recipient_type', 'class', $selected_student == null)) ?> <span>Классу "<strong><?= $class->class; ?></strong>"</span></label></li>
            <li><label><?= form_radio('recipient_type', 'user', set_radio('recipient_type', 'user', $selected_student != null)) ?> <span>Родителю ученика: <?= form_dropdown('user', $students, $selected_student) ?></span></label></li>
            <?php endif; ?>
        </ul>
    </div>
</div>
<?= form_fieldset_close(); ?>

<?= form_fieldset("SMS"); ?>
<div class="clearfix">
    <?= form_textarea('sms_text', set_value('sms_text'), 'id="sms_text"') ?>
</div>
<?= form_fieldset_close(); ?>

<?= form_fieldset("E-mail"); ?>
<div class="clearfix">
    <?= form_label("Тема письма:", 'email_title') ?>
    <div class="input">
        <?= form_input('email_title', set_value('email_title')); ?>
    </div>
</div>

<div class="clearfix">
    <?= form_textarea('email_text', set_value('email_text'), 'id="email_text"') ?>
</div>
<?= form_fieldset_close(); ?>

<div class="actions">
    <?= form_submit('submit', 'Отправить', 'class="btn primary" id="submit"') ?>
    <em>Cообщение будет разослано по e-mail и sms</em>
</div>
<?= form_close() ?>