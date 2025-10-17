<?php
global $mysql;
require_once __DIR__.'/db.php';

$ok = null; $err = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $term       = trim($_POST['term'] ?? '');
    $definition = trim($_POST['definition'] ?? '');

    if ($term === '' || $definition === '') {
        $err = 'Заполните термин и определение.';
    } else {
        $saved = null;
        if (!empty($_FILES['image']['name']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $filename = basename($_FILES['image']['name']);
            $target   = __DIR__ . '/img/' . $filename;
            if (move_uploaded_file($_FILES['image']['tmp_name'], $target)) {
                $saved = $filename;
            }
        }

        $termEsc = mysqli_real_escape_string($mysql, $term);
        $defEsc  = mysqli_real_escape_string($mysql, $definition);

        mysqli_query($mysql, "INSERT INTO terms (term, definition) VALUES ('$termEsc', '$defEsc')");
        $term_id = mysqli_insert_id($mysql);

        if ($saved) {
            $nameEsc = mysqli_real_escape_string($mysql, $term);
            $fileEsc = mysqli_real_escape_string($mysql, $saved);
            mysqli_query($mysql, "INSERT INTO images (term_id, name, filename) VALUES ($term_id, '$nameEsc', '$fileEsc')");
        }

        $ok = 'Данные успешно добавлены!';
    }
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Добавление данных</title>
    <link rel="stylesheet" href="styles/style.css">
</head>
<body>
<header>
    <strong>Лабораторная работа №5</strong>
    <nav style="float:right"><a class="btn" href="terms.php">На главную</a></nav>
</header>

<main>
    <h1>Добавить термин и картинку</h1>

    <?php if ($ok): ?>
        <p style="color:green"><?= htmlspecialchars($ok) ?></p>
        <p><a class="btn" href="terms.php">Перейти на главную</a></p>
    <?php endif; ?>
    <?php if ($err): ?>
        <p style="color:#b00020"><?= htmlspecialchars($err) ?></p>
    <?php endif; ?>

    <form action="add.php" method="post" enctype="multipart/form-data">
        <div class="row">
            <label for="term">Термин *</label>
            <input type="text" id="term" name="term" required>
        </div>
        <div class="row">
            <label for="definition">Определение *</label>
            <textarea id="definition" name="definition" required></textarea>
        </div>
        <div class="row">
            <label for="image">Картинка</label>
            <input type="file" id="image" name="image">
        </div>
        <div class="row">
            <button class="btn" type="submit">Сохранить</button>
            <a class="btn" href="terms.php" style="background:#6c757d;margin-left:8px">Отмена</a>
        </div>
    </form>
</main>
</body>
</html>
