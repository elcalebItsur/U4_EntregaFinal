<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['ajax'])) {
    if (!isset($_SESSION['usuario'])) {
        echo json_encode(['error' => 'Debes iniciar sesión para ver tu carrito.']);
        exit();
    }

    $carrito = $_SESSION['carrito'] ?? [];
    echo json_encode($carrito);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_SESSION['usuario'])) {
        echo json_encode(['error' => 'Debes iniciar sesión para agregar al carrito.']);
        exit();
    }

    $productoId = $_POST['productoId'] ?? null;
    if ($productoId) {
        if (!isset($_SESSION['carrito'])) {
            $_SESSION['carrito'] = [];
        }
        if (!in_array($productoId, $_SESSION['carrito'])) {
            $_SESSION['carrito'][] = $productoId;
        }
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['error' => 'ID de producto no válido.']);
    }
    exit();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Carrito - Textisur</title>
    <link rel="stylesheet" href="css/main.css" />
    <link rel="stylesheet" href="css/cart.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"/>
    <script>
    window.usuarioActual = <?php echo isset($_SESSION['usuario']) ? json_encode($_SESSION['usuario']) : 'null'; ?>;
    </script>
</head>
<body>
    <header>
        <h1 class="logo" style="cursor: pointer;" onclick="location.href='index.php'">Textisur</h1>
    </header>

    <main>
        <h2>Tu Carrito</h2>
        <div id="carrito-lista" class="carrito-lista"></div>
        <div id="resumen" class="resumen"></div>
    </main>

    <button class="back-button" onclick="location.href='index.php'">Regresar</button>

    <script src="js/cart.js"></script>
</body>
</html>