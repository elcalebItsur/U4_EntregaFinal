<?php
// Parámetros de conexión
$host = 'localhost';
$db = 'textisur_PW';
$user = 'textisur';
$pass = 'SQL55H7#';
$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
];
try {
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8", $user, $pass, $options);
} catch (PDOException $e) {
    die('Error de conexión: ' . $e->getMessage());
}
?>
