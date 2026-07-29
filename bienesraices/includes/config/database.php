<?php

function conectarDB(): mysqli {
    $db = mysqli_connect("localhost", "root", "12345678", "bienesraices");
    if (!$db)
    {
        echo "No se pudo conectar...";
        exit;
    }
    
    return $db;

}