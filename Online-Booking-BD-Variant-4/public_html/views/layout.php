<?php
// 🔥 Сессия должна стартовать ДО любого вывода HTML
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Музыкальная школа</title>
    <style>
        body { font-family: system-ui, sans-serif; margin: 20px; background: #f8f9fa; }
        nav { margin-bottom: 20px; background: #333; padding: 10px; border-radius: 4px; }
        nav a { color: #fff; margin-right: 15px; text-decoration: none; }
        nav a:hover { text-decoration: underline; }
        table { border-collapse: collapse; width: 100%; background: #fff; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background: #e9ecef; }
        .flash { padding: 10px; margin: 10px 0; border-radius: 4px; }
        .flash-success { background: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
        .flash-error { background: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }
        .btn { display: inline-block; padding: 6px 12px; background: #007bff; color: #fff; text-decoration: none; border-radius: 4px; margin-bottom: 10px; }
        form { margin: 10px 0; }
        input, select, textarea { padding: 6px; width: 250px; max-width: 100%; margin: 4px 0; }
        button { padding: 6px 12px; background: #28a745; color: #fff; border: none; border-radius: 4px; cursor: pointer; }
    </style>
</head>
<body>
    <nav>
        <a href="?entity=student">Ученики</a>
        <a href="?entity=teacher">Преподаватели</a>
        <a href="?entity=instrument">Инструменты</a>
        <a href="?entity=appointment">Записи</a>
    </nav>

    <?php if (!empty($_SESSION['flash'])): ?>
        <div class="flash flash-<?php echo htmlspecialchars($_SESSION['flash']['type']); ?>">
            <?php echo htmlspecialchars($_SESSION['flash']['message']); ?>
        </div>
        <?php unset($_SESSION['flash']); ?>
    <?php endif; ?>

    <?php if (isset($__content_file) && file_exists($__content_file)): ?>
        <?php include $__content_file; ?>
    <?php endif; ?>
</body>
</html>