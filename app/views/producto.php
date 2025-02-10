<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $producto->getNombre() ?></title>
    <link rel="stylesheet" href="/boardbyte/resources/css/normalize.css">

    <link rel="stylesheet" href="/boardbyte/resources/css/body.css">
    <link rel="stylesheet" href="/boardbyte/resources/css/navbar.css">
    <link rel="stylesheet" href="/boardbyte/resources/css/producto.css">
</head>

<body>
    <?php
    include '../resources/commons/navbar.php';
    ?>

    <div class="container">
        <?php
        include '../app/includes/generarProductoUnico.php';
        ?>
    </div>
</body>

</html>