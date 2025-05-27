<?php
session_start();
require_once '../datos/FavoritosDAO.php';
require_once '../datos/ProductoDAO.php';

if (!isset($_SESSION['usuario_id'])) {
    header('Location: login.php');
    exit();
}
$usuario_id = $_SESSION['usuario_id'];

// AJAX: agregar/eliminar favorito
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['productoId'])) {
    $producto_id = intval($_POST['productoId']);
    if (isset($_POST['eliminar'])) {
        FavoritosDAO::eliminar($usuario_id, $producto_id);
        echo json_encode(['success' => true, 'eliminado' => true]);
    } else {
        FavoritosDAO::agregar($usuario_id, $producto_id);
        echo json_encode(['success' => true]);
    }
    exit();
}
// AJAX: obtener favoritos
if (isset($_GET['ajax']) && $_GET['ajax'] == 'true') {
    $ids = FavoritosDAO::obtenerFavoritos($usuario_id);
    $productos = [];
    foreach ($ids as $pid) {
        $prod = ProductoDAO::obtenerPorId($pid);
        if ($prod) $productos[] = $prod;
    }
    echo json_encode($productos);
    exit();
}
// Mostrar favoritos en la vista
$favoritos_ids = FavoritosDAO::obtenerFavoritos($usuario_id);
$favoritos = [];
foreach ($favoritos_ids as $pid) {
    $prod = ProductoDAO::obtenerPorId($pid);
    if ($prod) $favoritos[] = $prod;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Favoritos - TextiSur</title>
    <link rel="stylesheet" href="../css/main.css" />
    <link rel="stylesheet" href="../css/favorites.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"/>
    <script src="../js/favorites.js" defer></script>
    <script>window.usuarioActual = <?php echo json_encode($usuario_id); ?>;</script>
</head>
<body>
    <?php include 'header.php'; ?>
    <main class="favoritos-container">
        <h2 style="color:#44ff99;text-align:center;margin-bottom:2rem;">Tus Favoritos</h2>
        <div id="favoritos-lista" class="productos-grid">
            <?php if (empty($favoritos)): ?>
                <p>No tienes productos favoritos.</p>
            <?php else: ?>
                <?php foreach ($favoritos as $prod): ?>
                    <div class="producto-card">
                        <img src="../assets/images/<?php echo htmlspecialchars($prod['imagen'] ?? 'hero_image.jpg'); ?>" alt="<?php echo htmlspecialchars($prod['nombre']); ?>">
                        <h3><?php echo htmlspecialchars($prod['nombre']); ?></h3>
                        <div class="precio">$<?php echo number_format($prod['precio'],2); ?></div>
                        <div class="acciones">
                            <button class="eliminar-favorito" onclick="eliminarDeFavoritos(<?php echo $prod['id']; ?>)"><i class="fa fa-heart-broken"></i> Quitar</button>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </main>
    <?php include 'footer.php'; ?>
</body>
</html>