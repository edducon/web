<?php
session_start();
require_once __DIR__ . '/db.php';
require_once __DIR__ . '/functions.php';
$err = '';
if (is_post()) {
    $email = trim($_POST['email'] ?? '');
    $pass = (string)($_POST['pass'] ?? '');
    $st = $db->prepare("SELECT id,name,email,pass_hash,is_admin FROM users WHERE email=?");
    $st->bind_param('s', $email);
    $st->execute();
    $u = $st->get_result()->fetch_assoc();
    if ($u && password_verify($pass, $u['pass_hash'])) {
        $_SESSION['user'] = ['id' => $u['id'], 'name' => $u['name'], 'email' => $u['email'], 'is_admin' => $u['is_admin']];
        header('Location: index.php');
        exit;
    } else {
        $err = 'Неверный email или пароль';
    }
}
?>
<!doctype html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <title>Game-shop - Авторизация</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="assets/styles.css">
    <script src="assets/app.js" defer></script>
</head>
<body>
<?php include __DIR__ . '/header.php'; ?>
<main>
    <form class="form" method="post">
        <h1>Вход</h1>
        <?php if ($err): ?>
            <div class="notice err"><?= e($err) ?></div><?php endif; ?>
        <div class="row"><label>Email<input type="email" name="email" required></label></div>
        <div class="row"><label>Пароль<input type="password" name="pass" required></label></div>
        <button class="btn" type="submit">Войти</button>
    </form>
</main>
<?php include __DIR__ . '/footer.php'; ?>
</body>
</html>
