<?php

function conectarDB(): mysqli {
    $db = new mysqli("localhost", "root", "12345678", "bienesraices");
    if (!$db)
    {
        echo "No se pudo conectar...";
        exit;
    }
    
    return $db;

}