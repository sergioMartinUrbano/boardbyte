<?php

class CarritoController
{
    public RepositorioCarrito $repositorioCarrito;
    protected carrito $carrito;

    public function __construct($repositorioCarrito, carrito $carrito)
    {
        $this->repositorioCarrito = $repositorioCarrito;
        $this->carrito = $carrito;
    }

    public function añadirElemento($id_producto)
    {
        $result = $this->repositorioCarrito->obtenerProducto($id_producto);
        if ($result) {
            $product = new ProductoCarrito($id_producto, $result['nombre'], $result['precio'], $result['foto_portada']);
            $this->carrito->addProducto($product);
            $this->guardarCarrito();
        } else {
            throw new Exception('Producto no encontrado');
        }
    }
    public function eliminarElemento($id_producto)
    {
        $this->carrito->removeProducto($id_producto);
        $this->guardarCarrito();
    }

    public function guardarCarrito()
    {
        $_SESSION['carrito'] = serialize($this->carrito);
    }

    public function saveCarritoDatabase()
    {
        $this->repositorioCarrito->saveCarritoDatabase($this->carrito);
    }

    //realmente esta función es el builder del carrito
    public function getCarritoDatabase()
    {
        $result = $this->repositorioCarrito->getCarritoDatabase($this->carrito); //array de datos de la consulta
        $nuevoCarrito = [];
        if (is_array($result)) {
            if(!empty($result)){
                foreach ($result as $item) {
                    $productoCarrito = new ProductoCarrito($item['id_producto'], $item['nombre_producto'], $item['precio'], $item['foto_portada']);
                    $nuevoCarrito[] = ['id' => $productoCarrito->getIdProducto(), 'cantidad' => $item['cantidad'], 'producto' => $productoCarrito];
                }
            }
            
            $_SESSION['carrito']=serialize(new carrito($this->carrito->getID(), $nuevoCarrito));
        } else {
            throw new Exception('Error al obtener el carrito');
        }
    }

    public function vaciarCarrito()
    {
        $_SESSION['carrito'] = serialize(new carrito($this->carrito->getID()));
    }

    public function eliminarCarrito(){
        $this->repositorioCarrito->eliminarCarrito($this->carrito->getID());
    }

}