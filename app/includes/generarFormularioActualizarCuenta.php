<form action="/boardbyte/micuenta/update" method="post" enctype="multipart/form-data">
    <h2>Información Obligatoria</h2>
    <div class="form-group">
        <label for="nombre">Nombre:</label>
        <input type="text" id="nombre" name="nombre" value="<?php echo $usuario->getNombre(); ?>" required>
    </div>
    <div class="form-group">
        <label for="usuario">Nombre de usuario:</label>
        <input type="text" id="usuario" name="usuario" value="<?php echo $usuario->getNombreUsuario(); ?>" required>
    </div>
    <div class="form-group">
        <label for="correo">Correo electrónico:</label>
        <input type="email" id="correo" name="correo" value="<?php echo $usuario->getCorreo(); ?>" required>
    </div>
    <div class="form-group">
        <label for="contrasena">Contraseña:</label>
        <input type="password" id="contrasena" name="contrasena" required>
    </div>
    <div class="form-group">
        <label for="confirmar_contrasena">Confirmar contraseña:</label>
        <input type="password" id="confirmar_contrasena" name="confirmar_contrasena" required>
    </div>
    <div class="form-group">
        <label for="direccion">Dirección:</label>
        <textarea id="direccion" name="direccion" required><?php echo $usuario->getDirecciones()[0] ?? ''; ?></textarea>
    </div>

    <h2>Información Opcional</h2>
    <div class="form-group">
        <label for="direccion2">Dirección adicional 1:</label>
        <textarea id="direccion2" name="direccion2"><?php echo $usuario->getDirecciones()[1] ?? ''; ?></textarea>
    </div>
    <div class="form-group">
        <label for="direccion3">Dirección adicional 2:</label>
        <textarea id="direccion3" name="direccion3"><?php echo $usuario->getDirecciones()[2] ?? ''; ?></textarea>
    </div>
    <div class="form-group">
        <label for="telefono">Teléfono:</label>
        <input type="tel" id="telefono" name="telefono" value="<?php echo $usuario->getTelefono(); ?>">
    </div>
    <div class="form-group">
        <label for="foto_perfil">Foto de perfil:</label>
        <input type="file" id="foto_perfil" name="foto_perfil" accept="image/*">
        <?php if ($usuario->getFotoPerfil()): ?>
            <img src="/boardbyte/resources/images/usuarios/<?php echo $usuario->getFotoPerfil(); ?>" 
     alt="Foto de perfil actual: <?php echo $usuario->getFotoPerfil(); ?>">
        <?php endif; ?>
    </div>

    <input type="hidden" name="id" value="<?php echo $usuario->getID(); ?>">
    <input type="hidden" name="foto_actual" value="<?php echo $usuario->getFotoPerfil(); ?>">
    <input type="hidden" name="rol" value="<?php echo $usuario->getRol(); ?>">
    <button type="submit">Actualizar tus datos</button>
    <p><i>Nota: los campos vacíos obligatorios darán error. Los valores por defecto son los originales. Se sustituirá
            cualquier elemento introducido.</i></p>
</form>