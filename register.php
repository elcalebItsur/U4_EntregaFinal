<?php
// Procesa el registro de usuario y guarda en usuarios.txt
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = trim($_POST['nombre'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = trim($_POST['password'] ?? '');
    $telefono = trim($_POST['telefono'] ?? '');
    $genero = trim($_POST['genero'] ?? '');
    $fecha_nacimiento = trim($_POST['fecha_nacimiento'] ?? '');
    $tipo = trim($_POST['tipo'] ?? '');
    $nombre_tienda = trim($_POST['nombre_tienda'] ?? '');
    $rfc = trim($_POST['rfc'] ?? '');
    $direccion = trim($_POST['direccion'] ?? '');

    if ($nombre && $email && $password && $telefono) {
        // Guardar todos los campos, separados por |
        $linea = "$nombre|$email|$password|$telefono|$genero|$fecha_nacimiento|$tipo|$nombre_tienda|$rfc|$direccion\n";
        file_put_contents('usuarios.txt', $linea, FILE_APPEND);
        header('Location: login.html?registro=exitoso');
        exit();
    } else {
        header('Location: register.html?error=campos');
        exit();
    }
}
?>