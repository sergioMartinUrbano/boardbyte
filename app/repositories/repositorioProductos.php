<?php

class repositorioProductos
{

    protected PDO $conexion;

    public function __construct(PDO $conexion)
    {
        $this->conexion = $conexion;
    }


    public function obtenerUnicoProducto($id): Productos
    {
        $statement = $this->conexion->prepare('select * from Productos where id_producto=:id_producto');
        $statement->execute(['id_producto' => $id]);
        $result = $statement->fetch(PDO::FETCH_ASSOC);

        $Producto = new Productos($result['id_producto'], $result['nombre'], $result['precio'], $result['foto_portada'], $result['descripcion_corta'], $result['descripcion_larga']);

        $statement = $this->conexion->prepare('select * from Fotos_productos where id_producto=:id_producto');
        $statement->execute(['id_producto' => $id]);
        $result = $statement->fetchAll(PDO::FETCH_ASSOC);

        foreach ($result as $item) {
            $Producto->addFotos($item['foto']);
        }
        return $Producto;
    }


    public function obtenerTodosProductos()
    {
        $statement = $this->conexion->prepare('select * from productos');
        $statement->execute();
        $result = $statement->fetchAll(PDO::FETCH_ASSOC);


        $todosLosProductos = [];
        foreach ($result as $item) {
            $Producto = new Productos($item['id_producto'], $item['nombre'], $item['precio'], $item['foto_portada'], $item['descripcion_corta'], $item['descripcion_larga']);

            $statement = $this->conexion->prepare('select * from Fotos_productos where id_producto=:id_producto');
            $statement->execute(['id_producto' => $item['id_producto']]);
            $fotos = $statement->fetchAll(PDO::FETCH_ASSOC);

            foreach ($fotos as $item2) {
                $Producto->addFotos($item2['foto']);
            }


            array_push($todosLosProductos, $Producto);
        }

        return $todosLosProductos;
    }


    public function insertarProducto(Productos $producto, $nuevoNombre)
    {
        $this->conexion->beginTransaction();

        try {
            $statement = $this->conexion->prepare('insert into productos (nombre, precio, foto_portada,descripcion_corta,descripcion_larga) values (:nombre, :precio, :foto_portada,:descripcion_corta,:descripcion_larga)');
            $statement->execute(['nombre' => $producto->getNombre(), 'precio' => $producto->getPrecio(), 'foto_portada' => $nuevoNombre, 'descripcion_corta' => $producto->getDescripcionCorta(), 'descripcion_larga' => $producto->getDescripcionCompleta()]);

            $id = $this->conexion->lastInsertId();



            foreach ($producto->getFotosAdicionales() as $item) {
                $sql = $this->conexion->prepare('insert into fotos_productos (id_producto, foto) values(:id_producto, :foto)');
                $sql->execute(['id_producto' => $id, 'foto' => $item]);
            }
            $this->conexion->commit();
        } catch (Exception $e) {
            $this->conexion->rollBack();
            throw new Exception($e->getMessage());
        }
    }


    public function borrarProducto($id){
        $producto=$this->obtenerUnicoProducto($id);

        $statement = $this->conexion->prepare('delete from productos where id_producto=:id');
        $statement->execute(['id' => $id]);
        return $producto;
    }

    public function modificarProducto(Productos $producto, $nuevoNombre)
{
    try {
        $this->conexion->beginTransaction();

        $statement = $this->conexion->prepare('
            UPDATE productos 
            SET nombre=:nombre, precio=:precio, foto_portada=:foto_portada, 
                descripcion_corta=:descripcion_corta, descripcion_larga=:descripcion_larga 
            WHERE id_producto=:id_producto');
        
        $statement->execute([
            'id_producto' => $producto->getID(), 
            'nombre' => $producto->getNombre(), 
            'precio' => $producto->getPrecio(), 
            'foto_portada' => $nuevoNombre, 
            'descripcion_corta' => $producto->getDescripcionCorta(), 
            'descripcion_larga' => $producto->getDescripcionCompleta()
        ]);

        if(!empty($producto->getFotosAdicionales())){
            foreach ($producto->getFotosAdicionales() as $item) {
                $sql = $this->conexion->prepare('
                    INSERT INTO Fotos_Productos (id_producto, foto) 
                    VALUES(:id_producto, :foto)
                ');
                if(!$sql->execute(['id_producto' => $producto->getID(), 'foto' => $item])){
                    throw new Exception('Error');
                }
            }
        }


        $this->conexion->commit();
            return true;

    } catch (Exception $e) {
        $this->conexion->rollBack();
        throw new Exception($e->getMessage());
    }
}

}