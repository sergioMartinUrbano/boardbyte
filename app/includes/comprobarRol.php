<?php
if(unserialize($_SESSION['usuario'])->getRol()!=='admin'){
    header('Location: /boardbyte/micuenta');
    die;
}