<?php
// Vista para agregar productos y subir imágenes
session_start();
require_once '../datos/conexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = trim($_POST['nombre'] ?? '');
    $descripcion = trim($_POST['descripcion'] ?? '');
    $precio = floatval($_POST['precio'] ?? 0);
    $categoria = trim($_POST['categoria'] ?? '');
    $stock = intval($_POST['stock'] ?? 0);
    $vendedor_id = $_SESSION['usuario_id'] ?? null;
    $imagen = $_FILES['imagen'] ?? null;
    $mensaje = '';

    if ($nombre && $precio && $vendedor_id && $imagen && $imagen['tmp_name']) {
        try {
            $pdo->beginTransaction();
            $stmt = $pdo->prepare("INSERT INTO productos (nombre, descripcion, precio, categoria, stock, vendedor_id) VALUES (?, ?, ?, ?, ?, ?) RETURNING id");
            $stmt->execute([$nombre, $descripcion, $precio, $categoria, $stock, $vendedor_id]);
            $producto_id = $stmt->fetchColumn();

            $imgData = file_get_contents($imagen['tmp_name']);
            $stmtImg = $pdo->prepare("INSERT INTO imagenes (producto_id, nombre_archivo, tipo, datos) VALUES (?, ?, ?, ?)");
            $stmtImg->execute([$producto_id, $imagen['name'], $imagen['type'], $imgData]);
            $pdo->commit();
            $mensaje = 'Producto registrado correctamente.';
        } catch (Exception $e) {
            $pdo->rollBack();
            $mensaje = 'Error al registrar producto: ' . $e->getMessage();
        }
    } else {
        $mensaje = 'Faltan datos obligatorios.';
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <title>Agregar Producto</title>
</head>
<body>
    <h2>Agregar Producto</h2>
    <?php if (!empty($mensaje)) echo '<p>' . htmlspecialchars($mensaje) . '</p>'; ?>
    <form method="POST" enctype="multipart/form-data">
        <label>Nombre: <input type="text" name="nombre" required></label><br>
        <label>Descripción: <textarea name="descripcion"></textarea></label><br>
        <label>Precio: <input type="number" step="0.01" name="precio" required></label><br>
        <label>Categoría: <input type="text" name="categoria"></label><br>
        <label>Stock: <input type="number" name="stock" value="0"></label><br>
        <label>Imagen: <input type="file" name="imagen" accept="image/*" required></label><br>
        <button type="submit">Guardar Producto</button>
    </form>
</body>
</html>
