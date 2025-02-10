<?php

class UsuariosController
{
    private RepositorioUsuario $usuariosRepositorio;

    public function __construct(RepositorioUsuario $usuariosRepositorio)
    {
        $this->usuariosRepositorio = $usuariosRepositorio;
    }

    public function validar(usuario $usuario)
    {
        $errores = [];

        // Validaciones formato
        if ($usuario->getTelefono()) {
            if (!preg_match('/^\d{9,10}$/', $usuario->getTelefono())) {
                $errores[] = 'El teléfono no es válido';
            }
        }

        if ($usuario->getDireccion2()) {
            if (strlen($usuario->getDireccion2()) < 5) {
                $errores[] = 'La dirección debe tener como mínimo 5 caracteres';
            }
        }

        if ($usuario->getDireccion3()) {
            if (strlen($usuario->getDireccion3()) < 5) {
                $errores[] = 'La dirección debe tener como mínimo 5 caracteres';
            }
        }

        return $errores;
    }

    // Funcion para insertar un usuario
    public function insertarUsuario(usuario $usuario)
    {
        $errores = [];
        $foto = $_FILES['foto_perfil'] ?? null;

        // Validaciones
        if (!$usuario->getNombre()) {
            $errores[] = 'No has introducido el nombre';
        }

        if (strlen($usuario->getNombre()) < 3) {
            $errores[] = 'El nombre debe tener como mínimo 3 caracteres';
        }

        if (!$usuario->getNombreUsuario()) {
            $errores[] = 'No has introducido el nombre de usuario';
        }

        if (!$usuario->getCorreo()) {
            $errores[] = 'No has introducido el correo electrónico';
        } else if (!filter_var($usuario->getCorreo(), FILTER_VALIDATE_EMAIL)) {
            $errores[] = 'El formato del correo no es válido';
        }

        if (!$usuario->getDireccion1()) {
            $errores[] = 'No has introducido la dirección';
        }

        if (!$usuario->getContraseña()) {
            $errores[] = 'No has introducido la contraseña';
        }

        if (strlen($usuario->getContraseña()) < 8) {
            $errores[] = 'La contraseña debe tener como mínimo 8 caracteres';
        }

        if (!$usuario->getRol()) {
            $errores[] = 'No has introducido el rol';
        } else if (!in_array($usuario->getRol(), ['admin', 'usuario'])) {
            $errores[] = 'El rol introducido no es válido';
        }

        if ($foto) {
            if ($foto['error'] === UPLOAD_ERR_OK) {
                $ruta = BASE_PATH . '/resources/images/usuarios/';
                $ruta = str_replace('\\', '/', $ruta);

                if (!is_dir($ruta)) {
                    mkdir($ruta, 0777, true);
                }

                $archivo = pathinfo($foto['name']);

                if (in_array($archivo['extension'], ['jpg', 'jpeg', 'png', 'gif', 'bmp', 'webp', 'tiff', 'svg', 'heif', 'heic'])) {

                } else {
                    $errores[] = 'La extensión de la foto no se encuentra entre las válidas';
                }

            } else if ($foto['error'] !== 4) {
                $errores[] = 'La foto se ha subido incorrectamente';
            }
        } else {
            $errores[] = 'No has añadido la foto';
        }


        $erroresAdicionales = $this->validar($usuario);

        if (!empty($erroresAdicionales)) {
            foreach ($erroresAdicionales as $item) {
                $errores[] = $item;
            }
        }

        if (empty($errores)) {
            // Conectarnos a la base de datos
            if (isset($nuevoNombre)) {
                $nuevoNombre = $usuario->getNombreUsuario() . '.' . $archivo['extension'];
            } else {
                $nuevoNombre = 'default.jpg';
            }
            $this->usuariosRepositorio->insertarUsuario($usuario, $nuevoNombre);

            if ($nuevoNombre !== 'default.jpg') {
                if (!move_uploaded_file($foto['tmp_name'], $ruta . $nuevoNombre)) {
                    throw new Exception('No se ha podido guardar la foto');
                }
            }

            header("Location: /boardbyte/usuarios");
            exit;

        } else {
            include('../app/views/errores.php');
        }
    }

    // Funcion para actualizar un usuario
    public function actualizarUsuario(usuario $usuario)
    {
        $errores = [];
        $foto = $_FILES['foto_perfil'] ?? null;

        // Validaciones
        if (!$usuario->getId()) {
            $errores[] = 'El ID de usuario no se encuentra en la base de datos';
        }

        if ($usuario->getId() < 1) {
            $errores[] = 'El ID de usuario no puede ser menor a 1';
        }

        if (!$usuario->getNombre()) {
            $errores[] = 'No has introducido el nombre';
        }

        if (strlen($usuario->getNombre()) < 3) {
            $errores[] = 'El nombre debe tener como mínimo 3 caracteres';
        }

        if (!$usuario->getNombreUsuario()) {
            $errores[] = 'No has introducido el nombre de usuario';
        }

        if (!$usuario->getCorreo()) {
            $errores[] = 'No has introducido el correo electrónico';
        } else if (!filter_var($usuario->getCorreo(), FILTER_VALIDATE_EMAIL)) {
            $errores[] = 'El formato del correo no es válido';
        }

        if (!$usuario->getDireccion1()) {
            $errores[] = 'No has introducido la dirección';
        }

        if (!$usuario->getContraseña()) {
            $errores[] = 'No has introducido la contraseña';
        }

        if (strlen($usuario->getContraseña()) < 8) {
            $errores[] = 'La contraseña debe tener como mínimo 8 caracteres';
        }

        if (!$usuario->getRol()) {
            $errores[] = 'No has introducido el rol';
        }

        if ($foto) {
            if ($foto['error'] === UPLOAD_ERR_OK) {
                $ruta = BASE_PATH . '/resources/images/usuarios/';
                $ruta = str_replace('\\', '/', $ruta);

                if (!is_dir($ruta)) {
                    mkdir($ruta, 0777, true);
                }

                $archivo = pathinfo($foto['name']);

                if (in_array($archivo['extension'], ['jpg', 'jpeg', 'png', 'gif', 'bmp', 'webp', 'tiff', 'svg', 'heif', 'heic'])) {
                    $bandera = true;
                    $nuevoNombre = $usuario->getNombreUsuario() . '.' . $archivo['extension'];
                } else {
                    $errores[] = 'La extensión de la foto no se encuentra entre las válidas';
                }

            } else if ($foto['error'] !== 4) {
                $errores[] = 'La foto se ha subido incorrectamente';
            } else {
                $nuevoNombre = $usuario->getFotoPerfil();
                $bandera = false;
            }
        } else {
            $errores[] = 'No has añadido la foto';
        }

        $erroresAdicionales = $this->validar($usuario);

        if (!empty($erroresAdicionales)) {
            foreach ($erroresAdicionales as $item) {
                $errores[] = $item;
            }
        }

        if (empty($errores)) {
            // Conectarnos a la base de datos
            $this->usuariosRepositorio->actualizarUsuario($usuario, $nuevoNombre);

            if ($bandera) {
                if (!move_uploaded_file($foto['tmp_name'], $ruta . $nuevoNombre)) {
                    throw new Exception('No se ha podido guardar la foto');
                }
            }

            header("Location: /boardbyte/usuarios");
            exit;

        } else {
            include('../app/views/errores.php');
        }
    }

    // Funcion para eliminar un usuario
    public function eliminarUsuario($id)
    {
        $errores = [];

        // Validaciones
        if (!$id) {
            $errores[] = 'No has introducido el id';
        }

        if ($id < 1) {
            $errores[] = 'El id no puede ser menor a 1';
        }

        if (empty($errores)) {
            $foto = $this->usuariosRepositorio->eliminarUsuario($id);
            $ruta = BASE_PATH . '/resources/images/usuarios/';
            $ruta = str_replace('\\', '/', $ruta);

            if (file_exists($ruta . $foto) && $foto !== 'default.jpg') {
                if (!unlink($ruta . $foto)) {
                    throw new Exception('No se ha podido eliminar la foto');
                }
            }
            header("Location: /boardbyte/usuarios");
            exit;

        } else {
            include('../app/views/errores.php');
        }

    }

    // Funcion para buscar un usuario por su ID
    public function buscarUsuario($id)
    {
        $errores = [];

        // Validaciones
        if (!$id) {
            $errores[] = 'No has introducido el id';
        }

        if ($id < 1) {
            $errores[] = 'El id no puede ser menor a 1';
        }

        if (empty($errores)) {
            $usuario = $this->usuariosRepositorio->buscarUsuario($id);

            include('../app/views/cuenta.php');
        } else {
            include('../app/views/errores.php');
        }

    }

    // Funcion para mostrar todos los usuarios registrados en la base de datos
    public function mostrarUsuarios()
    {
        // Conexión
        $usuarios = $this->usuariosRepositorio->mostrarUsuarios();

        include('../app/views/mostrarUsuarios.php');
    }

    public function formularioActualizar($id)
    {
        $usuario = $this->usuariosRepositorio->buscarUsuario($id);

        include('../app/views/actualizarUsuario.php');
    }

    public function formularioCrear()
    {
        include('../app/views/crearUsuario.php');
    }
    public function registrar(usuario $usuario)
    {
        $errores = [];

        // Validaciones
        if (!$usuario->getNombre()) {
            $errores[] = 'No has introducido el nombre';
        }

        if (strlen($usuario->getNombre()) < 3) {
            $errores[] = 'El nombre debe tener como mínimo 3 caracteres';
        }

        if (!$usuario->getNombreUsuario()) {
            $errores[] = 'No has introducido el nombre de usuario';
        }

        if (!$usuario->getCorreo()) {
            $errores[] = 'No has introducido el correo electrónico';
        } else if (!filter_var($usuario->getCorreo(), FILTER_VALIDATE_EMAIL)) {
            $errores[] = 'El formato del correo no es válido';
        }

        if (!$usuario->getContraseña()) {
            $errores[] = 'No has introducido la contraseña';
        }

        if (strlen($usuario->getContraseña()) < 8) {
            $errores[] = 'La contraseña debe tener como mínimo 8 caracteres';
        }



        if (empty($errores)) {


            $usuarioFinal = $this->usuariosRepositorio->registrarUsuario($usuario);
            $_SESSION['usuario'] = serialize($usuarioFinal);

            $_SESSION['carrito'] = serialize(new carrito($usuarioFinal->getId()));

            header('Location: /boardbyte/micuenta');
            exit;

        } else {
            include('../app/views/errores.php');
        }

    }
}