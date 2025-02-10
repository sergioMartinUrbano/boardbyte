<?php
class carrito
{
    public $id_usuario;
    public $carrito;

    public function __construct($id_usuario, array $carrito = [])
    {
        $this->id_usuario = $id_usuario;
        $this->carrito = $carrito;
    }

    public function getID()
    {
        return $this->id_usuario;
    }
    public function getCarrito()
    {
        return $this->carrito;
    }

    public function addProducto(ProductoCarrito $producto)
    {
        $noEncontrado = true;
        foreach ($this->carrito as &$item) {
            if ($item['id'] == $producto->getIdProducto()) {
                $noEncontrado = false;
                $item['cantidad']++;
                break;
            }
        }
        if ($noEncontrado) {
            $this->carrito[] = ['id' => $producto->getIdProducto(), 'cantidad' => 1, 'producto' => $producto];
        }
    }

    public function removeProducto($id_producto)
    {
        foreach ($this->carrito as $key => $item) {
            if ($item['id'] == $id_producto) {
                unset($this->carrito[$key]);
                $this->carrito = array_values($this->carrito);
                break;
            }
        }
    }
    public function getCarritoJSON()
    {
        $carritoSimplificado = [];
        foreach ($this->carrito as $item) {
            $carritoSimplificado[] = [
                'id_producto' => $item['id'],
                'cantidad' => $item['cantidad']
            ];
        }
        return json_encode($carritoSimplificado);
    }

}