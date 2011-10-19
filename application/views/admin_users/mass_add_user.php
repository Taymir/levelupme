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

<script type="text/javascript"> 
    window.addEvent('domready', function(){
        $('users').addEvent('keydown', function(e) {
            if(e.code == 9)
            {
                e.stop();
                $(this).insertAtCursor(String.fromCharCode(9), false);
            }
        });
    });
</script> 

<div class="clearfix">
<p>
<strong>Введите данные учеников через пробел: ФИО Тариф Телефон Email. Например:</strong>
</p>
<p>
<em>Иванов Иван Иванович 250 8-909-123-45-67 test@mail.com<br />
    Сидоров Анатолий Степанович<br/>
    Петров Петр Петрович 150 8-926-124-17-89<br />
</em>
</p>
<?= form_textarea('users', set_value('users'), 'id="users" class="mass-add"') ?>
<?= form_error('users'); ?>
</div>

<div class="actions">
<?= form_submit('submit', "Добавить", 'class="btn primary"') ?>
</div>

<?= form_close(); ?>