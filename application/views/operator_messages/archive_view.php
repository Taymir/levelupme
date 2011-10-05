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
        $this->load->helper('text');
        $text = $mailing->email_text;
        if($mailing->email_text == '')
            $text = $mailing->sms_text;
        
        echo anchor('#TODO', ellipsize(strip_tags($text), 50, 1));
        ?>
    </td>
</tr>
<?php endforeach; ?>
</tbody>
</table>

<div class="actions">
<!--//@TODO: FILTERS -->
<?php echo anchor('operator_messages/index', '<img src="' . base_url() . 'styles/icons/add.png" />Новая рассылка', array('class' => "btn success")) ?>
</div>