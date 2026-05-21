<?php
/**
 * @var array $errors
 * @var array $old
 * @var string $csrf
 */
?>

<h2>➕ Добавить ученика</h2>

<?php if (!empty($errors)): ?>
    <div class="flash flash-error">
        <ul style="margin:0; padding-left:20px;">
            <?php foreach ($errors as $err): ?>
                <li><?= htmlspecialchars($err) ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>

<form method="post" action="">
    <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrf) ?>">

    <label>Фамилия: <br>
        <input type="text" name="last_name" value="<?= htmlspecialchars($old['last_name'] ?? '') ?>" required>
    </label><br>

    <label>Имя: <br>
        <input type="text" name="first_name" value="<?= htmlspecialchars($old['first_name'] ?? '') ?>" required>
    </label><br>

    <label>Отчество: <br>
        <input type="text" name="patronymic" value="<?= htmlspecialchars($old['patronymic'] ?? '') ?>">
    </label><br>

    <label>Телефон: <br>
        <input type="tel" name="phone" value="<?= htmlspecialchars($old['phone'] ?? '') ?>" required>
    </label><br>

    <label>Email: <br>
        <input type="email" name="email" value="<?= htmlspecialchars($old['email'] ?? '') ?>" required>
    </label><br>

    <label>Дата рождения: <br>
        <input type="date" name="birth_date" value="<?= htmlspecialchars($old['birth_date'] ?? '') ?>">
    </label><br>

    <button type="submit">Сохранить</button>
    <a href="?entity=student" style="margin-left: 10px;">Отмена</a>
</form>