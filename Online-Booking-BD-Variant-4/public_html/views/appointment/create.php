<?php /** @var array $errors, $old, $students, $teachers, $instruments, string $csrf */ ?>
<h2>➕ Новая запись на урок</h2>

<?php if (!empty($errors)): ?>
    <div class="flash flash-error">
        <ul><?php foreach($errors as $e) echo "<li>$e</li>"; ?></ul>
    </div>
<?php endif; ?>

<form method="post">
    <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrf) ?>">

    <label>Ученик: <br>
        <select name="student_id" required style="width: 300px;">
            <option value="">-- Выберите ученика --</option>
            <?php foreach ($students as $s): ?>
                <option value="<?= $s['student_id'] ?>" <?= ($old['student_id'] ?? '') == $s['student_id'] ? 'selected' : '' ?>>
                    <?= htmlspecialchars($s['last_name'] . ' ' . $s['first_name']) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </label><br>

    <label>Преподаватель: <br>
        <select name="teacher_id" required style="width: 300px;">
            <option value="">-- Выберите преподавателя --</option>
            <?php foreach ($teachers as $t): ?>
                <option value="<?= $t['teacher_id'] ?>" <?= ($old['teacher_id'] ?? '') == $t['teacher_id'] ? 'selected' : '' ?>>
                    <?= htmlspecialchars($t['last_name'] . ' ' . $t['first_name']) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </label><br>

    <label>Инструмент: <br>
        <select name="instrument_id" required style="width: 300px;">
            <option value="">-- Выберите инструмент --</option>
            <?php foreach ($instruments as $i): ?>
                <option value="<?= $i['instrument_id'] ?>" <?= ($old['instrument_id'] ?? '') == $i['instrument_id'] ? 'selected' : '' ?>>
                    <?= htmlspecialchars($i['instrument_name']) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </label><br>

    <label>Дата и время урока: <br>
        <input type="datetime-local" name="lesson_datetime" value="<?= htmlspecialchars($old['lesson_datetime'] ?? '') ?>" required style="width: 300px;">
    </label><br>

    <label>Длительность (мин): <br>
        <input type="number" name="duration_minutes" value="<?= htmlspecialchars($old['duration_minutes'] ?? '60') ?>" style="width: 100px;">
    </label><br>

    <br>
    <button type="submit">Записать</button>
    <a href="?entity=appointment" style="margin-left: 10px;">Отмена</a>
</form>