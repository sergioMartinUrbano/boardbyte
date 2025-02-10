<?php
$dsn = 'mysql:host=localhost;dbname=boardbyte;charset=utf8';
$username = 'root';
$password = '';
$conexion = new PDO($dsn, $username, $password);
$conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);