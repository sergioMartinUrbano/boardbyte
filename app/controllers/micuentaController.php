<?php
class MicuentaController
{
    private RepositorioUsuario $usuarioRepositorios;
    public function __construct(RepositorioUsuario $usuarioRepositorios)
    {
        $this->usuarioRepositorios = $usuarioRepositorios;
    }
    public function index()
    {
        include '../app/views/micuenta.php';
    }
    public function actualizarMisDatos()
    {
        include '../app/views/actualizarMisDatos.php';
    }
    public function update(usuario $usuario)
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
            $this->usuarioRepositorios->actualizarUsuario($usuario, $nuevoNombre);

            if ($bandera) {
                if (!move_uploaded_file($foto['tmp_name'], $ruta . $nuevoNombre)) {
                    throw new Exception('No se ha podido guardar la foto');
                }
            }
            $_SESSION['usuario'] = serialize(new Usuario($usuario->getId(), $usuario->getNombre(), $usuario->getNombreUsuario(), $usuario->getCorreo(), $usuario->getDireccion1(), $usuario->getDireccion2(), $usuario->getDireccion3(), $usuario->getTelefono(), $nuevoNombre, null, $usuario->getRol()));
            header("Location: /boardbyte/micuenta");
            exit;

        } else {
            include('../app/views/errores.php');
        }
    }

    private function validar(usuario $usuario)
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
    public function borrar($id){
        $this->usuarioRepositorios->eliminarUsuario($id);
        header('Location: /boardbyte/logout');
        exit;
    }

}