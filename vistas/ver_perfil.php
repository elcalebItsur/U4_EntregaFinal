<?php
session_start();
require_once '../datos/conexion.php';
if (!isset($_SESSION['usuario'])) {
    header('Location: login.php');
    exit();
}
$usuario_id = $_SESSION['usuario_id'];
$stmt = $pdo->prepare('SELECT * FROM usuarios WHERE id = ?');
$stmt->execute([$usuario_id]);
$usuario = $stmt->fetch(PDO::FETCH_ASSOC);
$productos = [];
if ($usuario['tipo'] === 'Vendedor') {
    $stmt = $pdo->prepare('SELECT * FROM productos WHERE vendedor_id = ?');
    $stmt->execute([$usuario_id]);
    $productos = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <title>Perfil de <?php echo htmlspecialchars($usuario['nombre']); ?></title>
    <link rel="stylesheet" href="../css/main.css" />
    <link rel="stylesheet" href="../css/home.css" />
    <style>
        .perfil-main { max-width: 900px; margin: 2.5rem auto; background: #1e1e1e; border-radius: 14px; padding: 2.5rem 2rem; box-shadow: 0 4px 16px #0002; animation: fadeInUp 0.7s cubic-bezier(.4,2,.3,1); }
        .perfil-header { display: flex; align-items: center; gap: 2rem; margin-bottom: 2.5rem; }
        .perfil-avatar { width: 100px; height: 100px; border-radius: 50%; background: #44ff99; display: flex; align-items: center; justify-content: center; font-size: 2.5rem; color: #181818; font-weight: bold; box-shadow: 0 2px 8px #0003; }
        .perfil-avatar img { width: 100px; height: 100px; border-radius: 50%; object-fit: cover; }
        .perfil-info { flex:1; }
        .perfil-info h2 { color: #44ff99; margin-bottom: 0.5rem; }
        .perfil-info p { color: #aaa; margin-bottom: 0.2rem; }
        .productos-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(250px, 1fr)); gap: 1.5rem; margin-top: 2rem; }
        .producto-card { background: #232323; border-radius: 12px; padding: 1.2rem; box-shadow: 0 2px 8px #0002; display: flex; flex-direction: column; align-items: center; transition: box-shadow 0.2s; }
        .producto-card img { width: 100%; max-width: 180px; border-radius: 8px; margin-bottom: 1rem; aspect-ratio: 1/1; object-fit: cover; }
        .producto-card h3 { color: #fff; margin-bottom: 0.5rem; font-size: 1.1rem; }
        .producto-card .precio { color: #44ff99; font-weight: bold; margin-bottom: 0.5rem; }
        .producto-card p { color: #b0b0b0; font-size: 0.98rem; margin-bottom: 0.5rem; }
    </style>
</head>
<body>
<?php include 'index.php'; // Header global ?>
<main class="perfil-main">
    <div class="perfil-header">
        <div class="perfil-avatar">
            <?php if (!empty($usuario['foto_perfil'])): ?>
                <img src="../assets/images/<?php echo htmlspecialchars($usuario['foto_perfil']); ?>" alt="Foto de perfil" />
            <?php else: ?>
                <?php echo strtoupper(substr($usuario['nombre'], 0, 1)); ?>
            <?php endif; ?>
        </div>
        <div class="perfil-info">
            <h2><?php echo htmlspecialchars($usuario['nombre']); ?></h2>
            <p><strong>Email:</strong> <?php echo htmlspecialchars($usuario['email']); ?></p>
            <p><strong>Tipo:</strong> <?php echo htmlspecialchars($usuario['tipo']); ?></p>
            <?php if ($usuario['tipo'] === 'Vendedor'): ?>
                <p><strong>Tienda:</strong> <?php echo htmlspecialchars($usuario['nombre_tienda']); ?></p>
                <p><strong>RFC:</strong> <?php echo htmlspecialchars($usuario['rfc']); ?></p>
            <?php else: ?>
                <p><strong>Dirección:</strong> <?php echo htmlspecialchars($usuario['direccion']); ?></p>
            <?php endif; ?>
        </div>
    </div>
    <?php if ($usuario['tipo'] === 'Vendedor'): ?>
        <h3 style="color:#eab308;margin-bottom:1rem;">Mis productos</h3>
        <div class="productos-grid">
            <?php foreach ($productos as $prod): ?>
                <div class="producto-card">
                    <?php
                    $img = '../assets/images/hero_image.jpg';
                    if (!empty($prod['imagen']) && file_exists(__DIR__ . '/../assets/images/' . $prod['imagen'])) {
                        $img = '../assets/images/' . $prod['imagen'];
                    }
                    ?>
                    <img src="<?php echo $img; ?>" alt="<?php echo htmlspecialchars($prod['nombre']); ?>" />
                    <h3><?php echo htmlspecialchars($prod['nombre']); ?></h3>
                    <p><?php echo htmlspecialchars($prod['descripcion']); ?></p>
                    <div class="precio">$<?php echo number_format($prod['precio'],2); ?></div>
                    <div style="color:#eab308;font-size:0.95rem;">Stock: <?php echo htmlspecialchars($prod['stock']); ?></div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</main>
</body>
</html>
