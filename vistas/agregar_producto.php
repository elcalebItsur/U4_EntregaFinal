<?php
require_once '../datos/ProductoDAO.php';
session_start();

$esEdicion = false;
$mensaje = '';
$producto = [
    'nombre' => '',
    'descripcion' => '',
    'precio' => '',
    'categoria' => '',
    'stock' => '',
    'imagen' => ''
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
    $descripcion = $_POST['descripcion'];
    $precio = $_POST['precio'];
    $categoria = $_POST['categoria'];
    $stock = $_POST['stock'];
    $id = $_POST['id'];
    $imagen = $producto['imagen'];
    if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
        $tmp_name = $_FILES['imagen']['tmp_name'];
        $original_name = basename($_FILES['imagen']['name']);
        $ext = strtolower(pathinfo($original_name, PATHINFO_EXTENSION));
        $allowed = ['jpg', 'jpeg', 'png', 'webp'];
        if (in_array($ext, $allowed)) {
            $new_name = uniqid('prod_') . '.' . $ext;
            $destino = '../assets/images/' . $new_name;
            if (move_uploaded_file($tmp_name, $destino)) {
                $imagen = $new_name;
            }
        }
    }
    ProductoDAO::actualizarCompleto($id, $nombre, $descripcion, $precio, $categoria, $stock, $imagen, $_SESSION['usuario_id']);
    header('Location: admin_tienda.php');
    exit();
}

// Agregar nuevo producto
if (isset($_POST['agregar']) && !$esEdicion) {
    $nombre = $_POST['nombre'];
    $descripcion = $_POST['descripcion'];
    $precio = $_POST['precio'];
    $categoria = $_POST['categoria'];
    $stock = $_POST['stock'];
    $imagen = null;
    if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
        $tmp_name = $_FILES['imagen']['tmp_name'];
        $original_name = basename($_FILES['imagen']['name']);
        $ext = strtolower(pathinfo($original_name, PATHINFO_EXTENSION));
        $allowed = ['jpg', 'jpeg', 'png', 'webp'];
        if (in_array($ext, $allowed)) {
            $new_name = uniqid('prod_') . '.' . $ext;
            $destino = '../assets/images/' . $new_name;
            if (move_uploaded_file($tmp_name, $destino)) {
                $imagen = $new_name;
            }
        }
    }
    ProductoDAO::agregarCompleto($nombre, $descripcion, $precio, $categoria, $stock, $imagen, $_SESSION['usuario_id']);
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
        <form method="post" enctype="multipart/form-data">
            <input type="text" name="nombre" value="<?php echo htmlspecialchars($producto['nombre']); ?>" placeholder="Nombre" required><br><br>
            <textarea name="descripcion" placeholder="Descripción" required><?php echo htmlspecialchars($producto['descripcion']); ?></textarea><br><br>
            <input type="number" step="0.01" name="precio" value="<?php echo htmlspecialchars($producto['precio']); ?>" placeholder="Precio" required><br><br>
            <input type="text" name="categoria" value="<?php echo htmlspecialchars($producto['categoria']); ?>" placeholder="Categoría" required><br><br>
            <input type="number" name="stock" value="<?php echo htmlspecialchars($producto['stock']); ?>" placeholder="Stock" required><br><br>
            <input type="file" name="imagen" accept="image/*"><br><br>
            <?php if (!empty($producto['imagen'])): ?>
                <img src="../assets/images/<?php echo htmlspecialchars($producto['imagen']); ?>" alt="Imagen actual" style="max-width:120px;border-radius:8px;margin-bottom:1rem;" />
            <?php endif; ?>
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
