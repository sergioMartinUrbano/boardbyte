<?php
if(!isset($_SESSION['usuario'])){
    header('Location: /boardbyte/login');
    die;
}