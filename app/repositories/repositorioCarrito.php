<?php

class RepositorioCarrito
{
    protected PDO $conexion;

    public function __construct(PDO $conexion)
    {
        $this->conexion = $conexion;
    }

    public function obtenerProducto($id_producto)
    {
        $prepare = $this->conexion->prepare('select * from productos where id_producto=:id_producto limit 1');
        $prepare->execute(['id_producto' => $id_producto]);
        $result = $prepare->fetch(PDO::FETCH_ASSOC);
        if (!empty($result)) {
            return $result;
        } else {
            throw new Exception('No se ha podido encontrar el producto');
        }
    }

    public function saveCarritoDatabase(carrito $carrito)
    {
        $statement = $this->conexion->prepare('call GuardarCarrito(:id_usuario, :carrito_json)');
        $statement->execute(['id_usuario' => $carrito->getID(), 'carrito_json' => $carrito->getCarritoJSON()]);
    }

    public function getCarritoDatabase(carrito $carrito)
    {
        $statement = $this->conexion->prepare('select * from Vista_Carrito where id_usuario=:id_usuario');
        $statement->execute(['id_usuario' => $carrito->getID()]);
        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }

    public function eliminarCarrito($id_usuario){
        $statement = $this->conexion->prepare('delete from carrito where id_usuario=:id_usuario');
        $statement->execute(['id_usuario' => $id_usuario]);
    }
}