<?php
session_start();
require_once __DIR__ . '/db.php';
require_once __DIR__ . '/functions.php';

$msg = '';
$err = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $pass = (string)($_POST['pass'] ?? '');
    $pass2 = (string)($_POST['pass2'] ?? '');
    if ($name === '' || $email === '' || $pass === '' || $pass2 === '') {
        $err = 'Заполните все поля';
    } elseif ($pass !== $pass2) {
        $err = 'Пароли не совпадают';
    } else {
        try {
            $hash = password_hash($pass, PASSWORD_DEFAULT);
            $st = $db->prepare("INSERT INTO users (name,email,pass_hash) VALUES (?,?,?)");
            $st->bind_param('sss', $name, $email, $hash);
            $st->execute();
            $msg = 'Регистрация успешно завершена. Теперь можно войти.';
        } catch (Throwable $e) {
            $err = 'Пользователь с таким email уже существует';
        }
    }
}
?>
<!doctype html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <title>Game-shop - Регистрация</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="assets/styles.css">
</head>
<body>
<?php include __DIR__ . '/header.php'; ?>
<main>
    <form class="form" method="post">
        <h1>Регистрация</h1>
        <?php if ($msg): ?>
            <div class="notice ok"><?= e($msg) ?></div><?php endif; ?>
        <?php if ($err): ?>
            <div class="notice err"><?= e($err) ?></div><?php endif; ?>
        <div class="row"><label>Имя<input type="text" name="name" required></label></div>
        <div class="row"><label>Email<input type="email" name="email" required></label></div>
        <div class="row"><label>Пароль<input type="password" name="pass" required></label></div>
        <div class="row"><label>Повторите пароль<input type="password" name="pass2" required></label></div>
        <button class="btn" type="submit">Создать аккаунт</button>
    </form>
</main>
<?php include __DIR__ . '/footer.php'; ?>
</body>
</html>
