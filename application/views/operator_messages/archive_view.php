<?php $this->load->helper('widgets'); ?>
<?= school_class_widget($schools_classes, '', $school_id, $class_id) ?>

<?php if($class): ?>
<h2>Архив рассылок</h2>

<p>
<strong>Школа: <?= $class->school ?></strong><br />
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
        if($mailing->sms_status != 'empty'){
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
        } else echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
        
        if($mailing->email_status != 'empty'){
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
        } else echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
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
            $text = strip_tags($mailing->email_text);
            if(trim($text) == '')
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

<?php endif; ?>

<div class="actions">
<?php if($class): ?>
    <?= form_open('') ?>
    <?= form_fieldset('Выводить только:') ?>
    <div class="input">
    <ul class="inputs-list">
        <li><label><?= form_checkbox('filters[]', 'school', in_array('school', $filters)) ?> <span>Рассылки по школе</span></label></li>
        <li><label><?= form_checkbox('filters[]', 'class', in_array('class', $filters)) ?> <span>Рассылки по классу</span></label></li>
        <li><label><?= form_checkbox('filters[]', 'user', in_array('user', $filters)) ?> <span>Рассылки индвидульно родителям</span></label></li>
        <li><label><?= form_checkbox('filters[]', 'analytic', in_array('analytic', $filters)) ?> <span>Рассылки оценок</span></label></li>
        <li><label><?= form_checkbox('filters[]', 'grades', in_array('grades', $filters)) ?> <span>Рассылки аналитики</span></label></li>
    </ul>
    </div>
<?= form_submit('submit', 'Обновить', 'class="btn primary"') ?><br />
<?= form_fieldset_close(); ?>
<?= form_close() ?>
<?php endif; ?>
<?= anchor('operator_messages/add', '<img src="' . base_url() . 'styles/icons/add.png" />Новая рассылка', array('class' => "btn success")) ?>
</div>
