<?php
session_start();
require_once __DIR__ . '/db.php';
require_once __DIR__ . '/functions.php';
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: store.php');
    exit;
}
$id = (int)($_POST['id'] ?? 0);
$qty = max(1, (int)($_POST['qty'] ?? 1));
// clamp with stock
$st = $db->prepare("SELECT stock FROM games WHERE id=?");
$st->bind_param('i', $id);
$st->execute();
$r = $st->get_result()->fetch_assoc();
if (!$r) {
    header('Location: store.php');
    exit;
}
$qty = min($qty, (int)$r['stock']);
if (!isset($_SESSION['cart'])) $_SESSION['cart'] = [];
$cur = $_SESSION['cart'][$id] ?? 0;
$_SESSION['cart'][$id] = min($cur + $qty, (int)$r['stock']);
header('Location: cart.php');
