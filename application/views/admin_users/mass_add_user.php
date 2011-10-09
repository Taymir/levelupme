<h2>Массовое добавление учеников</h2>

<?php $this->load->helper('form'); ?>
<?= form_open('admin_users/mass_add_user', 'class="niceform"'); ?>

<div class="clearfix">
<label>Класс</label>
<div class="input">
    <?php 
    $this->load->helper('widgets');
    echo form_class_selector($schools_classes, "Выбрать класс", 'title="Выбор класса" class="btn"', $default_class);
    echo form_error('class_id');
    ?>
</div>
</div>

<div class="clearfix">
<em>Введите имена учеников построчно (каждая строка - новый ученик)</em><br />
<?= form_textarea('names', set_value('names')) ?>
<?= form_error('names'); ?>
</div>

<div class="actions">
<?= form_submit('submit', "Добавить", 'class="btn primary"') ?>
</div>

<?= form_close(); ?>