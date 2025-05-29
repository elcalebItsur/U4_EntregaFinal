<?php
session_start();
require_once '../datos/VentaDAO.php';
require_once '../datos/ProductoDAO.php';
if (!isset($_SESSION['usuario']) || $_SESSION['tipo'] !== 'Vendedor') {
    header('Location: index.php');
    exit();
}
$vendedor_id = $_SESSION['usuario_id'];
$ventas = VentaDAO::obtenerVentasPorVendedorAgrupadas($vendedor_id);

if (isset($_POST['atender_detalle'])) {
    $detalle_id = intval($_POST['detalle_id']);
    require_once '../datos/VentaDAO.php';
    VentaDAO::marcarDetalleAtendido($detalle_id);
    // Recargar ventas después de actualizar
    $ventas = VentaDAO::obtenerVentasPorVendedorAgrupadas($vendedor_id);
}
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
            <?php foreach ($ventas as $venta): ?>
                <?php $comprador = VentaDAO::obtenerDatosCompradorPorVenta($venta['id']); ?>
                <table class="ventas-table">
                    <thead>
                        <tr>
                            <th>Fecha</th>
                            <th>Producto</th>
                            <th>Cantidad</th>
                            <th>Precio Unitario</th>
                            <th>Total</th>
                            <th>Acciones</th>
                        </tr>
                        <tr>
                            <td colspan="6" style="background:#f7f7f7; color:#333; font-size:0.98em;">
                                <strong>Comprador:</strong> <?php echo htmlspecialchars($comprador['nombre'] ?? ''); ?> &nbsp; | &nbsp; <strong>Dirección:</strong> <?php echo htmlspecialchars($comprador['direccion'] ?? ''); ?>
                            </td>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($venta['detalles'] as $detalle): ?>
                            <?php $producto = ProductoDAO::obtenerPorId($detalle['producto_id']); ?>
                            <tr>
                                <td><?php echo htmlspecialchars($venta['fecha']); ?></td>
                                <td><?php echo htmlspecialchars($producto['nombre'] ?? ''); ?></td>
                                <td><?php echo htmlspecialchars($detalle['cantidad']); ?></td>
                                <td>$<?php echo number_format($detalle['precio_unitario'],2); ?></td>
                                <td>$<?php echo number_format($detalle['cantidad'] * $detalle['precio_unitario'],2); ?></td>
                                <td>
                                    <?php if (empty($detalle['atendido']) || $detalle['atendido'] == false): ?>
                                        <form method="post" style="display:inline;">
                                            <input type="hidden" name="detalle_id" value="<?php echo $detalle['id']; ?>">
                                            <button type="submit" name="atender_detalle" class="btn-primary" style="padding:0.3rem 1rem; font-size:0.95rem;">Atender</button>
                                        </form>
                                    <?php else: ?>
                                        <span style="color:green;font-weight:bold;">Atendido</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endforeach; ?>
        <?php endif; ?>
    </main>
</body>
</html>
