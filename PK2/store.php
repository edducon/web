<?php
session_start();
require_once __DIR__ . '/db.php';
require_once __DIR__ . '/functions.php';
$q = trim($_GET['q'] ?? '');
$sql = "SELECT id,title,price,short_desc,cover,stock FROM games";
$params = [];
if ($q !== '') {
    $sql .= " WHERE title LIKE ? OR short_desc LIKE ?";
    $like = "%$q%";
    $params = [$like, $like];
}
$sql .= " ORDER BY title";
$stmt = $db->prepare($sql);
if ($params) {
    $stmt->bind_param('ss', ...$params);
}
$stmt->execute();
$rows = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
?>
<!doctype html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <title>Game-shop - Магазин</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="assets/styles.css">
    <script src="assets/app.js" defer></script>
</head>
<body>
<?php include __DIR__ . '/header.php'; ?>
<main>
    <h1>Магазин</h1>
    <form method="get" style="display:flex;gap:8px;margin:12px 0">
        <input type="text" name="q" placeholder="Поиск по названию/описанию" value="<?= e($q) ?>">
        <button class="btn" type="submit">Искать</button>
    </form>
    <table>
        <thead>
        <tr>
            <th>Обложка</th>
            <th>Название</th>
            <th>Цена</th>
            <th>Описание</th>
            <th>Наличие</th>
            <th></th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($rows as $g): ?>
            <tr class="product-row">
                <td><img class="thumb" src="<?= e($g['cover']) ?>" alt=""></td>
                <td><a href="product.php?id=<?= (int)$g['id'] ?>"><?= e($g['title']) ?></a></td>
                <td><strong><?= (int)$g['price'] ?> ₽</strong></td>
                <td><?= e($g['short_desc']) ?></td>
                <td><?= (int)$g['stock'] ?> шт.</td>
                <td class="actions">
                    <form action="add_to_cart.php" method="post">
                        <input type="hidden" name="id" value="<?= (int)$g['id'] ?>">
                        <input type="number" name="qty" value="1" min="1" max="<?= (int)$g['stock'] ?>"
                               style="width:70px">
                        <button class="btn" type="submit"<?= empty($_SESSION['user']) ? ' disabled' : ''; ?>>В корзину
                        </button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
    <?php if (empty($_SESSION['user'])): ?>
        <p class="notice">Список покупок доступен после входа (кнопка «Войти» в шапке).</p>
    <?php endif; ?>
</main>
<?php include __DIR__ . '/footer.php'; ?>
</body>
</html>
