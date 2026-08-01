<main class="contenedor seccion">
    <h1>Crear Propiedad</h1>
    <a href="/admin" class="boton boton-verde">Volver</a>

    <?php foreach($errores as $error): ?>
        <div class="alerta error">
            <?php echo $error ?>
        </div>
    <?php endforeach ?>

    <form action="" class="formulario" method="POST" action="<?php echo $_SERVER["SCRIPT_NAME"] ?>" enctype="multipart/form-data">
        <?php include __DIR__ . "/formulario.php" ?>
        <input type="submit" value="Crear Propiedad" class="boton boton-verde">
    </form>

</main>