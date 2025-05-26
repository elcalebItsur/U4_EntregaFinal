<?php
session_start();
require_once '../datos/conexion.php';
if (!isset($_SESSION['usuario'])) {
    header('Location: login.php');
    exit();
}
$mensaje = '';
$usuario_id = $_SESSION['usuario_id'];
// Obtener datos actuales del usuario
$stmt = $pdo->prepare('SELECT * FROM usuarios WHERE id = ?');
$stmt->execute([$usuario_id]);
$usuario = $stmt->fetch(PDO::FETCH_ASSOC);
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
    $stmt = $pdo->prepare('UPDATE usuarios SET nombre = ?, email = ?, telefono = ?, fecha_nacimiento = ?, foto_perfil = ? WHERE id = ?');
    $stmt->execute([$nombre, $email, $telefono, $fecha_nacimiento, $foto_perfil, $usuario_id]);
    $_SESSION['usuario'] = $nombre;
    $_SESSION['email'] = $email;
    $mensaje = 'Perfil actualizado correctamente';
    // Refrescar datos
    $stmt = $pdo->prepare('SELECT * FROM usuarios WHERE id = ?');
    $stmt->execute([$usuario_id]);
    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <!-- Mismo head que las otras páginas -->
    <style>
        .profile-container {
            max-width: 800px;
            margin: 2rem auto;
            background: #1e1e1e;
            border-radius: 14px;
            padding: 2rem;
            box-shadow: 0 4px 16px #0002;
            animation: fadeInUp 0.7s cubic-bezier(.4,2,.3,1);
        }
        
        .profile-header {
            display: flex;
            align-items: center;
            gap: 1.5rem;
            margin-bottom: 2rem;
        }
        
        .profile-avatar {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            background: #44ff99;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2.5rem;
            color: #181818;
            font-weight: bold;
        }
        
        .profile-form {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1.5rem;
        }
        
        .form-group {
            margin-bottom: 1rem;
        }
        
        .form-group.full-width {
            grid-column: span 2;
        }
    </style>
</head>
<body>
    <!-- Mismo header que las otras páginas -->
    
    <main class="profile-container">
        <div class="profile-header">
            <div class="profile-avatar">
                <?php if (!empty($usuario['foto_perfil'])): ?>
                    <img src="../assets/images/<?php echo htmlspecialchars($usuario['foto_perfil']); ?>" alt="Foto de perfil" style="width:100px;height:100px;border-radius:50%;object-fit:cover;" />
                <?php else: ?>
                    <?php echo strtoupper(substr($_SESSION['usuario'], 0, 1)); ?>
                <?php endif; ?>
            </div>
            <div>
                <h2 style="color:#44ff99;">Mi Perfil</h2>
                <p><?php echo htmlspecialchars($_SESSION['email']); ?></p>
                <p style="color:#aaa;"><?php echo htmlspecialchars($_SESSION['tipo']); ?></p>
            </div>
        </div>
        
        <?php if (isset($mensaje)): ?>
            <div style="background:#232323;color:#44ff99;padding:1rem;border-radius:8px;margin-bottom:1.5rem;">
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
                <input type="tel" name="telefono">
            </div>
            
            <div class="form-group">
                <label>Fecha de nacimiento</label>
                <input type="date" name="fecha_nacimiento">
            </div>
            
            <div class="form-group full-width">
                <label>Acerca de mí</label>
                <textarea name="bio" rows="3"></textarea>
            </div>
            
            <div class="form-group full-width">
                <label>Cambiar foto de perfil</label>
                <input type="file" name="foto_perfil" accept="image/*" />
            </div>
            
            <div class="form-group full-width" style="margin-top:1rem;">
                <button type="submit" class="btn-primary">Guardar cambios</button>
                <a href="cambiar-password.php" class="btn-secondary" style="margin-left:1rem;">Cambiar contraseña</a>
            </div>
        </form>
    </main>
    
    <!-- Mismo footer que las otras páginas -->
</body>
</html>