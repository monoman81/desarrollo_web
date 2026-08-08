document.addEventListener('DOMContentLoaded', () => {
    eventListeners();
    darkMode();
})

const eventListeners = () => {
    const mobileMenu = document.querySelector('.mobile-menu');
    const navegacion = document.querySelector('.navegacion');
    const metodosContacto = document.querySelectorAll('input[name="contacto[contactar]"]');
    
    mobileMenu.addEventListener('click', () => {
        navegacion.classList.toggle('mostrar');
    });
    
    metodosContacto.forEach(metodo => {
        metodo.addEventListener('click', (e) => {
            const contacto = document.querySelector('#contacto');
            if (e.target.value === 'telefono') {
                contacto.innerHTML = `
                    <label for="tel">Telefono</label>
                    <input type="tel" id="tel" placeholder="Tu Telefono" name="contacto[tel]">
                    <p>Elija la fecha y hora para ser contactado</p>
                    <label for="fecha">Fecha</label>
                    <input type="date" id="fecha" name="contacto[fecha]">
                    <label for="hora">Hora</label>
                    <input type="time" id="hora" min="09:00" max="18:00" name="contacto[hora]">
                `;
            }
            else {
                contacto.innerHTML = `
                    <label for="email">Email</label>
                    <input type="email" id="email" placeholder="Tu Email" name="contacto[email]">
                `;
            }
        });
    });
}

const darkMode = () => {
    const schemaDark = window.matchMedia('(prefers-color-scheme: dark)');
    const botonDarkMode = document.querySelector('.dark-mode-boton');

    if (schemaDark.matches)
        document.body.classList.add('dark-mode');
    else
        document.body.classList.remove('dark-mode');

    schemaDark.addEventListener('change', () => {
        if (schemaDark.matches)
            document.body.classList.add('dark-mode');
        else
            document.body.classList.remove('dark-mode');
    });

    botonDarkMode.addEventListener('click', () => {
        document.body.classList.toggle('dark-mode');
    })
}