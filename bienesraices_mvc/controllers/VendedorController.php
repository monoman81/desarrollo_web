<?php

namespace Controllers;
use MVC\Router;
use Model\Vendedor;

class VendedorController {

    public static function crear(Router $router) {
        $vendedor = new Vendedor;
        $errores = Vendedor::getErrores();

        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $vendedor = new Vendedor($_POST["vendedor"]);
            $errores = $vendedor->validar();

            if (empty($errores)) {
                $resultado = $vendedor->guardar();
                if ($resultado)
                    header("Location: /admin?res=1");
            }
        }

        $router->render("vendedores/crear", [
            "vendedor" => $vendedor,
            "errores" => $errores
        ]);
    }

    public static function actualizar(Router $router) {
        $id = validarORedireccionar("/admin");
        $vendedor = Vendedor::find($id);
        $errores = Vendedor::getErrores();

        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $args = [];
            $args = $_POST["vendedor"];
            
            $vendedor->sincronizar($args);

            $errores = $vendedor->validar();

            if (empty($errores)) {
                $resultado = $vendedor->guardar();
                if ($resultado)
                    header("location: /admin?res=2");
            }

        }

        $router->render("vendedores/actualizar", [
            "vendedor" => $vendedor,
            "errores" => $errores
        ]);
    }

    public static function eliminar() {
        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $id = $_POST["id"];
            $id = filter_var($id, FILTER_VALIDATE_INT);
            if (!$id)
                header("Location: /admin");
            $vendedor = Vendedor::find($id);
            $resultado = $vendedor->eliminar();
            if ($resultado) {
                header("location: /admin?res=3");
            }
        }
    }

}