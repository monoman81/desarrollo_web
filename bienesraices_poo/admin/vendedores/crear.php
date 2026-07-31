<?php

    require "../../includes/app.php";

    use App\Vendedor;

    autenticado();

    $errores = Vendedor::getErrores();

    $vendedor = new Vendedor;

    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        $vendedor = new Vendedor($_POST["vendedor"]);
        
        $errores = $vendedor->validar();
        
        if (empty($errores)) {
            
            $resultado = $vendedor->guardar();

            if ($resultado)
                header("Location: /admin?res=1");
        }
    }
    
    incluirTemplate("header")
?>
    <main class="contenedor seccion">
        <h1>Registrar Vendedor</h1>
        <a href="/admin" class="boton boton-verde">Volver</a>

        <?php foreach($errores as $error): ?>
            <div class="alerta error">
                <?php echo $error ?>
            </div>
        <?php endforeach ?>

        <form action="" class="formulario" method="POST" action="<?php echo $_SERVER["SCRIPT_NAME"] ?>">
            <?php include "../../includes/templates/formulario_vendedores.php" ?>
            <input type="submit" value="Registrar Vendedor" class="boton boton-verde">

        </form>

    </main>

<?php 
    incluirTemplate("footer");
?>