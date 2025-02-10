<?php
class Pedidos{
    private $id_pedido;
    private $id_usuario;
    private $fecha_pedido;
    private $fecha_llegada;
    private $estado;
    private $detallesPedidos;
    private $direccion;
    private $facturacion;


    public function __construct($id_pedido, $id_usuario, $estado, $direccion, $facturacion, $fecha_pedido = null, $fecha_llegada = null) {
        $this->id_pedido = $id_pedido;
        $this->id_usuario = $id_usuario;
        $this->estado = $estado;
        $this->direccion = $direccion;
        $this->facturacion = $facturacion;
        $this->fecha_pedido = $fecha_pedido ?: (new DateTime())->format('d-m-Y');
        $this->fecha_llegada = $fecha_llegada ?: (new DateTime())->modify('+2 weeks')->format('d-m-Y');
        $this->detallesPedidos = [];
    }

    public function getPedido_ID(){
        return $this->id_pedido;
    }

    public function getUsuario_ID(){
        return $this->id_usuario;
    }

    public function getFechaPedido(){
        return $this->fecha_pedido;
    }

    public function getFechaLlegada(){
        return $this->fecha_llegada;
    }

    public function getEstado(){
        return $this->estado;
    }

    public function getDetallesPedidos(){
        return $this->detallesPedidos;
    }
    public function getDireccion(){
        return $this->direccion;
    }
    public function getFacturacion(){
        return $this->facturacion;
    }

    public function addDetallePedido(detallesPedidos $detalle){
        $this->detallesPedidos[] = $detalle;
    }
}
