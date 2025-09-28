<?php
date_default_timezone_set('Europe/Moscow');
$pageTitle = 'Панпушный Эдуард — ЛР №3: Главная';
$current   = 'index.php';

$skills = [
  'Git: работа с ветками, клонирование, pull-request',
  'Java: ООП, JDBC, алгоритмы',
    'PHP: Базовые скиллы'
];
?>
<!DOCTYPE html>
<html lang="ru">
<head>
  <meta charset="UTF-8">
  <title><?php echo $pageTitle; ?></title>
  <link rel="stylesheet" href="styles.css">
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

<main>
  <section id="about">
    <h1>Панпушный Эдуард</h1>
    <p>Привет! Я студент направления "Разработка и интеграция бизнес-приложений". Занимаюсь разработкой ботов, веб-сайтов и backend-приложений. На данной странице вы можете увидеть результаты внедрения PHP в статичные HTML страницы.</p>
      <img src="images/person<?= (date('s') % 2) + 1 ?>.png" alt="Фото профиля">
  </section>

  <section id="study">
    <h2>Учеба</h2>
    <p>Обучаюсь на втором курсе Московского политеха. С примерным расписанием можно ознакомиться в таблице.</p>
    <table>
      <caption><strong>Учебная неделя</strong></caption>
      <thead>
      <tr>
        <th>День</th>
        <th>Пара</th>
        <th>Дисциплина</th>
        <th>Аудитория</th>
        <th>Преподаватель</th>
      </tr>
      </thead>
      <tbody>
      <tr>
        <td>Четверг</td>
        <td>1</td>
        <td>Инженерная коммуникация в области информационных технологий</td>
        <td>Пр2303</td>
        <td>Змазнева Олеся Анатольевна</td>
      </tr>
      <tr>
        <td></td>
        <td>2</td>
        <td>Математическая логика и теория алгоритмов в практике программирования</td>
        <td>Пр1316</td>
        <td>Набебин Алексей Александрович</td>
      </tr>
      <tr>
        <td></td>
        <td>3</td>
        <td>Основы веб-технологий</td>
        <td>Пр1315</td>
        <td>Даньшина Марина Владимировна</td>
      </tr>
      </tbody>
    </table>
  </section>

  <section id="skills">
    <h2>Навыки</h2>
    <ul>
      <?php foreach ($skills as $s) { echo '<li>'.$s.'</li>'; } ?>
    </ul>
  </section>
</main>

<footer id="contacts">
  <p><strong>Контакты:</strong> e@panpushnyy.ru | Telegram: @edducon</p>
  <p>© Панпушный Эдуард. Лабораторная работа №3.</p>
  <p>Сформировано <?php echo date('d.m.Y \в H:i:s'); ?></p>
</footer>
</body>
</html>
