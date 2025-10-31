<?php
session_start(); require_once __DIR__ . '/../db.php'; require_once __DIR__ . '/../functions.php'; require_admin();
$msg = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['inline_stock_id'])) {
    $gid = (int)$_POST['inline_stock_id'];
    $stock = max(0, (int)$_POST['inline_stock']);
    $st = $db->prepare("UPDATE games SET stock=? WHERE id=?");
    $st->bind_param('ii', $stock, $gid);
    $st->execute();
    $msg = 'Наличие обновлено';
}
$rows = $db->query("SELECT id,title,price,stock FROM games ORDER BY id DESC")->fetch_all(MYSQLI_ASSOC);
?><!doctype html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <title>Админ — Игры</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="../assets/styles.css">
</head>
<body>
<?php include __DIR__ . '/../header.php'; ?>
<main>
    <h1>Игры</h1><?php if ($msg): ?><p class="notice ok"><?= e($msg) ?></p><?php endif; ?>
    <p><a class="btn" href="add_game.php">Добавить игру</a></p>
    <table>
        <thead>
        <tr>
            <th>#</th>
            <th>Название</th>
            <th>Цена</th>
            <th>Наличие</th>
            <th>Действия</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($rows as $r): ?>
            <tr>
            <td><?= (int)$r['id'] ?></td>
            <td><?= e($r['title']) ?></td>
            <td><?= (int)$r['price'] ?> ₽</td>
            <td>
                <form method="post" style="display:flex;gap:8px;align-items:center">
                    <input type="hidden" name="inline_stock_id" value="<?= (int)$r['id'] ?>">
                    <input type="number" name="inline_stock" value="<?= (int)$r['stock'] ?>" min="0"
                           style="width:100px">
                    <button class="btn" type="submit">Сохранить</button>
                </form>
            </td>
            <td><a class="btn" href="edit_game.php?id=<?= (int)$r['id'] ?>">Редактировать</a></td>
            </tr><?php endforeach; ?></tbody>
    </table>
</main><?php include __DIR__ . '/../footer.php'; ?></body>
</html>