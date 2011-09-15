<h1>Добавление класса</h1>

<?php $this->load->helper('form'); ?>
<?= form_open('', 'class="niceform"', array('school_id' => $school_id)) ?>
<p>
    <?= form_label('Название: ', 'class') ?>
    <?= form_input('class') ?>
    <?= form_error('class') ?>
</p>

<p>
    <?= form_submit('submit', "Сохранить", 'class="submit"') ?>
</p>

<?= form_close(); ?>