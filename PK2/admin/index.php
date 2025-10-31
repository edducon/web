<?php
session_start(); require_once __DIR__ . '/../db.php'; require_once __DIR__ . '/../functions.php'; require_admin();
?><!doctype html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <title>Админ</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="../assets/styles.css">
</head>
<body>
<?php include __DIR__ . '/../header.php'; ?>
<main><h1>Админ-панель</h1>
    <p><a class="btn" href="games.php">Список игр</a> <a class="btn" href="add_game.php">Добавить игру</a></p>
</main>
<?php include __DIR__ . '/../footer.php'; ?></body>
</html>