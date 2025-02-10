<?php
if(!empty($pedidos)){
    foreach ($pedidos as $pedido) {
        $total=0;
        echo '<div class="order">';
        echo '<details>';
        echo '<summary class="order-summary">';
        echo '<h2>Pedido #' . $pedido->getPedido_ID() . '</h2>';
        echo '<p>Dirección: ' . $pedido->getDireccion() . '</p>';
        echo '<p>Fecha de llegada: ' . $pedido->getFechaLlegada() . '</p>';
        echo '<p>Estado: ' . $pedido->getEstado() . '</p>';
        echo '</summary>';
        echo '<div class="order-details">';
        echo '<h3>Detalles del Pedido</h3>';
        echo '<ul>';
        foreach ($pedido->getDetallesPedidos() as $detalle) {
            $total+=$detalle->getSubtotal();
            echo '<li>';
            echo '<span>' . $detalle->getNombreProducto() . ' x' . $detalle->getCantidad() . '</span>';
            if ($simbolo === '€') {
                echo '<span>' . $detalle->getSubtotal() . '€</span>';
            } else {
                echo '<span>' . $simbolo . number_format($detalle->getSubtotal() * $conversion, 2) . '</span>';
            }
            echo '</li>';
        }
        echo '<div class="total-separator"></div>';
        echo '<div class="total">';
        if ($simbolo === '€') {
            echo '<span>Total: ' . $total . '€</span>';
        } else {
            echo '<span>' . $simbolo . number_format($total * $conversion, 2) . '</span>';
        }
        echo '</div>';
        echo '</div>';
        echo '</details>';
        echo '</div>';
    }
}else{
    echo '<p>No has realizado ningún pedido</p>';
}
