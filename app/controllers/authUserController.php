<?php
class authUserController{
    private repositorioAuth $repositorioAuth;

    public function __construct($repositorioAuth) {
        $this->repositorioAuth = $repositorioAuth;
    }
    
    public function loginUsuario(AuthUser $authUsuario) {
        if (!$authUsuario->getNombreUsuario()) {
            throw new Exception('El usuario no tiene nombre');
        }

        if (!$authUsuario->getContraseña()) {
            throw new Exception('El usuario no tiene contraseña');
        }

        $usuario = $this->repositorioAuth->buscarUsuario($authUsuario->getNombreUsuario());

        if (!$usuario) {
            throw new Exception('No se ha encontrado al usuario');
        }

        if (!password_verify($authUsuario->getContraseña(), $usuario->getContraseña())) {
            throw new Exception('Las contraseñas no coinciden');
        }

        $_SESSION['usuario'] = serialize($usuario);

        return $usuario->getId();

    }
    
    public function logoutUsuario() {
        foreach($_COOKIE as $item=>$value){
            setcookie($item,'',time() - 3600, '/');
        }
        session_unset();
        session_destroy();

        header('Location: /boardbyte/');
        exit;
    }
    
    public function index() {
        include('../app/views/login.php');
    }
}
