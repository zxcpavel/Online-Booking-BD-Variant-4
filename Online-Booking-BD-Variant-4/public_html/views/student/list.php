<?php
/**
 * @var array $students
 * @var string $search
 */
?>

<h2>📚 Ученики</h2>

<a href="?entity=student&action=create" class="btn">+ Добавить</a>

<form method="get" style="margin: 15px 0;">
    <input type="hidden" name="entity" value="student">
    <input type="text" name="search" placeholder="Поиск по ФИО..." value="<?php echo htmlspecialchars($search ?? ''); ?>">
    <button type="submit">Найти</button>
    <a href="?entity=student" style="margin-left: 10px; color: #007bff;">Сбросить</a>
</form>

<table>
    <thead>
        <tr>
            <th>ID</th>
            <th>Фамилия</th>
            <th>Имя</th>
            <th>Телефон</th>
            <th>Email</th>
            <th>Дата рождения</th>
            <th>Действия</th>
        </tr>
    </thead>
    <tbody>
        <?php if (!empty($students)): ?>
            <?php foreach ($students as $s): ?>
            <tr>
                <td><?php echo $s['student_id']; ?></td>
                <td><?php echo htmlspecialchars($s['last_name']); ?></td>
                <td><?php echo htmlspecialchars($s['first_name']); ?></td>
                <td><?php echo htmlspecialchars($s['phone']); ?></td>
                <td><?php echo htmlspecialchars($s['email']); ?></td>
                <td><?php echo htmlspecialchars($s['birth_date']); ?></td>
                <td>
                    <a href="?entity=student&action=view&id=<?php echo $s['student_id']; ?>" title="Просмотр">👁</a>
                    <a href="?entity=student&action=edit&id=<?php echo $s['student_id']; ?>" title="Редактировать">✏️</a>
                    <a href="?entity=student&action=delete&id=<?php echo $s['student_id']; ?>" onclick="return confirm('Удалить ученика?')" title="Удалить">🗑️</a>
                </td>
            </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td colspan="7" style="text-align:center; color:#666;">Нет данных</td>
            </tr>
        <?php endif; ?>
    </tbody>
</table>