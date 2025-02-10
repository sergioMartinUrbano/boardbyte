<?php

class productosController
{
    private repositorioProductos $productosRepositorio;

    public function __construct(repositorioProductos $productosRepositorio)
    {
        $this->productosRepositorio = $productosRepositorio;
    }

    public function addProducto(Productos $Producto)
    {
        $errores = [];

        //los campos no pueden ser nulos
        //el precio no puede ser menor que 0
        //tiene que tener foto de portada obligatoriamente
        //descripcion corta no puede ser mayor de 255 caracteres

        if (!$Producto->getNombre()) {
            array_push($errores, 'El Campo Nombre no puede estar vacío.');
        }

        if (!$Producto->getPrecio()) {
            array_push($errores, 'El Campo Precio no puede estar vacío.');
        } else if ($Producto->getPrecio() < 0) {
            array_push($errores, 'El precio del producto no puede ser menor que 0,');
        }

        if (!$Producto->getDescripcionCorta()) {
            array_push($errores, 'El Campo Descripción Corta no puede estar vacío.');
        } else if (strlen($Producto->getDescripcionCorta()) > 255) {
            array_push($errores, 'La descripción corta no puede tener más de 255 caracteres.');
        }

        if (!$Producto->getDescripcionCompleta()) {
            array_push($errores, 'El Campo Descripción Completa no puede estar vacío.');
        }

        $fotosExtras = $this->manipularFotosAdicionales($_FILES['fotos_adicionales']);
        $fotosFinales = [];
        foreach ($fotosExtras as $item) {
            if ($item['error'] != null) {
                array_push($errores, $item);
            }
            if ($item['nombre'] != null && $item['tmp_name'] != null) {
                $fotosFinales[] = ['nombre' => $item['nombre'], 'tmp_name' => $item['tmp_name']];
            }
        }
        $foto = $_FILES['foto_principal'] ?? null;

        if ($foto) {
            if ($foto['error'] === UPLOAD_ERR_OK) {
                $ruta = BASE_PATH . '/resources/images/productos/portadas/';
                $ruta = str_replace('\\', '/', $ruta);
                $fotoInfo = pathinfo($foto['name']);
                if (!is_dir($ruta)) {
                    mkdir($ruta, 0777, true);
                }
                if (!in_array($fotoInfo['extension'], ['jpg', 'jpeg', 'png', 'gif', 'bmp', 'webp', 'tiff', 'svg', 'heif', 'heic'])) {
                    array_push($errores, 'La extensión no es correcta.');
                }
            } else {
                array_push($errores, 'No se ha subido la imagen.');
            }
        }

        if (empty($errores)) {
            if (!empty($fotosFinales)) {
                foreach ($fotosFinales as $item) {
                    $Producto->addFotos($item['nombre']);
                }
            }
            $nuevoNombre = $Producto->getNombre() . "." . $fotoInfo['extension'];
            $this->productosRepositorio->insertarProducto($Producto, $nuevoNombre);
            if (!move_uploaded_file($foto['tmp_name'], $ruta . $nuevoNombre)) {
                throw new Exception('No se ha podido guardar la foto.');
            }
            $ruta = BASE_PATH . '/resources/images/productos/adicional/';
            $ruta = str_replace('\\', '/', $ruta);
            foreach ($fotosFinales as $item) {
                if (!move_uploaded_file($item['tmp_name'], $ruta . $item['nombre'])) {
                    throw new Exception('Ayúdame señor');

                }
            }
            header('Location: /boardbyte/productos');
            exit;
        } else {
            include '../app/views/errores.php';
        }

    }


    public function manipularFotosAdicionales(array $fotos)
    {
        $fotosExtras = [];


        foreach ($fotos['error'] as $key => $error) {
            if ($error == 0) {
                $fotoInfo = pathinfo($fotos['name'][$key]);
                if (in_array($fotoInfo['extension'], ['jpg', 'jpeg', 'png', 'gif', 'bmp', 'webp', 'tiff', 'svg', 'heif', 'heic'])) {
                    $id = uniqid("", true);
                    $nuevoNombre = $id . "." . $fotoInfo['extension'];
                    array_push($fotosExtras, ['tmp_name' => $fotos['tmp_name'][$key], 'nombre' => $nuevoNombre, 'error' => null]);
                } else {
                    array_push($fotosExtras, ['tmp_name' => null, 'nombre' => null, 'error' => 'Alguna de las fotos no tiene la extensión correcta.']);
                }
            } else if ($error != 4) {
                array_push($fotosExtras, ['tmp_name' => null, 'nombre' => null, 'error' => 'Ha habido una foto errónea.']);
            }
        }
        return $fotosExtras;
    }

    public function eliminarProducto($id)
    {
        $errores = [];
        if ($id == null) {
            array_push($errores, 'El campo ID no puede ser nulo.');
        } else if ($id < 1) {
            array_push($errores, 'El campo ID no puede ser menor de 1.');
        }

        if (empty($errores)) {
            $producto=$this->productosRepositorio->borrarProducto($id);
            $ruta = BASE_PATH . '/resources/images/productos/portadas/';
            $ruta = str_replace('\\', '/', $ruta);
            
            if(file_exists($ruta.$producto->getFotoPortada())){
                if(!unlink($ruta.$producto->getFotoPortada())){
                    throw new Exception('No se ha podido eliminar la foto de portada');
                }
            }
            $ruta = BASE_PATH . '/resources/images/productos/adicional/';
            $ruta = str_replace('\\', '/', $ruta);
            foreach($producto->getFotosAdicionales() as $item){
                if(file_exists($ruta.$item)){
                    if(!unlink($ruta.$item)){
                        throw new Exception('No se ha podido eliminar la foto adicional');
                    }
                }
            }

            header('Location: /boardbyte/productos');
            exit;
        } else {
            include '../app/views/errores.php';

        }


    }

    public function modificarProducto(Productos $Producto)
    {
        $errores = [];

        if ($Producto->getID() == null) {
            array_push($errores, 'El campo ID no puede ser nulo.');
        } else if ($Producto->getID() < 1) {
            array_push($errores, 'El campo ID no puede ser menor de 1.');
        }

        if (!$Producto->getNombre()) {
            array_push($errores, 'El Campo Nombre no puede estar vacío.');
        }

        if (!$Producto->getPrecio()) {
            array_push($errores, 'El Campo Precio no puede estar vacío.');
        } else if ($Producto->getPrecio() < 0) {
            array_push($errores, 'El precio del producto no puede ser menor que 0,');
        }

        if (!$Producto->getDescripcionCorta()) {
            array_push($errores, 'El Campo Descripción Corta no puede estar vacío.');
        } else if (strlen($Producto->getDescripcionCorta()) > 255) {
            array_push($errores, 'La descripción corta no puede tener más de 255 caracteres.');
        }

        if (!$Producto->getDescripcionCompleta()) {
            array_push($errores, 'El Campo Descripción Completa no puede estar vacío.');
        }

        $foto = $_FILES['foto_principal'] ?? null;
        $fotosExtras = $this->manipularFotosAdicionales($_FILES['fotos_adicionales']);
        $fotosFinales = [];
        foreach ($fotosExtras as $item) {
            if ($item['error'] != null) {
                array_push($errores, $item);
            }
            if ($item['nombre'] != null && $item['tmp_name'] != null) {
                $fotosFinales[] = ['nombre' => $item['nombre'], 'tmp_name' => $item['tmp_name']];
            }
        }

        $bandera = false;
        if ($foto) {
            if ($foto['error'] === UPLOAD_ERR_OK) {
                $ruta = BASE_PATH . '/resources/images/productos/portadas/';
                $ruta = str_replace('\\', '/', $ruta);
                $fotoInfo = pathinfo($foto['name']);
                if (!is_dir($ruta)) {
                    mkdir($ruta, 0777, true);
                }
                if (!in_array($fotoInfo['extension'], ['jpg', 'jpeg', 'png', 'gif', 'bmp', 'webp', 'tiff', 'svg', 'heif', 'heic'])) {
                    array_push($errores, 'La extensión no es correcta.');
                } else {
                    $nuevoNombre = $Producto->getNombre() . "." . $fotoInfo['extension'];
                    $bandera = true;
                }
            } else if ($foto['error'] !== 4) {
                array_push($errores, 'No se ha subido la imagen.');
            } else {
                $nuevoNombre = $Producto->getFotoPortada();
            }
        }

        if (empty($errores)) {

            if (!empty($fotosFinales)) {
                foreach ($fotosFinales as $item) {
                    $Producto->addFotos($item['nombre']);
                }
            }

            if ($this->productosRepositorio->modificarProducto($Producto, $nuevoNombre)) {
                if ($bandera) {
                    if (!move_uploaded_file($foto['tmp_name'], $ruta . $nuevoNombre)) {
                        throw new Exception('No se ha podido guardar la foto.');
                    }
                }
                if (!empty($fotosFinales)) {
                    $ruta = BASE_PATH . '/resources/images/productos/adicional/';
                    $ruta = str_replace('\\', '/', $ruta);
                    foreach ($fotosFinales as $item) {
                        if (!move_uploaded_file($item['tmp_name'], $ruta . $item['nombre'])) {
                            throw new Exception('Ayúdame señor');

                        }
                    }
                }
                header('Location: /boardbyte/productos');
                exit;
            } else {
                throw new Exception('Que coño, que coño es un bigmac');
            }

        } else {
            include '../app/views/errores.php';
        }




    }

    public function buscarUnicoProducto($id)
    {
        $errores = [];
        if ($id == null) {
            array_push($errores, 'El campo ID no puede ser nulo.');
        } else if ($id < 1) {
            array_push($errores, 'El campo ID no puede ser menor de 1.');
        }
        if (empty($errores)) {
            $producto = $this->productosRepositorio->obtenerUnicoProducto($id);
            include '../app/views/producto.php';
        } else {
            include '../app/views/errores.php';
        }




    }

    public function buscarTodosLosProductos()
    {
        $productos = $this->productosRepositorio->obtenerTodosProductos();
        include '../app/views/mostrarProductos.php';
    }
    public function formularioCrear()
    {
        include '../app/views/crearProducto.php';
    }
    public function formularioActualizar($id)
    {
        $producto = $this->productosRepositorio->obtenerUnicoProducto($id);
        include '../app/views/actualizarProducto.php';
    }
}
