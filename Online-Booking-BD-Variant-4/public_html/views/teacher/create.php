<?php /** @var array $errors, $old, string $csrf */ ?>
<h2>➕ Добавить преподавателя</h2>
<?php if (!empty($errors)): ?>
    <div class="flash flash-error"><ul><?php foreach($errors as $e) echo "<li>$e</li>"; ?></ul></div>
<?php endif; ?>

<form method="post">
    <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrf) ?>">
    <label>Фамилия: <input type="text" name="last_name" value="<?= htmlspecialchars($old['last_name'] ?? '') ?>" required></label><br>
    <label>Имя: <input type="text" name="first_name" value="<?= htmlspecialchars($old['first_name'] ?? '') ?>" required></label><br>
    <label>Отчество: <input type="text" name="patronymic" value="<?= htmlspecialchars($old['patronymic'] ?? '') ?>"></label><br>
    <label>Телефон: <input type="tel" name="phone" value="<?= htmlspecialchars($old['phone'] ?? '') ?>" required></label><br>
    <label>Email: <input type="email" name="email" value="<?= htmlspecialchars($old['email'] ?? '') ?>" required></label><br>
    <br><button type="submit">Сохранить</button>
    <a href="?entity=teacher" style="margin-left:10px;">Отмена</a>
</form>