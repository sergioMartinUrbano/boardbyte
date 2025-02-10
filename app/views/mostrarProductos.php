<?php
include '../app/includes/comprobarSesion.php';
include '../app/includes/comprobarRol.php';
?>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="/boardbyte/resources/css/normalize.css">

    <link rel="stylesheet" href="/boardbyte/resources/css/body.css">
    <link rel="stylesheet" href="/boardbyte/resources/css/navbar.css">
    <link rel="stylesheet" href="/boardbyte/resources/css/adminTablas.css">
</head>

<body>
    <?php
    include '../resources/commons/navbar.php';
    ?>
    <div class="container">
        <h1>Lista de Productos</h1>

        <div class="create">
            <a href="/boardbyte/productos/crear" class="btn-create">Crear Producto</a>
        </div>
        <?php
        include '../app/includes/generarProductos.php';
        ?>
    </div>
</body>

</html>