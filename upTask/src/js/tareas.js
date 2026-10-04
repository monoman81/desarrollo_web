(function () {
    const nuevaTareaBtn = document.querySelector('#agregar-tarea');
    nuevaTareaBtn.addEventListener('click', mostrarFormulario);

    function mostrarFormulario() {
        const modal = document.createElement('DIV');
        modal.classList.add('modal');
        modal.innerHTML = `
            <form class="formulario nueva-tarea">
                <legend>Anade una nueva tarea</legend>
                <div class="campo">
                    <label for="tarea">Tarea</label>
                    <input type="text" id="tarea" name="tarea" placeholder="Anadir tarea al proyecto actual">
                </div>
                <div class="opciones">
                    <input type="submit" class="submit-nueva-tarea" value="Anadir Tarea" />
                    <button type="button" class="cerrar-modal">Cancelar</button>
                </div>
            </form>
        `;

        modal.addEventListener('click', e => {
            e.preventDefault();
            if (e.target.classList.contains('cerrar-modal')) {
                const formulario = document.querySelector('.formulario');
                formulario.classList.add('cerrar');
                setTimeout(() => {
                    modal.remove();
                }, 500);
            }
        });

        setTimeout(() => {
            const formulario = document.querySelector('.formulario');
            formulario.classList.add('animar');
        }, 100)
        document.querySelector('body').appendChild(modal);
    }
})();