<h2>Просмотр рассылки</h2>
<?php if(!empty($email_text)): ?>
<h3>E-mail: <?= $email_title?></h3>
<p><?= $email_text ?></p>
<hr />
<?php endif; ?>
<pre><?= $sms_text ?></pre>
