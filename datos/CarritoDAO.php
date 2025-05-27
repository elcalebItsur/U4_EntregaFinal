<?php
require_once 'conexion.php';
class CarritoDAO {
    public static function agregarProducto($usuario_id, $producto_id, $cantidad = 1) {
        global $pdo;
        // Si ya existe, suma cantidad
        $stmt = $pdo->prepare('SELECT cantidad FROM carrito WHERE usuario_id = ? AND producto_id = ?');
        $stmt->execute([$usuario_id, $producto_id]);
        $row = $stmt->fetch();
        if ($row) {
            $nuevaCantidad = $row['cantidad'] + $cantidad;
            $update = $pdo->prepare('UPDATE carrito SET cantidad = ? WHERE usuario_id = ? AND producto_id = ?');
            $update->execute([$nuevaCantidad, $usuario_id, $producto_id]);
        } else {
            $insert = $pdo->prepare('INSERT INTO carrito (usuario_id, producto_id, cantidad) VALUES (?, ?, ?)');
            $insert->execute([$usuario_id, $producto_id, $cantidad]);
        }
    }
    public static function obtenerCarrito($usuario_id) {
        global $pdo;
        $stmt = $pdo->prepare('SELECT c.producto_id, c.cantidad, p.nombre, p.precio, p.imagen, p.stock FROM carrito c JOIN productos p ON c.producto_id = p.id WHERE c.usuario_id = ?');
        $stmt->execute([$usuario_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public static function eliminarProducto($usuario_id, $producto_id) {
        global $pdo;
        $stmt = $pdo->prepare('DELETE FROM carrito WHERE usuario_id = ? AND producto_id = ?');
        $stmt->execute([$usuario_id, $producto_id]);
    }
    public static function vaciarCarrito($usuario_id) {
        global $pdo;
        $stmt = $pdo->prepare('DELETE FROM carrito WHERE usuario_id = ?');
        $stmt->execute([$usuario_id]);
    }
}
