<table>
    <thead>
        <tr>
            <th id="id-header">ID</th>
            <th>Foto</th>
            <th>Nombre de Usuario</th>
            <th>Nombre Real</th>
            <th>Correo</th>
            <th>Teléfono</th>
            <th>Direcciones</th>
            <th>Rol</th>
            <th>Acciones</th>
        </tr>
    </thead>
    <tbody>
        <?php
        foreach ($usuarios as $usuario) {
            echo "<tr>";
            echo "<td>".$usuario->getId()."</td>";
                echo "<td><img src='/boardbyte/resources/images/usuarios/". $usuario->getFotoPerfil() . "'></td>";
                echo "<td>" . $usuario->getNombreUsuario() . "</td>";
                echo "<td>" . $usuario->getNombre() . "</td>";
                echo "<td>" . $usuario->getCorreo() . "</td>";
                echo "<td>" . $usuario->getTelefono() . "</td>";
                echo "<td><ul>";
                foreach ($usuario->getDirecciones() as $item) {
                    echo "<li>$item</li>";
                }
                echo "</ul></td>";
                echo "<td>". $usuario->getRol() ."</td>";
                echo '<td>';
                    echo '<div class="actions">
                                <form action="/boardbyte/usuarios/actualizar/'.$usuario->getId().'" method="POST">
                                    <input type="hidden" name="id" value="' . $usuario->getId() . '">
                                    <button type="submit" class="btn-update">Actualizar</button>
                                </form>
                                <form action="/boardbyte/usuarios/eliminar/'.$usuario->getId().'" method="POST">
                                    <input type="hidden" name="id" value="' . $usuario->getId() . '">
                                    <button type="submit" class="btn-delete">Eliminar</button>
                                </form>
                        </div>';
                echo '</td>';
            echo "</tr>";
        }
        ?>

    </tbody>
</table>
<script src="/boardbyte/resources/js/ordenarID.js"></script>
