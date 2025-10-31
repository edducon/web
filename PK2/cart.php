<?php
session_start();
require_once __DIR__ . '/db.php';
require_once __DIR__ . '/functions.php';
$cart = $_SESSION['cart'] ?? [];
$ids = array_keys($cart);
$items = [];
$total = 0;
if ($ids) {
    $in = implode(',', array_map('intval', $ids));
    $rows = $db->query("SELECT id,title,price,cover,stock FROM games WHERE id IN ($in)")->fetch_all(MYSQLI_ASSOC);
    foreach ($rows as $r) {
        $r['qty'] = $cart[$r['id']];
        $r['sum'] = $r['price'] * $r['qty'];
        $total += $r['sum'];
        $items[] = $r;
    }
}
?>
<!doctype html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <title>Game-shop - Корзина</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="assets/styles.css">
</head>
<body>
<?php include __DIR__ . '/header.php'; ?>
<main>
    <h1>Корзина</h1>
    <?php if (!$items): ?>
        <p>Корзина пуста. Перейти в <a href="store.php">магазин</a>.</p>
    <?php else: ?>
        <form action="update_cart.php" method="post">
            <table>
                <thead>
                <tr>
                    <th>Обложка</th>
                    <th>Игра</th>
                    <th>Цена</th>
                    <th>Кол-во</th>
                    <th>Сумма</th>
                    <th></th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($items as $it): ?>
                    <tr>
                        <td><img class="thumb" src="<?= e($it['cover']) ?>" alt=""></td>
                        <td><?= e($it['title']) ?></td>
                        <td><?= (int)$it['price'] ?> ₽</td>
                        <td><input type="number" name="qty[<?= (int)$it['id'] ?>]" value="<?= (int)$it['qty'] ?>"
                                   min="1" max="<?= (int)$it['stock'] ?>" style="width:80px"></td>
                        <td><?= (int)$it['sum'] ?> ₽</td>
                        <td>
                            <button class="btn" name="remove" value="<?= (int)$it['id'] ?>">Убрать</button>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
            <p><strong>Итого: <?= (int)$total ?> ₽</strong></p>
            <p style="display:flex;gap:8px">
                <button class="btn" type="submit">Обновить корзину</button>
                <a class="btn" href="checkout.php">Купить</a></p>
        </form>
    <?php endif; ?>
</main>
<?php include __DIR__ . '/footer.php'; ?>
</body>
</html>
