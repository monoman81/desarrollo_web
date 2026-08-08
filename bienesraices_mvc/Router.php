<?php

namespace MVC;

class Router {

    public $rutasGET = [];
    public $rutasPOST = [];

    public function get($url, $fn) {
        $this->rutasGET[$url] = $fn;
    }

    public function post($url, $fn) {
        $this->rutasPOST[$url] = $fn;
    }

    public function comprobarRutas() {
        session_start();

        $rutas_protegidas = [
            "/admin", 
            "/propiedades/crear", 
            "/propiedades/actualizar",
            "/propiedades/eliminar",
            "/vendedores/crear", 
            "/vendedores/actualizar",
            "/vendedores/eliminar",
            "/logout"
        ];
        $auth = $_SESSION["logged"] ?? false;

        $urlActual = strtok($_SERVER["REQUEST_URI"], "?") ?? "/";
        $method = $_SERVER["REQUEST_METHOD"];

        if ($method === "GET") {
            $fn = $this->rutasGET[$urlActual] ?? null;
        }
        if ($method === "POST") {
            $fn = $this->rutasPOST[$urlActual] ?? null;
        }

        //Protegiendo Rutas
        if (in_array($urlActual, $rutas_protegidas) && !$auth) {
           header("Location: /");
        }

        if ($fn) {
            call_user_func($fn, $this);
        }
        else {
            echo "Pagina No Encontrada";
        }
    }

    public function render($view, $datos = []) {
        foreach($datos as $key => $value) {
            $$key = $value;
        }
        $view_full_path = __DIR__ . "/views/$view.php";
        ob_start();
        include $view_full_path;
        $contenido = ob_get_clean();
        include __DIR__ . DIRECTORY_SEPARATOR . "views" . DIRECTORY_SEPARATOR . "layout.php";
    }

}