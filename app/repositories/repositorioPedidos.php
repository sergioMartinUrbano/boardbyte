<?php

class RepositorioPedidos
{
    private PDO $conexion;

    public function __construct($conexion)
    {
        $this->conexion = $conexion;
    }

    public function obtenerTodosPedidos(): array
    {
        $statement = $this->conexion->prepare('select * from pedidos');
        $statement->execute();
        $pedidos = $statement->fetchAll(PDO::FETCH_ASSOC);
        $result = [];
        foreach ($pedidos as $item){
            $pedido = new Pedidos(
                $item['id_pedido'],
                $item['id_usuario'],
                $item['estado'],
                $item['direccion'],
                $item['facturacion'],
                $item['fecha_pedido'],
                $item['fecha_llegada']
            );

            $statement = $this->conexion->prepare('select * from Vista_Detalles_Pedido where id_pedido=:id_pedido');
            $statement->execute(['id_pedido' => $item['id_pedido']]);
            $detalles = $statement->fetchAll(PDO::FETCH_ASSOC);

            foreach ($detalles as $item2) {
                $detalle = new detallesPedidos($item2['id_producto'], $item2['cantidad'], $item2['subtotal'], $item2['nombre_producto']);
                $pedido->addDetallePedido($detalle);
            }
            $result[] = $pedido;
        }
        return $result;
    }
    public function realizarPedido(pedidos $pedido, carrito $carrito)
    {
        $this->conexion->beginTransaction();
        try {
            $statement = $this->conexion->prepare('insert into pedidos (id_usuario, direccion, facturacion)
        values (:id_usuario, :direccion, :facturacion)');
            $statement->execute([
                'id_usuario' => $pedido->getUsuario_ID(),
                'direccion' => $pedido->getDireccion(),
                'facturacion' => $pedido->getFacturacion()
            ]);
            $id_pedido = $this->conexion->lastInsertId();
            foreach ($carrito->getCarrito() as $item) {
                $statement = $this->conexion->prepare('insert into detalles_pedido (id_pedido, id_producto, cantidad, subtotal)
            values (:id_pedido, :id_producto, :cantidad, :subtotal)');
                $statement->execute([
                    'id_pedido' => $id_pedido,
                    'id_producto' => $item['producto']->getIdProducto(),
                    'cantidad' => $item['cantidad'],
                    'subtotal' => $item['cantidad'] * $item['producto']->getPrecio()
                ]);

            }

            $statement = $this->conexion->prepare('delete from carrito where id_usuario=:id_usuario');
            $statement->execute(['id_usuario'=> $pedido->getUsuario_ID()]);
            $this->conexion->commit();

        } catch (Exception $e) {
            $this->conexion->rollBack();
            throw new Exception($e->getMessage());
        }
    }

    public function mispedidos($id): array
    {
        $statement = $this->conexion->prepare('select * from pedidos where id_usuario=:id_usuario');
        $statement->execute(['id_usuario' => $id]);
        $result = $statement->fetchAll(PDO::FETCH_ASSOC);
        $pedidos=[];
        foreach ($result as $item) {
            $pedido = new Pedidos(
                $item['id_pedido'],
                $item['id_usuario'],
                $item['estado'],
                $item['direccion'],
                $item['facturacion'],
                $item['fecha_pedido'],
                $item['fecha_llegada']
            );
            $statement = $this->conexion->prepare('select * from Vista_Detalles_Pedido where id_pedido=:id_pedido');
            $statement->execute(['id_pedido' => $pedido->getPedido_ID()]);
            $detalles = $statement->fetchAll(PDO::FETCH_ASSOC);

            foreach ($detalles as $item2) {
                $detalle = new detallesPedidos($item2['id_producto'], $item2['cantidad'], $item2['subtotal'], $item2['nombre_producto']);
                $pedido->addDetallePedido($detalle);
            }
            $pedidos[] = $pedido;
        }
        return $pedidos;
    }

}

