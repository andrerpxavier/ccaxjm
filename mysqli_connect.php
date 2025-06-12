<?php
try {
    $pdo = new PDO("mysql:host=localhost;dbname=telemoveis_bd", "root", "1234");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erro na ligação: " . $e->getMessage());
}
?>
