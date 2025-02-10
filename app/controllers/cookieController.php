<?php
class CookieController
{
    function encriptar($data) {
        $clave = hash('sha256', 'croquetas', true);
        $iv = openssl_random_pseudo_bytes(16);
        $encryptedData = openssl_encrypt($data, 'AES-256-CBC', $clave, OPENSSL_RAW_DATA, $iv);
        return base64_encode($iv . $encryptedData);
    }
    

    public function crearDirecciónFacturacion(string $direccion)
    {
        $direccion ? setcookie('facturacion', $direccion, time() + 3600 * 7, '/') : throw new Exception('La dirección de facturación no es válida');
        header('Location: ' . $_SERVER['REQUEST_URI']);
        die;
    }
    public function crearDirecciónDefecto(string $direccion)
    {
        $direccion ? setcookie('defecto', $direccion, time() + 3600 * 7, '/') : throw new Exception('La dirección por defecto no es válida');
        header('Location: ' . $_SERVER['REQUEST_URI']);
        die;
    }
    public function crearDivisa(string $divisa)
    {
        in_array($divisa, ['eur', 'usd', 'gbp']) ? setcookie('divisa', $divisa, time() + 3600 * 7, '/') : throw new Exception('La divisa no es válida');
        header('Location: ' . $_SERVER['REQUEST_URI']);
        die;
    }

    public function crearDatosTarjeta(array $tarjeta)
    {
        $numero = $this->encriptar($tarjeta['numero']);
        $fecha = $this->encriptar($tarjeta['fecha']);
        $cvc = $this->encriptar($tarjeta['cvc']);
    
        setcookie('tarjeta', serialize(['numero' => $numero, 'fecha' => $fecha, 'cvc' => $cvc]), time() + 3600 * 7, '/');
    }



}