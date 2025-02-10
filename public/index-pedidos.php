<?php
$controlador = new PedidosController(new RepositorioPedidos($conexion));
switch ($action) {
    case 'mispedidos':
        $controlador->mispedidos($usuarioSesion->getID());
        break;
    case null:
        $controlador->pedidos();
        break;
    case 'realizar':
        $controlador->nuevoPedido();
        break;
        case 'create':
            if (isset($_POST['card-information'])) {
                $cookieController = new CookieController();
                $cookieController->crearDatosTarjeta([
                    'numero' => trim(strip_tags($_POST['card-number'] ?? null)),
                    'fecha' => trim(strip_tags($_POST['card-expiration'] ?? null)),
                    'cvc' => trim(strip_tags($_POST['card-cvc'] ?? null))
                ]);
            }
            $pedido = new Pedidos(
                0,
                $usuarioSesion->getId(),
                'pendiente',
                trim(strip_tags($_POST['direccion'] ?? null)),
                trim(strip_tags($_POST['facturacion'] ?? null))
            );
            $controlador->create($pedido, $carritoSesion);
            break;
        
}