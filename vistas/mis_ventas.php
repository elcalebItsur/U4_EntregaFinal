<?php
session_start();
require_once '../datos/VentaDAO.php';
require_once '../datos/ProductoDAO.php';
if (!isset($_SESSION['usuario']) || $_SESSION['tipo'] !== 'Vendedor') {
    header('Location: index.php');
    exit();
}
$vendedor_id = $_SESSION['usuario_id'];
$ventas = VentaDAO::obtenerVentasPorVendedor($vendedor_id);
?><!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <title>Mis Ventas</title>
    <link rel="stylesheet" href="../css/main.css" />
    <link rel="stylesheet" href="../css/mis_ventas.css" />
</head>
<body>
    <header>
        <div class="header-content">
            <h1 class="logo" onclick="location.href='index.php'">Textisur</h1>
            <nav>
                <ul>
                    <li><a href="index.php">Inicio</a></li>
                    <li><a href="about.php">Vende</a></li>
                    <li><a href="acerca.php" class="btn-accent">Acerca de</a></li>
                    <?php if (isset($_SESSION['usuario'])): ?>
                        <?php if (isset($_SESSION['tipo']) && $_SESSION['tipo'] === 'Vendedor'): ?>
                            <li><a href="admin_tienda.php" class="btn-primary">Administrar Tienda</a></li>
                        <?php endif; ?>
                        <li class="user-menu">
                            <button class="user-avatar-btn" id="user-avatar-btn">
                                <?php
                                require_once __DIR__ . '/../datos/conexion.php';
                                $stmt = $pdo->prepare('SELECT foto_perfil FROM usuarios WHERE id = ?');
                                $stmt->execute([$_SESSION['usuario_id']]);
                                $foto = $stmt->fetchColumn();
                                if ($foto): ?>
                                    <img src="../assets/images/<?php echo htmlspecialchars($foto); ?>" alt="Perfil" />
                                <?php else: ?>
                                    <span class="avatar-inicial"><?php echo strtoupper(substr($_SESSION['usuario'],0,1)); ?></span>
                                <?php endif; ?>
                            </button>
                        </li>
                    <?php else: ?>
                        <li><a href="login.php" class="btn-primary">Iniciar Sesión</a></li>
                        <li><a href="register.php" class="btn-secondary">Registrarse</a></li>
                    <?php endif; ?>
                </ul>
            </nav>
        </div>
    </header>
    <main class="main-container">
        <h2 class="section-title">Mis Ventas</h2>
        <?php if (empty($ventas)): ?>
            <div class="warning-label">No tienes ventas registradas.</div>
        <?php else: ?>
        <table class="ventas-table">
            <thead>
                <tr>
                    <th>Fecha</th>
                    <th>Producto</th>
                    <th>Cantidad</th>
                    <th>Precio Unitario</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
            <?php foreach ($ventas as $venta): ?>
                <?php $producto = ProductoDAO::obtenerPorId($venta['producto_id']); ?>
                <tr>
                    <td><?php echo htmlspecialchars($venta['fecha']); ?></td>
                    <td><?php echo htmlspecialchars($producto['nombre'] ?? ''); ?></td>
                    <td><?php echo htmlspecialchars($venta['cantidad']); ?></td>
                    <td>$<?php echo number_format($venta['precio_unitario'],2); ?></td>
                    <td>$<?php echo number_format($venta['cantidad'] * $venta['precio_unitario'],2); ?></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
        <?php endif; ?>
    </main>
</body>
</html>
