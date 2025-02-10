<?php
include '../app/includes/comprobarSesion.php';
include '../app/includes/comprobarRol.php';
?>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulario de Usuario - BoardByte</title>
    <link rel="stylesheet" href="/boardbyte/resources/css/normalize.css">

    <link rel="stylesheet" href="/boardbyte/resources/css/body.css">
    <link rel="stylesheet" href="/boardbyte/resources/css/navbar.css">
    <link rel="stylesheet" href="/boardbyte/resources/css/form.css">
</head>

<body>
<?php include '../resources/commons/navbar.php'; ?>

    <div class="container">
        <h1>Registro de Usuario</h1>
        <form action="/boardbyte/usuarios/create" method="post" enctype="multipart/form-data">
            <h2>Información Obligatoria</h2>
            <div class="form-group">
                <label for="nombre">Nombre:</label>
                <input type="text" id="nombre" name="nombre" required>
            </div>
            <div class="form-group">
                <label for="usuario">Nombre de usuario:</label>
                <input type="text" id="usuario" name="usuario" required>
            </div>
            <div class="form-group">
                <label for="correo">Correo electrónico:</label>
                <input type="email" id="correo" name="correo" required>
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
                <textarea id="direccion" name="direccion" required></textarea>
            </div>

            <h2>Información Opcional</h2>
            <div class="form-group">
                <label for="direccion2">Dirección adicional 1:</label>
                <textarea id="direccion2" name="direccion2"></textarea>
            </div>
            <div class="form-group">
                <label for="direccion3">Dirección adicional 2:</label>
                <textarea id="direccion3" name="direccion3"></textarea>
            </div>
            <div class="form-group">
                <label for="telefono">Teléfono:</label>
                <input type="tel" id="telefono" name="telefono">
            </div>
            <div class="form-group">
                <label for="foto_perfil">Foto de perfil:</label>
                <input type="file" id="foto_perfil" name="foto_perfil" accept="image/*">
            </div>
            <div class="form-group">
                <label for="rol">Rol</label>
                <select name="rol" id="">
                    <option value="usuario">Usuario</option>
                    <option value="admin">Administrador</option>
                </select>
            </div>

            <button type="submit">Registrarse</button>
        </form>
    </div>
</body>

</html>