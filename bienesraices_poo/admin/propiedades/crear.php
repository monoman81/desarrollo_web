<?php
    require "../../includes/app.php";

    use App\Propiedad;
    use App\Vendedor;
    use Intervention\Image\ImageManager;
    use Intervention\Image\Drivers\Gd\Driver;

    autenticado();

    $vendedores = Vendedor::all();

    $errores = Propiedad::getErrores();
    
    $propiedad = new Propiedad;

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
    
    incluirTemplate("header")
?>
    <main class="contenedor seccion">
        <h1>Crear Propiedad</h1>
        <a href="/admin" class="boton boton-verde">Volver</a>

        <?php foreach($errores as $error): ?>
            <div class="alerta error">
                <?php echo $error ?>
            </div>
        <?php endforeach ?>

        <form action="" class="formulario" method="POST" action="<?php echo $_SERVER["SCRIPT_NAME"] ?>" enctype="multipart/form-data">
            <?php include "../../includes/templates/formulario_propiedades.php" ?>
            <input type="submit" value="Crear Propiedad" class="boton boton-verde">

        </form>

    </main>

<?php 
    incluirTemplate("footer");
?>