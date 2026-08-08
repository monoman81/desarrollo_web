<main class="contenedor seccion contenido-centrado">
    <h1>Iniciar Sesion</h1>
    <?php foreach($errores as $error): ?>
        <div class="alerta error">
            <?php echo $error; ?>
        </div>
    <?php endforeach ?>
    <form method="POST" class="formulario" action="/login">
        <fieldset>
            <legend>Email y Password</legend>

            <label for="email">Email</label>
            <input type="email" id="email" name="email" placeholder="Tu Email">

            <label for="password">Password</label>
            <input type="password" id="password" name="password" placeholder="Tu Password">
        </fieldset>
        <input type="submit" value="Iniciar Sesion" class="boton boton-verde">
    </form>

</main>