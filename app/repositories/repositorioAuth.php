<?php
    class repositorioAuth {
        private $conexion;

        public function __construct(PDO $conexion) {
            $this->conexion = $conexion;
        }
        
        public function buscarUsuario($usuario) {
            $statement = $this->conexion->prepare('select * from usuarios where usuario = :usuario');
            $statement->execute(['usuario'=> $usuario]);

            $result = $statement->fetch(PDO::FETCH_ASSOC);

            if (!$result || empty($result)) {
                throw new Exception('Usuario no encontrado');
            }

            return new Usuario($result['id_usuario'], 
                                   $result['nombre'],
                            $result['usuario'],
                                   $result['correo'],
                               $result['direccion_1'],
                               $result['direccion_2'],
                               $result['direccion_3'],
                                 $result['telefono'],
                              $result['foto_perfil'],
                               $result['contrasena'],
                                      $result['rol']);
        }
    }