let paso = 1;
const pasoInicial = 1;
const pasoFinal = 3;

const cita = {
    userId: '',
    nombre: '',
    fecha: '',
    hora: '',
    servicios: []
}

document.addEventListener('DOMContentLoaded', () => {
    iniciarApp();
});

const iniciarApp = () => {
    mostrarSeccion();
    tabs();
    paginador();
    consultarAPI();
    datosCita();
    if (paso === pasoFinal)
        mostrarResumen();
}

const tabs = () => {
    const botones = document.querySelectorAll('.tabs button');
    botones.forEach(boton => boton.addEventListener('click', (e) => {
        paso = +e.target.dataset.paso;
        mostrarSeccion();
    }));
}

const mostrarSeccion = () => {
    const seccion = document.querySelector(`#paso-${paso}`);
    const seccionActual = document.querySelector('.mostrar');
    if (seccionActual) {
        seccionActual.classList.remove('mostrar');
        seccionActual.classList.add('ocultar');
    }
    seccion.classList.add('mostrar');
    seccion.classList.remove('ocultar');
    resaltarNav();
    if (paso === pasoFinal)
        mostrarResumen();
}

const resaltarNav = () => {
    const navActual = document.querySelector('.actual');
    if (navActual) {
        navActual.classList.remove('actual');
    }
    const navSel = document.querySelector(`[data-paso="${paso}"]`);
    navSel.classList.add('actual');
}

const paginador = () => {
    const botones = document.querySelectorAll('.paginacion button');
    botones.forEach(boton => boton.addEventListener('click', (e) => {
        const step = +e.target.dataset.step;
        paso += step;
        if (paso >= pasoInicial && paso <= pasoFinal)
            mostrarSeccion();
        else if (paso < pasoInicial)
            paso = pasoInicial;
        else
            paso = pasoFinal;
    }));
}

const consultarAPI = async () => {
    try {
        const url = "http://appsalon.test/api/servicios";
        const result = await fetch(url);
        const servicios = await result.json();
        mostrarServicios(servicios);
    }
    catch(error) {
        console.log(error);
    }
}

const mostrarServicios = (servicios) => {
    servicios.forEach(servicio => {
        const {id, nombre, precio} = servicio;
        const nombreServicio = document.createElement('P');
        nombreServicio.classList.add('nombre-servicio');
        nombreServicio.textContent = nombre;

        const precioServicio = document.createElement('P');
        precioServicio.classList.add('precio-servicio');
        precioServicio.textContent = precio;

        const servicioDiv = document.createElement('DIV');
        servicioDiv.classList.add('servicio');
        servicioDiv.dataset.idServicio = id;

        servicioDiv.appendChild(nombreServicio);
        servicioDiv.appendChild(precioServicio);
        servicioDiv.addEventListener('click', e => {
            seleccionarServicio(servicio);
        })

        document.querySelector('#servicios').appendChild(servicioDiv);

    });
}

const seleccionarServicio = (servicio) => {
    const {servicios} = cita;
    const {id} = servicio;
    if (servicios.some(s => s.id === id)) {
        cita.servicios = servicios.filter(s => s.id !== id);
    }
    else 
        cita.servicios = [...servicios, servicio];
    
    const divServicio = document.querySelector(`[data-id-servicio="${id}"]`);
    divServicio.classList.toggle('seleccionado');
}

const datosCita = () => {
    cita.nombre = document.querySelector('#nombre').value;
    cita.userId = document.querySelector('#userId').value;
    const inputFecha = document.querySelector('#fecha');
    const inputHora = document.querySelector('#hora');

    inputFecha.addEventListener('input', (e) => {
        const dia = new Date(e.target.value).getUTCDay();
        if ([0,6].includes(dia)) {
            e.target.value = ''
            mostrarAlerta('.formulario', 'Lo sentimos. Sabado y Domingo no laboramos', 'error');
        }
        else {
            cita.fecha = e.target.value;
        }
    });

    inputHora.addEventListener('input', (e) => {
        const hora = +e.target.value.split(':')[0];
        if (hora < 10 || hora > 18) {
            e.target.value = '';
            mostrarAlerta('.formulario', 'Nuestro horario de citas es de 10 de la manana a 6 de la tarde', 'error');
        }
        else
            cita.hora = e.target.value;
    })

}

const mostrarResumen = () => {
    const resumen = document.querySelector('.contenido-resumen');
    while (resumen.firstChild)
        resumen.removeChild(resumen.firstChild);
    if (Object.values(cita).includes('') || cita.servicios.length == 0) {
        mostrarAlerta('.contenido-resumen', 'Falta seleccionar uno o mas servicios o informacion para completar la cita.', 'error', false);
        return;
    }
    const {nombre, fecha, hora, servicios} = cita;
    
    const headingServicios = document.createElement('H3');
    headingServicios.textContent = "Resumen de Servicios";

    resumen.appendChild(headingServicios);

    servicios.forEach(servicio => {
        const {id, precio, nombre: nombreServicio} = servicio;
        const contenedor = document.createElement('DIV');
        contenedor.classList.add('contenedor-servicio');
        const textoServicio = document.createElement('P');
        textoServicio.textContent = nombreServicio;

        const precioServicio = document.createElement('P');
        precioServicio.innerHTML = `<span>Precio: </span>$ ${precio}`;

        contenedor.appendChild(textoServicio);
        contenedor.appendChild(precioServicio);

        resumen.appendChild(contenedor);
    });

    const nombreCliente = document.createElement('P');
    const fechaCita = document.createElement('P');
    const horaCita = document.createElement('P');

    const headingCita = document.createElement('H3');
    headingCita.textContent = "Resumen de Cita";

    resumen.appendChild(headingCita);
    
    nombreCliente.innerHTML = `<span>Nombre:</span> ${nombre}`;
    fechaCita.innerHTML = `<span>Fecha:</span> ${fecha}`;
    horaCita.innerHTML = `<span>Hora:</span> ${hora} Horas`;

    const botonReservar = document.createElement('BUTTON');
    botonReservar.classList.add('boton');
    botonReservar.textContent = "Reservar Cita";

    botonReservar.addEventListener('click', () => {
        reservarCita();
    });

    resumen.appendChild(nombreCliente);
    resumen.appendChild(fechaCita);
    resumen.appendChild(horaCita);
    resumen.appendChild(botonReservar);
    
}

const reservarCita = async () => {
    const {userId, fecha, hora} = cita;
    const url = 'http://appsalon.test/api/citas';
    const datos = new FormData();
    datos.append("usuarioId", userId);
    datos.append("fecha", fecha);
    datos.append("hora", hora);
    datos.append("servicios", cita.servicios.map(servicio => servicio.id));
    try {
        const respuesta = await fetch(url, {
            method: 'POST',
            body: datos
        });
        const resultado = await respuesta.json(); 
        const {success, id} = resultado;
        if (success) {
            Swal.fire({
                title: "Cita Creada",
                text: "Tu cita ha sido creada correctamente.",
                icon: "success"
            }).then(() => {
                setTimeout(() => {
                    window.location.reload();    
                }, 1000);
            });
        }
        else {
            Swal.fire({
                title: "Error",
                text: "La cita no se almaceno. Verifica los datos.",
                icon: "error"
            });
        }
    }
    catch (error) {
        Swal.fire({
                title: "Error",
                text: `Hubo un error de comunicacion con la API. ${error.message}`,
                icon: "error"
        });
    }
    
}

const mostrarAlerta = (appendTo, msg, tipo, fadeOut = true) => {
    const alertaPrevia = document.querySelector('.alerta');
    if (alertaPrevia)
        removeAlerta(alertaPrevia, 0);
    const alerta = document.createElement('DIV');
    alerta.textContent = msg;
    alerta.classList.add('alerta');
    alerta.classList.add(tipo);
    document.querySelector(appendTo).appendChild(alerta);

    if (!fadeOut) return;

    removeAlerta(alerta);

}

const removeAlerta = (alerta, duration = 3000) => {
    if (duration === 0) {
        alerta.remove();
        return;
    }
    setTimeout(() => {
        alerta.classList.add('fade-out');
        alerta.addEventListener('transitionend', () => {
            alerta.remove();
        }, {once: true});
    }, duration);
}

