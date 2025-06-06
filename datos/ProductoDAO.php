<?php
require_once 'conexion.php';
class ProductoDAO {
    public static function obtenerPorVendedor($vendedor_id) {
        global $pdo;
        $stmt = $pdo->prepare('SELECT * FROM productos WHERE vendedor_id = ?');
        $stmt->execute([$vendedor_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public static function obtenerPorId($id, $vendedor_id = null) {
        global $pdo;
        if ($vendedor_id === null) {
            $stmt = $pdo->prepare('SELECT * FROM productos WHERE id = ?');
            $stmt->execute([$id]);
        } else {
            $stmt = $pdo->prepare('SELECT * FROM productos WHERE id = ? AND vendedor_id = ?');
            $stmt->execute([$id, $vendedor_id]);
        }
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
    public static function obtenerTodos() {
        global $pdo;
        $stmt = $pdo->query('SELECT p.*, u.nombre_tienda FROM productos p LEFT JOIN usuarios u ON p.vendedor_id = u.id');
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public static function obtenerPorCategoria($categoria) {
        global $pdo;
        $stmt = $pdo->prepare('SELECT p.*, u.nombre_tienda FROM productos p LEFT JOIN usuarios u ON p.vendedor_id = u.id WHERE p.categoria = ?');
        $stmt->execute([$categoria]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public static function obtenerPopulares($limite = 6) {
        global $pdo;
        $stmt = $pdo->query('SELECT p.*, u.nombre_tienda FROM productos p LEFT JOIN usuarios u ON p.vendedor_id = u.id ORDER BY RANDOM() LIMIT ' . intval($limite));
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public static function agregarCompleto($nombre, $descripcion, $precio, $categoria, $stock, $imagen, $vendedor_id) {
        global $pdo;
        $stmt = $pdo->prepare('INSERT INTO productos (nombre, descripcion, precio, categoria, stock, imagen, vendedor_id) VALUES (?, ?, ?, ?, ?, ?, ?)');
        return $stmt->execute([$nombre, $descripcion, $precio, $categoria, $stock, $imagen, $vendedor_id]);
    }
    public static function actualizarCompleto($id, $nombre, $descripcion, $precio, $categoria, $stock, $imagen, $vendedor_id) {
        global $pdo;
        $stmt = $pdo->prepare('UPDATE productos SET nombre=?, descripcion=?, precio=?, categoria=?, stock=?, imagen=? WHERE id=? AND vendedor_id=?');
        return $stmt->execute([$nombre, $descripcion, $precio, $categoria, $stock, $imagen, $id, $vendedor_id]);
    }
}
