<?php
require_once __DIR__.'/db.php';

$sql  = "SELECT filename, name FROM images ORDER BY id";
$rows = $mysql->query($sql)->fetch_all(MYSQLI_ASSOC);
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Галерея</title>
    <link rel="stylesheet" href="styles/style.css">
</head>
<body>
<header>
    <strong>Лабораторная работа №5</strong>
    <nav style="margin-left:auto;display:flex;gap:10px">
        <a class="btn" href="terms.php">Термины</a>
        <a class="btn" href="gallery.php">Галерея</a>
        <a class="btn" href="add.php">Добавить</a>
    </nav>
</header>
<main>
    <h1>Галерея изображений</h1>

    <div class="gallery">
        <?php foreach ($rows as $r):
            $title = trim($r['name']) !== '' ? $r['name'] : pathinfo($r['filename'], PATHINFO_FILENAME);
            ?>
            <div class="tile" data-title="<?= htmlspecialchars($title, ENT_QUOTES, 'UTF-8') ?>">
                <img
                        src="img/<?= rawurlencode($r['filename']) ?>"
                        title="<?= htmlspecialchars($title, ENT_QUOTES, 'UTF-8') ?>"
                        alt="<?= htmlspecialchars($title, ENT_QUOTES, 'UTF-8') ?>"
                >
                <div class="hint"><?= htmlspecialchars($title, ENT_QUOTES, 'UTF-8') ?></div>
            </div>
        <?php endforeach; ?>
    </div>
</main>
</body>
</html>
