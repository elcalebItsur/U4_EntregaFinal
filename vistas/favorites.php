<?php
// ...existing code...
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Favoritos - Textisur</title>
    <link rel="stylesheet" href="../../css/main.css" />
    <link rel="stylesheet" href="../../css/favorites.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"/>
    <script>
    window.usuarioActual = <?php echo isset($_SESSION['usuario']) ? json_encode($_SESSION['usuario']) : 'null'; ?>;
    </script>
</head>
<body>
    <header>
        <!-- ...existing code... -->
    </header>
    <!-- ...resto del contenido... -->
</body>
</html>