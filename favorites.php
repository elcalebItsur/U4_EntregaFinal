<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['ajax'])) {
    if (!isset($_SESSION['usuario'])) {
        echo json_encode(['error' => 'Debes iniciar sesión para ver tus favoritos.']);
        exit();
    }

    $favoritos = $_SESSION['favoritos'] ?? [];
    echo json_encode($favoritos);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_SESSION['usuario'])) {
        echo json_encode(['error' => 'Debes iniciar sesión para agregar a favoritos.']);
        exit();
    }

    $productoId = $_POST['productoId'] ?? null;
    if ($productoId) {
        if (!isset($_SESSION['favoritos'])) {
            $_SESSION['favoritos'] = [];
        }
        if (!in_array($productoId, $_SESSION['favoritos'])) {
            $_SESSION['favoritos'][] = $productoId;
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
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Favoritos - Textisur</title>
    <link rel="stylesheet" href="css/main.css" />
    <link rel="stylesheet" href="css/favorites.css" />
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
        <h2>Productos Favoritos</h2>
        <div id="favoritos-lista" class="productos-favoritos"></div>
    </main>   
    
    <button class="back-button" onclick="location.href='index.php'">Regresar</button>

    <script src="js/favorites.js"></script>
</body>
</html>