<h2>Добавление школы</h2>

<?php $this->load->helper('form'); ?>
<?= form_open('', 'class="niceform"') ?>

<div class="clearfix"><?= form_label('Название', 'school') ?>
    <div class="input">
    <?= form_input('school') ?>
    <?= form_error('school') ?>
    </div>
</div>

<div class="actions">
    <?= form_submit('submit', "Сохранить", 'class="btn primary"') ?>
</div>

<?= form_close(); ?>