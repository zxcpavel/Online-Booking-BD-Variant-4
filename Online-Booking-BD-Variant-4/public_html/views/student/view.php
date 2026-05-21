<?php
/**
 * @var array $student
 */
?>

<h2> Карточка ученика</h2>

<table style="max-width: 600px;">
    <tr><th>ID</th><td><?= htmlspecialchars($student['student_id']) ?></td></tr>
    <tr><th>ФИО</th><td><?= htmlspecialchars($student['last_name'] . ' ' . $student['first_name'] . ' ' . ($student['patronymic'] ?? '')) ?></td></tr>
    <tr><th>Телефон</th><td><?= htmlspecialchars($student['phone']) ?></td></tr>
    <tr><th>Email</th><td><?= htmlspecialchars($student['email']) ?></td></tr>
    <tr><th>Дата рождения</th><td><?= htmlspecialchars($student['birth_date']) ?></td></tr>
    <tr><th>Дата зачисления</th><td><?= htmlspecialchars($student['enrollment_date']) ?></td></tr>
</table>

<br>
<a href="?entity=student" style="color: #007bff;">← Вернуться к списку</a>
<a href="?entity=student&action=edit&id=<?= $student['student_id'] ?>" style="margin-left: 20px;">✏️ Редактировать</a>