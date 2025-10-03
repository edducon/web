<?php include 'header.html'; ?>

<main>
    <h1>Ответ на обращение</h1>

    <?php
    if ($_SERVER['REQUEST_METHOD']!=='POST' || !isset($_POST['name'])) {
        echo '<p>Данные не получены. Перейти к <a href="index.php">форме</a>.</p>';
    } else {
        $name     = htmlspecialchars(trim($_POST['name']));
        $email    = htmlspecialchars(trim($_POST['email']));
        $category = $_POST['category'] ?? '';
        $message  = htmlspecialchars(trim($_POST['message']));
        $source   = $_POST['source'] ?? '';

        echo '<p><strong>ФИО:</strong> '.$name.'</p>';

        if ($category==='propose') {
            echo '<p>Спасибо за ваше предложение:</p>';
        } else {
            echo '<p>Мы рассмотрим вашу жалобу:</p>';
        }

        echo '<textarea rows="7" readonly class="readonly">'.$message.'</textarea>';

        if (isset($_FILES['attachment']) && $_FILES['attachment']['error']===UPLOAD_ERR_OK) {
            echo '<p>Прикреплён файл: <strong>'.htmlspecialchars(basename($_FILES['attachment']['name'])).'</strong></p>';
        }

        $q = http_build_query(['name'=>$name,'email'=>$email,'source'=>$source]);
        echo '<p><a class="btn" href="index.php?'.$q.'">Заполнить снова</a></p>';
    }
    ?>
</main>

