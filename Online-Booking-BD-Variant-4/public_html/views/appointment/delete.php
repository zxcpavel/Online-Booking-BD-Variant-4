<?php /** @var array $appointment, string $csrf */ 
$lesson = is_array($appointment) && isset($appointment[0]) ? $appointment[0] : $appointment;
?>
<h2>️ Удаление записи</h2>
<div class="flash flash-error">
    Вы уверены, что хотите удалить урок от <?= htmlspecialchars($lesson['student_name'] ?? 'неизвестно') ?>?
</div>
<form method="post">
    <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrf) ?>">
    <button type="submit" style="background:#dc3545;">Да, удалить</button>
    <a href="?entity=appointment" style="margin-left:10px;">Отмена</a>
</form>