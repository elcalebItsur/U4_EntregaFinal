<?php
// Página de administración de tienda para vendedores
session_start();
require_once '../datos/ProductoDAO.php';
if (!isset($_SESSION['usuario']) || $_SESSION['tipo'] !== 'Vendedor') {
    header('Location: ../index.php');
    exit();
}
$vendedor_id = $_SESSION['usuario_id'];
$mensaje = '';
// Eliminar producto
if (isset($_GET['eliminar'])) {
    $id = intval($_GET['eliminar']);
    try {
        ProductoDAO::eliminar($id, $vendedor_id);
        $mensaje = 'Producto eliminado correctamente.';
    } catch (PDOException $e) {
        if (strpos($e->getMessage(), '23503') !== false) {
            $mensaje = 'No se puede eliminar el producto porque ya tiene ventas asociadas.';
        } else {
            $mensaje = 'Error al eliminar el producto: ' . $e->getMessage();
        }
    }
}
// Obtener productos del vendedor
$productos = ProductoDAO::obtenerPorVendedor($vendedor_id);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <title>Administrar Tienda</title>
    <link rel="stylesheet" href="../css/main.css" />
    <link rel="stylesheet" href="../css/admin_tienda.css" />
</head>
<body>
    <header>
        <div class="header-content">
            <h1 class="logo" onclick="location.href='../vistas/index.php'">Textisur</h1>
            <nav>
                <ul>
                    <li><a href="../vistas/index.php">Inicio</a></li>
                    <li><a href="perfil.php">Mi perfil</a></li>
                    <li><a href="../logout.php" class="btn-secondary">Cerrar sesión</a></li>
                </ul>
            </nav>
        </div>
    </header>
    <main class="admin-container">
        <div class="admin-header">
            <h2>Administrar Tienda</h2>
            <a href="agregar_producto.php" class="btn-add">Agregar Producto</a>
        </div>
        <?php if ($mensaje): ?>
            <div class="admin-warning">
                <?php echo htmlspecialchars($mensaje); ?>
            </div>
        <?php endif; ?>
        <table class="admin-table">
            <thead>
                <tr>
                    <th>Nombre</th>
                    <th>Precio</th>
                    <th>Categoría</th>
                    <th>Stock</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($productos as $prod): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($prod['nombre']); ?></td>
                        <td>$<?php echo number_format($prod['precio'],2); ?></td>
                        <td><?php echo htmlspecialchars($prod['categoria']); ?></td>
                        <td><?php echo htmlspecialchars($prod['stock']); ?></td>
                        <td>
                            <a href="agregar_producto.php?editar=<?php echo $prod['id']; ?>" class="btn-edit">Editar</a>
                            <a href="#" class="btn-danger btn-eliminar-modal" data-id="<?php echo $prod['id']; ?>" data-nombre="<?php echo htmlspecialchars($prod['nombre']); ?>">Eliminar</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <div id="modal-eliminar-producto" class="modal" style="display:none; align-items:center; justify-content:center;">
            <div class="modal-content" style="min-width:320px; max-width:95vw;">
                <button id="cerrar-modal-eliminar" style="position:absolute; top:1rem; right:1rem; background:none; border:none; font-size:2rem; color:#ff6b6b; cursor:pointer;">&times;</button>
                <h3>¿Eliminar producto?</h3>
                <p>¿Estás seguro de que deseas eliminar <span id="modal-eliminar-nombre" style="font-weight:bold;"></span>?</p>
                <form id="modal-eliminar-form">
                    <button type="submit" class="btn-danger" style="margin-right:1rem;">Eliminar</button>
                    <button type="button" id="cerrar-modal-eliminar-2" class="btn-secondary">Cancelar</button>
                </form>
            </div>
        </div>
    </main>
    <script src="../js/admin_tienda.js" defer></script>
</body>
</html>
