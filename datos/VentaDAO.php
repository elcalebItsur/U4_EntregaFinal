<?php
require_once 'conexion.php';
class VentaDAO {
    public static function registrarVenta($usuario_id, $productos) {
        global $pdo;
        $pdo->beginTransaction();
        try {
            $stmt = $pdo->prepare('INSERT INTO ventas (usuario_id, fecha) VALUES (?, NOW()) RETURNING id');
            $stmt->execute([$usuario_id]);
            $venta_id = $stmt->fetchColumn();
            $detalle = $pdo->prepare('INSERT INTO detalle_venta (venta_id, producto_id, cantidad, precio_unitario) VALUES (?, ?, ?, ?)');
            $updateStock = $pdo->prepare('UPDATE productos SET stock = stock - ? WHERE id = ?');
            foreach ($productos as $prod) {
                $detalle->execute([$venta_id, $prod['producto_id'], $prod['cantidad'], $prod['precio']]);
                $updateStock->execute([$prod['cantidad'], $prod['producto_id']]);
            }
            $pdo->commit();
            return $venta_id;
        } catch (Exception $e) {
            $pdo->rollBack();
            throw $e;
        }
    }
    public static function obtenerVentasPorUsuario($usuario_id) {
        global $pdo;
        $stmt = $pdo->prepare('SELECT * FROM ventas WHERE usuario_id = ? ORDER BY fecha DESC');
        $stmt->execute([$usuario_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public static function obtenerVentasPorVendedor($vendedor_id) {
        global $pdo;
        $stmt = $pdo->prepare('SELECT dv.*, v.fecha FROM detalle_venta dv JOIN ventas v ON dv.venta_id = v.id JOIN productos p ON dv.producto_id = p.id WHERE p.vendedor_id = ? ORDER BY v.fecha DESC');
        $stmt->execute([$vendedor_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
