<?php
require_once 'conexion.php';
class ProductoDAO {
    public static function obtenerPorVendedor($vendedor_id) {
        global $pdo;
        $stmt = $pdo->prepare('SELECT * FROM productos WHERE vendedor_id = ?');
        $stmt->execute([$vendedor_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public static function obtenerPorId($id, $vendedor_id) {
        global $pdo;
        $stmt = $pdo->prepare('SELECT * FROM productos WHERE id = ? AND vendedor_id = ?');
        $stmt->execute([$id, $vendedor_id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    public static function actualizar($id, $nombre, $precio, $categoria, $stock, $vendedor_id) {
        global $pdo;
        $stmt = $pdo->prepare('UPDATE productos SET nombre=?, precio=?, categoria=?, stock=? WHERE id=? AND vendedor_id=?');
        return $stmt->execute([$nombre, $precio, $categoria, $stock, $id, $vendedor_id]);
    }
    public static function agregar($nombre, $precio, $categoria, $stock, $vendedor_id) {
        global $pdo;
        $stmt = $pdo->prepare('INSERT INTO productos (nombre, precio, categoria, stock, vendedor_id) VALUES (?, ?, ?, ?, ?)');
        return $stmt->execute([$nombre, $precio, $categoria, $stock, $vendedor_id]);
    }
    public static function eliminar($id, $vendedor_id) {
        global $pdo;
        $stmt = $pdo->prepare('DELETE FROM productos WHERE id = ? AND vendedor_id = ?');
        return $stmt->execute([$id, $vendedor_id]);
    }
}
