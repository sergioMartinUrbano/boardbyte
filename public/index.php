<?php
session_start();
ob_start();
?>
<!DOCTYPE html>
<html lang="es">
<?php
$vista = '../app/views/';//esto es para ir llamando a las vistas, debemos cambiarlo a controller según sea necesario
try {

    // public/index.php
    define('BASE_PATH', dirname(__DIR__)); // Esto apunta a la raíz del proyecto

    include '../config/database.php'; //archivo de configuración para conectarnos a la base de datos.
    include '../config/loader.php'; //archivo donde se carga todo.

    $uri = trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/');
    $uriSegments = explode('/', $uri);

    //todas las rutas deben ser /boardbyte/...
    //omitimos el $uriSegments['0'] por defecto ya que nos devolverá siempre boardbyte;
    $resource = $uriSegments[1] ?? null; //si esto es nulo es index o inicio, de resto indica a donde quiere ir; default es error404.
    $action = $uriSegments[2] ?? null; //si lo anterior es una tabla de nuestra BD, miramos la acción que quiera hacer (CRUD+A);
    $idUrl = $uriSegments[3] ?? null; //para saber el ID del elemento que queremos modificar;


    //este es el objeto usuario de la sesión, para tenerlo a mano y no estar llamando a la sesión todo el rato
    //al hacer un update hay que realizar una transacción por si acaso.
    //Si todo es correcto se crea un nuevo usuario con todos los datos (el de parámetros) y se le asigna a la sesion
    if (isset($_SESSION['usuario'])) {
        include '../app/includes/comprobarTiempoSesion.php';
        $usuarioSesion = unserialize($_SESSION['usuario']);
    }

    //esto es el carrito, por si acaso yo que sé
    if (isset($_SESSION['carrito'])) {
        $carritoSesion = unserialize($_SESSION['carrito']);
        // foreach ($carritoSesion->getCarrito() as $key => $value) {
        //     echo '<br>ID '.$value['id'];
        //     echo '<br>cantidad '.$value['cantidad'];
        //     $producto=$value['producto'];
        //     echo '<br> Nombre: '. $producto->getNombreProducto();
        //     echo '<br> ID: '. $producto->getIdProducto();
        //     echo '<br> PRECIO: '. $producto->getPrecio();
        //     echo '<br> FOTO: '. $producto->getFotoPortada();
        //     echo '<br><br>';
        // }   
    }

    switch ($resource) {
        case 'cookies':
            if (isset($usuarioSesion)) {
                $cookieController = new cookieController();
                if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                    switch ($action) {
                        case 'divisa':
                            $cookieController->crearDivisa($_POST['currency'] ?? null);
                            break;
                        case 'defecto':
                            $cookieController->crearDirecciónDefecto($_POST['address']);
                            break;
                        case 'facturacion':
                            $cookieController->crearDirecciónFacturacion($_POST['address']);
                            break;
                    }
                }
            }
            header('Location: /boardbyte/micuenta');
            exit;

        case 'carrito':
            if (isset($usuarioSesion)) {
                $carritoController = new CarritoController(new repositorioCarrito($conexion), $carritoSesion);
                switch ($action) {
                    case 'add':
                        $carritoController->añadirElemento($idUrl);
                        break;
                    case 'eliminar':
                        $carritoController->eliminarElemento($idUrl);
                        break;
                    case 'guardar':
                        $carritoController->saveCarritoDatabase();
                        break;
                    case 'vaciar':
                        $carritoController->vaciarCarrito();
                        break;
                }
            }
            header('Location: /boardbyte/catalogo');
            exit;

        case 'productos':
            include './index-productos.php';
            break;

        case 'usuarios':
            include './index-usuarios.php';
            break;

        case 'micuenta':
            include './index-micuenta.php';
            break;

        case 'pedidos':
            include './index-pedidos.php';
            break;

        case 'login':
            $authController = new authUserController(new repositorioAuth($conexion));
            $authController->index();
            break;

        case 'logout':
            $authController = new authUserController(new repositorioAuth($conexion));
            $authController->logoutUsuario();
            break;

        case 'loginUsuario':
            $authController = new authUserController(new repositorioAuth($conexion));
            $id = $authController->loginUsuario(new AuthUser((filter_var($username = trim(strip_tags($_POST['login-username'] ?? null)), FILTER_VALIDATE_EMAIL)) ? filter_var($username, FILTER_SANITIZE_EMAIL) : $username, trim(strip_tags($_POST['login-password'] ?? null))));

            $carritoController = new carritoController(new RepositorioCarrito($conexion), new carrito($id));
            $carritoController->getCarritoDatabase();
            header('Location: /boardbyte/micuenta');
            exit;

        case 'catalogo':
            $repositorio = new otrosRepositorio($conexion);
            $controller = new otrosController($repositorio);
            $controller->catalogo();
            break;

        case 'registrar':
            $controlador = new UsuariosController(new RepositorioUsuario($conexion));
            $controlador->registrar(new Usuario(
                0,
                trim(strip_tags($_POST['register-name'] ?? null)),
                trim(strip_tags($_POST['register-username'] ?? null)),
                (filter_var($email = trim(strip_tags($_POST['register-email'] ?? null)), FILTER_VALIDATE_EMAIL)) ? filter_var($email, FILTER_SANITIZE_EMAIL) : $email,
                NULL, NULL, NULL, NULL, NULL,
                trim(strip_tags($_POST['register-password'] ?? null)),
                null
            ));
            

        case 'admin':
            $repositorio = new otrosRepositorio($conexion);
            $controller = new otrosController($repositorio);
            $controller->admin();
break;
        default:
            include 'error404.html';
            break;

        case null:
            $repositorio = new otrosRepositorio($conexion);
            $controller = new otrosController($repositorio);
            $controller->index();
            break;
    }
} catch (Exception $e) {
    $errores[] = $e->getMessage();
    include $vista . 'errores.php';
}
?>
</html>
<?php
ob_end_flush();
?>