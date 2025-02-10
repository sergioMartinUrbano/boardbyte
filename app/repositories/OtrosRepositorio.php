<?php
class otrosRepositorio{
    protected PDO $conexion;
    public function __construct( PDO $conexion){
        $this->conexion = $conexion;
    }
    public function catalogo(){
        $statement = $this->conexion->prepare('select * from productos');
        $statement->execute();
        $result = $statement->fetchAll(PDO::FETCH_ASSOC);
        $todosLosProductos = [];
        foreach ($result as $item) {
            $Producto = new Productos($item['id_producto'], $item['nombre'], $item['precio'], $item['foto_portada'], $item['descripcion_corta'], $item['descripcion_larga']);
           
            
            array_push($todosLosProductos, $Producto);
        }
        return $todosLosProductos;
    }

    public function index(){
        $statement = $this->conexion->prepare('select * from Productos_Mas_Vendidos');
        $statement->execute();
        $result = $statement->fetchAll(PDO::FETCH_ASSOC);
        $todosLosProductos = [];
        foreach ($result as $item) {
            $Producto = new Productos($item['id_producto'], $item['nombre'], null, $item['foto_portada'], $item['descripcion_corta'], null);
            array_push($todosLosProductos, $Producto);
        }
        return $todosLosProductos;
    }
}
?>