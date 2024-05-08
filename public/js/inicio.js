// Función para verificar si un elemento está dentro del viewport
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
    elements.forEach(function(element) {
        if (isInViewport(element)) {
            element.classList.add('animate-fadeInUp');
            element.style.visibility = 'visible';
        }
    });
}

// Ejecutar la animación al cargar la página y al desplazarse
document.addEventListener('DOMContentLoaded', function() {
    animateOnScroll(); // Ejecutar al cargar la página

    // Ejecutar al desplazarse
    window.addEventListener('scroll', function() {
        requestAnimationFrame(animateOnScroll); // Utilizar requestAnimationFrame para mejorar el rendimiento
    });
});

// Alerta de Cookies
document.addEventListener('DOMContentLoaded', function() {
    const cookieAcceptButton = document.getElementById('cookieAcceptButton');
    const cookieBanner = document.getElementById('cookieBanner');
    const acceptedCookies = localStorage.getItem('cookies_accepted');

    if (!acceptedCookies) {
        cookieBanner.style.display = 'block';

        cookieAcceptButton.addEventListener('click', function() {
            // Guardar la aceptación de cookies en localStorage
            localStorage.setItem('cookies_accepted', 'true');
            cookieBanner.style.display = 'none';
        });
    }
});