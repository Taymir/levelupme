<h2>Добавление класса</h2>

<?php $this->load->helper('form'); ?>
<?= form_open('', 'class="niceform"', array('school_id' => $school_id)) ?>

<div class="clearfix">
<?= form_label('Название', 'class') ?>
    <div class="input">
    <?= form_input('class') ?>
    <?= form_error('class') ?>
    </div>
</div>

<div class="actions">
    <?= form_submit('submit', 'Сохранить', 'class="btn primary"') ?>
</div>

<?= form_close(); ?>