<?php
require_once __DIR__.'/db.php';
$ok = null; $err = null;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $term = trim($_POST['term'] ?? '');
    $definition = trim($_POST['definition'] ?? '');
    if ($term === '' || $definition === '') { $err = 'Термин и определение обязательны.'; }
    $saved = null;
    if (!$err && isset($_FILES['image']) && $_FILES['image']['error'] !== UPLOAD_ERR_NO_FILE) {
        if ($_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $tmp = $_FILES['image']['tmp_name']; $orig = $_FILES['image']['name'];
            $allowed = ['image/png'=>'png','image/jpeg'=>'jpg','image/gif'=>'gif','image/webp'=>'webp'];
            $f = finfo_open(FILEINFO_MIME_TYPE); $mime = finfo_file($f,$tmp); finfo_close($f);
            if (!isset($allowed[$mime])) { $err = 'Допустимы PNG/JPG/GIF/WEBP.'; }
            else {
                $ext = $allowed[$mime];
                $safe = preg_replace('~[^a-zA-Z0-9-_]~','_', pathinfo($orig, PATHINFO_FILENAME));
                $saved = date('Ymd_His')."_".$safe.".".$ext;
                if (!@move_uploaded_file($tmp, __DIR__."/img/".$saved)) $err = 'Не удалось сохранить файл.';
            }
        } else { $err = 'Ошибка загрузки файла.'; }
    }
    if (!$err) {
        $mysql->begin_transaction();
        try {
            $stmt = $mysql->prepare("INSERT INTO terms(term, definition) VALUES(?, ?)");
            $stmt->bind_param('ss', $term, $definition); $stmt->execute();
            $term_id = $stmt->insert_id;
            if ($saved) {
                $stmt2 = $mysql->prepare("INSERT INTO images(term_id, name, filename) VALUES(?, ?, ?)");
                $name = $term;
                $stmt2->bind_param('iss', $term_id, $name, $saved); $stmt2->execute();
            }
            $mysql->commit(); $ok = 'Данные успешно добавлены!';
        } catch (Throwable $e) { $mysql->rollback(); $err = 'Ошибка: '.$e->getMessage(); }
    }
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
  <meta charset="UTF-8">
  <title>Добавление новых данных</title>
  <link rel="stylesheet" href="styles/style.css">
</head>
<body>
<header>
  <strong>Лабораторная работа №5</strong>
  <nav>
    <a class="btn" href="terms.php">Термины</a>
    <a class="btn" href="gallery.php">Галерея</a>
  </nav>
</header>
<main>
  <h1>Добавить термин и картинку</h1>
  <?php if ($ok): ?><p style="color:green"><?= htmlspecialchars($ok) ?></p><p><a class="btn" href="terms.php">Перейти на главную</a></p><?php endif; ?>
  <?php if ($err): ?><p style="color:#b00020">Ошибка: <?= htmlspecialchars($err) ?></p><?php endif; ?>
  <form action="add.php" method="post" enctype="multipart/form-data">
    <div class="row"><label for="term">Термин *</label><input type="text" id="term" name="term" required></div>
    <div class="row"><label for="definition">Определение *</label><textarea id="definition" name="definition" required></textarea></div>
    <div class="row"><label for="image">Картинка</label><input type="file" id="image" name="image" accept=".png,.jpg,.jpeg,.gif,.webp"></div>
    <div class="row"><button class="btn" type="submit">Сохранить</button><a class="btn" href="terms.php" style="background:#6c757d;margin-left:8px">Отмена</a></div>
  </form>
</main>
</body>
</html>
