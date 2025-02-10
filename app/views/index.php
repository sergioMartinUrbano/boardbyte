<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BoardByte - Tu tienda online de juegos de mesa</title>
    <link rel="stylesheet" href="/resources/css/normalize.css">
    <link rel="stylesheet" href="/BoardByte/resources/css/body.css">
    <link rel="stylesheet" href="/BoardByte/resources/css/navbar.css">
    <link rel="stylesheet" href="/BoardByte/resources/css/index.css">
</head>

<body>
    <?php
    include '../resources/commons/navbar.php';
    ?>

    <div class="container">
        <section class="hero">
            <h1>Bienvenido a BoardByte</h1>
            <p>Tu tienda online española de juegos de mesa. Descubre nuevas aventuras, estrategias y diversión para toda
                la familia.</p>
            <a href="/boardbyte/catalogo" class="cta-button">Explorar juegos</a>
        </section>

        <section class="featured-games">
            <h2>Juegos destacados</h2>
            <div class="game-grid">
                <?php
                include '../app/includes/generarJuegosDestacados.php';
                ?>
            </div>
        </section>

        <section class="about-us">
            <h2>Sobre BoardByte</h2>
            <p>BoardByte es tu destino online para juegos de mesa en España. Nos apasiona traerte los mejores juegos del
                mercado, desde clásicos atemporales hasta las últimas novedades. Nuestro equipo de expertos selecciona
                cuidadosamente cada juego para asegurar la mejor calidad y diversión para nuestros clientes.</p>
            <p>Con envíos rápidos a toda España y un servicio al cliente excepcional, en BoardByte nos esforzamos por
                hacer que tu experiencia de compra sea tan divertida como los juegos que vendemos.</p>
        </section>
    </div>
</body>

</html>