<?php /** @var array $instruments, string $search */ ?>
<h2>🎻 Инструменты</h2>
<a href="?entity=instrument&action=create" class="btn">+ Добавить</a>

<form method="get" style="margin: 15px 0;">
    <input type="hidden" name="entity" value="instrument">
    <input type="text" name="search" placeholder="Поиск..." value="<?= htmlspecialchars($search ?? '') ?>">
    <button type="submit">Найти</button>
    <a href="?entity=instrument" style="margin-left: 10px; color: #007bff;">Сбросить</a>
</form>

<table>
    <tr><th>ID</th><th>Название</th><th>Описание</th><th>Действия</th></tr>
    <?php if (!empty($instruments)): foreach ($instruments as $i): ?>
    <tr>
        <td><?= $i['instrument_id'] ?></td>
        <td><?= htmlspecialchars($i['instrument_name']) ?></td>
        <td><?= htmlspecialchars($i['description'] ?? '-') ?></td>
        <td>
            <a href="?entity=instrument&action=view&id=<?= $i['instrument_id'] ?>">👁</a>
            <a href="?entity=instrument&action=edit&id=<?= $i['instrument_id'] ?>">️</a>
            <a href="?entity=instrument&action=delete&id=<?= $i['instrument_id'] ?>" onclick="return confirm('Удалить?')">🗑️</a>
        </td>
    </tr>
    <?php endforeach; else: ?>
    <tr><td colspan="4" style="text-align:center;">Нет данных</td></tr>
    <?php endif; ?>
</table>