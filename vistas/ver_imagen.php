<?php
// Vista para mostrar la imagen de un producto desde la base de datos
require_once '../datos/conexion.php';
$id = $_GET['id'] ?? null;
if ($id) {
    $stmt = $pdo->prepare('SELECT tipo, datos FROM imagenes WHERE id = ?');
    $stmt->execute([$id]);
    $img = $stmt->fetch();
    if ($img) {
        header('Content-Type: ' . $img['tipo']);
        echo $img['datos'];
        exit;
    }
}
http_response_code(404);
echo 'Imagen no encontrada';
