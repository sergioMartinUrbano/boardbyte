<?php
include '../app/includes/comprobarSesion.php';
include '../app/includes/comprobarDivisa.php';
include '../app/includes/desencriptarcookies.php';
$carrito = unserialize($_SESSION['carrito'])->getCarrito();
$direcciones = unserialize($_SESSION['usuario'])->getDirecciones();
$contadorNull=0;
foreach($direcciones as $item){
    if(!$item){
        $contadorNull++;
    }
}
if($contadorNull>=3){
    throw new Exception('No tienes direcciones ahora mismo.');
}


if (isset($_COOKIE['tarjeta'])) {
    $cookie = unserialize($_COOKIE['tarjeta']);
    $numero = desencriptar($cookie['numero']);
    $fecha = desencriptar($cookie['fecha']);
    $cvc = desencriptar($cookie['cvc']);

}
?>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Procesar Pedido</title>
    <link rel="stylesheet" href="/BoardByte/resources/css/normalize.css">
    <link rel="stylesheet" href="/BoardByte/resources/css/body.css">
    <link rel="stylesheet" href="/BoardByte/resources/css/navbar.css">
    <link rel="stylesheet" href="/BoardByte/resources/css/adminTablas.css">
    <link rel="stylesheet" href="/BoardByte/resources/css/shopping.css">
    <script>
        function openModal() {
            document.querySelector('.modal-overlay').style.display = 'flex';
        }

        function closeModal() {
            event.preventDefault();
            document.querySelector('.modal-overlay').style.display = 'none';
        }
    </script>
</head>

<body>
    <?php
    include '../resources/commons/navbar.php';
    ?>
    <div class="container">
        <h1>Resumen del Pedido</h1>
        <table class="order-table">
            <thead>
                <tr>
                    <th>Producto</th>
                    <th>Cantidad</th>
                    <th>Precio por Unidad</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $total = 0;
                foreach ($carrito as $item) {
                    $precioUnidad = $item['producto']->getPrecio();
                    $totalItem = $precioUnidad * $item['cantidad'];
                    $total += $totalItem;

                    echo "<tr>";
                    echo "<td><ul><li>" . $item['producto']->getNombreProducto() . "</li></ul></td>";
                    echo "<td>" . $item['cantidad'] . "</td>";

                    if ($simbolo === '€') {
                        echo '<td class="cart-item-price">' . number_format($precioUnidad, 2) . '€</td>';
                        echo '<td class="cart-item-price">' . number_format($totalItem, 2) . '€</td>';
                    } else {
                        $precioConvertido = $precioUnidad * $conversion;
                        $totalItemConvertido = $totalItem * $conversion;
                        echo '<td class="cart-item-price">' . $simbolo . number_format($precioConvertido, 2) . '</td>';
                        echo '<td class="cart-item-price">' . $simbolo . number_format($totalItemConvertido, 2) . '</td>';
                    }

                    echo "</tr>";
                }
                ?>
            </tbody>
        </table>

        <div class="total-bar">
            <span>Total a Pagar:</span>
            <span class="total-price">
                <?php
                if ($simbolo === '€') {
                    echo number_format($total, 2) . '€';
                } else {
                    echo $simbolo . number_format($total * $conversion, 2);
                }
                ?>
            </span>
        </div>

        <form id="order-form" action="/BoardByte/pedidos/create" method="POST">
            <div class="address-section">
                <div class="pepito">
                    <label for="shipping-address">Dirección de Envío:</label>
                    <select id="shipping-address" name="direccion">
                        <?php
                        foreach ($direcciones as $direccion) {
                            if ($direccion !== null&& $direccion !== '') {
                                $selected = ($_COOKIE['defecto'] == $direccion) ? 'selected' : '';
                                echo "<option value='$direccion' $selected>$direccion</option>";
                            }
                        }
                        ?>
                    </select>
                </div>
                <div class="pepito">
                    <label for="billing-address">Dirección de Facturación:</label>
                    <select id="billing-address" name="facturacion">
                        <?php
                        foreach ($direcciones as $direccion) {
                            if ($direccion !== null&& $direccion !== '') {
                                $selected = ($_COOKIE['facturacion'] == $direccion) ? 'selected' : '';
                                echo "<option value='$direccion' $selected>$direccion</option>";
                            }
                        }
                        ?>
                    </select>
                </div>
                <button type="button" class="btn-accept" onclick="openModal()">Aceptar y Proceder al Pago</button>

                <div class="modal-overlay" onclick="closeModal()">
                    <div class="modal-content" onclick="event.stopPropagation()">
                        <button class="close-modal" onclick="closeModal()">×</button>
                        <label for="card-number">Número de Tarjeta:</label>
                        <input type="text" id="card-number" name="card-number" placeholder="1234 5678 9012 3456"
                            required
                            value="<?php echo isset($numero) ? htmlspecialchars($numero) : '' ?>">

                        <label for="card-expiration">Fecha de Expiración:</label>
                        <input type="text" id="card-expiration" name="card-expiration" placeholder="MM/AA" required
                            value="<?php echo isset($fecha) ? htmlspecialchars($fecha) : '' ?>">

                        <label for="card-cvc">CVC:</label>
                        <input type="text" id="card-cvc" name="card-cvc" placeholder="123" required
                            value="<?php echo isset($cvc) ? htmlspecialchars($cvc) : '' ?>">

                        <p class="checkbox"><input type="checkbox" name="card-information">Guardar información de la
                            tarjeta</p>
                        <button type="submit" class="btn-accept">Pagar</button>
                    </div>
                </div>

            </div>
        </form>
    </div>
</body>

</html>