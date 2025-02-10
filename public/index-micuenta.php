<?php

$controlador = new MicuentaController(new RepositorioUsuario($conexion));
switch ($action) {
    case null:
        $controlador->index();
        break;
    case 'update':
        $controlador->update(new Usuario(
            $usuarioSesion->getId(),
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
    case 'actualizar';
        $controlador->actualizarMisDatos();
        break;
    case 'borrar':
        $controlador->borrar($usuarioSesion->getId());

}