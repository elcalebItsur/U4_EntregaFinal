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
            $detalle = $pdo->prepare('INSERT INTO detalle_venta (venta_id, producto_id, cantidad, precio_unitario, vendedor_id) VALUES (?, ?, ?, ?, ?)');
            $updateStock = $pdo->prepare('UPDATE productos SET stock = stock - ? WHERE id = ?');
            foreach ($productos as $prod) {
                // Obtener vendedor_id del producto
                $vendedor_id = null;
                $stmtV = $pdo->prepare('SELECT vendedor_id FROM productos WHERE id = ?');
                $stmtV->execute([$prod['producto_id']]);
                $vendedor_id = $stmtV->fetchColumn();
                $detalle->execute([$venta_id, $prod['producto_id'], $prod['cantidad'], $prod['precio'], $vendedor_id]);
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
    public static function marcarDetalleAtendido($detalle_id) {
        global $pdo;
        $stmt = $pdo->prepare('UPDATE detalle_venta SET atendido = TRUE WHERE id = ?');
        return $stmt->execute([$detalle_id]);
    }
    public static function obtenerDetallesVenta($venta_id) {
        global $pdo;
        $stmt = $pdo->prepare('SELECT * FROM detalle_venta WHERE venta_id = ?');
        $stmt->execute([$venta_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public static function obtenerVentasPorVendedorAgrupadas($vendedor_id) {
        global $pdo;
        // Paso 1: Obtener ventas únicas donde el vendedor participó
        $stmt = $pdo->prepare('SELECT DISTINCT v.id, v.fecha FROM ventas v JOIN detalle_venta dv ON v.id = dv.venta_id WHERE dv.vendedor_id = ? ORDER BY v.fecha DESC');
        $stmt->execute([$vendedor_id]);
        $ventas = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Paso 2: Para cada venta, obtener los productos vendidos por ese vendedor
        foreach ($ventas as &$venta) {
            $stmt2 = $pdo->prepare('SELECT * FROM detalle_venta WHERE venta_id = ? AND vendedor_id = ?');
            $stmt2->execute([$venta['id'], $vendedor_id]);
            $venta['detalles'] = $stmt2->fetchAll(PDO::FETCH_ASSOC);
        }
        return $ventas;
    }
    public static function obtenerDatosCompradorPorVenta($venta_id) {
        global $pdo;
        $stmt = $pdo->prepare('SELECT u.nombre, u.direccion FROM ventas v JOIN usuarios u ON v.usuario_id = u.id WHERE v.id = ?');
        $stmt->execute([$venta_id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    public static function obtenerNombreTiendaPorProducto($producto_id) {
        global $pdo;
        $stmt = $pdo->prepare('SELECT u.nombre_tienda FROM productos p JOIN usuarios u ON p.vendedor_id = u.id WHERE p.id = ?');
        $stmt->execute([$producto_id]);
        return $stmt->fetchColumn();
    }
}
