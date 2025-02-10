<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['carrito'])) {
    $carritoSesion = new carrito(unserialize($_SESSION['usuario'])->getID());
    $_SESSION['carrito'] = serialize($carritoSesion);
} else {
    $carritoSesion = unserialize($_SESSION['carrito']);
    if (!$carritoSesion instanceof carrito) {
        $carritoSesion = new carrito(unserialize($_SESSION['usuario'])->getID());
        $_SESSION['carrito'] = serialize($carritoSesion);
    }
}