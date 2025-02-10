<?php
$controlador = new UsuariosController(new repositorioUsuario($conexion));

switch ($action) {
    case null:
        $controlador->mostrarUsuarios();
        break;
    case 'actualizar':
        if($idUrl==$usuarioSesion->getId()){
            throw new Exception('No puedes actualizar tu propio usuario de esta manera.');
        }
        $controlador->formularioActualizar($idUrl);
        break;
        case 'create':
            $controlador->insertarUsuario(new Usuario(
                0,
                trim(strip_tags($_POST['nombre'] ?? null)),
                trim(strip_tags($_POST['usuario'] ?? null)),
                (filter_var($correo = trim(strip_tags($_POST['correo'] ?? null)), FILTER_VALIDATE_EMAIL)) ? filter_var($correo, FILTER_SANITIZE_EMAIL) : $correo,
                trim(strip_tags($_POST['direccion'] ?? null)),
                trim(strip_tags($_POST['direccion2'] ?? null)),
                trim(strip_tags($_POST['direccion3'] ?? null)),
                trim(strip_tags($_POST['telefono'] ?? null)),
                null,
                trim(strip_tags($_POST['contrasena'] ?? null)),
                trim(strip_tags($_POST['rol'] ?? null))
            ));
            break;
        
    case 'crear':
        $controlador->formularioCrear();
        break;
        case 'update':
            $controlador->actualizarUsuario(new Usuario(
                trim(strip_tags($_POST['id'] ?? null)),
                trim(strip_tags($_POST['nombre'] ?? null)),
                trim(strip_tags($_POST['usuario'] ?? null)),
                (filter_var($correo = trim(strip_tags($_POST['correo'] ?? null)), FILTER_VALIDATE_EMAIL)) ? filter_var($correo, FILTER_SANITIZE_EMAIL) : $correo,
                trim(strip_tags($_POST['direccion'] ?? null)),
                trim(strip_tags($_POST['direccion2'] ?? null)),
                trim(strip_tags($_POST['direccion3'] ?? null)),
                trim(strip_tags($_POST['telefono'] ?? null)),
                trim(strip_tags($_POST['foto_actual'] ?? null)),
                trim(strip_tags($_POST['contrasena'] ?? null)),
                trim(strip_tags($_POST['rol'] ?? null))
            ));
            break;
        
    case 'eliminar':
        if($idUrl==$usuarioSesion->getId()){
            throw new Exception('No puedes eliminar tu propio usuario.');
        }
        $controlador->eliminarUsuario($idUrl);
        break;
    default:
        $vista . 'errores.php';
        break;
}