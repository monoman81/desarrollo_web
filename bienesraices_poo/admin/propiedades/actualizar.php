<?php
    require "../../includes/app.php";
    autenticado();

    use App\Propiedad;
    use App\Vendedor;
    use Intervention\Image\ImageManager;
    use Intervention\Image\Drivers\Gd\Driver;

    $id = $_GET["id"];
    $id = filter_var($id, FILTER_VALIDATE_INT);

    if (!$id)
        header("Location: /admin");

    $vendedores = Vendedor::all();
    $propiedad = Propiedad::find($id);
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
            if ($imagen)
                $imagen->save(CARPETA_IMAGENES . $nombreImagen);

            if ($resultado)
                header("location: /admin?res=2");
        }

    }

    
    incluirTemplate("header")
?>
    <main class="contenedor seccion">
        <h1>Actualizar Propiedad</h1>
        <a href="/admin" class="boton boton-verde">Volver</a>

        <?php foreach($errores as $error): ?>
            <div class="alerta error">
                <?php echo $error ?>
            </div>
        <?php endforeach ?>

        <form action="" class="formulario" method="POST" enctype="multipart/form-data">
            <?php include "../../includes/templates/formulario_propiedades.php" ?>

            <input type="submit" value="Actualizar Propiedad" class="boton boton-verde">

        </form>

    </main>

<?php 
    incluirTemplate("footer");
?>