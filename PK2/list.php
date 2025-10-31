<?php
session_start();
require_once __DIR__ . '/db.php';
require_once __DIR__ . '/functions.php';
require_auth();
$uid = uid();
$orders = $db->query("SELECT id,total,created_at FROM orders WHERE user_id=" . $uid . " ORDER BY created_at DESC")->fetch_all(MYSQLI_ASSOC);
?>
<!doctype html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <title>Game-shop - Список покупок</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="assets/styles.css">
</head>
<body>
<?php include __DIR__ . '/header.php'; ?>
<main>
    <h1>Список покупок</h1>
    <?php if (isset($_GET['bought'])): ?><p class="notice ok">Покупка оформлена — спасибо!</p><?php endif; ?>
    <?php if (!$orders): ?>
        <p>Заказов пока нет. Перейти в <a href="store.php">магазин</a>.</p>
    <?php else: ?>
        <?php foreach ($orders as $o): ?>
            <section style="background:#fff;border:1px solid #e7ebf3;border-radius:10px;padding:12px;margin:10px 0">
                <h3>Заказ №<?= (int)$o['id'] ?> — <?= htmlspecialchars($o['created_at']) ?> —
                    <strong><?= (int)$o['total'] ?> ₽</strong></h3>
                <?php
                $it = $db->query("SELECT oi.qty,oi.price,g.title FROM order_items oi JOIN games g ON g.id=oi.game_id WHERE oi.order_id=" . (int)$o['id'])->fetch_all(MYSQLI_ASSOC);
                ?>
                <ul>
                    <?php foreach ($it as $row): ?>
                        <li><?= (int)$row['qty'] ?> × <?= htmlspecialchars($row['title']) ?> — <?= (int)$row['price'] ?>
                            ₽
                        </li>
                    <?php endforeach; ?>
                </ul>
            </section>
        <?php endforeach; ?>
    <?php endif; ?>
</main>
<?php include __DIR__ . '/footer.php'; ?>
</body>
</html>
