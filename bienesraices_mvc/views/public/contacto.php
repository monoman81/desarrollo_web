<main class="contenedor seccion">
    <h1>Contacto</h1>
    <?php if ($mensaje): ?>  
        <p class="alerta exito"><?php echo $mensaje ?><p>
    <?php endif ?>
    <picture>
        <source srcset="build/img/destacada3.webp" type="image/webp">
        <source srcset="build/img/destacada3.jpg" type="image/jpeg">
        <img loading="lazy" src="buil/img/destacada3.jpg" alt="Destacada">
    </picture>
    <h2>Llene el formulario de Contacto</h2>
    <form action="/contacto" method="POST" class="formulario">
        <fieldset>
            <legend>Informacion Personal</legend>
            
            <label for="nombre">Nombre</label>
            <input type="text" id="nombre" placeholder="Tu Nombre" name="contacto[nombre]">

            <label for="mensaje">Mensaje</label>
            <textarea id="mensaje" placeholder="Escribe tu mensaje" name="contacto[mensaje]"></textarea>

        </fieldset>
        <fieldset>
            <legend>Informacion sobre la Propiedad</legend>
            
            <label for="opciones">Vende o Compra</label>
            <select name="opciones" id="opciones" name="contacto[tipo]">
                <option value="" disabled selected>--- Seleccione ---</option>
                <option value="Compra">Compra</option>
                <option value="Vende">Vende</option>
            </select>
            
            <label for="presupuesto">Precio o Presupuesto</label>
            <input type="number" id="presupuesto" placeholder="Tu Precio o Presupuesto" name="contacto[precio]">
        </fieldset>
        <fieldset>
            <legend>Contacto</legend>
            <p>Como desea ser contactado</p>
            <div class="forma-contacto">
                <label for="contactar-telefono">Telefono</label>
                <input type="radio" value="telefono" id="contactar-telefono" name="contacto[contactar]">

                <label for="contactar-email">Email</label>
                <input type="radio" value="email" id="contactar-email" name="contacto[contactar]">

            </div>
            <div id="contacto"></div>
        </fieldset>
        <input type="submit" value="Enviar" class="boton-verde">
    </form>
</main>