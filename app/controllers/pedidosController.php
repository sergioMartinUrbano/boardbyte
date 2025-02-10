<?php

class PedidosController
{
    private RepositorioPedidos $pedidosRepositorio;

    public function __construct($pedidosRepositorio)
    {
        $this->pedidosRepositorio = $pedidosRepositorio;
    }

    public function pedidos(){
        $pedidos = $this->pedidosRepositorio->obtenerTodosPedidos();
        include '../app/views/pedidos.php';
    }

    public function mispedidos($id){
        $pedidos=$this->pedidosRepositorio->mispedidos($id);
        include '../app/views/mispedidos.php';
        
    }
    
    public function nuevoPedido(){
        include '../app/views/finalizarCompra.php';
        
    }

    public function create(Pedidos $pedido, carrito $carrito)
    {
        $errores = [];
        if (!$pedido->getDireccion()) {
            $errores[] = 'La dirección no puede ser nula';
        }
        if (!$pedido->getFacturacion()) {
            $errores[] = 'La dirección de facturación no puede ser nula';
        }
        if(empty($errores)){
            $this->pedidosRepositorio->realizarPedido($pedido, $carrito);
            $_SESSION['carrito']=serialize(new carrito($pedido->getPedido_ID()));
            header('Location: /boardbyte/pedidos/mispedidos');
            die;
        }else{
            include '../app/views/errores.php';
        }

    }


}