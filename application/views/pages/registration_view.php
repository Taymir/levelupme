<?php $this->load->helper('form'); ?>
<?= form_open('', ''); ?>
<?= form_fieldset('Информация об ученике'); ?>
<?= form_label('Ф.', 'f_name'); ?>
<?= form_input('f_name'); ?>
<?= form_label('И.', 'i_name'); ?>
<?= form_input('i_name'); ?>
<?= form_label('О.', 'o_name'); ?>
<?= form_input('o_name'); ?>

<?= form_label('Школа', 'school'); ?>
<?= form_input('school'); ?>

<?= form_label('Класс', 'class'); ?>
<?= form_input('class'); ?>
<?= form_fieldset_close(); ?>

<?= form_fieldset('Информация о родителе'); ?>
<?= form_label('Ф.', 'f_pname'); ?>
<?= form_input('f_pname'); ?>
<?= form_label('И.', 'i_pname'); ?>
<?= form_input('i_pname'); ?>
<?= form_label('О.', 'o_pname'); ?>
<?= form_input('o_pname'); ?>

<?= form_label('Тел.', 'phone'); ?>
<?= form_input('phone'); ?>

<?= form_label('E-mail', 'mail_base'); ?>
<?= form_input('mail_base'); ?>
<?= form_label('@', 'mail_domain'); ?>
<?= form_input('mail_domain'); ?>
<?= form_label('.', 'mail_zone'); ?>
<?= form_input('mail_zone'); ?>

<label>
100 р Оценки
<?= form_radio('tariff', '100', false) ?>
</label>

<label>
150 р Оценки + необходимая организационная информация
<?= form_radio('tariff', '150', false) ?>
</label>

<label>
200 р Оценки + организационная и общешкольная информация + аналитический отчет
<?= form_radio('tariff', '200', false) ?>
</label>

<label>
250 р Оценки + организационная и общешкольная информация + сводка об олимпиадах 
и соревнованиях + граф-аналитический отчет
<?= form_radio('tariff', '250', false) ?>
</label>

<label>Я ознакомился и принимаю условия <a href="#" target="_blank">договора</a>: <?= form_checkbox('agreement', 'true', false) ?></label>
<?= form_fieldset_close(); ?>
<?= form_close(); ?>