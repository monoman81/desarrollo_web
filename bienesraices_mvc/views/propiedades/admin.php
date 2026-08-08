<main class="contenedor seccion">
    <h1>Administrador de Bienes Raices</h1>
    <?php 
        $mensaje = mostrarNofificacion(intval($res));
    ?>
    <?php if ($mensaje): ?>
    <div class="alerta exito"><?php echo s($mensaje) ?></div>
    <?php endif ?>
    <h2>Propiedades</h2>
    <a href="/propiedades/crear" class="boton boton-verde">Nueva Propiedad</a>
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
            <?php foreach($propiedades as $propiedad): ?>
                <tr>
                    <td><?php echo $propiedad->id ?></td>
                    <td><?php echo $propiedad->titulo ?></td>
                    <td><img src="/imagenes/<?php echo $propiedad->imagen ?>" alt="<?php echo $propiedad->titulo ?>" class="imagen-tabla"></td>
                    <td>$ <?php echo $propiedad->precio ?></td>
                    <td>
                        <form method="POST" action="/propiedades/eliminar" class="w-100">
                            <input type="hidden" name="id" value="<?php echo $propiedad->id ?>">
                            <input type="submit" class="boton-rojo-block" value="Eliminar" /> 
                        </form>
                        
                        <a href="/propiedades/actualizar?id=<?php echo $propiedad->id ?>" class="boton-amarillo-block">Actualizar</a>
                    </td>
                </tr>
            <?php endforeach ?>
        </tbody>

    </table>

    <h2>Vendedores</h2>
    <a href="/vendedores/crear" class="boton boton-verde">Nuevo Vendedor</a>
    <table class="propiedades">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Apellido</th>
                <th>Telefono</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($vendedores as $vendedor): ?>
                <tr>
                    <td><?php echo $vendedor->id ?></td>
                    <td><?php echo $vendedor->nombre ?></td>
                    <td><?php echo $vendedor->apellido ?></td>
                    <td><?php echo $vendedor->telefono ?></td>
                    <td>
                        <form method="POST" class="w-100" action="/vendedores/eliminar">
                            <input type="hidden" name="id" value="<?php echo $vendedor->id ?>">
                            <input type="submit" class="boton-rojo-block" value="Eliminar" /> 
                        </form>
                        <a href="/vendedores/actualizar?id=<?php echo $vendedor->id ?>" class="boton-amarillo-block">Actualizar</a>
                    </td>
                </tr>
            <?php endforeach ?>
        </tbody>

    </table>

    <!-- <h2>Vendedores</h2>
    <a href="/admin/vendedores/crear.php" class="boton boton-verde">Nuevo Vendedor</a>
    <table class="propiedades">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Apellido</th>
                <th>Telefono</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php //foreach($vendedores as $vendedor): ?>
                <tr>
                    <td><?php //echo $vendedor->id ?></td>
                    <td><?php //echo $vendedor->nombre ?></td>
                    <td><?php //echo $vendedor->apellido ?></td>
                    <td><?php //echo $vendedor->telefono ?></td>
                    <td>
                        <form method="POST" action="" class="w-100">
                            <input type="hidden" name="id" value="<?php //echo $vendedor->id ?>">
                            <input type="hidden" name="class" value="App\Vendedor">
                            <input type="submit" class="boton-rojo-block" value="Eliminar" /> 
                        </form>
                        
                        <a href="vendedores/actualizar.php?id=<?php //echo $vendedor->id ?>" class="boton-amarillo-block">Actualizar</a>
                    </td>
                </tr>
            <?php //endforeach ?>
        </tbody>

    </table> -->

</main>
