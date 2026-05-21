<?php /** @var array $teacher, string $csrf */ ?>
<h2>️ Удаление преподавателя</h2>
<div class="flash flash-error">Удалить <?= htmlspecialchars($teacher['last_name'].' '.$teacher['first_name']) ?>?</div>
<form method="post">
    <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrf) ?>">
    <button type="submit" style="background:#dc3545;">Да, удалить</button>
    <a href="?entity=teacher" style="margin-left:10px;">Отмена</a>
</form>