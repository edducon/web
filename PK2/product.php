<?php
session_start();
require_once __DIR__ . '/db.php';
require_once __DIR__ . '/functions.php';
$id = (int)($_GET['id'] ?? 0);
$st = $db->prepare("SELECT * FROM games WHERE id=?");
$st->bind_param('i', $id);
$st->execute();
$g = $st->get_result()->fetch_assoc();
if (!$g) {
    http_response_code(404);
    exit('Товар не найден');
}
$attrs = $db->query("SELECT name,value FROM game_attrs WHERE game_id=" . $id . " ORDER BY id")->fetch_all(MYSQLI_ASSOC);
?>
<!doctype html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <title>Game-shop - <?= e($g['title']) ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="assets/styles.css">
    <script src="assets/app.js" defer></script>
</head>
<body>
<?php include __DIR__ . '/header.php'; ?>
<main>
    <div class="product">
        <div class="cover"><img src="<?= e($g['cover']) ?>" alt=""></div>
        <div class="kv">
            <h1><?= e($g['title']) ?></h1>
            <p><strong><?= (int)$g['price'] ?> ₽</strong></p>
            <p><?= nl2br(e($g['long_desc'])) ?></p>
            <h2>Характеристики</h2>
            <ul style="list-style:none;padding:0;margin:0">
                <?php foreach ($attrs as $a): ?>
                    <li><span style="display:inline-block;min-width:140px;color:#555"><?= e($a['name']) ?>:</span>
                        <strong><?= e($a['value']) ?></strong></li>
                <?php endforeach; ?>
                <li><span style="display:inline-block;min-width:140px;color:#555">Наличие:</span>
                    <strong><?= (int)$g['stock'] ?> шт.</strong></li>
            </ul>
            <form action="add_to_cart.php" method="post" style="margin-top:10px">
                <input type="hidden" name="id" value="<?= (int)$g['id'] ?>">
                <label>Кол-во <input type="number" name="qty" value="1" min="1" max="<?= (int)$g['stock'] ?>"
                                     style="width:80px"></label>
                <button class="btn" type="submit"<?= empty($_SESSION['user']) ? ' disabled' : ''; ?>>В корзину покупок
                </button>
            </form>
            <?php if (empty($_SESSION['user'])): ?><p class="notice">Список покупок доступен после
                входа.</p><?php endif; ?>
        </div>
    </div>
</main>
<?php include __DIR__ . '/footer.php'; ?>
</body>
</html>
