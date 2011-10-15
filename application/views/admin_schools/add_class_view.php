<h2><?= isset($class) ? 'Изменение класса' : 'Добавление класса' ?></h2>

<?php $this->load->helper('form'); ?>
<?= form_open('', 'class="niceform"') ?>
<?php if(isset($school_id)) echo form_hidden ('school_id', $school_id); ?>
<?php if(isset($class)) echo form_hidden ('class_id', $class->id); ?>

<div class="clearfix">
<?= form_label('Название', 'class') ?>
    <div class="input">
    <?= form_input('class', set_value('class', isset($class->class) ? $class->class : '')) ?>
    <?= form_error('class') ?>
    </div>
</div>

<div class="actions">
    <?= form_submit('submit', 'Сохранить', 'class="btn primary"') ?>
</div>

<?= form_close(); ?>