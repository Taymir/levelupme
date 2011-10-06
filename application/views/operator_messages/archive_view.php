<?php $this->load->helper('widgets'); ?>
<?= school_class_widget($schools_classes, '', $school_id, $class_id) ?>

<h2>Архив рассылок</h2>

<p>
<strong>Школа: <?= $school ?></strong><br />
<strong>Класс: <?= $class->class ?></strong><br />
</p>

<table class="nicetable">
<thead>
<th>Статус</th>
<th>Дата</th>
<th>Получатель</th>
<th>Сообщение</th>
</thead>
<tbody>
<?php foreach($mailings as $mailing): ?>
<tr>
    <td>
        <?php
        if($mailing->sms_status == 'pending') {
            $status = 'pending';
            $title = 'В очереди';
        } elseif($mailing->sms_status == 'sent') {
            $status = 'sent';
            $title = 'Отправлено';
        } else {
            $status = 'error';
            $title = 'Ошибка отправки';
        }
        
        echo '<img src="' . base_url() . 'styles/icons/sms_' . $status . '.png" title="SMS: ' . $title . '" />&nbsp;';
        
        if($mailing->email_status == 'pending') {
            $status = 'pending';
            $title = 'В очереди';
        } elseif($mailing->email_status == 'sent') {
            $status = 'sent';
            $title = 'Отправлено';
        } else {
            $status = 'error';
            $title = 'Ошибка отправки';
        }
        
        echo '<img src="' . base_url() . 'styles/icons/mail_' . $status . '.png" title="EMail: ' . $title . '" />&nbsp;';
        ?>
    </td>
    <td><?= date('d.m.Y', strtotime($mailing->created)) ?></td>
    <td><?= $mailing->name . ', ' . $mailing->school . ', ' . $mailing->class ?></td>
    <td>
        <?php 
        $title = $mailing->email_title;
        if($title == '')
        {
            $this->load->helper('text');
            $text = $mailing->email_text;
            if($mailing->email_text == '')
                $text = $mailing->sms_text;
            $title = ellipsize(strip_tags($text), 50, 1);
        }
        echo anchor('operator_messages/view/' . $mailing->mailing_id, $title);
        ?>
    </td>
</tr>
<?php endforeach; ?>
</tbody>
</table>

<div class="pagination"><?= $this->pagination->create_links(); ?></div>

<div class="actions">
    <?= form_open('') ?>
    <?= form_fieldset('Выводить только:') ?>
    <div class="input">
    <ul class="inputs-list">
        <li><label><?= form_checkbox('filters[]', 'school', set_checkbox('filters', 'school', TRUE)) ?> <span>Рассылки по школе</span></label></li>
        <li><label><?= form_checkbox('filters[]', 'class', set_checkbox('filters', 'class', TRUE)) ?> <span>Рассылки по классу</span></label></li>
        <li><label><?= form_checkbox('filters[]', 'user', set_checkbox('filters', 'user', TRUE)) ?> <span>Рассылки индвидульно родителям</span></label></li>
        <li><label><?= form_checkbox('filters[]', 'other', set_checkbox('filters', 'other', TRUE)) ?> <span>Рассылки прочие</span></label></li>
    </ul>
    </div>
<?= form_submit('submit', 'Обновить', 'class="btn primary"') ?><br />
<?= form_fieldset_close(); ?>
<?= form_close() ?>
<?= anchor('operator_messages/add', '<img src="' . base_url() . 'styles/icons/add.png" />Новая рассылка', array('class' => "btn success")) ?>
</div>