<?php
class detallesPedidos {
    private $id_producto;
    private $cantidad;
    private $subtotal;
    private $nombreProducto;

    public function __construct($id_producto, $cantidad, $subtotal, $nombreProducto) {
        $this->id_producto = $id_producto;
        $this->cantidad = $cantidad;
        $this->subtotal = $subtotal;
        $this->nombreProducto = $nombreProducto;
    }

    public function getIdProducto() {
        return $this->id_producto;
    }

    public function getCantidad() {
        return $this->cantidad;
    }

    public function getSubtotal() {
        return $this->subtotal;
    }

    public function getNombreProducto() {
        return $this->nombreProducto;
    }
}
