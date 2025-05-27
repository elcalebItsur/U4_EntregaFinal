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
    <style>
    .form-producto {
        background: #232323;
        border-radius: 16px;
        box-shadow: 0 8px 32px #0005;
        padding: 2.5rem 2rem 2rem 2rem;
        max-width: 480px;
        margin: 2.5rem auto;
        color: #fff;
        display: flex;
        flex-direction: column;
        gap: 1.2rem;
        border: 1.5px solid #44ff99;
    }
    .form-producto h2 {
        color: #44ff99;
        text-align: center;
        margin-bottom: 1.2rem;
        font-size: 2rem;
        letter-spacing: 1px;
    }
    .form-producto input[type="text"],
    .form-producto input[type="number"],
    .form-producto input[type="file"],
    .form-producto textarea {
        width: 100%;
        padding: 0.7rem 1rem;
        border-radius: 8px;
        border: 1.5px solid #333;
        background: #181818;
        color: #fff;
        font-size: 1rem;
        margin-top: 0.2rem;
        margin-bottom: 0.2rem;
        transition: border 0.2s;
    }
    .form-producto input[type="text"]:focus,
    .form-producto input[type="number"]:focus,
    .form-producto textarea:focus {
        border: 1.5px solid #44ff99;
        outline: none;
    }
    .form-producto label {
        color: #eab308;
        font-weight: 500;
        margin-bottom: 0.2rem;
    }
    .form-producto .img-preview {
        display: block;
        margin: 0.5rem auto 1rem auto;
        max-width: 140px;
        border-radius: 10px;
        box-shadow: 0 2px 8px #0003;
    }
    .form-producto .form-actions {
        display: flex;
        gap: 1rem;
        justify-content: center;
        margin-top: 1.2rem;
    }
    .form-producto button,
    .form-producto .btn-danger {
        padding: 0.7rem 1.5rem;
        border-radius: 8px;
        border: none;
        font-weight: bold;
        font-size: 1rem;
        cursor: pointer;
        transition: background 0.2s, color 0.2s, box-shadow 0.2s, transform 0.2s;
    }
    .form-producto button.btn-add {
        background: #44ff99;
        color: #181818;
    }
    .form-producto button.btn-add:hover {
        background: #22d37b;
        color: #fff;
    }
    .form-producto button.btn-edit {
        background: #eab308;
        color: #181818;
    }
    .form-producto button.btn-edit:hover {
        background: #facc15;
        color: #232323;
    }
    .form-producto .btn-danger {
        background: #e63946;
        color: #fff;
    }
    .form-producto .btn-danger:hover {
        background: #b91c1c;
    }
    </style>
</head>
<body>
    <main>
        <form method="post" enctype="multipart/form-data" class="form-producto">
            <h2><?php echo $esEdicion ? 'Editar Producto' : 'Agregar Producto'; ?></h2>
            <div>
                <label>Nombre</label>
                <input type="text" name="nombre" value="<?php echo htmlspecialchars($producto['nombre']); ?>" required>
            </div>
            <div>
                <label>Descripción</label>
                <textarea name="descripcion" required><?php echo htmlspecialchars($producto['descripcion']); ?></textarea>
            </div>
            <div>
                <label>Precio</label>
                <input type="number" step="0.01" name="precio" value="<?php echo htmlspecialchars($producto['precio']); ?>" required>
            </div>
            <div>
                <label>Categoría</label>
                <input type="text" name="categoria" value="<?php echo htmlspecialchars($producto['categoria']); ?>" required>
            </div>
            <div>
                <label>Stock</label>
                <input type="number" name="stock" value="<?php echo htmlspecialchars($producto['stock']); ?>" required>
            </div>
            <div>
                <label>Imagen</label>
                <input type="file" name="imagen" accept="image/*">
                <?php if (!empty($producto['imagen'])): ?>
                    <img src="../assets/images/<?php echo htmlspecialchars($producto['imagen']); ?>" alt="Imagen actual" class="img-preview" />
                <?php endif; ?>
            </div>
            <?php if ($esEdicion): ?>
                <input type="hidden" name="id" value="<?php echo $producto['id']; ?>">
                <div class="form-actions">
                    <button type="submit" name="guardar" class="btn-edit">Guardar Cambios</button>
                    <a href="admin_tienda.php" class="btn-danger">Regresar</a>
                </div>
            <?php else: ?>
                <div class="form-actions">
                    <button type="submit" name="agregar" class="btn-add">Agregar Producto</button>
                    <a href="admin_tienda.php" class="btn-danger">Regresar</a>
                </div>
            <?php endif; ?>
        </form>
    </main>
</body>
</html>
