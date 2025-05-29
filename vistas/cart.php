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

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['agregar']) && isset($_POST['productoId'])) {
    $producto_id = intval($_POST['productoId']);
    $cantidad = isset($_POST['cantidad']) ? max(1, intval($_POST['cantidad'])) : 1;
    CarritoDAO::agregarProducto($usuario_id, $producto_id, $cantidad);
    $mensaje = 'Producto agregado al carrito.';
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
</head>
<body>
    <?php include 'header.php'; ?>
    <main class="main-container">
        <h2 class="section-title cart-title">Tu Carrito</h2>
        <?php if ($mensaje): ?>
            <div class="cart-warning">
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
                        <img src="../assets/images/<?php echo htmlspecialchars($item['imagen'] ?? 'hero_image.jpg'); ?>" alt="<?php echo htmlspecialchars($item['nombre']); ?>" class="cart-thumb" />
                        <div class="cart-item-info">
                            <p class="cart-item-title"> <?php echo htmlspecialchars($item['nombre']); ?> </p>
                            <p>Cantidad: <?php echo $item['cantidad']; ?> | Stock: <?php echo $item['stock']; ?></p>
                            <p>Precio: $<?php echo number_format($item['precio'],2); ?></p>
                        </div>
                        <button type="button" class="btn-danger btn-eliminar-cart-modal" data-id="<?php echo $item['producto_id']; ?>" data-nombre="<?php echo htmlspecialchars($item['nombre']); ?>">Eliminar</button>
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
                    <button type="submit" name="finalizar" class="btn-primary">Comprar carrito</button>
                </form>
            <?php endif; ?>
        </div>
    </main>
    <div style="text-align:center; margin: 2rem 0;">
        <a href="index.php" class="btn-primary" style="padding:0.7rem 2rem; font-size:1.1rem; border-radius:8px; text-decoration:none;">Seguir comprando</a>
    </div>
    <div id="modal-eliminar-cart" class="modal" style="display:none; align-items:center; justify-content:center; position:fixed; top:0; left:0; width:100vw; height:100vh; background:rgba(0,0,0,0.45); z-index:3000;">
        <div class="modal-content" style="background:#fff; color:#232323; border-radius:14px; padding:2rem 2.5rem; min-width:320px; max-width:95vw; box-shadow:0 8px 32px #0005; position:relative; display:flex; flex-direction:column; align-items:center;">
            <button id="cerrar-modal-eliminar-cart" style="position:absolute; top:1rem; right:1rem; background:none; border:none; font-size:2rem; color:#ff6b6b; cursor:pointer;">&times;</button>
            <h3>¿Eliminar producto del carrito?</h3>
            <p>¿Estás seguro de que deseas eliminar <span id="modal-eliminar-cart-nombre" style="font-weight:bold;"></span> de tu carrito?</p>
            <form id="modal-eliminar-cart-form">
                <button type="submit" class="btn-danger" style="margin-right:1rem;">Eliminar</button>
                <button type="button" id="cerrar-modal-eliminar-cart-2" class="btn-secondary">Cancelar</button>
            </form>
        </div>
    </div>
    <?php include 'footer.php'; ?>
    <script src="../js/cart.js" defer></script>
</body>
</html>