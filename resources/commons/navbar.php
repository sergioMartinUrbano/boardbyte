<nav class="navbar">
    <div class="navbar-container">
        <a href="/boardbyte/" class="navbar-logo"><img src="/boardbyte/resources/images/logo.png" alt="Boardbyte"></a>
        <ul class="navbar-menu">
            <li><a href="/boardbyte/admin" class="navbar-link">Administración</a></li>
            <li><a href="/boardbyte/catalogo" class="navbar-link">Catálogo</a></li>
            <li><a href="/boardbyte/micuenta"
                    class="navbar-link"><?php echo isset($_SESSION['usuario']) ? htmlspecialchars(unserialize($_SESSION['usuario'])->getNombreUsuario()) : 'Cuenta'; ?></a>
            </li>
        </ul>
    </div>
</nav>