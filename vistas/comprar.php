<?php
session_start();
require_once '../datos/ProductoDAO.php';
require_once '../datos/VentaDAO.php';
header('Content-Type: application/json');

if (!isset($_SESSION['usuario_id'])) {
    echo json_encode(['error' => 'Debes iniciar sesión para comprar.']);
    exit();
}

$usuario_id = $_SESSION['usuario_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $producto_id = intval($_POST['producto_id'] ?? 0);
    $cantidad = intval($_POST['cantidad'] ?? 1);
    if ($producto_id <= 0 || $cantidad <= 0) {
        echo json_encode(['error' => 'Datos inválidos.']);
        exit();
    }
    $producto = ProductoDAO::obtenerPorId($producto_id, null);
    if (!$producto) {
        echo json_encode(['error' => 'Producto no encontrado.']);
        exit();
    }
    if ($producto['stock'] < $cantidad) {
        echo json_encode(['error' => 'No hay suficiente stock disponible.']);
        exit();
    }
    $venta = [
        [
            'producto_id' => $producto_id,
            'cantidad' => $cantidad,
            'precio' => $producto['precio']
        ]
    ];
    try {
        $venta_id = VentaDAO::registrarVenta($usuario_id, $venta);
        echo json_encode(['success' => true, 'venta_id' => $venta_id]);
    } catch (Exception $e) {
        echo json_encode(['error' => 'Error al registrar la venta: ' . $e->getMessage()]);
    }
    exit();
}
echo json_encode(['error' => 'Método no permitido.']);
