<?php
require_once __DIR__ . '/db.php';
require_once __DIR__ . '/functions.php';
$id = (int)($_GET['id'] ?? 0);
$st = $db->prepare("SELECT * FROM games WHERE id=?");
$st->bind_param('i', $id);
$st->execute();
$g = $st->get_result()->fetch_assoc();
if (!$g) {
    http_response_code(404);
    echo '<p>Товар не найден</p>';
    exit;
}
$attrs = $db->query("SELECT name,value FROM game_attrs WHERE game_id=" . $id . " ORDER BY id")->fetch_all(MYSQLI_ASSOC);
?>
<div class="head">
    <img src="<?= e($g['cover']) ?>" alt="">
    <div>
        <h2><?= e($g['title']) ?></h2>
        <div class="meta"><strong><?= (int)$g['price'] ?> ₽</strong> · В наличии: <?= (int)$g['stock'] ?> шт.</div>
        <p><?= nl2br(e($g['long_desc'])) ?></p>
        <ul style="list-style:none;padding:0;margin:8px 0 0">
            <?php foreach ($attrs as $a): ?>
                <li><span style="display:inline-block;min-width:130px;color:#555"><?= e($a['name']) ?>:</span>
                    <strong><?= e($a['value']) ?></strong></li>
            <?php endforeach; ?>
        </ul>
        <p style="margin-top:10px">
            <a class="btn" href="product.php?id=<?= (int)$g['id'] ?>">Открыть страницу товара</a>
        </p>
    </div>
</div>
