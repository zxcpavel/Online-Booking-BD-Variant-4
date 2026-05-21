<?php /** @var array $instrument, string $csrf */ ?>
<h2>🗑️ Удаление инструмента</h2>
<div class="flash flash-error">Удалить <?= htmlspecialchars($instrument['instrument_name']) ?>?</div>
<form method="post">
    <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrf) ?>">
    <button type="submit" style="background:#dc3545;">Да, удалить</button>
    <a href="?entity=instrument" style="margin-left:10px;">Отмена</a>
</form>