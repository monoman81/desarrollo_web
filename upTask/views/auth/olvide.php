<div class="contenedor olvide">
    <?php
    include_once __DIR__ . '/../templates/nombre-sitio.php' ?>
    <div class="contenedor-sm">
        <p class="descripcion-pagina">Recupera tu Acceso Uptask</p>
        <?php
        include_once __DIR__ . '/../templates/alertas.php' ?>
        <form method="POST" class="formulario" action="/olvide" novalidate>
            <div class="campo">
                <label for="email">Email</label>
                <input type="email" id="email" placeholder="Tu email" name="email">
            </div>
            <input type="submit" class="boton" value="Enviar Instrucciones">
        </form>
        <div class="acciones">
            <a href="/">Ya tienes una cuenta? Inicia Sesion</a>
            <a href="/crear">Aun no tienes una cuenta? Obtener una</a>
        </div>
    </div>
</div>