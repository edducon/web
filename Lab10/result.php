<?php
header('Content-Type: text/html; charset=utf-8');

echo '<!doctype html><html lang="ru"><head><meta charset="utf-8"><title>Результат анализа</title></head><body>';
echo '<h1>Результат анализа</h1>';

if (isset($_POST['data']) && $_POST['data']) {
    echo '<div class="src_text" style="color:#0066cc;font-style:italic;white-space:pre-wrap;border:1px solid #ccc;padding:8px;margin-bottom:10px">'
        . $_POST['data'] . '</div>';

    test_it( iconv('utf-8', 'cp1251', $_POST['data']) );

} else {
    echo '<div class="src_error" style="color:#c00;font-style:italic">Нет текста для анализа</div>';
}

echo '<p><a href="index.html">Другой анализ</a></p>';
echo '</body></html>';


function test_it($text_cp)
{
    echo '<table border="1" cellpadding="6" cellspacing="0">';
    echo '<tr><td>Количество символов (включая пробелы)</td><td>'.strlen($text_cp).'</td></tr>';

    $digits = ['0'=>true,'1'=>true,'2'=>true,'3'=>true,'4'=>true,'5'=>true,'6'=>true,'7'=>true,'8'=>true,'9'=>true];

    $puncts = [
        '!' => true, '"' => true, '#' => true, '$' => true, '%' => true, '&' => true, "'" => true, '(' => true, ')' => true,
        '*' => true, '+' => true, ',' => true, '-' => true, '.' => true, '/' => true, ':' => true, ';' => true, '<' => true,
        '=' => true, '>' => true, '?' => true, '@' => true, '[' => true, '\\' => true, ']' => true, '^' => true, '_' => true,
        '`' => true, '{' => true, '|' => true, '}' => true, '~' => true,
        "\xAB" => true,
        "\xBB" => true,
        "\x96" => true,
        "\x97" => true,
        "\x85" => true
    ];

    $digits_cnt = 0;
    $letters_cnt = 0;
    $lower_cnt = 0;
    $upper_cnt = 0;
    $puncts_cnt = 0;

    $word = '';
    $words = [];

    $len = strlen($text_cp);
    for ($i = 0; $i < $len; $i++) {
        $ch = $text_cp[$i];

        if (isset($digits[$ch])) {
            $digits_cnt++;
            $word .= $ch;
            continue;
        }

        $ord = ord($ch);
        if ($ord >= 0x41 && $ord <= 0x5A) { // A..Z
            $letters_cnt++; $upper_cnt++; $word .= $ch; continue;
        }
        if ($ord >= 0x61 && $ord <= 0x7A) { // a..z
            $letters_cnt++; $lower_cnt++; $word .= $ch; continue;
        }

        if (($ord >= 0xC0 && $ord <= 0xDF) || $ord == 0xA8) { // верхний регистр
            $letters_cnt++; $upper_cnt++; $word .= $ch; continue;
        }
        if (($ord >= 0xE0 && $ord <= 0xFF) || $ord == 0xB8) { // нижний регистр
            $letters_cnt++; $lower_cnt++; $word .= $ch; continue;
        }

        $is_space = ($ch === ' ' || $ch === "\t" || $ch === "\r" || $ch === "\n");
        $is_punct = isset($puncts[$ch]);

        if ($is_space || $is_punct || $i == $len - 1) {
            if ($i == $len - 1 && !$is_space && !$is_punct) {
                $word .= $ch;
            }
            if ($word !== '') {
                if (isset($words[$word])) $words[$word]++; else $words[$word] = 1;
                $word = '';
            }
            if ($is_punct) $puncts_cnt++;
            continue;
        }
    }

    if ($word !== '') {
        if (isset($words[$word])) $words[$word]++; else $words[$word] = 1;
        $word = '';
    }

    echo '<tr><td>Количество букв</td><td>'.$letters_cnt.'</td></tr>';
    echo '<tr><td>Количество строчных букв</td><td>'.$lower_cnt.'</td></tr>';
    echo '<tr><td>Количество заглавных букв</td><td>'.$upper_cnt.'</td></tr>';
    echo '<tr><td>Количество знаков препинания</td><td>'.$puncts_cnt.'</td></tr>';
    echo '<tr><td>Количество цифр</td><td>'.$digits_cnt.'</td></tr>';
    echo '<tr><td>Количество слов</td><td>'.count($words).'</td></tr>';
    echo '</table>';

    $symbs = test_symbs($text_cp);

    echo '<h2>Вхождения каждого символа (без учёта регистра)</h2>';
    echo '<table border="1" cellpadding="4" cellspacing="0"><tr><th>Символ</th><th>Количество</th></tr>';
    foreach ($symbs as $k => $v) {
        $u = iconv('cp1251','utf-8',$k);
        if ($u === "\n")      $u = 'перенос строки';
        elseif ($u === " ")   $u = 'пробелы';
        echo '<tr><td style="text-align:center">'.$u.'</td><td style="text-align:right">'.$v.'</td></tr>';
    }
    echo '</table>';

    ksort($words);
    echo '<h2>Список слов и количество их вхождений (по алфавиту)</h2>';
    echo '<table border="1" cellpadding="4" cellspacing="0"><tr><th>Слово</th><th>Количество</th></tr>';
    foreach ($words as $w => $cnt) {
        echo '<tr><td>'.iconv('cp1251','utf-8',$w).'</td><td style="text-align:right">'.$cnt.'</td></tr>';
    }
    echo '</table>';
}

function test_symbs($text_cp)
{
    $norm = str_replace(
        ["\r\n", "\r", "\t"],
        ["\n",   "\n", " "],
        $text_cp
    );
    $symbs = [];
    $l_text = strtolower($norm);
    $len = strlen($l_text);
    for ($i = 0; $i < $len; $i++) {
        $c = $l_text[$i];
        isset($symbs[$c]) ? $symbs[$c]++ : $symbs[$c] = 1;
    }
    return $symbs;
}
