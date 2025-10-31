<?php
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
$DB_HOST = 'std-mysql';
$DB_USER = 'std_2741_pk2';
$DB_PASS = 'qwerty12345';
$DB_NAME = 'std_2741_pk2';
try {
    $db = new mysqli($DB_HOST, $DB_USER, $DB_PASS, $DB_NAME);
    $db->set_charset('utf8mb4');
} catch (mysqli_sql_exception $e) {
    http_response_code(500);
    echo '<h1>DB error</h1><pre>' . htmlspecialchars($e->getMessage()) . '</pre>';
    exit;
}
