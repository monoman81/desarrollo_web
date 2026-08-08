<h1 class="nombre-pagina">Recuperacion de Password</h1>
<p class="descripcion-pagina">
    Se te olvido tu password? Ingresa tu email registrado para instrucciones para reestablecer la cuenta.
</p>

<form action="/crear-cuenta" method="POST" class="formulario">
    <div class="campo">
        <label for="email">Email</label>
        <input type="email" id="email" name="email" placeholder="Tu email registrado" />
    </div>
    <input type="submit" class="boton" value="Enviar Instrucciones">
</form>
<div class="acciones">
    <a href="/">Ya tienes una cuenta? Inicia Sesion</a>
    <a href="/crear-cuenta">Aun no tienes una cuenta? Crear una</a>
</div>