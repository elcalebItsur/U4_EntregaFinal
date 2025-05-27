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
    <style>
        .ventas-table { width:100%; border-collapse:collapse; margin-top:2rem; }
        .ventas-table th, .ventas-table td { border:1px solid #333; padding:0.7rem; text-align:center; }
        .ventas-table th { background:#232323; color:#44ff99; }
        .ventas-table tr:nth-child(even) { background:#232323; }
    </style>
</head>
<body>
    <header>
        <div class="header-content">
            <h1 class="logo" onclick="location.href='index.php'">Textisur</h1>
        </div>
    </header>
    <main style="max-width:900px;margin:2rem auto;">
        <h2 style="color:#44ff99;">Mis Compras</h2>
        <?php if (empty($ventas)): ?>
            <div style="color:#eab308;">No tienes compras registradas.</div>
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
            <?php endforeach; ?>
            </tbody>
        </table>
        <?php endif; ?>
    </main>
</body>
</html>
