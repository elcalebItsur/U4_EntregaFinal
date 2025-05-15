<?php
// Procesa el login de usuario verificando en usuarios.txt
session_start();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = trim($_POST['password'] ?? '');
    $encontrado = false;
    if ($email && $password && file_exists('usuarios.txt')) {
        $usuarios = file('usuarios.txt');
        foreach ($usuarios as $usuario) {
            list($nombre, $correo, $pass) = explode('|', trim($usuario));
            if ($correo === $email && $pass === $password) {
                $_SESSION['usuario'] = $nombre;
                $encontrado = true;
                break;
            }
        }
    }
    if ($encontrado) {
        header('Location: index.php');
        exit();
    } else {
        header('Location: login.html?error=credenciales');
        exit();
    }
}
?>