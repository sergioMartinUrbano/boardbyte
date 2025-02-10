<?php
include '../app/includes/comprobarSesion.php';
include '../app/includes/comprobarRol.php';
?>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Actualizar Producto - BoardByte</title>
    <link rel="stylesheet" href="/boardbyte/resources/css/normalize.css">

    <link rel="stylesheet" href="/boardbyte/resources/css/body.css">
    <link rel="stylesheet" href="/boardbyte/resources/css/navbar.css">
    <link rel="stylesheet" href="/boardbyte/resources/css/form.css">
</head>

<body>
    <?php include '../resources/commons/navbar.php'; ?>

    <div class="container">
        <h1>Actualizar Producto</h1>
        <?php include '../app/includes/generarFormularioActualizarProducto.php';?>
    </div>
</body>

</html>