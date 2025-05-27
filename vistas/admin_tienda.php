<?php
// Página de administración de tienda para vendedores
session_start();
require_once '../datos/conexion.php';
if (!isset($_SESSION['usuario']) || $_SESSION['tipo'] !== 'Vendedor') {
    header('Location: ../index.php');
    exit();
}
$vendedor_id = $_SESSION['usuario_id'];
$mensaje = '';
// Eliminar producto
if (isset($_GET['eliminar'])) {
    $id = intval($_GET['eliminar']);
    $stmt = $pdo->prepare('DELETE FROM productos WHERE id = ? AND vendedor_id = ?');
    $stmt->execute([$id, $vendedor_id]);
    $mensaje = 'Producto eliminado correctamente.';
}
// Obtener productos del vendedor
$stmt = $pdo->prepare('SELECT * FROM productos WHERE vendedor_id = ?');
$stmt->execute([$vendedor_id]);
$productos = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <title>Administrar Tienda</title>
    <link rel="stylesheet" href="../css/main.css" />
    <style>
        .admin-container { max-width: 900px; margin: 2rem auto; background: #1e1e1e; border-radius: 14px; padding: 2rem; box-shadow: 0 4px 16px #0002; }
        .admin-header { display: flex; align-items: center; justify-content: space-between; margin-bottom: 2rem; }
        .admin-header h2 { color: #44ff99; }
        .admin-table { width: 100%; border-collapse: collapse; margin-top: 1.5rem; }
        .admin-table th, .admin-table td { padding: 0.7rem 1rem; border-bottom: 1px solid #232323; color: #fff; }
        .admin-table th { background: #232323; color: #44ff99; }
        .admin-table tr:last-child td { border-bottom: none; }
        .btn-danger { background: #ff6b6b; color: #fff; border: none; padding: 0.4rem 1rem; border-radius: 6px; cursor: pointer; }
        .btn-edit { background: #eab308; color: #181818; border: none; padding: 0.4rem 1rem; border-radius: 6px; cursor: pointer; margin-right: 0.5rem; }
        .btn-add { background: #44ff99; color: #181818; border: none; padding: 0.5rem 1.2rem; border-radius: 6px; cursor: pointer; font-weight: bold; }
    </style>
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
            <div style="background:#232323;color:#44ff99;padding:1rem;border-radius:8px;margin-bottom:1rem;">
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
                            <a href="admin_tienda.php?eliminar=<?php echo $prod['id']; ?>" class="btn-danger" onclick="return confirm('¿Eliminar este producto?')">Eliminar</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </main>
</body>
</html>
