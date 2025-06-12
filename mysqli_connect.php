<?php
$db_error = null;
$host = '10.0.2.4';
$charset='utf8mb4';
try {
    $pdo = new PDO("mysql:host="$host;"dbname=telemoveis_bd,charset="$charset, "root", "1234");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    $db_error = $e->getMessage();
    $pdo = null;
}
?>
