<div class="game-header">
    <h1 class="game-title"><?php echo $producto->getNombre() ?></h1>
    <?php
    echo '<img src="/boardbyte/resources/images/productos/portadas/' . $producto->getFotoPortada() . '". alt="Portada del juego ' . $producto->getNombre() . '" class="game-image">';
    ?>
</div>

<section class="description">
    <p>
        <?php echo $producto->getDescripcionCompleta(); ?>
    </p>
</section>

<section class="game-images">
    <h2>Imágenes del Juego</h2>
    <div class="image-gallery">
        <?php
        foreach ($producto->getFotosAdicionales() as $item) {
            echo '<img src="/boardbyte/resources/images/productos/adicional/' . strtolower($item) . '">';
        }
        ?>
    </div>
</section>