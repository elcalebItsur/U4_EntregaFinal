<?php
session_start();
require_once '../datos/CarritoDAO.php';
require_once '../datos/ProductoDAO.php';

if (!isset($_SESSION['usuario_id'])) {
    header('Location: login.php');
    exit();
}
$usuario_id = $_SESSION['usuario_id'];
$mensaje = '';

// AJAX: agregar producto al carrito
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['agregar']) && isset($_POST['productoId'])) {
    $producto_id = intval($_POST['productoId']);
    CarritoDAO::agregarProducto($usuario_id, $producto_id, 1);
    echo json_encode(['success' => true]);
    exit();
}
// AJAX: obtener carrito
if (isset($_GET['ajax']) && $_GET['ajax'] == 'true') {
    $carrito = CarritoDAO::obtenerCarrito($usuario_id);
    echo json_encode($carrito);
    exit();
}
// Eliminar producto del carrito
if (isset($_GET['eliminar'])) {
    $prod_id = intval($_GET['eliminar']);
    CarritoDAO::eliminarProducto($usuario_id, $prod_id);
    $mensaje = 'Producto eliminado del carrito.';
}
// Finalizar compra
if (isset($_POST['finalizar'])) {
    $carrito = CarritoDAO::obtenerCarrito($usuario_id);
    if ($carrito) {
        require_once '../datos/VentaDAO.php';
        try {
            VentaDAO::registrarVenta($usuario_id, $carrito);
            CarritoDAO::vaciarCarrito($usuario_id);
            $mensaje = '¡Compra realizada con éxito!';
        } catch (Exception $e) {
            $mensaje = 'Error al procesar la compra: ' . $e->getMessage();
        }
    } else {
        $mensaje = 'El carrito está vacío.';
    }
}
$carrito = CarritoDAO::obtenerCarrito($usuario_id);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Carrito - Textisur</title>
    <link rel="stylesheet" href="../css/main.css" />
    <link rel="stylesheet" href="../css/cart.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"/>
    <script src="../js/cart.js" defer></script>
    <script>
    window.usuarioActual = <?php echo json_encode($usuario_id); ?>;
    </script>
</head>
<body>
    <?php include 'header.php'; ?>
    <main style="max-width: 900px; margin: 0 auto;">
        <h2 style="color:#44ff99;text-align:center;margin-bottom:2rem;">Tu Carrito</h2>
        <?php if ($mensaje): ?>
            <div style="background:#232323;color:#44ff99;padding:1rem;border-radius:8px;margin-bottom:1rem;">
                <?php echo htmlspecialchars($mensaje); ?>
            </div>
        <?php endif; ?>
        <div id="carrito-lista" class="carrito-lista">
            <?php if (empty($carrito)): ?>
                <p>Tu carrito está vacío.</p>
            <?php else: ?>
                <?php $subtotal = 0; ?>
                <?php foreach ($carrito as $item): ?>
                    <?php $subtotal += $item['precio'] * $item['cantidad']; ?>
                    <div class="item-carrito">
                        <img src="../assets/images/<?php echo htmlspecialchars($item['imagen'] ?? 'hero_image.jpg'); ?>" alt="<?php echo htmlspecialchars($item['nombre']); ?>" style="width:60px;height:60px;object-fit:cover;border-radius:8px;">
                        <div style="flex:1;margin-left:1rem;">
                            <p style="font-weight:600;"> <?php echo htmlspecialchars($item['nombre']); ?> </p>
                            <p>Cantidad: <?php echo $item['cantidad']; ?> | Stock: <?php echo $item['stock']; ?></p>
                            <p>Precio: $<?php echo number_format($item['precio'],2); ?></p>
                        </div>
                        <form method="get" action="cart.php" style="margin-left:1rem;">
                            <input type="hidden" name="eliminar" value="<?php echo $item['producto_id']; ?>">
                            <button type="submit" class="btn-danger">Eliminar</button>
                        </form>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
        <div class="resumen" id="resumen">
            <?php if (!empty($carrito)): ?>
                <?php $envio = 10.00; $total = $subtotal + $envio; ?>
                <p>Subtotal: $<?php echo number_format($subtotal,2); ?></p>
                <p>Envío: $<?php echo number_format($envio,2); ?></p>
                <p>Total: $<?php echo number_format($total,2); ?></p>
                <form method="post" action="cart.php">
                    <button type="submit" name="finalizar" class="btn-primary">Finalizar compra</button>
                </form>
            <?php endif; ?>
        </div>
    </main>
    <?php include 'footer.php'; ?>
</body>
</html>