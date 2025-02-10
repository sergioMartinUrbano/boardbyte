<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Catálogo - BoardByte</title>
    <link rel="stylesheet" href="/boardbyte/resources/css/normalize.css">
    <link rel="stylesheet" href="/boardbyte/resources/css/body.css">
    <link rel="stylesheet" href="/boardbyte/resources/css/navbar.css">
    <link rel="stylesheet" href="/boardbyte/resources/css/search.css">
    <link rel="stylesheet" href="/boardbyte/resources/css/pag.css">
    <link rel="stylesheet" href="/boardbyte/resources/css/products.css">
    <link rel="stylesheet" href="/boardbyte/resources/css/carrito.css">
</head>

<body>

<nav class="navbar">
    <div class="navbar-container">
        <a href="/boardbyte/" class="navbar-logo"><img src="/boardbyte/resources/images/logo.png" alt="Boardbyte"></a>
        <ul class="navbar-menu">
            <li><a href="/boardbyte/admin" class="navbar-link">Administración</a></li>
            <li><a href="/boardbyte/catalogo" class="navbar-link">Catálogo</a></li>
            <li><a class="navbar-link" id="carrito">Carrito</a></li>
            <li><a href="/boardbyte/micuenta"
                    class="navbar-link"><?php echo isset($_SESSION['usuario']) ? htmlspecialchars(unserialize($_SESSION['usuario'])->getNombreUsuario()) : 'Cuenta'; ?></a>
            </li>
        </ul>
    </div>
</nav>

    <?php
    include '../resources/commons/search.html';
    ?>

    <main id="productList">
        <?php
        include '../app/includes/comprobarDivisa.php';
        include '../app/includes/generarCatalogo.php';
        ?>
    </main>
    <!-- <a href="https://boardgamegeek.com">Para pillar las imágenes</a> -->
    <div id="paginacion"></div>
    
        <script src="/boardbyte/resources/js/search.js"></script>
        <script src="/boardbyte/resources/js/pag.js"></script>
        

<?php
if(isset($_SESSION['usuario'])){
    include '../app/includes/generarCarrito.php';
}else{
    echo '<div id="cartModal" class="cart-modal">
    <div class="cart-modal-content">
        <span class="close">×</span>
        <p>Debes iniciar sesión para usar el carrito.</p>
        </div>
        </div>';
}
?>
    

    <script src="/boardbyte/resources/js/carrito.js"></script>
</body>

</html>