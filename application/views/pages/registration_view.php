<?php $this->load->helper('form'); ?>
<?= form_open('', 'id="registrationForm" class="niceForm"'); ?>
<?= form_fieldset('Ученик:'); ?>
<div class="clearfix">
<?= form_label('<img src="' . base_url() . 'styles/images/name_symb.png" />', 'name'); ?>
    <div class="input">
        <span style="margin-left: -40px; font-weight: bold;">Ф. </span><?= form_input('name_f', set_value('name_f'), 'class="fio"'); ?> <span style="font-weight: bold;">И.</span> <?= form_input('name_i', set_value('name_i'), 'class="fio"'); ?> <span style="font-weight: bold;">О.</span> <?= form_input('name_o', set_value('name_o'), 'class="fio"'); ?>
    <br/><?= form_error('name_f'); ?> <?= form_error('name_i'); ?> <?= form_error('name_o'); ?>
    </div>
</div>

<div class="clearfix">
<?= form_label('Школа', 'school'); ?> 
    <div class="input">
    <?php if(isset($schoolslist)): ?>
    <?= form_dropdown('school', $schoolslist, set_value('school')) ?>
    <?php else: ?>
    <?= form_input('school', set_value('school')); ?> 
    <?php endif; ?>
    <?= form_error('school'); ?>
    </div>
</div>
<div class="clearfix">
<?= form_label('Класс', 'class'); ?> 
    <div class="input">
    <?= form_input('class', set_value('class')); ?> 
    <?= form_error('class'); ?>
    </div>
</div>
<?= form_fieldset_close(); ?>

<?= form_fieldset('Родитель:'); ?> 
<div class="clearfix">
<?= form_label('<img src="' . base_url() . 'styles/images/name_symb.png" />', 'pname'); ?>
    <div class="input">
    <span style="margin-left: -40px; font-weight: bold;">Ф. </span><?= form_input('pname_f', set_value('pname_f'), 'class="fio"'); ?> <span style="font-weight: bold;">И.</span> <?= form_input('pname_i', set_value('pname_i'), 'class="fio"'); ?> <span style="font-weight: bold;">О.</span> <?= form_input('pname_o', set_value('pname_o'), 'class="fio"'); ?>
    <br/><?= form_error('pname_f'); ?> <?= form_error('pname_i'); ?> <?= form_error('pname_o'); ?>
    </div>
</div>

<div class="clearfix">
<?= form_label('<img src="' . base_url() . 'styles/images/mail_symb.png" />', 'mail'); ?>
    <div class="input">
    <span style="margin-left: -40px;font-size: 14px;font-style: italic;">e-mail </span><?= form_input('mail', set_value('mail')); ?>
    <?= form_error('mail'); ?>
    </div>
</div>

<div class="clearfix">
<?= form_label('<img src="' . base_url() . 'styles/images/phone_symb.png" />', 'phone'); ?>
    <div class="input">
    <span style="margin-left: -31px; font-style: italic;">+7 </span><?= form_input('phone', set_value('phone')); ?>
    <?= form_error('phone'); ?>
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
<?= form_label("Пароль <span style='font-size: 65%'>(повторите)</span>", 'confirm'); ?>
    <div class="input">
    <?= form_password('confirm'); ?> 
    <?= form_error('confirm'); ?>
    </div>
</div>

<div class="clearfix">
<div class="tariff">
<label>
<img src="<?= base_url() ?>styles/images/tariff_100.png" />
<strong><nobr><?= form_radio('tariff', '100', set_radio('tariff', '100')) ?>Оценки</nobr></strong><br/>
</label>
</div>

<div class="tariff">
<label>
<img src="<?= base_url() ?>styles/images/tariff_150.png" />
<strong><nobr><?= form_radio('tariff', '150', set_radio('tariff', '150')) ?>Оценки +</nobr></strong><br/> необходимая организационная информация
</label>
</div>

<div class="tariff">
<label>
<img src="<?= base_url() ?>styles/images/tariff_200.png" />
<strong><nobr><?= form_radio('tariff', '200', set_radio('tariff', '200')) ?>Оценки +</nobr></strong><br/> организационная и общешкольная информация + аналитический отчет
</label>
</div>

<div class="tariff">
<label>
<img src="<?= base_url() ?>styles/images/tariff_250.png" />
<strong><nobr><?= form_radio('tariff', '250', set_radio('tariff', '250')) ?>Оценки +</nobr></strong><br/> организационная и общешкольная информация + сводка об олимпиадах 
и соревнованиях + граф-аналитический отчет
</label>
</div>
</div>

<?= form_error('tariff'); ?>
<?= form_fieldset_close(); ?>

<?= form_fieldset('Примечание:'); ?>
<div class="clearfix">
    <div style="text-align: center;">
    <?= form_textarea('comment', set_value('comment')); ?>
    </div>
</div>
<?= form_fieldset_close(); ?>

<div class="clearfix">
    Я ознакомился и принимаю условия <a href="/agreement" target="_blank">договора</a>: <?= form_checkbox('agreement', 'true', set_checkbox('agreement', 'true')) ?>
<br/><?= form_error('agreement'); ?>
</div>

<div class="clearfix">
    <div style="text-align: center;">
    <?= form_submit('submit', 'Отправить заявку', 'class="btn success"'); ?>
    </div>
</div>
<?= form_close(); ?>

<p><strong>Ваша школа ещё не подключена к системе LevelUP? <a href="mailto:<?= $this->config->item('registration_mail'); ?>">Напишите нам</a>.</strong></p>