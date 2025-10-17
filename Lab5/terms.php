<?php
global $mysql;
require_once __DIR__.'/db.php';
$terms = $mysql->query("SELECT id, term, definition FROM terms ORDER BY term")->fetch_all(MYSQLI_ASSOC);
?>
<!DOCTYPE html>
<html lang="ru">
<head>
  <meta charset="UTF-8">
  <title>Термины</title>
  <link rel="stylesheet" href="styles/style.css">
</head>
<body>
<header>
  <strong>Лабораторная работа №5</strong>
  <nav>
    <a class="btn" href="terms.php">Термины</a>
    <a class="btn" href="gallery.php">Галерея</a>
    <a class="btn" href="add.php">Добавить</a>
  </nav>
</header>
<main>
  <h1>Термины и определения</h1>
  <div class="table-wrap">
    <table>
      <thead><tr><th style="width:30%">Термин</th><th>Определение</th></tr></thead>
      <tbody>
      <?php foreach ($terms as $row): ?>
        <tr>
          <td><?= htmlspecialchars($row['term']) ?></td>
          <td><?= nl2br(htmlspecialchars($row['definition'])) ?></td>
        </tr>
      <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</main>
</body>
</html>
