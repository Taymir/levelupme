<?php $this->load->helper('form'); ?>
<?= form_open('', 'id="registrationForm" class="niceForm"'); ?>
<?= form_fieldset('Информация об ученике'); ?>
<div class="clearfix">
<?= form_label("ФИО", 'name'); ?>
    <div class="input">
    <?= form_input('name'); ?>
    <?= form_error('name'); ?>
    </div>
</div>

<div class="clearfix">
<?= form_label('Школа', 'school'); ?> 
    <div class="input">
    <?= form_input('school'); ?> 
    <?= form_error('school'); ?>
    </div>
</div>
<div class="clearfix">
<?= form_label('Класс', 'class'); ?> 
    <div class="input">
    <?= form_input('class'); ?> 
    <?= form_error('class'); ?>
    </div>
</div>
<?= form_fieldset_close(); ?>

<?= form_fieldset('Информация о родителе'); ?> 
<div class="clearfix">
<?= form_label("ФИО", 'pname'); ?>
    <div class="input">
    <?= form_input('pname'); ?>
    <?= form_error('pname'); ?>
    </div>
</div>

<div class="clearfix">
<?= form_label("E-Mail", 'mail'); ?>
    <div class="input">
    <?= form_input('mail'); ?>
    <?= form_error('mail'); ?>
    </div>
</div>

<div class="clearfix">
<?= form_label("Телефон", 'phone'); ?>
    <div class="input">
    <?= form_input('phone'); ?>
    <?= form_error('phone'); ?>
    </div>
</div>

<div class="clearfix">
<?= form_label("Логин", 'username'); ?>
    <div class="input">
    <?= form_input('username'); ?> 
    <?= form_error('username'); ?>
    </div>
</div>

<div class="clearfix">
<?= form_label("Пароль", 'password'); ?>
    <div class="input">
    <?= form_password('password'); ?> 
    <?= form_error('password'); ?>
    </div>
</div>

<div class="clearfix">
<?= form_label("Пароль (повторите)", 'confirm'); ?>
    <div class="input">
    <?= form_password('confirm'); ?> 
    <?= form_error('confirm'); ?>
    </div>
</div>

<div class="clearfix">
<div class="tariff">
<label>
<img src="<?= base_url() ?>styles/images/tariff_100.png" />
<strong><?= form_radio('tariff', '100', false) ?>Оценки</strong><br/>
</label>
</div>

<div class="tariff">
<label>
<img src="<?= base_url() ?>styles/images/tariff_150.png" />
<strong><?= form_radio('tariff', '150', false) ?>Оценки +</strong><br/> необходимая организационная информация
</label>
</div>

<div class="tariff">
<label>
<img src="<?= base_url() ?>styles/images/tariff_200.png" />
<strong><?= form_radio('tariff', '200', false) ?>Оценки +</strong><br/> организационная и общешкольная информация + аналитический отчет
</label>
</div>

<div class="tariff">
<label>
<img src="<?= base_url() ?>styles/images/tariff_250.png" />
<strong><?= form_radio('tariff', '250', false) ?>Оценки +</strong><br/> организационная и общешкольная информация + сводка об олимпиадах 
и соревнованиях + граф-аналитический отчет
</label>
</div>
</div>

<?= form_error('tariff'); ?>
<?= form_fieldset_close(); ?>

<div class="clearfix">
Я ознакомился и принимаю условия <a href="/agreement" target="_blank">договора</a>: <?= form_checkbox('agreement', 'true', false) ?>
<br/><?= form_error('agreement'); ?>
</div>

<div class="clearfix">
    <div class="input">
    <?= form_submit('submit', 'Отправить заявку', 'class="btn success"'); ?>
    </div>
</div>
<?= form_close(); ?>