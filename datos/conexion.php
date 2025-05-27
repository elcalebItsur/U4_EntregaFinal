<?php
// Conexión a PostgreSQL para todo el sistema
$host = 'localhost';
$db = 'textisur';
$user = 'postgres';
$pass = 'root';
$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
];
try {
    $pdo = new PDO("pgsql:host=$host;dbname=$db", $user, $pass, $options);
    $pdo->exec("SET NAMES 'UTF8'");
} catch (PDOException $e) {
    die('Error de conexión: ' . $e->getMessage());
}
?>
