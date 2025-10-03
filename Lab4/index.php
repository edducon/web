<?php include 'header.html'; ?>

<main>
    <h1>Обратная связь</h1>

    <?php
    $name   = isset($_GET['name'])   ? htmlspecialchars($_GET['name'])   : '';
    $email  = isset($_GET['email'])  ? htmlspecialchars($_GET['email'])  : '';
    $source = isset($_GET['source']) ? htmlspecialchars($_GET['source']) : '';
    ?>

    <form action="home.php" method="post" enctype="multipart/form-data">
        <div class="row">
            <label for="fio">ФИО</label>
            <input type="text" id="fio" name="name" value="<?php echo $name; ?>" required>
        </div>

        <div class="row">
            <label for="email">E-mail</label>
            <input type="email" id="email" name="email" placeholder="name@example.com" value="<?php echo $email; ?>" required>
        </div>

        <fieldset class="row">
            <legend>Откуда узнали о нас</legend>
            <label><input type="radio" name="source" value="internet" <?php echo ($source==='internet'?'checked':''); ?>> Реклама из интернета</label>
            <label><input type="radio" name="source" value="friends"  <?php echo ($source==='friends'?'checked':''); ?>> Рассказали друзья</label>
        </fieldset>

        <div class="row">
            <label for="category">Тема обращения</label>
            <select id="category" name="category" required>
                <option value="propose">Предложение</option>
                <option value="complaint">Жалоба</option>
            </select>
        </div>

        <div class="row">
            <label for="message">Сообщение</label>
            <textarea id="message" name="message" rows="7" required></textarea>
        </div>

        <div class="row">
            <label for="attachment">Прикрепить файл</label>
            <input type="file" id="attachment" name="attachment">
        </div>

        <div class="row">
            <label class="checkbox">
                <input type="checkbox" id="consent" name="consent">
                Даю согласие на обработку данных
            </label>
        </div>

        <div class="row">
            <button type="submit" class="btn">Отправить</button>
        </div>
    </form>
</main>
