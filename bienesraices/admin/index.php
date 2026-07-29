<?php
    require "../includes/funciones.php";
    if (!autenticado()) {
        header("location: /");
    }

    
    require "../includes/config/database.php";
    $res = $_GET["res"] ?? null;

    $db = conectarDB();
    $query = "SELECT * FROM propiedades";
    $resultado = mysqli_query($db, $query);

    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        $id = $_POST["id"];
        $id = filter_var($id, FILTER_VALIDATE_INT);
        if (!$id)
            header("Location:/admin");

        $consulta = "SELECT imagen FROM propiedades WHERE id=$id";
        $resultado = mysqli_query($db, $consulta);
        $propiedad = mysqli_fetch_assoc($resultado);
        
        $query = "DELETE FROM propiedades WHERE id=$id";
        $resultado = mysqli_query($db, $query);
        if ($resultado) {
            $carpetaImagenes = "../imagenes/";
            unlink($carpetaImagenes . $propiedad["imagen"]);
            header("location: /admin?res=3");
        }
    }

    incluirTemplate("header")
?>
    <main class="contenedor seccion">
        <h1>Administrador de Bienes Raices</h1>
        <?php if (intval($res) === 1): ?>
            <div class="alerta exito">Propiedad creada correctamente.</div>
        <?php elseif (intval($res) === 2): ?>
            <div class="alerta exito">Propiedad actualizada correctamente.</div>
        <?php elseif (intval($res) === 3): ?>
            <div class="alerta exito">Propiedad eliminada correctamente.</div>
        <?php endif ?>
        <a href="/admin/propiedades/crear.php" class="boton boton-verde">Nueva Propiedad</a>
        <table class="propiedades">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Titulo</th>
                    <th>Imagen</th>
                    <th>Precio</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php while($propiedad = mysqli_fetch_assoc($resultado)): ?>
                    <tr>
                        <td><?php echo $propiedad["id"] ?></td>
                        <td><?php echo $propiedad["titulo"] ?></td>
                        <td><img src="/imagenes/<?php echo $propiedad["imagen"] ?>" alt="<?php echo $propiedad["titulo"] ?>" class="imagen-tabla"></td>
                        <td>$ <?php echo $propiedad["precio"] ?></td>
                        <td>
                            <form method="POST" action="" class="w-100">
                                <input type="hidden" name="id" value="<?php echo $propiedad["id"] ?>">
                                <input type="submit" class="boton-rojo-block" value="Eliminar" /> 
                            </form>
                            
                            <a href="propiedades/actualizar.php?id=<?php echo $propiedad["id"] ?>" class="boton-amarillo-block">Actualizar</a>
                        </td>
                    </tr>
                <?php endwhile ?>
            </tbody>

        </table>
    </main>
    
<?php 
    mysqli_close($db);
    incluirTemplate("footer");
?>