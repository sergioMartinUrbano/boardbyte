<?php

if (isset($_COOKIE['divisa'])) {
    $divisa = strtolower($_COOKIE['divisa']);
    if ($divisa == 'usd') {
        $simbolo = '$';
        $conversion = 1.07;
    } else if ($divisa == 'gbp') {
        $simbolo = '£';
        $conversion = 0.83;
    } else {
        $simbolo = '€';
    }
}else {
    setcookie('divisa', 'eur', time()+3600*24*7, '/');
    $simbolo = '€';
    $divisa='eur';
}

