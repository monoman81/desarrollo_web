<h1 class="nombre-pagina">Panel de Administracion</h1>

<?php
use Dom\NamedNodeMap;
    include_once __DIR__ . "/../templates/barra.php";
?>
<h2>Buscar Citas</h2>
<div class="busqueda">
    <form class="formulario">
        <div class="campo">
            <label for="fecha">Fecha</label>
            <input type="date" id="fecha" name="fecha" value="<?php echo $fecha ?>">
        </div>
    </form>
</div>

<?php if (count($citas) === 0): ?>
    <h2>No hay citas en esta fecha</h2>
<?php endif ?>

<div class="citas-admin">
    <ul class="citas">
    <?php
        $citaId = -1;

        foreach($citas as $key => $cita):
            if ($citaId !== $cita->id): 
                $total = 0;
                $citaId = $cita->id;
    ?>  
                <li>
                    <p>Id: <span><?php echo $cita->id ?></span></p>
                    <p>Hora: <span><?php echo $cita->hora ?></span></p>
                    <p>Cliente: <span><?php echo $cita->cliente ?></span></p>
                    <p>Email: <span><?php echo $cita->email ?></span></p>
                    <h3>Servicios</h3>
    <?php 
            endif; 
            $total += $cita->precio
    ?>
                    <p class="servicio"><?php echo $cita->servicio . " $ " . $cita->precio; ?></p>
    <?php 
        $actual = $cita->id;
        $proximo = $citas[$key + 1]->id ?? 0;
        if ($actual !== $proximo) {
    ?>
        <p class="total">Total: $ <span><?php echo $total ?></span></p>
        <form action="/api/eliminar" method="POST">
            <input type="hidden" name="id" value="<?php echo $cita->id ?>">
            <input type="submit"class="boton-eliminar" value="Eliminar">
        </form>
    <?php    
        }
    ?>                 
    <?php endforeach; ?>
    </ul>

</div>

<?php
    $script = "<script src='build/js/buscador.js'></script>";
?>