<?php
$productosController = new productosController(new repositorioProductos($conexion));
switch ($action) {
    case 'actualizar':
        $productosController->formularioActualizar($idUrl);
        break;

    case null:
        $productosController->buscarTodosLosProductos();
        break;

    case 'eliminar':
        $productosController->eliminarProducto($idUrl);
        break;

        case 'update':
            $producto = new Productos(
                trim(strip_tags($_POST['id'] ?? null)),
                trim(strip_tags($_POST['nombre'] ?? null)),
                trim(strip_tags($_POST['precio'] ?? null)),
                trim(strip_tags($_POST['foto_portada'] ?? null)),
                trim(strip_tags($_POST['descripcion_corta'] ?? null)),
                trim(strip_tags($_POST['descripcion_completa'] ?? null))
            );
            $productosController->modificarProducto($producto);
            break;
        

    case 'crear':
        $productosController->formularioCrear();
        break;

        case 'create':
            $producto = new Productos(
                0,
                trim(strip_tags($_POST['nombre'] ?? null)),
                trim(strip_tags($_POST['precio'] ?? null)),
                trim(strip_tags($_POST['foto_principal'] ?? null)),
                trim(strip_tags($_POST['descripcion_corta'] ?? null)),
                trim(strip_tags($_POST['descripcion_completa'] ?? null))
            );
            $productosController->addProducto($producto);
            break;
        
    case 'info':
        $productosController->buscarUnicoProducto($idUrl);
        break;
}