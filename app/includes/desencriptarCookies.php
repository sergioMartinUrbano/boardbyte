<?php

function desencriptar($encryptedData) {
    $clave = hash('sha256', 'croquetas', binary: true);
    $decodedData = base64_decode(string: $encryptedData);
    $iv = substr($decodedData, 0, 16);
    $ciphertext = substr($decodedData, 16);
    $decryptedData = openssl_decrypt($ciphertext, 'AES-256-CBC', $clave, OPENSSL_RAW_DATA, $iv);
    return $decryptedData;
}



