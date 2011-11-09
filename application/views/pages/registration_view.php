<?php $this->load->helper('form'); ?>
<?= form_open('', 'id="registrationForm" class="niceForm"'); ?>
<?= form_fieldset('Информация об ученике'); ?>
<p>
<label>
<img src="<?= base_url() ?>styles/images/name_symb.png" />Ф. 
<?= form_input('f_name'); ?> 
</label>
<?= form_label('И.', 'i_name'); ?> 
<?= form_input('i_name'); ?> 
<?= form_label('О.', 'o_name'); ?> 
<?= form_input('o_name'); ?> 
</p>

<p>
<?= form_label('Школа', 'school'); ?> 
<?= form_input('school'); ?> 

<?= form_label('Класс', 'class'); ?> 
<?= form_input('class'); ?> 
</p>
<?= form_fieldset_close(); ?>

<?= form_fieldset('Информация о родителе'); ?> 
<p>
<label>
<img src="<?= base_url() ?>styles/images/name_symb.png" />Ф. 
<?= form_input('f_pname'); ?> 
</label>
<?= form_label('И.', 'i_pname'); ?> 
<?= form_input('i_pname'); ?> 
<?= form_label('О.', 'o_pname'); ?> 
<?= form_input('o_pname'); ?> 
</p>

<p>
<label>
<img src="<?= base_url() ?>styles/images/phone_symb.png" />
+7 <?= form_input('phone'); ?> 
</label>
    
<label>
<img src="<?= base_url() ?>styles/images/mail_symb.png" />
<?= form_input('mail_base'); ?> 
</label>
<?= form_label('@', 'mail_domain'); ?> 
<?= form_input('mail_domain', '', 'style="width:50px"'); ?> 
<?= form_label('.', 'mail_zone'); ?> 
<?= form_input('mail_zone', '', 'style="width:30px"'); ?> 
</p>
<p>
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
</p>
<?= form_fieldset_close(); ?>

<label>Я ознакомился и принимаю условия <a href="#" target="_blank">договора</a>: <?= form_checkbox('agreement', 'true', false) ?></label>
<?= form_submit('submit', 'Зарегистрироваться', 'class="btn success"'); ?>
<?= form_close(); ?>