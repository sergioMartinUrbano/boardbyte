<?php
class RepositorioUsuario
{
    private PDO $conexion;

    public function __construct(PDO $conexion)
    {
        $this->conexion = $conexion;
    }

    public function mostrarUsuarios()
    {
        $statement = $this->conexion->prepare('select * from usuarios');
        $statement->execute();

        $result = $statement->fetchAll(PDO::FETCH_ASSOC);

        $usuarios = [];
        foreach ($result as $item) {
            $usuarios[] = new Usuario(
                $item['id_usuario'],
                $item['nombre'],
                $item['usuario'],
                $item['correo'],
                $item['direccion_1'],
                $item['direccion_2'],
                $item['direccion_3'],
                $item['telefono'],
                $item['foto_perfil'],
                $item['contrasena'],
                $item['rol']
            );
        }

        return $usuarios;
    }

    public function buscarUsuario($id)
    {
        $statement = $this->conexion->prepare('select * from usuarios where id_usuario = :id_usuario');
        $statement->execute(['id_usuario' => $id]);

        $result = $statement->fetch(PDO::FETCH_ASSOC);

        return new Usuario(
            $result['id_usuario'],
            $result['nombre'],
            $result['usuario'],
            $result['correo'],
            $result['direccion_1'],
            $result['direccion_2'],
            $result['direccion_3'],
            $result['telefono'],
            $result['foto_perfil'],
            $result['contrasena'],
            $result['rol']
        );
    }

    public function actualizarUsuario(Usuario $usuario, $nuevoNombre)
    {

        $this->conexion->beginTransaction();
        try {

            $statement = $this->conexion->prepare('update usuarios set nombre = :nombre, 
                                                                                  usuario = :usuario,
                                                                                  correo = :correo,
                                                                                  direccion_1 = :direccion_1,
                                                                                  direccion_2 = :direccion_2,
                                                                                  direccion_3 = :direccion_3,
                                                                                  telefono = :telefono,
                                                                                  foto_perfil = :foto_perfil,
                                                                                  contrasena = :contrasena,
                                                                                  rol = :rol
                                                                where id_usuario = :id_usuario');

            $statement->execute([
                'nombre' => $usuario->getNombre(),
                'usuario' => $usuario->getNombreUsuario(),
                'correo' => $usuario->getCorreo(),
                'direccion_1' => $usuario->getDireccion1(),
                'direccion_2' => $usuario->getDireccion2(),
                'direccion_3' => $usuario->getDireccion3(),
                'telefono' => $usuario->getTelefono(),
                'foto_perfil' => $nuevoNombre,
                'contrasena' => password_hash($usuario->getContraseña(), PASSWORD_DEFAULT),
                'rol' => $usuario->getRol(),
                'id_usuario' => $usuario->getId()
            ]);
            $this->conexion->commit();
        } catch (Exception $e) {
            $this->conexion->rollBack();
            throw new Exception('No se ha podido actualizar el usuario');
        }
    }

    public function eliminarUsuario($id)
    {
        $statement = $this->conexion->prepare('select foto_perfil from usuarios where id_usuario = :id_usuario');
        $statement->execute(['id_usuario' => $id]);
        $foto = $statement->fetch(PDO::FETCH_ASSOC);
        $foto=$foto['foto_perfil'];

        $statement = $this->conexion->prepare('delete from usuarios where id_usuario = :id_usuario');

        $statement->execute(['id_usuario' => $id]);
        return $foto;
    }

    public function insertarUsuario(Usuario $usuario, $nuevoNombre)
    {

        $this->conexion->beginTransaction();
        try {
            $statement = $this->conexion->prepare('insert into usuarios (nombre, usuario, correo, direccion_1, direccion_2, direccion_3, telefono, foto_perfil, contrasena, rol) 
                                                            values (:nombre, :usuario, :correo, :direccion_1, :direccion_2, :direccion_3, :telefono, :foto_perfil, :contrasena, :rol)');

            $statement->execute([
                'nombre' => $usuario->getNombre(),
                'usuario' => $usuario->getNombreUsuario(),
                'correo' => $usuario->getCorreo(),
                'direccion_1' => $usuario->getDireccion1(),
                'direccion_2' => $usuario->getDireccion2(),
                'direccion_3' => $usuario->getDireccion3(),
                'telefono' => $usuario->getTelefono(),
                'foto_perfil' => $nuevoNombre,
                'contrasena' => password_hash($usuario->getContraseña(), PASSWORD_DEFAULT),
                'rol' => $usuario->getRol()
            ]);
            $this->conexion->commit();
        } catch (Exception $e) {
            $this->conexion->rollBack();
            throw new Exception('No se ha podido insertar el usuario');
        }
    }
    public function registrarUsuario(Usuario $usuario)
    {

        try {
            $this->conexion->beginTransaction();
            $statement = $this->conexion->prepare('insert into usuarios (nombre, usuario, correo, contrasena) 
                                                            values (:nombre, :usuario, :correo, :contrasena)');

            $statement->execute([
                'nombre' => $usuario->getNombre(),
                'usuario' => $usuario->getNombreUsuario(),
                'correo' => $usuario->getCorreo(),
                'contrasena' => password_hash($usuario->getContraseña(), PASSWORD_DEFAULT)
            ]);

            $id = $this->conexion->lastInsertId();
            if (!$id) {
                throw new Exception('No se pudo obtener el ID del nuevo usuario');
            }

            $this->conexion->commit();
            return new Usuario($id,$usuario->getNombre(), $usuario->getNombreUsuario(), $usuario->getCorreo(),null, null, null, null, 'default.jpg',null, 'usuario');
             
        } catch (Exception $e) {
            $this->conexion->rollBack();
            throw new Exception('No se ha podido insertar el usuario');
        }
    }
}