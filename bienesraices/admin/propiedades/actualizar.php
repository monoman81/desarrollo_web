<?php
    require "../../includes/funciones.php";
    if (!autenticado()) {
        header("location: /");
    }

    require "../../includes/config/database.php";

    $id=$_GET["id"];
    $id = filter_var($id, FILTER_VALIDATE_INT);

    if (!$id)
        header("Location:/admin");

    $db = conectarDB();

    $consulta = "SELECT * FROM vendedores";
    $vendedores = mysqli_query($db, $consulta);

    
    $consulta = "SELECT * FROM propiedades WHERE id=$id";
    $resultado = mysqli_query($db, $consulta);
    $propiedad = mysqli_fetch_assoc($resultado);

    $errores = [];
    $titulo = $propiedad["titulo"];
    $precio = $propiedad["precio"];
    $descripcion = $propiedad["descripcion"];
    $habitaciones = $propiedad["habitaciones"];
    $wc = $propiedad["wc"];
    $estacionamiento = $propiedad["estacionamiento"];
    $vendedor_id = $propiedad["vendedor_id"];
    $imagen = $propiedad["imagen"];

    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        $titulo = mysqli_real_escape_string($db, $_POST["titulo"]);
        $precio = mysqli_real_escape_string($db, $_POST["precio"]);
        $descripcion = mysqli_real_escape_string($db, $_POST["descripcion"]);
        $habitaciones = mysqli_real_escape_string($db, $_POST["habitaciones"]);
        $wc = mysqli_real_escape_string($db, $_POST["wc"]);
        $estacionamiento = mysqli_real_escape_string($db, $_POST["estacionamiento"]);
        $vendedor_id = mysqli_real_escape_string($db, $_POST["vendedor_id"]);

        $imagen = $_FILES["imagen"];

        if (!$titulo)
            $errores[] = "Debes de anadir un titulo";
        if (!$precio)
            $errores[] = "Debes de anadir un precio";
        if (!$descripcion)
            $errores[] = "Debes de anadir una descripcion";
        if (!$habitaciones)
            $errores[] = "Debes de anadir el numero de habitaciones. Usar 0 si no tiene.";
        if (!$wc)
            $errores[] = "Debes de anadir el numero de banos. Usar 0 si no tiene.";
        if (!$estacionamiento)
            $errores[] = "Debes de anadir el numero de espacios de estacionamiento disponibles. Usar 0 si no tiene.";
        if (!$vendedor_id)
            $errores[] = "Debes de escoger un vendedor.";
        if ($imagen["name"] && !$imagen["error"] && $imagen["size"] > 1000 * 1000)
            $errores[] = "La imagen es muy pesada.";

        if ($imagen["name"] && !$imagen["error"])
            $actualizaImagen = true;
        else
            $actualizaImagen = false;

        if (empty($errores)) {

            $query = "UPDATE propiedades SET titulo='$titulo',precio=$precio,descripcion='$descripcion',habitaciones=$habitaciones,wc=$wc,estacionamiento=$estacionamiento,vendedor_id=$vendedor_id";

            if ($actualizaImagen) {
                $carpetaImagenes = "../../imagenes/";
                unlink($carpetaImagenes . $propiedad["imagen"]);
                
                if (!is_dir($carpetaImagenes))
                    mkdir($carpetaImagenes);
                $nombreImagen = md5(uniqid(rand(), true)) . ".jpg";
                move_uploaded_file($imagen["tmp_name"], $carpetaImagenes . $nombreImagen );

                $query = $query . ",imagen='$nombreImagen'";

            }
            $query = $query . " WHERE id=$id";

            $resultado = mysqli_query($db, $query);

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
            <fieldset>
                <legend>Informacion General</legend>
                
                <label for="titulo">Titulo:</label>
                <input type="text" id="titulo" name="titulo" placeholder="Titulo Propiedad" value="<?php echo $titulo ?>">

                <label for="precio">Precio ($):</label>
                <input type="number" id="precio" name="precio" placeholder="Precio" value="<?php echo $precio ?>">

                <label for="imagen">Imagen:</label>
                <input type="file" id="imagen" name="imagen" accept="image/jpeg, image/png, image/jpg">
                <img src="/imagenes/<?php echo $imagen ?>" alt="<?php echo $titulo ?>" class="imagen-small">

                <label for="descripcion">Descripcion:</label>
                <textarea id="descripcion" name="descripcion"><?php echo $descripcion ?></textarea>

            </fieldset>
            <fieldset>
                <legend>Informacion Propiedad</legend>
                
                <label for="habitaciones">Habitaciones:</label>
                <input type="number" id="habitaciones" name="habitaciones" placeholder="Ej. 3" min="1" max="9" value="<?php echo $habitaciones ?>">

                <label for="wc">WC:</label>
                <input type="number" id="wc" name="wc" placeholder="Ej. 3" value="<?php echo $wc ?>">

                <label for="estacionamiento">Estacionamiento:</label>
                <input type="number" id="estacionamiento" name="estacionamiento" placeholder="Ej. 3" value="<?php echo $estacionamiento ?>">

            </fieldset>
            <fieldset>
                <legend>Vendedor</legend>
                
                <select id="vendedor" name="vendedor_id">
                    <option value="">-- Seleccione --</option>
                    <?php while($vendedor = mysqli_fetch_assoc($vendedores)): ?>
                        <option value="<?php echo $vendedor["id"] ?>" <?php echo $vendedor_id === $vendedor["id"] ? "selected" : ""; ?>>
                            <?php echo $vendedor["nombre"] . " " . $vendedor["apellido"] ?>
                        </option>
                    <?php endwhile ?>
                </select>
                    
            </fieldset>

            <input type="submit" value="Actualizar Propiedad" class="boton boton-verde">

        </form>

    </main>

<?php 
    incluirTemplate("footer");
?>