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
    <script src="../js/login.js" defer></script>
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
            <div style="background:#232323;color:#ff6b6b;padding:1rem;border-radius:8px;margin-bottom:1rem;text-align:center;">
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
    <script>
    // Control del menú de usuario
    document.addEventListener('DOMContentLoaded', function() {
        const userBtn = document.getElementById('user-menu-btn');
        const userDropdown = document.getElementById('user-dropdown');
        
        if (userBtn && userDropdown) {
            userBtn.addEventListener('click', function(e) {
                e.stopPropagation();
                userDropdown.classList.toggle('show');
            });
            
            // Cerrar al hacer clic fuera
            document.addEventListener('click', function() {
                userDropdown.classList.remove('show');
            });
        }
    });
    </script>
</body>
</html>