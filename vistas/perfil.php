<?php
session_start();
require_once '../datos/UsuarioDAO.php';
if (!isset($_SESSION['usuario'])) {
    header('Location: login.php');
    exit();
}
$mensaje = '';
$usuario_id = $_SESSION['usuario_id'];
// Obtener datos actuales del usuario
$usuario = UsuarioDAO::obtenerPorId($usuario_id);
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = trim($_POST['nombre'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $telefono = trim($_POST['telefono'] ?? '');
    $fecha_nacimiento = trim($_POST['fecha_nacimiento'] ?? '');
    $foto_perfil = $usuario['foto_perfil'];
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
    UsuarioDAO::actualizarPerfil($usuario_id, $nombre, $email, $telefono, $fecha_nacimiento, $foto_perfil);
    $_SESSION['usuario'] = $nombre;
    $_SESSION['email'] = $email;
    $mensaje = 'Perfil actualizado correctamente';
    // Refrescar datos
    $usuario = UsuarioDAO::obtenerPorId($usuario_id);
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <!-- Mismo head que las otras páginas -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mi Perfil</title>
    <link rel="stylesheet" href="../css/perfil.css">
    <link rel="stylesheet" href="../css/main-header.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
</head>
<body>
    <nav class="main-navbar">
        <ul class="nav-list">
            <li><a href="index.php" class="nav-btn"><i class="fas fa-home"></i> Inicio</a></li>
            <li><a href="about.php" class="nav-btn"><i class="fas fa-store"></i> Vende</a></li>
            <li><a href="acerca.php" class="nav-btn btn-accent"><i class="fas fa-info-circle"></i> Acerca de</a></li>
            <?php if (isset($_SESSION['usuario'])): ?>
                <?php if (isset($_SESSION['tipo']) && $_SESSION['tipo'] === 'Vendedor'): ?>
                    <li><a href="admin_tienda.php" class="nav-btn btn-primary"><i class="fas fa-cogs"></i> Administrar Tienda</a></li>
                <?php endif; ?>
                <li class="user-menu">
                    <button class="user-btn" id="user-menu-btn">
                        <span class="user-avatar-nav">
                            <?php if (!empty($usuario['foto_perfil'])): ?>
                                <img src="../assets/images/<?php echo htmlspecialchars($usuario['foto_perfil']); ?>" alt="Avatar" />
                            <?php else: ?>
                                <?php echo strtoupper(substr($_SESSION['usuario'], 0, 1)); ?>
                            <?php endif; ?>
                        </span>
                        <?php echo htmlspecialchars($_SESSION['usuario']); ?>
                        <i class="fas fa-chevron-down" style="font-size:0.8rem;"></i>
                    </button>
                    <div class="user-dropdown" id="user-dropdown">
                        <div class="user-info">
                            <span class="user-avatar-dropdown">
                                <?php if (!empty($usuario['foto_perfil'])): ?>
                                    <img src="../assets/images/<?php echo htmlspecialchars($usuario['foto_perfil']); ?>" alt="Avatar" />
                                <?php else: ?>
                                    <?php echo strtoupper(substr($_SESSION['usuario'], 0, 1)); ?>
                                <?php endif; ?>
                            </span>
                            <?php echo htmlspecialchars($_SESSION['usuario']); ?>
                            <small style="display:block;color:#aaa;"> <?php echo htmlspecialchars($_SESSION['email']); ?> </small>
                        </div>
                        <a href="perfil.php"><i class="fas fa-user"></i> Mi perfil</a>
                        <?php if ($_SESSION['tipo'] === 'Vendedor'): ?>
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
    <main class="profile-container">
        <div class="profile-header">
            <div class="profile-avatar">
                <?php if (!empty($usuario['foto_perfil'])): ?>
                    <img src="../assets/images/<?php echo htmlspecialchars($usuario['foto_perfil']); ?>" alt="Foto de perfil" />
                <?php else: ?>
                    <?php echo strtoupper(substr($_SESSION['usuario'], 0, 1)); ?>
                <?php endif; ?>
            </div>
            <div>
                <h2 class="profile-title">Mi Perfil</h2>
                <p class="profile-email"><?php echo htmlspecialchars($_SESSION['email']); ?></p>
                <p class="profile-type"><?php echo htmlspecialchars($_SESSION['tipo']); ?></p>
            </div>
        </div>
        <?php if (isset($mensaje)): ?>
            <div class="profile-message">
                <?php echo htmlspecialchars($mensaje); ?>
            </div>
        <?php endif; ?>
        <form method="POST" class="profile-form" enctype="multipart/form-data">
            <div class="form-group">
                <label>Nombre completo</label>
                <input type="text" name="nombre" value="<?php echo htmlspecialchars($_SESSION['usuario']); ?>" required>
            </div>
            <div class="form-group">
                <label>Correo electrónico</label>
                <input type="email" name="email" value="<?php echo htmlspecialchars($_SESSION['email']); ?>" required>
            </div>
            <div class="form-group">
                <label>Teléfono</label>
                <input type="tel" name="telefono" value="<?php echo htmlspecialchars($usuario['telefono'] ?? ''); ?>">
            </div>
            <div class="form-group">
                <label>Fecha de nacimiento</label>
                <input type="date" name="fecha_nacimiento" value="<?php echo htmlspecialchars($usuario['fecha_nacimiento'] ?? ''); ?>">
            </div>
            <div class="form-group full-width">
                <label>Acerca de mí</label>
                <textarea name="bio" rows="3" disabled style="resize:vertical;">(Próximamente)</textarea>
            </div>
            <div class="form-group full-width">
                <label>Cambiar foto de perfil</label>
                <input type="file" name="foto_perfil" accept="image/*" />
            </div>
            <div class="form-group full-width profile-actions">
                <button type="submit" class="btn-primary">Guardar cambios</button>
                <a href="cambiar-password.php" class="btn-secondary">Cambiar contraseña</a>
            </div>
        </form>
    </main>
    <script>
    // Dropdown user menu
    document.addEventListener('DOMContentLoaded', function() {
        const btn = document.getElementById('user-menu-btn');
        const dropdown = document.getElementById('user-dropdown');
        if(btn && dropdown) {
            btn.addEventListener('click', function(e) {
                e.stopPropagation();
                dropdown.classList.toggle('show');
            });
            document.addEventListener('click', function() {
                dropdown.classList.remove('show');
            });
        }
    });
    </script>
    <!-- Mismo footer que las otras páginas -->
</body>
</html>