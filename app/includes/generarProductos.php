<table>
    <thead>
        <tr>
            <th id="id-header">ID</th>
            <th>Foto</th>
            <th>Nombre</th>
            <th>Descripción Corta</th>
            <th id="toggle-descripcion-larga">Descripción Larga</th>
            <th>Precio</th>
            <th>Acciones</th>
        </tr>
    </thead>
    <tbody>
        <?php
        foreach ($productos as $producto) {
            // Preparar descripciones
            $descripcionCorta = mb_substr($producto->getDescripcionCompleta(), 0, 50); // Límite de 50 caracteres
            $descripcionCompleta = $producto->getDescripcionCompleta();

            echo "<tr>";
            echo "<td>" . $producto->getID() . "</td>";
            echo "<td><img src='/boardbyte/resources/images/productos/portadas/" . $producto->getFotoPortada() . "' alt='Imagen del producto'></td>";
            echo "<td>" . $producto->getNombre() . "</td>";
            echo "<td>" . $producto->getDescripcionCorta() . "</td>";
            echo "<td>
                    <span class='descripcion-corta' style='display:none;'>" . htmlspecialchars($descripcionCompleta) . "</span>
                    <span class='descripcion-completa' style='display:block;'>" . htmlspecialchars($descripcionCorta) . "...</span>
                  </td>";
            echo "<td>" . $producto->getPrecio() . "€</td>";
            echo "<td>
                    <div class='actions'>
                        <form action='/boardbyte/productos/actualizar/" . $producto->getID() . "' method='POST' style='display:inline;'>
                            <input type='hidden' name='product_id' value='" . $producto->getID() . "'>
                            <button type='submit' class='btn-update'>Actualizar</button>
                        </form>
                        <form action='/boardbyte/productos/eliminar/" . $producto->getID() . "' method='POST' style='display:inline;'>
                            <input type='hidden' name='product_id' value='" . $producto->getID() . "'>
                            <button type='submit' class='btn-delete'>Eliminar</button>
                        </form>
                    </div>
                  </td>";
            echo "</tr>";
        }
        ?>
    </tbody>
</table>
<script src="/boardbyte/resources/js/ordenarID.js"></script>
<script src="/boardbyte/resources/js/reducirTexto.js"></script>