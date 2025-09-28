<?php
date_default_timezone_set('Europe/Moscow');
$pageTitle = 'Панпушный Эдуард — ЛР №3: Авторизация';
$current   = 'auth.php';


?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8" />
    <title><?php echo $pageTitle; ?></title>
    <link rel="stylesheet" href="styles.css" />
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Asimovian&display=swap" rel="stylesheet">
</head>
<body>
<header>
    <nav>
        <?php
        $name = 'На главную';
        $link = 'index.php';
        $current_page = ($current === $link);
        ?>
        <a href="<?php echo $link; ?>" <?php if ($current_page) echo 'class="selected_menu"'; ?>>
            <?php echo $name; ?>
        </a>

        <?php
        $name = 'Обратная связь';
        $link = 'feedback.php';
        $current_page = ($current === $link);
        ?>
        <a href="<?php echo $link; ?>" <?php if ($current_page) echo 'class="selected_menu"'; ?>>
            <?php echo $name; ?>
        </a>

        <?php
        $name = 'Авторизация';
        $link = 'auth.php';
        $current_page = ($current === $link);
        ?>
        <a href="<?php echo $link; ?>" <?php if ($current_page) echo 'class="selected_menu"'; ?>>
            <?php echo $name; ?>
        </a>
    </nav>
</header>

<div class="auth-screen">
    <form action="https://httpbin.org/post" method="post">
        <h1 style="margin-top:0">Вход</h1>

        <div class="form-group">
            <label for="login">Логин</label>
            <input type="text" id="login" name="login" autocomplete="username">
        </div>

        <div class="form-group">
            <label for="password">Пароль</label>
            <input type="password" id="password" name="password" autocomplete="current-password">
        </div>

        <div class="form-group checkbox-line">
            <input type="checkbox" id="remember" name="remember" value="1">
            <label for="remember">Запомнить меня</label>
        </div>

        <button type="submit">Войти</button>
    </form>
</div>

<footer id="contacts">
    <p><strong>Контакты:</strong> e@panpushnyy.ru | Telegram: @edducon</p>
    <p>© Панпушный Эдуард. Лабораторная работа №3.</p>
    <p>Сформировано <?php echo date('d.m.Y \в H:i:s'); ?></p>
</footer>
</body>
</html>
