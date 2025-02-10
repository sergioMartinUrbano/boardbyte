<?php

class ProductoCarrito{
    public $id_producto;
    public $nombre_producto;
    public $precio;
    public $foto_portada;

    public function __construct($id_producto, $nombre_producto, $precio, $foto_portada)
    {
        $this->id_producto = $id_producto;
        $this->nombre_producto = $nombre_producto;
        $this->precio = $precio;
        $this->foto_portada = $foto_portada;
    }

    public function getIdProducto()
    {
        return $this->id_producto;
    }

    public function getNombreProducto()
    {
        return $this->nombre_producto;
    }

    public function getPrecio()
    {
        return $this->precio;
    }

    public function getFotoPortada()
    {
        return $this->foto_portada;
    }
}
?>
