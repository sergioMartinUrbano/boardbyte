<?php
include '../app/includes/comprobarSesion.php';
include '../app/includes/comprobarRol.php';
include '../app/includes/comprobarDivisa.php';
?>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pedidos - BoardByte</title>
    <link rel="stylesheet" href="/boardbyte/resources/css/normalize.css">

    <link rel="stylesheet" href="/boardbyte/resources/css/body.css">
    <link rel="stylesheet" href="/boardbyte/resources/css/navbar.css">
    <link rel="stylesheet" href="/boardbyte/resources/css/pedidos.css">
</head>

<body>
    <?php
    include '../resources/commons/navbar.php';
    ?>

    <div class="container">
        <h1>Pedidos</h1>
        <div id="orders-container">
            <?php
            include '../app/includes/generarPedidos.php';
            ?>
        </div>
    </div>
</body>

</html>