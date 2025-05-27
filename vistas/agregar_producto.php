<?php
require_once '../datos/ProductoDAO.php';
session_start();

$esEdicion = false;
$mensaje = '';
$producto = [
    'nombre' => '',
    'precio' => '',
    'categoria' => '',
    'stock' => ''
];

// Si viene por edición, carga los datos
if (isset($_GET['editar'])) {
    $esEdicion = true;
    $id = intval($_GET['editar']);
    $producto = ProductoDAO::obtenerPorId($id, $_SESSION['usuario_id']);
    if (!$producto) {
        header('Location: admin_tienda.php');
        exit();
    }
}

// Guardar cambios (editar)
if (isset($_POST['guardar']) && $esEdicion) {
    $nombre = $_POST['nombre'];
    $precio = $_POST['precio'];
    $categoria = $_POST['categoria'];
    $stock = $_POST['stock'];
    $id = $_POST['id'];
    ProductoDAO::actualizar($id, $nombre, $precio, $categoria, $stock, $_SESSION['usuario_id']);
    header('Location: admin_tienda.php');
    exit();
}

// Agregar nuevo producto
if (isset($_POST['agregar']) && !$esEdicion) {
    $nombre = $_POST['nombre'];
    $precio = $_POST['precio'];
    $categoria = $_POST['categoria'];
    $stock = $_POST['stock'];
    ProductoDAO::agregar($nombre, $precio, $categoria, $stock, $_SESSION['usuario_id']);
    header('Location: admin_tienda.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <title><?php echo $esEdicion ? 'Editar Producto' : 'Agregar Producto'; ?></title>
    <link rel="stylesheet" href="../css/main.css" />
</head>
<body>
    <main style="max-width:500px;margin:2rem auto;background:#1e1e1e;padding:2rem;border-radius:12px;">
        <h2 style="color:#44ff99;"><?php echo $esEdicion ? 'Editar Producto' : 'Agregar Producto'; ?></h2>
        <form method="post">
            <input type="text" name="nombre" value="<?php echo htmlspecialchars($producto['nombre']); ?>" placeholder="Nombre" required><br><br>
            <input type="number" step="0.01" name="precio" value="<?php echo htmlspecialchars($producto['precio']); ?>" placeholder="Precio" required><br><br>
            <input type="text" name="categoria" value="<?php echo htmlspecialchars($producto['categoria']); ?>" placeholder="Categoría" required><br><br>
            <input type="number" name="stock" value="<?php echo htmlspecialchars($producto['stock']); ?>" placeholder="Stock" required><br><br>
            <?php if ($esEdicion): ?>
                <input type="hidden" name="id" value="<?php echo $producto['id']; ?>">
                <button type="submit" name="guardar" class="btn-edit">Guardar Cambios</button>
                <a href="admin_tienda.php" class="btn-danger" style="margin-left:1rem;">Regresar</a>
            <?php else: ?>
                <button type="submit" name="agregar" class="btn-add">Agregar Producto</button>
            <?php endif; ?>
        </form>
    </main>
</body>
</html>
