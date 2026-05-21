<?php /** @var array $teacher */ ?>
<h2>👤 Карточка преподавателя</h2>
<table>
    <tr><th>ФИО</th><td><?= htmlspecialchars($teacher['last_name'].' '.$teacher['first_name'].' '.($teacher['patronymic']??'')) ?></td></tr>
    <tr><th>Телефон</th><td><?= htmlspecialchars($teacher['phone']) ?></td></tr>
    <tr><th>Email</th><td><?= htmlspecialchars($teacher['email']) ?></td></tr>
</table>
<br><a href="?entity=teacher">← Назад</a>