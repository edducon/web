<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Лабораторная работа №11 - Панпушный Эдуард</title>
    <style>
        html, body { height:100%; margin:0; }
        .page { min-height:100%; min-height:100vh; display:flex; flex-direction:column; }
        header { padding:10px 12px; border-bottom:1px solid #ddd; background:#fff; }
        main { flex:1; display:flex; gap:24px; padding:14px; }
        footer { padding:10px 12px; border-top:1px solid #ddd; background:#f7f1d8; }

        header a { margin-right:12px; text-decoration:none; color:#333; }
        header a.selected { text-decoration:underline; font-weight:bold; }

        #side { width:240px; }
        #side .box { padding:10px; background:#f0eadb; border:1px solid #ccc; border-radius:6px; }
        #side a { display:block; padding:6px 8px; text-decoration:none; color:#333; border-radius:6px; }
        #side a.selected { text-decoration:underline; font-weight:bold; background:#f5f5f5; }

        #content .card { padding:10px; border:1px solid #333; background:#fafafa; border-radius:6px; }

        table { border-collapse:collapse; background:#fafafa; }
        td { border:1px solid #333; padding:6px 10px; vertical-align:top; }

        .ttRow { display:inline-block; vertical-align:top; margin-right:12px; padding:8px; border:1px solid #333; background:#fafafa; }
        .ttSingleRow { display:inline-block; padding:10px; border:1px solid #333; background:#fafafa; }
    </style>
</head>
<body>
<div class="page">
    <?php
    $hasHtmlParam    = array_key_exists('html_type', $_GET);
    $hasContentParam = array_key_exists('content', $_GET);

    $html = $hasHtmlParam ? $_GET['html_type'] : null;
    $cont = $hasContentParam ? $_GET['content']   : '';

    function outNumAsLink($x) {
        global $hasHtmlParam, $html;
        if ($x <= 9) {
            $params = ['content' => $x];
            if ($hasHtmlParam) {
                $params['html_type'] = $html;
            }
            return '<a href="'.buildQuery($params).'">'.$x.'</a>';
        }
        return (string)$x;
    }
    function outRow($n) {
        $s = '';
        for ($i=2; $i<=9; $i++) {
            $s .= outNumAsLink($n).'x'.outNumAsLink($i).'='.outNumAsLink($i*$n).'<br>';
        }
        return $s;
    }
    function outRowTable($n) {
        $s = '';
        for ($i=2; $i<=9; $i++) {
            $s .= outNumAsLink($n).'x'.outNumAsLink($i).'='.outNumAsLink($i*$n);
            if ($i < 9) {
                $s .= '<hr style="margin:6px 0; border:0; border-top:1px solid #666;">';
            }
        }
        return $s;
    }
    function outTableForm() {
        global $cont;
        if ($cont === '') {
            echo "<table><tr>";
            for ($i=2; $i<=9; $i++) echo "<td>".outRowTable($i)."</td>";
            echo "</tr></table>";
        } else {
            echo "<table><tr><td>".outRowTable($cont)."</td></tr></table>";
        }
    }
    function outDivForm() {
        global $cont;
        if ($cont === '') {
            for ($i=2; $i<=9; $i++) echo '<div class="ttRow">'.outRow($i).'</div>';
        } else {
            echo '<div class="ttSingleRow">'.outRow($cont).'</div>';
        }
    }
    function buildQuery($params) {
        if (empty($params)) return '?';
        $pairs=[]; foreach ($params as $k=>$v) { $pairs[]=$k.'='.urlencode($v); }
        return '?'.implode('&',$pairs);
    }
    ?>

    <header>
        <?php
        $hrefTable = buildQuery(array_merge(['html_type'=>'TABLE'], $hasContentParam ? ['content'=>$cont] : []));
        $hrefDiv   = buildQuery(array_merge(['html_type'=>'DIV'],   $hasContentParam ? ['content'=>$cont] : []));
        echo '<a href="'.$hrefTable.'"'.($hasHtmlParam && $html==='TABLE' ? ' class="selected"' : '').'>Табличная верстка</a>';
        echo '<a href="'.$hrefDiv  .'"'.($hasHtmlParam && $html==='DIV'   ? ' class="selected"' : '').'>Блочная верстка</a>';
        ?>
    </header>

    <main>
        <aside id="side">
            <div class="box">
                <?php
                if ($hasHtmlParam) {
                    $hrefAll = buildQuery(['html_type' => $html, 'content' => '']);
                } else {
                    $hrefAll = buildQuery(['content' => '']);
                }
                $allSelected = $hasContentParam && $cont === '';
                echo '<a href="'.$hrefAll.'"'.($allSelected ? ' class="selected"' : '').'>Вся таблица умножения</a>';

                for ($i=2; $i<=9; $i++) {
                    $params = ['content'=>$i];
                    if ($hasHtmlParam) $params['html_type'] = $html;
                    $href = buildQuery($params);
                    $sel  = ($hasContentParam && (string)$cont===(string)$i) ? ' class="selected"' : '';
                    echo '<a href="'.$href.'"'.$sel.'>Таблица умножения на '.$i.'</a>';
                }
                ?>
            </div>
        </aside>

        <section id="content" class="card" style="flex:1;">
            <?php
            $effectiveLayout = $hasHtmlParam ? $html : 'TABLE';

            if ($effectiveLayout === 'TABLE') {
                outTableForm();
            } else {
                outDivForm();
            }
            ?>
        </section>
    </main>

    <footer>
        <?php
        if (!$hasHtmlParam) {
            $s = 'Верстка не выбрана. ';
        } else {
            $s = ($html==='TABLE') ? 'Табличная верстка. ' : 'Блочная верстка. ';
        }
        if (!$hasContentParam || $cont==='')  $s .= 'Таблица умножения полностью. ';
        else                                   $s .= 'Таблица умножения на '.$cont.'. ';
        $s .= 'Дата: '.date('d.m.Y').'. Время: '.date('H:i:s');
        echo $s;
        ?>
    </footer>
</div>
</body>
</html>
