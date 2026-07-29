<?php
    require "../../includes/funciones.php";
    if (!autenticado()) {
        header("location: /");
    }
    require "../../includes/config/database.php";

    $db = conectarDB();

    $consulta = "SELECT * FROM vendedores";
    $vendedores = mysqli_query($db, $consulta);

    $errores = [];
    $titulo = "";
    $precio = "";
    $descripcion = "";
    $habitaciones = "";
    $wc = "";
    $estacionamiento = "";
    $vendedor_id = "";

    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        $titulo = mysqli_real_escape_string($db, $_POST["titulo"]);
        $precio = mysqli_real_escape_string($db, $_POST["precio"]);
        $descripcion = mysqli_real_escape_string($db, $_POST["descripcion"]);
        $habitaciones = mysqli_real_escape_string($db, $_POST["habitaciones"]);
        $wc = mysqli_real_escape_string($db, $_POST["wc"]);
        $estacionamiento = mysqli_real_escape_string($db, $_POST["estacionamiento"]);
        $vendedor_id = mysqli_real_escape_string($db, $_POST["vendedor_id"]);
        $creado = date("Y/m/d");

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
        if (!$imagen["name"] || $imagen["error"])
            $errores[] = "La imagen es obligatoria.";
        if ($imagen["size"] > 1000 * 1000)
            $errores[] = "La imagen es muy pesada.";

        if (empty($errores)) {

            $carpetaImagenes = "../../imagenes/";
            if (!is_dir($carpetaImagenes))
                mkdir($carpetaImagenes);
            $nombreImagen = md5(uniqid(rand(), true)) . ".jpg";
            move_uploaded_file($imagen["tmp_name"], $carpetaImagenes . $nombreImagen );


            $query = "INSERT INTO propiedades(titulo,precio,imagen,descripcion,habitaciones,wc,estacionamiento,creado,vendedor_id) "
                . "VALUES('$titulo',$precio,'$nombreImagen','$descripcion',$habitaciones,$wc,$estacionamiento,'$creado',$vendedor_id)";

            $resultado = mysqli_query($db, $query);

            if ($resultado)
                header("location: /admin?res=1");
        }


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
            <fieldset>
                <legend>Informacion General</legend>
                
                <label for="titulo">Titulo:</label>
                <input type="text" id="titulo" name="titulo" placeholder="Titulo Propiedad" value="<?php echo $titulo ?>">

                <label for="precio">Precio ($):</label>
                <input type="number" id="precio" name="precio" placeholder="Precio" value="<?php echo $precio ?>">

                <label for="imagen">Imagen:</label>
                <input type="file" id="imagen" name="imagen" accept="image/jpeg, image/png, image/jpg">

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

            <input type="submit" value="Crear Propiedad" class="boton boton-verde">

        </form>

    </main>

<?php 
    incluirTemplate("footer");
?>