<?php
require_once 'conexion.php';
class UsuarioDAO {
    public static function obtenerPorId($id) {
        global $pdo;
        $stmt = $pdo->prepare('SELECT * FROM usuarios WHERE id = ?');
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    public static function actualizarPerfil($id, $nombre, $email, $telefono, $fecha_nacimiento, $foto_perfil) {
        global $pdo;
        $stmt = $pdo->prepare('UPDATE usuarios SET nombre=?, email=?, telefono=?, fecha_nacimiento=?, foto_perfil=? WHERE id=?');
        return $stmt->execute([$nombre, $email, $telefono, $fecha_nacimiento, $foto_perfil, $id]);
    }
    public static function registrar($data) {
        global $pdo;
        $stmt = $pdo->prepare('INSERT INTO usuarios (nombre, email, password, telefono, genero, fecha_nacimiento, tipo, nombre_tienda, rfc, direccion, foto_perfil) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)');
        return $stmt->execute([
            $data['nombre'], $data['email'], $data['password'], $data['telefono'], $data['genero'],
            $data['fecha_nacimiento'], $data['tipo'], $data['nombre_tienda'], $data['rfc'], $data['direccion'], $data['foto_perfil']
        ]);
    }
    public static function obtenerPorEmail($email) {
        global $pdo;
        $stmt = $pdo->prepare('SELECT * FROM usuarios WHERE email = ?');
        $stmt->execute([$email]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
