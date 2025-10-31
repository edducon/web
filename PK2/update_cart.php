<?php
session_start();
require_once __DIR__ . '/functions.php';
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: cart.php');
    exit;
}
if (!isset($_SESSION['cart'])) $_SESSION['cart'] = [];
if (isset($_POST['remove'])) {
    $id = (int)$_POST['remove'];
    unset($_SESSION['cart'][$id]);
    header('Location: cart.php');
    exit;
}
$qtys = $_POST['qty'] ?? [];
foreach ($qtys as $id => $q) {
    $id = (int)$id;
    $q = max(1, (int)$q);
    $_SESSION['cart'][$id] = $q;
}
header('Location: cart.php');
