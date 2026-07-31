<?php 
    require "includes/app.php";
    incluirTemplate("header");
?>
    <main class="contenedor seccion">
        <h1>Anuncios</h1>
        <?php 
            $limite = 0;
            include "includes/templates/anuncios.php"; 
        ?>
    </main>

<?php incluirTemplate("footer"); ?>