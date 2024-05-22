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

// Efecto Parallax al hacer scroll
document.addEventListener('scroll', function() {
    let scrollTop = window.scrollY;
    let parallaxElements = document.querySelectorAll('.parallax');

    parallaxElements.forEach(function(element) {
        let speed = parseFloat(element.getAttribute('data-speed'));
        element.style.transform = `translateY(${scrollTop * speed}px)`;
    });
});

// Mostrar un modal al hacer click en un enlace
document.addEventListener('DOMContentLoaded', function() {
    const modalButton = document.getElementById('openModalButton');
    const modal = document.getElementById('myModal');

    if (modalButton && modal) {
        modalButton.addEventListener('click', function() {
            modal.style.display = 'block';
        });

        // Cerrar el modal al hacer click fuera del mismo
        window.addEventListener('click', function(event) {
            if (event.target === modal) {
                modal.style.display = 'none';
            }
        });
    }
});

// Desplazamiento suave al hacer click en enlaces internos
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function(e) {
            e.preventDefault();

            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                target.scrollIntoView({
                    behavior: 'smooth'
                });
            }
        });
    });
});

// Animaciones al hacer hover sobre elementos
document.addEventListener('DOMContentLoaded', function() {
    const hoverElement = document.getElementById('myHoverElement');

    if (hoverElement) {
        hoverElement.addEventListener('mouseenter', function() {
            hoverElement.classList.add('animate__animated', 'animate__pulse');
        });

        hoverElement.addEventListener('mouseleave', function() {
            hoverElement.classList.remove('animate__animated', 'animate__pulse');
        });
    }
});

const OPENWEATHERMAP_API_KEY = "adafd0b1551b7ddfe30c0185383408ab";

// Función para obtener el clima
function getWeather(city) {
    const url =
        `https://api.openweathermap.org/data/2.5/weather?q=${city}&appid=${OPENWEATHERMAP_API_KEY}&lang=es&units=metric`;

    function capitalizeFirstLetter(string) {
        return string.charAt(0).toUpperCase() + string.slice(1);
    }
    // Realiza la solicitud a la API de OpenWeatherMap
    fetch(url)
        .then(response => {
            if (!response.ok) {
                throw new Error('La solicitud no fue exitosa');
            }
            return response.json();
        })
        .then(data => {
            // Maneja la respuesta de la API
            console.log(data);
            // Actualiza el contenido del div con la información del clima
            const temperature = Math.round(data.main.temp); // Redondea la temperatura
            const description = data.weather[0].description;
            // Llama a la función para capitalizar la primera letra de la descripción
            const capitalizedDescription = capitalizeFirstLetter(description);

            const weatherInfo = `La temperatura en ${city} es de ${temperature}°C. ${capitalizedDescription}.`;

            document.getElementById('weatherInfo').textContent = weatherInfo;
        })
        .catch(error => {
            console.error('Error al obtener el clima:', error);
            // Muestra una alerta de error si la solicitud falla
            Swal.fire({
                title: 'Error',
                text: 'No se pudo obtener la información del clima. Por favor, inténtalo de nuevo más tarde.',
                icon: 'error',
                confirmButtonText: 'Cerrar'
            });
        });
}

// Llama a la función getWeather con el nombre de la ciudad deseada
getWeather('Barcelona');
