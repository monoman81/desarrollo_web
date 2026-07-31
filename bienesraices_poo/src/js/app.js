document.addEventListener('DOMContentLoaded', () => {
    eventListeners();
    darkMode();
})

const eventListeners = () => {
    const mobileMenu = document.querySelector('.mobile-menu');
    const navegacion = document.querySelector('.navegacion');
    mobileMenu.addEventListener('click', () => {
        navegacion.classList.toggle('mostrar');
    })
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