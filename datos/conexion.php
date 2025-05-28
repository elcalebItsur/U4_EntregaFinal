<?php
// Conexión a PostgreSQL para todo el sistema
$host = 'localhost';
$db = 'textisur';
$user = 'postgres';
$pass = 'root';
$port = 5432; // Puerto por defecto de PostgreSQL
$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
];
try {
    $pdo = new PDO("pgsql:host=$host;port=$port;dbname=$db", $user, $pass, $options);
    $pdo->exec("SET NAMES 'UTF8'");
} catch (PDOException $e) {
    // Mostrar error detallado para depuración
    die('<b>Error de conexión:</b> ' . $e->getMessage());
}
?>
