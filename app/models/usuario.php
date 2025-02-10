<?php 

class Usuario {
    private $id_usuarios;
    private $nombre;
    private $nombreUsuario;
    private $correo;
    private $direccion1;
    private $direccion2;
    private $direccion3;
    private $telefono;
    private $foto_perfil;
    private $contraseña;
    private $rol;

    public function __construct($id_usuarios, $nombre, $nombreUsuario, $correo, $direccion1, $direccion2, $direccion3, $telefono, $foto_perfil, $contraseña, $rol)
    {
        $this->id_usuarios = $id_usuarios;
        $this->nombre = $nombre;
        $this->nombreUsuario = $nombreUsuario;
        $this->correo = $correo;
        $this->direccion1 = $direccion1;
        $this->direccion2 = $direccion2;
        $this->direccion3 = $direccion3;
        $this->telefono = $telefono;
        $this->foto_perfil = $foto_perfil;
        $this->contraseña = $contraseña;
        $this->rol = $rol;
    }

    public function getId() {
        return $this->id_usuarios;
    }

    public function getNombre() {
        return $this->nombre;
    }

    public function getNombreUsuario() {
        return $this->nombreUsuario;
    }

    public function getCorreo() {
        return $this->correo;
    }

    public function getDireccion1() {
        return $this->direccion1;
    }

    public function getDireccion2() {
        return $this->direccion2;
    }

    public function getDireccion3() {
        return $this->direccion3;
    }

    public function getTelefono() {
        return $this->telefono;
    }

    public function getFotoPerfil() {
        return $this->foto_perfil;
    }

    public function getContraseña() {
        return $this->contraseña;
    }

    public function getRol() {
        return $this->rol;
    }

    // Función para obtener un array con las 3 direcciones que introduce el usuario
    public function getDirecciones(): array {
        return [$this->getDireccion1(), $this->getDireccion2(), $this->getDireccion3()];
    }

}
