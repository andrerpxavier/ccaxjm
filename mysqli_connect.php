<?php
$db_error = null;
try {
    $pdo = new PDO("mysql:host=localhost;dbname=telemoveis_bd", "root", "1234");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    $db_error = $e->getMessage();
    $pdo = null;
}
?>
