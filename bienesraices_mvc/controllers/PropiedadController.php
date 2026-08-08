<?php

namespace Controllers;
use MVC\Router;
use Model\Propiedad;
use Model\Vendedor;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class PropiedadController {

    public static function index(Router $router) {
        $propiedades = Propiedad::all();
        $vendedores = Vendedor::all();
        $res = $_GET["res"] ?? null;
        $router->render("propiedades/admin", [
            "propiedades" => $propiedades,
            "vendedores" => $vendedores,
            "res" => $res
        ]);
    }

    public static function crear(Router $router) {
        $propiedad = new Propiedad;
        $vendedores = Vendedor::all();
        $errores = Propiedad::getErrores();

        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            
            $propiedad = new Propiedad($_POST["propiedad"]);
            $nombreImagen = md5(uniqid(rand(), true)) . ".jpg";
            if ($_FILES["propiedad"]["tmp_name"]["imagen"]) {
                $manager = ImageManager::usingDriver(Driver::class);
                $imagen = $manager->decodePath($_FILES["propiedad"]["tmp_name"]["imagen"]);
                $imagen->cover(800, 600);
                $propiedad->setImagen($nombreImagen);
            }
            $errores = $propiedad->validar();

            if (empty($errores)) {
                
                if (!is_dir(CARPETA_IMAGENES))
                    mkdir(CARPETA_IMAGENES);
                
                $imagen->save(CARPETA_IMAGENES . $nombreImagen);

                $resultado = $propiedad->guardar();

                if ($resultado)
                    header("Location: /admin?res=1");
            }
            else
                $propiedad->imagen = null;
        }

        $router->render("propiedades/crear", [
            "propiedad" => $propiedad,
            "vendedores" => $vendedores,
            "errores" => $errores
        ]);
    }

    public static function actualizar(Router $router) {
        $id = validarORedireccionar("/admin");
        $propiedad = Propiedad::find($id);
        $vendedores = Vendedor::all();
        $errores = Propiedad::getErrores();

        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $args = [];
            $args = $_POST["propiedad"];
            
            $propiedad->sincronizar($args);

            $errores = $propiedad->validar();

            if (empty($errores)) {
                $nombreImagen = md5(uniqid(rand(), true)) . ".jpg";
                if ($_FILES["propiedad"]["tmp_name"]["imagen"]) {
                    $manager = ImageManager::usingDriver(Driver::class);
                    $imagen = $manager->decodePath($_FILES["propiedad"]["tmp_name"]["imagen"]);
                    $imagen->cover(800, 600);
                    $propiedad->setImagen($nombreImagen);
                }

                $resultado = $propiedad->guardar();
                if (isset($imagen))
                    $imagen->save(CARPETA_IMAGENES . $nombreImagen);
                if ($resultado)
                    header("location: /admin?res=2");
            }

        }

        $router->render("propiedades/actualizar", [
            "propiedad" => $propiedad,
            "vendedores" => $vendedores,
            "errores" => $errores
        ]);
    }

    public static function eliminar() {
        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $id = $_POST["id"];
            $id = filter_var($id, FILTER_VALIDATE_INT);
            if (!$id)
                header("Location: /admin");
            $propiedad = Propiedad::find($id);
            $resultado = $propiedad->eliminar();
            if ($resultado) {
                header("location: /admin?res=3");
            }
        }
    }

}