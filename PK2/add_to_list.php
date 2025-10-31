<?php
session_start();
require_once __DIR__ . '/db.php';
require_once __DIR__ . '/functions.php';
require_auth();
if (!is_post()) {
    header('Location: store.php');
    exit;
}
$user_id = uid();
$id = (int)($_POST['id'] ?? 0);
$qty = max(1, (int)($_POST['qty'] ?? 1));
$st = $db->prepare("SELECT stock FROM games WHERE id=?");
$st->bind_param('i', $id);
$st->execute();
$s = $st->get_result()->fetch_assoc();
if (!$s) {
    header('Location: store.php');
    exit;
}
$qty = min($qty, (int)$s['stock']);
$sel = $db->prepare("SELECT qty FROM shoplist WHERE user_id=? AND game_id=?");
$sel->bind_param('ii', $user_id, $id);
$sel->execute();
$r = $sel->get_result()->fetch_assoc();
if ($r) {
    $new = min($r['qty'] + $qty, (int)$s['stock']);
    $upd = $db->prepare("UPDATE shoplist SET qty=? WHERE user_id=? AND game_id=?");
    $upd->bind_param('iii', $new, $user_id, $id);
    $upd->execute();
} else {
    $ins = $db->prepare("INSERT INTO shoplist (user_id,game_id,qty) VALUES (?,?,?)");
    $ins->bind_param('iii', $user_id, $id, $qty);
    $ins->execute();
}
header('Location: list.php');
