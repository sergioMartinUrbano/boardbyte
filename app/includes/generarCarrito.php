<div id="cartModal" class="cart-modal">
    <div class="cart-modal-content">
        <span class="close">×</span>
        <h2>Carrito de Compras</h2>
        <div id="cartItemsContainer" class="cart-items-container">

            <?php
            $carrito=unserialize($_SESSION['carrito']);

            if (!empty($carrito->getCarrito())) {
                foreach ($carrito->getCarrito() as $item) {
                    if (isset($item['producto']) && is_object($item['producto'])) {
                        echo '<div class="cart-item">';
                        echo '<img src="/boardbyte/resources/images/productos/portadas/' . htmlspecialchars($item['producto']->getFotoPortada()) . '" alt="Imagen del producto">';
                        echo '<div class="cart-item-details">';
                        echo '<span>' . htmlspecialchars($item['producto']->getNombreProducto()) . ' x' . $item['cantidad'] . '</span> ';

                        if (isset($simbolo)) {
                            if ($simbolo === '€') {
                                echo ' <span class="cart-item-price">' . number_format($item['producto']->getPrecio(), 2) . '€</span>';
                            } else {
                                echo '<span class="cart-item-price">' . $simbolo . number_format($item['producto']->getPrecio() * $conversion, 2) . '</span>';
                            }
                        }
                        echo '</div>';
                        echo '<form action="/boardbyte/carrito/eliminar/' . $item['producto']->getIdProducto() . '" method="post">
                        <button class="remove-item" type="submit">Eliminar</button>
                        </form>';
                        echo '</div>';
                    }
                }
            } else {
                echo '<p>Tu carrito está vacío.</p>';
            }
            ?>

        </div>
        <div class="cart-footer">
            <a href="/boardbyte/carrito/vaciar"><button class="cart-button">Vaciar Carrito</button></a>
            <a href="/boardbyte/carrito/guardar"><button class="cart-button">Guardar Carrito</button></a>
            <a href="/boardbyte/pedidos/realizar"><button class="cart-button">Realizar Pedido</button></a>
        </div>
    </div>
</div>