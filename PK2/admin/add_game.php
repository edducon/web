<?php
session_start();
require_once __DIR__ . '/../db.php';
require_once __DIR__ . '/../functions.php';
require_admin();

function distinct_values(mysqli $db, string $name): array
{
    $st = $db->prepare("SELECT DISTINCT value FROM game_attrs WHERE name=? ORDER BY value");
    $st->bind_param('s', $name);
    $st->execute();
    $res = $st->get_result();
    $vals = [];
    while ($r = $res->fetch_assoc()) {
        $vals[] = trim($r['value']);
    }
    return $vals;
}

$dl_platforms = distinct_values($db, 'Платформа');
$dl_genres = distinct_values($db, 'Жанр');
$dl_types = distinct_values($db, 'Тип продукта');

$raw_langs = distinct_values($db, 'Поддерживаемые языки');
$lang_set = [];
foreach ($raw_langs as $row) {
    foreach (explode(',', $row) as $tok) {
        $t = trim($tok);
        if ($t !== '') $lang_set[$t] = true;
    }
}
$dl_langs = array_keys($lang_set);
sort($dl_langs);

$msg = '';
$err = '';
if (is_post()) {
    $title = trim((string)post('title', ''));
    $price = (int)post('price', 0);
    $stock = (int)post('stock', 0);
    $short = trim((string)post('short', ''));
    $long = trim((string)post('long', ''));

    $platform = trim((string)post('platform', ''));
    $genre = trim((string)post('genre', ''));
    $langs = trim((string)post('languages', ''));
    $ptype = trim((string)post('product_type', ''));

    if ($title === '' || $price <= 0 || $short === '' || $long === '' || $platform === '' || $genre === '' || $ptype === '') {
        $err = 'Заполните обязательные поля (Название, Цена, Краткое/Полное описание, Платформа, Жанр, Тип продукта).';
    } else {
        $langs = preg_replace('/\s*,\s*/u', ', ', $langs);
        $langs = preg_replace('/\s{2,}/u', ' ', $langs);

        $cover = 'assets/img/stardew.jpg';
        if (!empty($_FILES['cover']['name']) && $_FILES['cover']['error'] === UPLOAD_ERR_OK) {
            $ext = pathinfo($_FILES['cover']['name'], PATHINFO_EXTENSION);
            if ($ext === '') $ext = 'jpg';
            $cover = 'assets/img/cover_' . time() . '_' . mt_rand(1000, 9999) . '.' . $ext;
            @move_uploaded_file($_FILES['cover']['tmp_name'], __DIR__ . '/../' . $cover);
        }

        $st = $db->prepare("INSERT INTO games (title,price,short_desc,long_desc,cover,stock) VALUES (?,?,?,?,?,?)");
        $st->bind_param('sisssi', $title, $price, $short, $long, $cover, $stock);
        $st->execute();
        $gid = (int)$db->insert_id;

        $fixed = [
                ['Платформа', $platform],
                ['Жанр', $genre],
                ['Поддерживаемые языки', $langs],
                ['Тип продукта', $ptype],
        ];
        foreach ($fixed as [$name, $val]) {
            if ($val !== '') {
                $a = $db->prepare("INSERT INTO game_attrs (game_id,name,value) VALUES (?,?,?)");
                $a->bind_param('iss', $gid, $name, $val);
                $a->execute();
            }
        }

        $msg = 'Игра добавлена (#' . $gid . ')';
    }
}
?>
<!doctype html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <title>Админ - Добавить игру</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="../assets/styles.css">
</head>
<body>
<?php include __DIR__ . '/../header.php'; ?>
<main>
    <h1>Добавить игру</h1>
    <?php if ($msg): ?><p class="notice ok"><?= e($msg) ?></p><?php endif; ?>
    <?php if ($err): ?><p class="notice err"><?= e($err) ?></p><?php endif; ?>

    <form class="form" method="post" enctype="multipart/form-data">
        <div class="row"><label>Название *<input type="text" name="title" required></label></div>
        <div class="row"><label>Цена *<input type="number" name="price" min="1" required></label></div>
        <div class="row"><label>Наличие (шт.)<input type="number" name="stock" min="0" value="0"></label></div>
        <div class="row"><label>Краткое описание *<input type="text" name="short" required></label></div>
        <div class="row"><label>Полное описание *<textarea name="long" rows="6" required></textarea></label></div>
        <div class="row"><label>Обложка<input type="file" name="cover" accept="image/*"></label></div>

        <fieldset class="row" style="border:1px solid #e7ebf3;border-radius:10px;padding:10px">
            <legend>Характеристики</legend>

            <div class="row">
                <label style="flex:1">Платформа *
                    <input type="text" name="platform" list="dl-platforms" required placeholder="например: PC">
                    <datalist id="dl-platforms">
                        <?php foreach ($dl_platforms as $v): ?>
                            <option value="<?= e($v) ?>"></option>
                        <?php endforeach; ?>
                    </datalist>
                </label>

                <label style="flex:1">Жанр *
                    <input type="text" name="genre" list="dl-genres" required placeholder="например: Action">
                    <datalist id="dl-genres">
                        <?php foreach ($dl_genres as $v): ?>
                            <option value="<?= e($v) ?>"></option>
                        <?php endforeach; ?>
                    </datalist>
                </label>
            </div>

            <div class="row">
                <label style="flex:1">Поддерживаемые языки *
                    <input type="text" name="languages" list="dl-langs" required placeholder="Русский, Английский">
                    <datalist id="dl-langs">
                        <?php foreach ($dl_langs as $v): ?>
                            <option value="<?= e($v) ?>"></option>
                        <?php endforeach; ?>
                    </datalist>
                    <small>Несколько — через запятую</small>
                </label>

                <label style="flex:1">Тип продукта *
                    <input type="text" name="product_type" list="dl-types" required
                           placeholder="например: Цифровой ключ">
                    <datalist id="dl-types">
                        <?php foreach ($dl_types as $v): ?>
                            <option value="<?= e($v) ?>"></option>
                        <?php endforeach; ?>
                    </datalist>
                </label>
            </div>
        </fieldset>

        <button class="btn" type="submit">Сохранить</button>
        <a class="btn" href="games.php" style="margin-left:8px">К списку</a>
    </form>
</main>
<?php include __DIR__ . '/../footer.php'; ?>
</body>
</html>
