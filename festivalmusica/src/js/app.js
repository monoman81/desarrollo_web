// noinspection DuplicatedCode

document.addEventListener('DOMContentLoaded', () => {
    navegacionFija();
    crearGaleria();
    resaltarEnlace();
    scrollNav();
})

const navegacionFija = () => {
    const header = document.querySelector('.header');
    const sobreFestival = document.querySelector('.sobre-festival');

    window.addEventListener('scroll', () => {
        if (sobreFestival.getBoundingClientRect().bottom < 100) {
            header.classList.add('fixed');
        }
        else {
            header.classList.remove('fixed');
        }
    })

}

const crearGaleria = () => {
    const CANTIDAD_IMAGENES = 16;
    const galeria = document.querySelector('.galeria-imagenes');
    for(let i = 1; i <= CANTIDAD_IMAGENES; i++) {
        // const imagen = document.createElement('IMG');
        // imagen.loading = 'lazy';
        // imagen.width = '300';
        // imagen.height = '200';
        // imagen.src = `src/img/gallery/thumb/${i}.jpg`;
        // imagen.alt = 'Imagen Galeria';

        const imagen = document.createElement('PICTURE');
        imagen.innerHTML = `
            <source srcset="dist/img/gallery/thumb/${i}.avif" type="image/avif">
            <source srcset="dist/img/gallery/thumb/${i}.webp" type="image/webp">
            <img loading="lazy" width="200" height="300" src="dist/img/gallery/thumb/${i}.jpg" alt="imagen galeria">
        `;

        imagen.addEventListener('click', e => {
            //mostrarImagen(e.target.src.replace('thumb', 'full'));
            mostrarImagen(i);
        })

        galeria.appendChild(imagen);
    }
}

const mostrarImagen = idx => {
    //console.log(url);
    const body = document.querySelector('body');
    const modal = document.createElement('DIV');
    const imagen = document.createElement('PICTURE');

    modal.classList.add('modal');
    modal.addEventListener('click', () => {
        const modalEl = document.querySelector('.modal');
        modalEl?.classList.add('fade-out');
        setTimeout(() => {
            modalEl?.remove();
            body.classList.remove('overflow-hidden');
        }, 500)

    })

    imagen.innerHTML = `
        <source srcset="dist/img/gallery/full/${idx}.avif" type="image/avif">
        <source srcset="dist/img/gallery/full/${idx}.webp" type="image/webp">
        <img loading="lazy" width="200" height="300" src="dist/img/gallery/full/${idx}.jpg" alt="imagen galeria">
    `;

    // imagen.src = url;
    // imagen.alt = 'Imagen Galeria';

    const cerrarModal = document.createElement('BUTTON');
    cerrarModal.textContent = 'X';
    cerrarModal.classList.add('modal-cerrar');

    modal.appendChild(imagen);
    modal.appendChild(cerrarModal);
    body.appendChild(modal);
    body.classList.add('overflow-hidden');
}

// const mostrarImagen = url => {
//     //console.log(url);
//     const body = document.querySelector('body');
//     const modal = document.createElement('DIV');
//     const imagen = document.createElement('IMG');
//
//     modal.classList.add('modal');
//     modal.addEventListener('click', () => {
//         const modalEl = document.querySelector('.modal');
//         modalEl?.classList.add('fade-out');
//         setTimeout(() => {
//             modalEl?.remove();
//             body.classList.remove('overflow-hidden');
//         }, 500)
//
//     })
//
//     imagen.src = url;
//     imagen.alt = 'Imagen Galeria';
//     const cerrarModal = document.createElement('BUTTON');
//     cerrarModal.textContent = 'X';
//     cerrarModal.classList.add('modal-cerrar');
//
//     modal.appendChild(imagen);
//     modal.appendChild(cerrarModal);
//     body.appendChild(modal);
//     body.classList.add('overflow-hidden');
// }

const resaltarEnlace = () => {
    window.addEventListener('scroll', () => {
        const sections = document.querySelectorAll('section');
        const navLinks = document.querySelectorAll('.navegacion-principal a');
        let actual = '';
        sections.forEach(section => {
            const sectionTop = section.offsetTop;
            const sectionHeight = section.clientHeight;
            if (window.scrollY >= sectionTop - sectionHeight / 3) {
                actual = section.id;
            }
        });
        navLinks.forEach(link => {
            console.log(link.attributes['href'].value);
            if (link.getAttribute('href') === `#${actual}`)
                 link.classList.add('active');
             else
                 link.classList.remove('active');
        });
    });
};

const scrollNav = () => {
    const navLinks = document.querySelectorAll('.navegacion-principal a');
    navLinks.forEach(link => {
        link.addEventListener('click', e => {
            e.preventDefault();
            const sectionId = e.target.getAttribute('href');
            const section = document.querySelector(sectionId);
            section.scrollIntoView({behavior: 'smooth'});
        })
    });
}



