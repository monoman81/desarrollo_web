<?php

define("TEMPLATE_URL", __DIR__ . "/templates");
define("FUNCIONES_URL", __DIR__ . "funciones.php");
define("CARPETA_IMAGENES", __DIR__ . "/../imagenes/");

function incluirTemplate(string $nombre, bool $inicio = false)
{
    include TEMPLATE_URL."/$nombre.php";
}

function autenticado(): void {
    session_start();
    $auth = isset($_SESSION["logged"]) && $_SESSION["logged"];    
    if (!$auth)
        header("Location: /");
}

function debug($variable) {
    echo "<pre>";
    var_dump($variable);
    echo "</pre>";
    exit;
}

function s($html): string {
    $s = htmlspecialchars($html);
    return $s;
}

function mostrarNofificacion($codigo) {
    $mensaje = "";
    switch($codigo) {
        case 1:
            $mensaje = "Creado Correctamente";
            break;
        case 2:
            $mensaje = "Actualizado Correctamente";
            break;
        case 3:
            $mensaje = "Eliminado Correctamente";
            break;
        default:
            $mensaje = "";
            break;
    }
    return $mensaje;
}