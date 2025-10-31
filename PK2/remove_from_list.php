<?php
session_start();
require_once __DIR__ . '/db.php';
require_once __DIR__ . '/functions.php';
require_auth();
if (!is_post()) {
    header('Location: list.php');
    exit;
}
$uid = uid();
$id = (int)($_POST['id'] ?? 0);
$st = $db->prepare("DELETE FROM shoplist WHERE user_id=? AND game_id=?");
$st->bind_param('ii', $uid, $id);
$st->execute();
header('Location: list.php');
