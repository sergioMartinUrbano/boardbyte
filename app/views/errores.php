<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Listado de Errores</title>
    <link rel="stylesheet" href="/boardbyte/resources/css/normalize.css">

    <link rel="stylesheet" href="/boardbyte/resources/css/body.css">
    <link rel="stylesheet" href="/boardbyte/resources/css/navbar.css">
    <link rel="stylesheet" href="/boardbyte/resources/css/errores.css">
</head>
<body>
    <?php include'../resources/commons/navbar.php';?>
    <div class="container">
        <h1 class="error-title">Listado de Errores</h1>
        <p class="error-description">Aquí puedes ver los errores registrados:</p>
        <ul class="error-list">
            <?php foreach ($errores as $error): ?>
                <li class="error-item">
                    <p><?php echo htmlspecialchars($error); ?></p>
                </li>
            <?php endforeach; ?>
        </ul>
        <a href="/boardbyte/" class="error-button">Volver al inicio</a>
    </div>
</body>
</html>
