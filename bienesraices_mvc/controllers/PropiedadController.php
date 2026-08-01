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
        $res = $_GET["res"] ?? null;
        $router->render("propiedades/admin", [
            "propiedades" => $propiedades,
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

    public static function actualizar() {
        echo "Admin / Actualizar...";
    }

    public static function eliminar() {
        echo "Admin / Actualizar...";
    }

}