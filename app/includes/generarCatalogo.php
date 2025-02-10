<?php
if (!empty($productos)) {
    foreach ($productos as $producto) {
        echo '<div class="product">';
        echo '<div class="product-inner">';
        echo '<div class="product-front">';
        echo '<img src=/boardbyte/resources/images/productos/portadas/' . strtolower($producto->getFotoPortada()) . ' alt="' . $producto->getNombre() . '" class="product-image">';
        echo '<div class="product-name-container">';
        echo '<h2 class="product-name">' . $producto->getNombre() . '</h2>';
        echo '</div>';
        echo '</div>';
        echo '<div class="product-back">';
        echo '<p class="description">' . $producto->getDescripcionCorta() . '</p>';
        if ($simbolo !== '€') {
            echo '<span class="price">' . $simbolo . number_format($producto->getPrecio() * $conversion, 2) . '</span>';
        } else {
            echo '<span class="price">' . $producto->getPrecio() . '€</span>';
        }
        echo '<form action="/boardbyte/carrito/add/' . $producto->getID() . '"method="post">
                        <button type="submit" class="add-to-cart">Agregar al carrito</button>
                    </form>';
        echo '<form action="/boardbyte/productos/info/' . $producto->getID() . '"method="post">
                        <button type="submit" class="add-to-cart">Obtener más información</button>
                    </form>';
        echo '</div>
                </div>
            </div>';
    }
} else {
    echo "<p>Ahora mismo no hay productos disponibles</p>";
}