<?php 

class AuthUser {
    private $nombreUsuario;
    private $contraseña;

    public function __construct($nombreUsuario, $contraseña)
    {
        $this->nombreUsuario = $nombreUsuario;
        $this->contraseña = $contraseña;
    }

    public function getNombreUsuario() {
        return $this->nombreUsuario;
    }

    public function getContraseña() {
        return $this->contraseña;
    }
}