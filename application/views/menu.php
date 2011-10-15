<?php if($role == 'admin'): ?>
<strong>Раздел администратора:</strong>
<ul>
    <li><a href="/admin_schools">Школы и классы</a></li>
    <li><a href="/admin_operators">Операторы</a></li>
    <li><a href="/admin_users">Пользователи</a></li>
    <li><a href="/admin_pages">Страницы</a></li>
</ul>
<?php endif; ?>
<?php if($role == 'admin' || $role == 'operator'): ?>
<strong>Раздел оператора:</strong>
<ul>
    <li><a href="/operator_journal">Журнал</a></li>
    <li><a href="/operator_timetable">Расписание</a></li>
    <li><a href="/operator_messages">Рассылки</a></li>
    <!--<li><a href="/operator_settings">Настройки</a></li>-->
</ul>
<?php endif; ?>
<?php if($role == 'parent'): /*?>
<strong>Раздел родителя:</strong>
<ul>
    <li><a href="/recordbook">Дневник</a></li>
    <li><a href="/schedule">Расписание</a></li>
    <li><a href="/payment">Оплата</a></li>
    <!--<li><a href="/settings">Настройки</a></li>-->
</ul>
<?php*/ endif; ?>
<strong>Меню:</strong>
<ul>
    <li><a href="/">О системе Levelup</a></li>
    <li><a href="/join">Подключение</a></li>
    <li><a href="/help">Пользователям</a></li>
</ul>