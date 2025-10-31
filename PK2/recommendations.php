<?php
session_start();
require_once __DIR__ . '/db.php';
require_once __DIR__ . '/functions.php';
$rows = $db->query("SELECT id,title,cover FROM games ORDER BY id DESC LIMIT 30")->fetch_all(MYSQLI_ASSOC);
?>
<!doctype html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <title>Game-shop - Рекомендации</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="assets/styles.css">
    <script src="assets/app.js" defer></script>
</head>
<body>
<?php include __DIR__ . '/header.php'; ?>
<main>
    <h1>Рекомендации</h1>
    <p>Для того чтобы узнать подробности цифровой копии, нужно нажать на нее.</p>
    <div class="live-feed" id="liveFeed">
        <div class="track" id="liveTrack">
            <?php foreach ($rows as $g): ?>
                <img class="tile" data-id="<?= (int)$g['id'] ?>" src="<?= e($g['cover']) ?>"
                     alt="<?= e($g['title']) ?>">
            <?php endforeach; ?>
            <?php foreach ($rows as $g): ?>
                <img class="tile" data-id="<?= (int)$g['id'] ?>" src="<?= e($g['cover']) ?>"
                     alt="<?= e($g['title']) ?>">
            <?php endforeach; ?>
        </div>
    </div>

    <dialog id="gameModal">
        <div class="modal-body" id="modalBody">Загрузка…</div>
        <form method="dialog" style="text-align:right;margin-top:10px">
            <button class="btn" id="modalClose">Закрыть</button>
        </form>
    </dialog>
</main>
<?php include __DIR__ . '/footer.php'; ?>
</body>
</html>
