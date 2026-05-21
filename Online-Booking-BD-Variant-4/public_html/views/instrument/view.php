<?php /** @var array $instrument */ ?>
<h2>🎵 Инструмент</h2>
<table>
    <tr><th>Название</th><td><?= htmlspecialchars($instrument['instrument_name']) ?></td></tr>
    <tr><th>Описание</th><td><?= htmlspecialchars($instrument['description'] ?? '-') ?></td></tr>
</table>
<br><a href="?entity=instrument">← Назад</a>