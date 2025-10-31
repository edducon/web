<?php
session_start();
require_once __DIR__ . '/db.php';
require_once __DIR__ . '/functions.php';
$rows = $db->query("SELECT id,title,price,short_desc,cover FROM games ORDER BY id DESC LIMIT 5")->fetch_all(MYSQLI_ASSOC);
?>
<!doctype html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <title>Game-shop - Главная</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="assets/styles.css">
    <script src="assets/app.js" defer></script>
</head>
<body>
<?php include __DIR__ . '/header.php'; ?>
<main>
    <section>
        <h1>Game-shop</h1>
        <p>Магазин продажи цифровых копий. С новинками можно ознакомиться ниже</p>
    </section>
    <section>
        <h2>Новинки</h2>
        <table>
            <thead>
            <tr>
                <th>Обложка</th>
                <th>Название</th>
                <th>Цена</th>
                <th>Описание</th>
                <th></th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($rows as $g): ?>
                <tr class="product-row">
                    <td><img class="thumb" src="<?= e($g['cover']) ?>" alt=""></td>
                    <td><?= e($g['title']) ?></td>
                    <td><strong><?= (int)$g['price'] ?> ₽</strong></td>
                    <td><?= e($g['short_desc']) ?></td>
                    <td class="actions"><a class="btn" href="product.php?id=<?= (int)$g['id'] ?>">Подробнее</a></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </section>
</main>
<?php include __DIR__ . '/footer.php'; ?>
</body>
</html>
