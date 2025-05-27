<?php
require_once 'conexion.php';
class FavoritosDAO {
    public static function agregar($usuario_id, $producto_id) {
        global $pdo;
        $stmt = $pdo->prepare('INSERT INTO favoritos (usuario_id, producto_id) VALUES (?, ?) ON CONFLICT DO NOTHING');
        return $stmt->execute([$usuario_id, $producto_id]);
    }
    public static function eliminar($usuario_id, $producto_id) {
        global $pdo;
        $stmt = $pdo->prepare('DELETE FROM favoritos WHERE usuario_id = ? AND producto_id = ?');
        return $stmt->execute([$usuario_id, $producto_id]);
    }
    public static function existe($usuario_id, $producto_id) {
        global $pdo;
        $stmt = $pdo->prepare('SELECT 1 FROM favoritos WHERE usuario_id = ? AND producto_id = ?');
        $stmt->execute([$usuario_id, $producto_id]);
        return $stmt->fetchColumn() !== false;
    }
    public static function obtenerFavoritos($usuario_id) {
        global $pdo;
        $stmt = $pdo->prepare('SELECT producto_id FROM favoritos WHERE usuario_id = ?');
        $stmt->execute([$usuario_id]);
        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }
}
