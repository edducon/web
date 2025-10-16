<?php
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASSWORD', '');
define('DB_NAME', 'lab5_data');
try { $mysql = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME); $mysql->set_charset('utf8mb4'); }
catch (mysqli_sql_exception $e) { http_response_code(500); echo '<h1>Ошибка подключения к базе данных</h1><p>'.htmlspecialchars($e->getMessage(), ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8').'</p>'; exit; }
?>