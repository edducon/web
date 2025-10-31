<?php
$x = isset($_GET['x']) ? floatval($_GET['x']) : -10.0;
$n = isset($_GET['n']) ? intval($_GET['n']) : 50;
$step = isset($_GET['step']) ? floatval($_GET['step']) : 1.0;
$fmin = isset($_GET['fmin']) ? floatval($_GET['fmin']) : -1000.0;
$fmax = isset($_GET['fmax']) ? floatval($_GET['fmax']) : 1000.0;
$type = isset($_GET['type']) ? strtoupper(trim($_GET['type'])) : 'D';
if(!in_array($type,['A','B','C','D','E'])) $type='D';
$loop = isset($_GET['loop']) ? strtolower(trim($_GET['loop'])) : 'do';
if(!in_array($loop,['for','while','do'])) $loop='do';
$precision = 3;

function f_val($x,$p=3){
    if($x<=10) return round(7*$x+18,$p);
    if($x<20){ $d=8-0.5*$x; return (abs($d)<1e-12)?'error':round(($x-17)/$d,$p); }
    return round(($x+4)*($x-7),$p);
}
function fmt($v){
    return ($v==='error') ? 'error' : number_format((float)$v, 3, '.', '');
}

$rows=[];

if($loop==='for'){
    for($i=0,$cx=$x; $i<$n; $i++,$cx+=$step){
        $f=f_val($cx,$precision); $rows[]=['x'=>$cx,'f'=>$f];
        if($f!=='error' && is_numeric($f) && ($f<$fmin || $f>=$fmax)) break;
    }
}elseif($loop==='while'){
    $i=0; $cx=$x; $ok=true;
    while($i<$n && $ok){
        $f=f_val($cx,$precision); $rows[]=['x'=>$cx,'f'=>$f];
        if($f!=='error' && is_numeric($f) && ($f<$fmin || $f>=$fmax)) $ok=false;
        $i++; $cx+=$step;
    }
}else{
    $i=0; $cx=$x;
    if($n>0){
        do{
            $f=f_val($cx,$precision); $rows[]=['x'=>$cx,'f'=>$f];
            $i++; $cx+=$step;
            if($f!=='error' && is_numeric($f) && ($f<$fmin || $f>=$fmax)) break;
        }while($i<$n);
    }
}
?>
<!doctype html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <title>Панпушный Эдуард Васильевич | 241-362 | ЛР №9 | Вариант 8</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="styles.css">
</head>
<body>
<header>
    <img class="logo-left" src="logo.png" alt="Логотип">
    <div class="container header-inner">
        <h1>Панпушный Эдуард Васильевич | 241-362 | ЛР №9 | Вариант 8</h1>
    </div>
</header>

<main class="container">
    <form method="get" class="grid">
        <label>Начальное x
            <input type="number" step="0.001" name="x" value="<?php echo htmlspecialchars((string)$x); ?>">
        </label>
        <label>Количество n
            <input type="number" name="n" value="<?php echo htmlspecialchars((string)$n); ?>">
        </label>
        <label>Шаг step
            <input type="number" step="0.001" name="step" value="<?php echo htmlspecialchars((string)$step); ?>">
        </label>
        <label>f<sub>min</sub> (стоп, &lt;)
            <input type="number" step="0.001" name="fmin" value="<?php echo htmlspecialchars((string)$fmin); ?>">
        </label>
        <label>f<sub>max</sub> (стоп, ≥)
            <input type="number" step="0.001" name="fmax" value="<?php echo htmlspecialchars((string)$fmax); ?>">
        </label>
        <label>Тип верстки
            <select name="type">
                <?php foreach(['A','B','C','D','E'] as $t){ echo '<option value="'.$t.'"'.($type===$t?' selected':'').'>'.$t.'</option>'; } ?>
            </select>
        </label>
        <label>Тип цикла
            <select name="loop">
                <?php foreach(['for','while','do'] as $l){ echo '<option value="'.$l.'"'.($loop===$l?' selected':'').'>'.$l.'</option>'; } ?>
            </select>
        </label>
        <button type="submit">Пересчитать</button>
    </form>

    <?php if($type==='A'): ?>
        <?php for($i=0;$i<count($rows);$i++){ echo 'f('.fmt($rows[$i]['x']).')='.fmt($rows[$i]['f']); if($i<count($rows)-1) echo '<br>'; } ?>
    <?php elseif($type==='B'): ?>
        <ul>
            <?php foreach($rows as $r){ echo '<li>f('.fmt($r['x']).')='.fmt($r['f']).'</li>'; } ?>
        </ul>
    <?php elseif($type==='C'): ?>
        <ol>
            <?php foreach($rows as $r){ echo '<li>f('.fmt($r['x']).')='.fmt($r['f']).'</li>'; } ?>
        </ol>
    <?php elseif($type==='D'): ?>
        <table class="grid-table"><thead><tr><th>#</th><th>x</th><th>f(x)</th></tr></thead><tbody>
            <?php $k=1; foreach($rows as $r){ echo '<tr><td>'.$k++.'</td><td>'.htmlspecialchars(fmt($r['x'])).'</td><td>'.htmlspecialchars(fmt($r['f'])).'</td></tr>'; } ?>
            </tbody></table>
    <?php else: ?>
        <div class="blocks">
            <?php foreach($rows as $r){ echo '<div class="block-item">f('.fmt($r['x']).')='.fmt($r['f']).'</div>'; } ?>
        </div>
    <?php endif; ?>
</main>

<footer>
    <div class="container">Тип верстки: <?php echo htmlspecialchars($type); ?> | Тип цикла: <?php echo htmlspecialchars($loop); ?> | HTML5 + CSS3</div>
</footer>
</body>
</html>
