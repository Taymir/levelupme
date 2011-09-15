<h1>Добавление школы</h1>

<?php $this->load->helper('form'); ?>
<?= form_open('', 'class="niceform"') ?>
<p>
    <?= form_label('Название: ', 'school') ?>
    <?= form_input('school') ?>
    <?= form_error('school') ?>
</p>

<p>
    <?= form_submit('submit', "Сохранить", 'class="submit"') ?>
</p>

<?= form_close(); ?>