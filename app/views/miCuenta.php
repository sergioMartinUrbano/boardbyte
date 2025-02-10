<?php

include '../app/includes/comprobarSesion.php';
include '../app/includes/comprobarDivisa.php';
$usuarioSesion = unserialize($_SESSION['usuario']);

?>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $usuarioSesion->getNombreUsuario(); ?></title>
    <link rel="stylesheet" href="/boardbyte/resources/css/normalize.css">

    <link rel="stylesheet" href="/BoardByte/resources/css/body.css">
    <link rel="stylesheet" href="/BoardByte/resources/css/navbar.css">
    <link rel="stylesheet" href="/BoardByte/resources/css/information.css">
</head>

<body>

    <?php include '../resources/commons/navbar.php'; ?>

    <div class="container">
        <div class="header">
            <h1>Información de la cuenta</h1>
            <div class="avatar">
                <img src="/boardbyte/resources/images/usuarios/<?php echo $usuarioSesion->getFotoPerfil() ?>"
                    alt="Avatar del usuario">
            </div>
        </div>
        <div class="content">
            <div class="user-info">
                <div class="details">
                    <div class="detail-item">
                        <p><strong>Nombre:</strong></p>
                        <p><?php echo $usuarioSesion->getNombre(); ?></p>
                        <br>
                    </div>
                    <div class="detail-item">
                        <p><strong>Nombre de Usuario:</strong></p>
                        <p><?php echo $usuarioSesion->getNombreUsuario(); ?></p>
                        <br>
                    </div>
                    <div class="detail-item">
                        <p><strong>Correo:</strong></p>
                        <p><?php echo $usuarioSesion->getCorreo(); ?></p>
                    </div>
                </div>
            </div>

            <div class="addresses">
                <h2>Direcciones</h2>
                <?php
                $direcciones = $usuarioSesion->getDirecciones();
                $direccionEncontrada = false;
                
                foreach ($direcciones as $direccion) {
                    if ($direccion !== null) {
                        $direccionEncontrada = true;
                        break;
                    }
                }

                if (!$direccionEncontrada): ?>
                    <p>No tienes direcciones ahora mismo.</p>
                <?php else: ?>
                    <?php foreach ($direcciones as $index => $direccion): ?>
                        <?php if ($direccion !== null&& $direccion !== ''): ?>
                            <div class="address">
                                <p>Dirección <?php echo $index + 1; ?></p>
                                <address><?php echo htmlspecialchars($direccion); ?></address>
                                <div class="addressAction">
                                    <form action="/boardbyte/cookies/defecto" method="post">
                                        <input type="hidden" name="address"
                                            value="<?php echo htmlspecialchars($direccion); ?>">
                                        <button type="submit">Dirección por defecto</button>
                                    </form>

                                    <form action="/boardbyte/cookies/facturacion" method="post">
                                        <input type="hidden" name="address"
                                            value="<?php echo htmlspecialchars($direccion); ?>">
                                        <button type="submit">Dirección de facturación</button>
                                    </form>
                                </div>
                            </div>
                        <?php endif; ?>
                    <?php endforeach; ?>
                <?php endif; ?>

            </div>
            <div class="preferences">
                <h2>Preferencias</h2>
                <form class="currency-form" action="/boardbyte/cookies/divisa" method="post">
                    <div class="currency-selector">
                        <label for="currency">Elige tu divisa:</label>
                        <select id="currency" name="currency">
                            <option value="eur" <?php echo ($divisa === 'eur') ? 'selected' : ''; ?>>EUR - Euro</option>
                            <option value="usd" <?php echo ($divisa === 'usd') ? 'selected' : ''; ?>>USD - Dólar
                                estadounidense</option>
                            <option value="gbp" <?php echo ($divisa === 'gbp') ? 'selected' : ''; ?>>GBP - Libra esterlina
                            </option>
                        </select>
                        <button type="submit">Guardar Preferencias</button>
                    </div>
                </form>
            </div>
        </div>

        <div class="actions">
            <a href="/boardbyte/logout"><button>Cerrar Sesión</button></a>
            <a href="/boardbyte/micuenta/actualizar"><button>Editar Datos</button></a>
            <a href="/boardbyte/pedidos/mispedidos"><button>Ver Mis Pedidos</button></a>
            <a href="/boardbyte/micuenta/borrar"><button>Borrar Cuenta</button></a>
        </div>
    </div>
</body>

</html>