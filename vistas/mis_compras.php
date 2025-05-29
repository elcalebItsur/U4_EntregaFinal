<?php
session_start();
require_once '../datos/VentaDAO.php';
require_once '../datos/ProductoDAO.php';
if (!isset($_SESSION['usuario']) || $_SESSION['tipo'] !== 'Comprador') {
    header('Location: index.php');
    exit();
}
$usuario_id = $_SESSION['usuario_id'];
$ventas = VentaDAO::obtenerVentasPorUsuario($usuario_id);
?><!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <title>Mis Compras</title>
    <link rel="stylesheet" href="../css/main.css" />
    <link rel="stylesheet" href="../css/mis_compras.css" />
</head>
<body>
    <header>
        <div class="header-content">
            <h1 class="logo" onclick="location.href='index.php'">Textisur</h1>
        </div>
    </header>
    <main class="main-container">
        <h2 class="section-title">Mis Compras</h2>
        <?php if (empty($ventas)): ?>
            <div class="warning-label">No tienes compras registradas.</div>
        <?php else: ?>
        
            <?php foreach ($ventas as $venta): ?>
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
            <?php
                
                $detalles = VentaDAO::obtenerDetallesVenta($venta['id']);
                foreach ($detalles as $detalle):
                $producto = ProductoDAO::obtenerPorId($detalle['producto_id']);
                ?>
                <tr>
                    <td><?php echo htmlspecialchars($venta['fecha']); ?></td>
                    <td><?php echo htmlspecialchars($producto['nombre'] ?? ''); ?></td>
                    <td><?php echo htmlspecialchars($detalle['cantidad']); ?></td>
                    <td>$<?php echo number_format($detalle['precio_unitario'],2); ?></td>
                    <td>$<?php echo number_format($detalle['cantidad'] * $detalle['precio_unitario'],2); ?></td>
                </tr>
                <?php endforeach; ?>
                    </tbody>
                    </table>
            <?php endforeach; ?>
            
        <?php endif; ?>
    </main>
    <div style="text-align:center; margin: 2rem 0;">
        <a href="index.php" class="btn-primary" style="padding:0.7rem 2rem; font-size:1.1rem; border-radius:8px; text-decoration:none;">Seguir comprando</a>
    </div>
</body>
</html>
