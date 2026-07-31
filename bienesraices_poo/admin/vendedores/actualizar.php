<?php
    require "../../includes/app.php";
    autenticado();

    use App\Vendedor;

    $id = $_GET["id"];
    $id = filter_var($id, FILTER_VALIDATE_INT);

    if (!$id)
        header("Location: /admin");

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

    
    incluirTemplate("header")
?>
    <main class="contenedor seccion">
        <h1>Actualizar Vendedor</h1>
        <a href="/admin" class="boton boton-verde">Volver</a>

        <?php foreach($errores as $error): ?>
            <div class="alerta error">
                <?php echo $error ?>
            </div>
        <?php endforeach ?>

        <form action="" class="formulario" method="POST">
            <?php include "../../includes/templates/formulario_vendedores.php" ?>

            <input type="submit" value="Actualizar Vendedor" class="boton boton-verde">

        </form>

    </main>

<?php 
    incluirTemplate("footer");
?>