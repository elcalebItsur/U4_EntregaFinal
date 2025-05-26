<?php
session_start();
require_once '../datos/conexion.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = trim($_POST['nombre'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = password_hash(trim($_POST['password'] ?? ''), PASSWORD_DEFAULT);
    $telefono = trim($_POST['telefono'] ?? '');
    $genero = trim($_POST['genero'] ?? '');
    $fecha_nacimiento = trim($_POST['fecha_nacimiento'] ?? '');
    $tipo = trim($_POST['tipo'] ?? '');
    $nombre_tienda = trim($_POST['nombre_tienda'] ?? '');
    $rfc = trim($_POST['rfc'] ?? '');
    $direccion = trim($_POST['direccion'] ?? '');
    $foto_perfil = null;
    $mensaje = '';
    // Manejo de la imagen de perfil
    if (isset($_FILES['foto_perfil']) && $_FILES['foto_perfil']['error'] === UPLOAD_ERR_OK) {
        $tmp_name = $_FILES['foto_perfil']['tmp_name'];
        $original_name = basename($_FILES['foto_perfil']['name']);
        $ext = strtolower(pathinfo($original_name, PATHINFO_EXTENSION));
        $allowed = ['jpg', 'jpeg', 'png', 'webp'];
        if (in_array($ext, $allowed)) {
            $new_name = uniqid('perfil_') . '.' . $ext;
            $destino = '../assets/images/' . $new_name;
            if (move_uploaded_file($tmp_name, $destino)) {
                $foto_perfil = $new_name;
            }
        }
    }
    $stmt = $pdo->prepare('SELECT id FROM usuarios WHERE email = ?');
    $stmt->execute([$email]);
    if ($stmt->fetch()) {
        $mensaje = 'El correo ya está registrado.';
    } else {
        $stmt = $pdo->prepare('INSERT INTO usuarios (nombre, email, password, telefono, genero, fecha_nacimiento, tipo, nombre_tienda, rfc, direccion, foto_perfil) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)');
        $stmt->execute([$nombre, $email, $password, $telefono, $genero, $fecha_nacimiento, $tipo, $nombre_tienda, $rfc, $direccion, $foto_perfil]);
        $mensaje = '¡Registro exitoso! Ahora puedes iniciar sesión.';
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Registro - Textisur</title>
    <link rel="stylesheet" href="../css/main.css" />
    <link rel="stylesheet" href="../css/auth.css" />
    <script src="../js/register.js" defer></script>
    <style>
        header { background: #181818; box-shadow: none; border-bottom: 1.5px solid #232323; }
        .header-content { display: flex; align-items: center; justify-content: space-between; max-width: 1200px; margin: 0 auto; padding: 1.2rem 2rem; }
        .logo { font-size:2rem; color:#44ff99; font-weight:700; letter-spacing:1px; cursor:pointer; margin-right:2rem; }
        nav ul { display: flex; gap: 1.2rem; align-items: center; list-style: none; }
        nav ul li { display: flex; align-items: center; }
        nav ul li a, nav ul li span { font-size: 1rem; }
    </style>
</head>
<body>
    <!-- Reemplazar solo la parte del header (desde <header> hasta </header>) con este código: -->
<header>
    <div class="header-content">
        <h1 class="logo" onclick="location.href='../index.php'">Textisur</h1>
        <nav>
            <ul>
                <li><a href="../index.php">Inicio</a></li>
                <li><a href="cart.php">Carrito</a></li>
                <li><a href="favorites.php">Favoritos</a></li>
                <li><a href="about.php">Vende</a></li>
                <li><a href="acerca.php" class="btn-accent">Acerca de</a></li>
                <?php if (isset($_SESSION['usuario'])): ?>
                    <li class="user-menu">
                        <button class="user-btn" id="user-menu-btn">
                            <i class="fas fa-user-circle"></i>
                            <?php echo htmlspecialchars($_SESSION['usuario']); ?>
                            <i class="fas fa-chevron-down" style="font-size:0.8rem;"></i>
                        </button>
                        <div class="user-dropdown" id="user-dropdown">
                            <div class="user-info">
                                <?php echo htmlspecialchars($_SESSION['usuario']); ?>
                                <small style="display:block;color:#aaa;"><?php echo htmlspecialchars($_SESSION['email']); ?></small>
                            </div>
                            <a href="perfil.php"><i class="fas fa-user"></i> Mi perfil</a>
                            <a href="direcciones.php"><i class="fas fa-map-marker-alt"></i> Mis direcciones</a>
                            <a href="pedidos.php"><i class="fas fa-box"></i> Mis pedidos</a>
                            <a href="favorites.php"><i class="fas fa-heart"></i> Favoritos</a>
                            <div class="divider"></div>
                            <a href="../logout.php"><i class="fas fa-sign-out-alt"></i> Cerrar sesión</a>
                        </div>
                    </li>
                <?php else: ?>
                    <li><a href="login.php" class="btn-secondary">Iniciar Sesión</a></li>
                <?php endif; ?>
            </ul>
        </nav>
    </div>
</header>
    <main class="auth-container">
        <h2>Crea tu cuenta</h2>
        <?php if (isset($mensaje)): ?>
            <div style="background:#232323;color:#44ff99;padding:1rem;border-radius:8px;margin-bottom:1rem;text-align:center;">
                <?php echo htmlspecialchars($mensaje); ?>
            </div>
        <?php endif; ?>
        <form action="register.php" method="POST" enctype="multipart/form-data" onsubmit="return validarRegistro(event)">
            <label>Nombre completo:</label>
            <input type="text" name="nombre" id="nombre" required />
            <label>Correo electrónico:</label>
            <input type="email" name="email" id="email" required />
            <label>Contraseña:</label>
            <input type="password" name="password" id="password" required minlength="6" />
            <label>Teléfono:</label>
            <input type="tel" name="telefono" id="telefono" required pattern="[0-9]{10,15}" />
            <label>Género:</label>
            <select name="genero" id="genero">
                <option value="">Prefiero no decirlo</option>
                <option value="Masculino">Masculino</option>
                <option value="Femenino">Femenino</option>
                <option value="Otro">Otro</option>
            </select>
            <label>Fecha de nacimiento:</label>
            <input type="date" name="fecha_nacimiento" id="fecha_nacimiento" required />
            <label>Tipo de usuario:</label>
            <select name="tipo" id="tipo" onchange="mostrarCamposVendedor()">
                <option value="Comprador">Comprador</option>
                <option value="Vendedor">Vendedor</option>
            </select>
            <div id="campos-vendedor" style="display:none">
                <label>Nombre de tienda:</label>
                <input type="text" name="nombre_tienda" id="nombre_tienda" />
                <label>RFC:</label>
                <input type="text" name="rfc" id="rfc" />
            </div>
            <div id="campos-comprador">
                <label>Dirección:</label>
                <input type="text" name="direccion" id="direccion" />
            </div>
            <label>Foto de perfil (opcional):</label>
            <input type="file" name="foto_perfil" accept="image/*" />
            <button class="btn-primary" type="submit">Registrarse</button>
            <p>¿Ya tienes cuenta? <a href="login.php">Inicia sesión</a></p>
        </form>
    </main>
    <footer>
        <p>&copy; 2025 TEXTISUR. Todos los derechos reservados.</p>
    </footer>
</body>
</html>