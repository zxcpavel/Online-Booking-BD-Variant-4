<?php
/** @var array $appointments */
/** @var array $filters */
?>
<h2> Записи на уроки</h2>

<!-- Фильтры -->
<form method="get" style="background: #f8f9fa; padding: 15px; border-radius: 5px; margin-bottom: 20px;">
    <input type="hidden" name="entity" value="appointment">
    <div style="display: flex; gap: 10px; flex-wrap: wrap; align-items: end;">
        <div>
            <label>С даты:</label><br>
            <input type="date" name="date_from" value="<?php echo htmlspecialchars($filters['date_from'] ?? ''); ?>">
        </div>
        <div>
            <label>По дату:</label><br>
            <input type="date" name="date_to" value="<?php echo htmlspecialchars($filters['date_to'] ?? ''); ?>">
        </div>
        <div>
            <label>Статус:</label><br>
            <select name="status">
                <option value="">Все</option>
                <option value="запланировано" <?php echo ($filters['status'] ?? '') === 'запланировано' ? 'selected' : ''; ?>>Запланировано</option>
                <option value="проведено" <?php echo ($filters['status'] ?? '') === 'проведено' ? 'selected' : ''; ?>>Проведено</option>
                <option value="отменено" <?php echo ($filters['status'] ?? '') === 'отменено' ? 'selected' : ''; ?>>Отменено</option>
            </select>
        </div>
        <button type="submit" class="btn">Фильтр</button>
        <a href="index.php?entity=appointment" style="margin-left: 10px; padding: 6px; color: #007bff; text-decoration: none;">Сбросить</a>
    </div>
</form>

<a href="index.php?entity=appointment&action=create" class="btn">+ Создать запись</a>

<table style="margin-top: 15px; width: 100%; border-collapse: collapse;">
    <thead>
        <tr>
            <th style="border: 1px solid #ddd; padding: 8px; background: #f4f4f4;">ID</th>
            <th style="border: 1px solid #ddd; padding: 8px; background: #f4f4f4;">Дата/Время</th>
            <th style="border: 1px solid #ddd; padding: 8px; background: #f4f4f4;">Ученик</th>
            <th style="border: 1px solid #ddd; padding: 8px; background: #f4f4f4;">Преподаватель</th>
            <th style="border: 1px solid #ddd; padding: 8px; background: #f4f4f4;">Инструмент</th>
            <th style="border: 1px solid #ddd; padding: 8px; background: #f4f4f4;">Статус</th>
            <th style="border: 1px solid #ddd; padding: 8px; background: #f4f4f4;">Действия</th>
        </tr>
    </thead>
    <tbody>
        <?php if (!empty($appointments)): foreach ($appointments as $a): ?>
        <tr>
            <td style="border: 1px solid #ddd; padding: 8px;"><?php echo $a['lesson_id']; ?></td>
            <td style="border: 1px solid #ddd; padding: 8px;"><?php echo htmlspecialchars($a['lesson_datetime']); ?></td>
            <td style="border: 1px solid #ddd; padding: 8px;"><?php echo htmlspecialchars($a['student_name']); ?></td>
            <td style="border: 1px solid #ddd; padding: 8px;"><?php echo htmlspecialchars($a['teacher_name']); ?></td>
            <td style="border: 1px solid #ddd; padding: 8px;"><?php echo htmlspecialchars($a['instrument_name']); ?></td>
            <td style="border: 1px solid #ddd; padding: 8px;">
                <?php
                    $status = $a['status'];
                    $lessonTime = strtotime($a['lesson_datetime']);
                    $now = time();

                    if ($status === 'запланировано' && $lessonTime < $now) {
                        echo '<span style="background:#f8d7da; color:#721c24; padding:4px 8px; border-radius:4px;">⏳ Просрочено</span>';
                    } else {
                        $bg = '#e9ecef';
                        if ($status === 'запланировано') $bg = '#fff3cd';
                        elseif ($status === 'проведено') $bg = '#d4edda';
                        elseif ($status === 'отменено') $bg = '#f8d7da';
                        echo "<span style='background:$bg; padding:4px 8px; border-radius:4px;'>$status</span>";
                    }
                ?>
            </td>
            <td style="border: 1px solid #ddd; padding: 8px;">
                <?php if ($status === 'запланировано'): ?>
                    <form method="post" action="index.php" style="display:inline;">
                        <input type="hidden" name="entity" value="appointment">
                        <input type="hidden" name="action" value="change_status">
                        <input type="hidden" name="appointment_id" value="<?php echo $a['lesson_id']; ?>">
                        <input type="hidden" name="status" value="проведено">
                        <button type="submit" onclick="return confirm('Отметить урок как проведённый?')" style="background:none; border:none; color:#28a745; cursor:pointer; font-weight:bold;">✅ Проведено</button>
                    </form>
                    <form method="post" action="index.php" style="display:inline;">
                        <input type="hidden" name="entity" value="appointment">
                        <input type="hidden" name="action" value="change_status">
                        <input type="hidden" name="appointment_id" value="<?php echo $a['lesson_id']; ?>">
                        <input type="hidden" name="status" value="отменено">
                        <button type="submit" onclick="return confirm('Отменить запись?')" style="background:none; border:none; color:#dc3545; cursor:pointer; font-weight:bold;">❌ Отмена</button>
                    </form>
                <?php endif; ?>
            </td>
        </tr>
        <?php endforeach; else: ?>
        <tr>
            <td colspan="7" style="border: 1px solid #ddd; padding: 8px; text-align: center; color: #666;">Нет записей</td>
        </tr>
        <?php endif; ?>
    </tbody>
</table>