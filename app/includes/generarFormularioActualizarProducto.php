<form action="/boardbyte/productos/update" method="post" enctype="multipart/form-data">
    <div class="form-group">
        <label for="nombre">Nombre del producto:</label>
        <input type="text" id="nombre" name="nombre" value="<?= htmlspecialchars($producto->getNombre()) ?>" required>
    </div>
    <div class="form-group">
        <label for="descripcion_corta">Descripción corta:</label>
        <input type="text" id="descripcion_corta" name="descripcion_corta" value="<?= htmlspecialchars($producto->getDescripcionCorta()) ?>" required>
    </div>
    <div class="form-group">
        <label for="descripcion_completa">Descripción completa:</label>
        <textarea id="descripcion_completa" name="descripcion_completa" rows="5" required><?= htmlspecialchars($producto->getDescripcionCompleta()) ?></textarea>
    </div>
    <div class="form-group">
        <label for="precio">Precio (en euros):</label>
        <input type="number" id="precio" name="precio" step="0.01" min="0" value="<?= htmlspecialchars($producto->getPrecio()) ?>" required>
    </div>
    <div class="form-group">
        <label for="foto_principal">Foto principal del producto (sustituir):</label>
        <input type="file" id="foto_principal" name="foto_principal" accept="image/*">
        <?php if ($producto->getFotoPortada()): ?>
            <p>Portada actual: <?= htmlspecialchars($producto->getFotoPortada()) ?></p>
            <input type="hidden" name="foto_portada" value="<?php echo $producto->getFotoPortada(); ?>">
        <?php endif; ?>
    </div>
    <div class="form-group">
        <label for="fotos_adicionales">Fotos adicionales (añadir):</label>
        <input type="file" id="fotos_adicionales" name="fotos_adicionales[]" accept="image/*" multiple>

        <?php if (!empty($producto->getFotosAdicionales())): ?>
            <p>Fotos adicionales actuales:</p>
            <ul>
                <?php foreach ($producto->getFotosAdicionales() as $foto): ?>
                    <li><?= htmlspecialchars($foto) ?></li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>
    </div>
    <input type="hidden" name="id" value="<?php echo $producto->getID(); ?>">
    <button type="submit">Actualizar Producto</button>
    <p><i>Nota: Los valores por defecto son los originales. Se sustituirá cualquier elemento introducido.</i></p>

</form>
