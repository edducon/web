<?php
session_start();
require_once __DIR__ . '/db.php';
require_once __DIR__ . '/functions.php';
require_auth();
$cart = $_SESSION['cart'] ?? [];
if (!$cart) {
    header('Location: cart.php');
    exit;
}
$ids = array_keys($cart);
$in = implode(',', array_map('intval', $ids));
$rows = $db->query("SELECT id,price,stock FROM games WHERE id IN ($in)")->fetch_all(MYSQLI_ASSOC);
$prices = [];
foreach ($rows as $r) $prices[$r['id']] = $r;
$total = 0;
foreach ($cart as $id => $q) {
    $p = $prices[$id]['price'] ?? 0;
    $available = $prices[$id]['stock'] ?? 0;
    $q = min($q, $available);
    if ($q <= 0) continue;
    $total += $p * $q;
    $cart[$id] = $q;
}
if ($total <= 0) {
    $_SESSION['cart'] = [];
    header('Location: cart.php');
    exit;
}
$db->begin_transaction();
try {
    $uid = uid();
    $ins = $db->prepare("INSERT INTO orders (user_id,total) VALUES (?,?)");
    $ins->bind_param('id', $uid, $total);
    $ins->execute();
    $order_id = $db->insert_id;
    $stmt = $db->prepare("INSERT INTO order_items (order_id,game_id,qty,price) VALUES (?,?,?,?)");
    $upd = $db->prepare("UPDATE games SET stock=stock-? WHERE id=? AND stock>=?");
    foreach ($cart as $id => $q) {
        $price = $prices[$id]['price'];
        $stmt->bind_param('iiid', $order_id, $id, $q, $price);
        $stmt->execute();
        $upd->bind_param('iii', $q, $id, $q);
        $upd->execute();
    }
    $db->commit();
    $_SESSION['cart'] = [];
    header('Location: list.php?bought=1');
    exit;
} catch (Throwable $e) {
    $db->rollback();
    http_response_code(500);
    echo 'Ошибка оформления заказа: ' . htmlspecialchars($e->getMessage());
    exit;
}
