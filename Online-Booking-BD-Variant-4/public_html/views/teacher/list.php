<?php /** @var array $teachers, string $search */ ?>
<h2>👨‍🏫 Преподаватели</h2>
<a href="?entity=teacher&action=create" class="btn">+ Добавить</a>

<form method="get" style="margin: 15px 0;">
    <input type="hidden" name="entity" value="teacher">
    <input type="text" name="search" placeholder="Поиск по ФИО..." value="<?= htmlspecialchars($search ?? '') ?>">
    <button type="submit">Найти</button>
    <a href="?entity=teacher" style="margin-left: 10px; color: #007bff;">Сбросить</a>
</form>

<table>
    <tr><th>ID</th><th>Фамилия</th><th>Имя</th><th>Телефон</th><th>Email</th><th>Действия</th></tr>
    <?php if (!empty($teachers)): foreach ($teachers as $t): ?>
    <tr>
        <td><?= $t['teacher_id'] ?></td>
        <td><?= htmlspecialchars($t['last_name']) ?></td>
        <td><?= htmlspecialchars($t['first_name']) ?></td>
        <td><?= htmlspecialchars($t['phone']) ?></td>
        <td><?= htmlspecialchars($t['email']) ?></td>
        <td>
            <a href="?entity=teacher&action=view&id=<?= $t['teacher_id'] ?>">👁</a>
            <a href="?entity=teacher&action=edit&id=<?= $t['teacher_id'] ?>">✏️</a>
            <a href="?entity=teacher&action=delete&id=<?= $t['teacher_id'] ?>" onclick="return confirm('Удалить?')">🗑️</a>
        </td>
    </tr>
    <?php endforeach; else: ?>
    <tr><td colspan="6" style="text-align:center;">Нет данных</td></tr>
    <?php endif; ?>
</table>