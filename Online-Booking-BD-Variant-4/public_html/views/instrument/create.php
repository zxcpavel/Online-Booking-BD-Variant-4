<?php /** @var array $errors, $old, string $csrf */ ?>
<h2>➕ Добавить инструмент</h2>
<?php if (!empty($errors)): ?>
    <div class="flash flash-error"><ul><?php foreach($errors as $e) echo "<li>$e</li>"; ?></ul></div>
<?php endif; ?>

<form method="post">
    <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrf) ?>">
    <label>Название: <input type="text" name="instrument_name" value="<?= htmlspecialchars($old['instrument_name'] ?? '') ?>" required></label><br>
    <label>Описание: <textarea name="description"><?= htmlspecialchars($old['description'] ?? '') ?></textarea></label><br>
    <br><button type="submit">Сохранить</button>
    <a href="?entity=instrument" style="margin-left:10px;">Отмена</a>
</form>