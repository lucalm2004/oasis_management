
function isInViewport(element) {
    var rect = element.getBoundingClientRect();
    return (
        rect.top >= 0 &&
        rect.left >= 0 &&
        rect.bottom <= (window.innerHeight || document.documentElement.clientHeight) &&
        rect.right <= (window.innerWidth || document.documentElement.clientWidth)
    );
}

// Función para activar las animaciones al desplazarse por la página
function animateOnScroll() {
    var elements = document.querySelectorAll('.animated');
    elements.forEach(function (element) {
        if (isInViewport(element)) {
            element.classList.add('animate-fadeInUp');
            element.style.visibility = 'visible';
        }
    });
}

// Ejecutar la animación al cargar la página
animateOnScroll();




