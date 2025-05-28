<?php
session_start();
require_once '../datos/conexion.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = trim($_POST['password'] ?? '');
    $mensaje = '';
    if ($email && $password) {
        $stmt = $pdo->prepare('SELECT * FROM usuarios WHERE email = ?');
        $stmt->execute([$email]);
        $usuario = $stmt->fetch();
        if ($usuario && password_verify($password, $usuario['password'])) {
            $_SESSION['usuario'] = $usuario['nombre'];
            $_SESSION['email'] = $usuario['email'];
            $_SESSION['tipo'] = $usuario['tipo'];
            $_SESSION['usuario_id'] = $usuario['id'];
            header('Location: index.php');
            exit();
        } else {
            $mensaje = 'Correo o contraseña incorrectos.';
        }
    } else {
        $mensaje = 'Completa todos los campos.';
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Iniciar Sesión - Textisur</title>
    <link rel="stylesheet" href="../css/main.css" />
    <link rel="stylesheet" href="../css/auth.css" />
    <link rel="stylesheet" href="../css/login.css" />
    <script src="../js/login.js" defer></script>
    <script src="../js/login-menu.js" defer></script>
</head>
<body>
    <header>
        <div class="header-content">
            <h1 class="logo" onclick="location.href='../vistas/index.php'">Textisur</h1>
            <nav>
                <ul>
                    <li><a href="../index.php">Inicio</a></li>
                    <li><a href="about.php">Vende</a></li>
                    <li><a href="acerca.php" class="btn-accent">Acerca de</a></li>
                    <?php if (isset($_SESSION['usuario'])): ?>
                        <?php if (isset($_SESSION['tipo']) && $_SESSION['tipo'] === 'Vendedor'): ?>
                            <li><a href="admin_tienda.php" class="btn-primary">Administrar Tienda</a></li>
                        <?php endif; ?>
                        <li class="user-menu">
                            <button class="user-btn" id="user-menu-btn">
                                <i class="fas fa-user-circle"></i>
                                <?php echo htmlspecialchars($_SESSION['usuario']); ?>
                                <i class="fas fa-chevron-down chevron-icon"></i>
                            </button>
                            <div class="user-dropdown" id="user-dropdown">
                                <div class="user-info">
                                    <?php echo htmlspecialchars($_SESSION['usuario']); ?>
                                    <small class="user-email"> <?php echo htmlspecialchars($_SESSION['email']); ?> </small>
                                </div>
                                <a href="perfil.php"><i class="fas fa-user"></i> Mi perfil</a>
                                <?php if (isset($_SESSION['tipo']) && $_SESSION['tipo'] === 'Vendedor'): ?>
                                    <a href="mis_ventas.php"><i class="fas fa-box"></i> Mis ventas</a>
                                <?php else: ?>
                                    <a href="mis_compras.php"><i class="fas fa-box"></i> Mis compras</a>
                                <?php endif; ?>
                                <div class="divider"></div>
                                <a href="../logout.php"><i class="fas fa-sign-out-alt"></i> Cerrar sesión</a>
                            </div>
                        </li>
                    <?php else: ?>
                        <li><a href="login.php" class="btn-primary">Iniciar Sesión</a></li>
                        <li><a href="register.php" class="btn-secondary">Registrarse</a></li>
                    <?php endif; ?>
                </ul>
            </nav>
        </div>
    </header>
    <main class="auth-container">
        <h2>Inicia Sesión</h2>
        <?php if (isset($mensaje)): ?>
            <div class="login-error">
                <?php echo htmlspecialchars($mensaje); ?>
            </div>
        <?php endif; ?>
        <form action="login.php" method="POST" onsubmit="return validarLogin(event)">
            <label>Correo electrónico:</label>
            <input type="email" name="email" id="email" required />
            <label>Contraseña:</label>
            <input type="password" name="password" id="password" required />
            <button class="btn-primary" type="submit">Entrar</button>
            <p>¿No tienes cuenta? <a href="register.php">Regístrate aquí</a></p>
        </form>
    </main>
    <footer>
        <p>&copy; 2025 TEXTISUR. Todos los derechos reservados.</p>
    </footer>
</body>
</html>