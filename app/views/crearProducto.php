<?php
include '../app/includes/comprobarSesion.php';
include '../app/includes/comprobarRol.php';
?>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulario de Producto - BoardByte</title>
    <link rel="stylesheet" href="/boardbyte/resources/css/normalize.css">

    <link rel="stylesheet" href="/boardbyte/resources/css/body.css">
    <link rel="stylesheet" href="/boardbyte/resources/css/navbar.css">
    <link rel="stylesheet" href="/boardbyte/resources/css/form.css">
</head>

<body>
    <?php include '../resources/commons/navbar.php'; ?>

    <div class="container">
        <h1>Registro de Producto</h1>
        <form action="/boardbyte/productos/create" method="post" enctype="multipart/form-data">
            <div class="form-group">
                <label for="nombre">Nombre del producto:</label>
                <input type="text" id="nombre" name="nombre" required>
            </div>
            <div class="form-group">
                <label for="descripcion_corta">Descripción corta:</label>
                <input type="text" id="descripcion_corta" name="descripcion_corta" required>
            </div>
            <div class="form-group">
                <label for="descripcion_completa">Descripción completa:</label>
                <textarea id="descripcion_completa" name="descripcion_completa" rows="5" required></textarea>
            </div>
            <div class="form-group">
                <label for="precio">Precio:</label>
                <input type="number" id="precio" name="precio" step="0.01" min="0" required>
            </div>
            <div class="form-group">
                <label for="foto_principal">Foto principal del producto:</label>
                <input type="file" id="foto_principal" name="foto_principal" accept="image/*" required>
            </div>
            <div class="form-group">
                <label for="fotos_adicionales">Fotos adicionales (opcional):</label>
                <input type="file" id="fotos_adicionales" name="fotos_adicionales[]" accept="image/*" multiple>
            </div>
            <button type="submit">Guardar Producto</button>
        </form>
    </div>
</body>

</html>