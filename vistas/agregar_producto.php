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
            // Forzar conversión a UTF-8 solo si no está ya en UTF-8
            function to_utf8($str) {
                return mb_convert_encoding($str, 'UTF-8', mb_detect_encoding($str, 'UTF-8, ISO-8859-1, ISO-8859-15', true));
            }
            $nombre = to_utf8($nombre);
            $descripcion = to_utf8($descripcion);
            $categoria = to_utf8($categoria);
            // Guardar la imagen en assets/images y solo guardar el nombre en la base
            $imgName = basename($imagen['name']);
            $destino = __DIR__ . '/../assets/images/' . $imgName;
            if (!move_uploaded_file($imagen['tmp_name'], $destino)) {
                throw new Exception('No se pudo guardar la imagen en la carpeta de imágenes.');
            }
            $stmt = $pdo->prepare("INSERT INTO productos (nombre, descripcion, precio, categoria, stock, vendedor_id, imagen) VALUES (?, ?, ?, ?, ?, ?, ?)");
            $stmt->execute([$nombre, $descripcion, $precio, $categoria, $stock, $vendedor_id, $imgName]);
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
