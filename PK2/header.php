<?php
if (session_status() === PHP_SESSION_NONE) session_start();
require_once __DIR__ . '/functions.php';

/* базовый путь (работает и в /admin, и в корне) */
$scriptDir = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/\\');
$base = (basename($scriptDir) === 'admin') ? rtrim(dirname($scriptDir), '/\\') : $scriptDir;
/* нормализуем: для корня делаем пустую строку, чтобы не было // */
if ($base === '/' || $base === '') $base = '';

/* сборщик URL, чтобы не было двойных слэшей */
function url_to(string $path): string {
    global $base;
    return ($base ? '/'.ltrim($base,'/') : '') . '/' . ltrim($path,'/');
}

$right = [];
if (is_auth()) {
    $right[] = 'Привет, ' . e($_SESSION['user']['name'] ?? 'Гость');
    $right[] = '<a href="'.url_to('logout.php').'">Выход</a>';
    if (is_admin()) {
        $right[] = '<a href="'.url_to('admin/index.php').'">Админ</a>';
    }
} else {
    $right[] = '<a href="'.url_to('login.php').'">Войти</a>';
    $right[] = '<a href="'.url_to('register.php').'">Регистрация</a>';
}
?>
<header>
    <nav class="topnav">
        <a href="<?= url_to('index.php') ?>">Главная</a>
        <a href="<?= url_to('store.php') ?>">Магазин</a>
        <a href="<?= url_to('recommendations.php') ?>">Рекомендации</a>
        <a href="<?= url_to('list.php') ?>">Список покупок</a>
        <a href="<?= url_to('cart.php') ?>">Корзина</a>
        <span style="margin-left:auto"></span>
        <span><?= implode(' · ', $right) ?></span>
    </nav>
</header>
