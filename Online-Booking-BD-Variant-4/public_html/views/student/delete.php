<?php
/**
 * @var array $student
 * @var string $csrf
 */
?>

<h2>🗑️ Подтверждение удаления</h2>

<div class="flash flash-error">
    Вы уверены, что хотите удалить ученика <strong><?= htmlspecialchars($student['last_name'] . ' ' . $student['first_name']) ?></strong>?
</div>

<p>Если удалить, история уроков тоже может пострадать (в зависимости от настроек БД).</p>

<form method="post" action="">
    <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrf) ?>">
    <button type="submit" style="background: #dc3545;">Да, удалить</button>
    <a href="?entity=student" style="margin-left: 10px;">Отмена</a>
</form>