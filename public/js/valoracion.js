    // Funciones JavaScript para interacción con eventos y valoración
    function showEventsAndRate(idDiscoteca) {
        fetch(`/discotecas/${idDiscoteca}/eventos`)
            .then(response => response.json())
            .then(data => {
                const eventos = data.eventos;
                // Mostrar los eventos y permitir valorar cada uno
                showEventRatings(eventos);
            })
            .catch(error => {
                console.error('Error al obtener eventos:', error);
                alert('Hubo un problema al cargar los eventos. Por favor, inténtalo nuevamente.');
            });
    }
    function showEventRatings(eventos) {
const eventsContainer = document.getElementById('eventos-container');
eventsContainer.innerHTML = '';

if (Array.isArray(eventos) && eventos.length > 0) {
    eventos.forEach(evento => {
        const eventHtml = `
            <div class="card mb-3">
                <div class="card-body">
                    <h5 class="card-title">${evento.name}</h5>
                    <p class="card-text">${evento.descripcion}</p>
                    <div class="rating" id="rating-${evento.id}">
                        <!-- Iconos de estrella para valoración -->
                        <i class="far fa-star star" data-value="1"></i>
                        <i class="far fa-star star" data-value="2"></i>
                        <i class="far fa-star star" data-value="3"></i>
                        <i class="far fa-star star" data-value="4"></i>
                        <i class="far fa-star star" data-value="5"></i>
                    </div>
                    <br>
                    <div class="form-group">
                        <label for="descripcion-${evento.id}">Descripción:</label>
                        <textarea class="form-control" id="descripcion-${evento.id}" rows="3"></textarea>
                    </div>
                    <button onclick="submitRating(${evento.id}, ${evento.id_discoteca})"
                        class="btn btn-primary">Valorar</button>
                </div>
            </div>
        `;
        eventsContainer.innerHTML += eventHtml;

        // Agregar evento de clic a los iconos de estrella
        const stars = document.querySelectorAll(`#rating-${evento.id} .star`);
        stars.forEach(star => {
            star.addEventListener('click', () => {
                const value = parseInt(star.getAttribute('data-value'));
                // Cambiar la visualización de las estrellas
                setStarRating(evento.id, value);
            });
        });
    });
} else {
    eventsContainer.innerHTML = '<p>No hay eventos disponibles en este momento.</p>';
}
}

function setStarRating(eventId, rating) {
const stars = document.querySelectorAll(`#rating-${eventId} .star`);
stars.forEach(star => {
    const starValue = parseInt(star.getAttribute('data-value'));
    if (starValue <= rating) {
        star.classList.remove('far');
        star.classList.add('fas');
    } else {
        star.classList.remove('fas');
        star.classList.add('far');
    }
});

// Actualizar el valor del campo de valoración oculto (si es necesario)
const hiddenInput = document.getElementById(`rating-${eventId}`);
if (hiddenInput) {
    hiddenInput.value = rating;
}
}



  
    function showReviews(eventId) {
fetch(`/eventos/${eventId}/resenas`)
    .then(response => response.json())
    .then(data => {
        const reviews = data.resenas;
        const reviewsContainer = document.getElementById('eventos-container');
        reviewsContainer.innerHTML = '';

        if (Array.isArray(reviews) && reviews.length > 0) {
            reviews.forEach(review => {
                const userName = review.user ? review.user.name : 'Usuario desconocido';

                const reviewHtml = `
                    <div class="card mb-3">
                        <div class="card-body">
                            <h5 class="card-title">Reseña de ${userName}</h5>
                            <div class="rating">
                                <!-- Generar las estrellas según la valoración -->
                                ${generateStarRating(review.rating)}
                            </div>
                            <p class="card-text">${review.descripcion}</p>
                            <p class="card-text text-muted">Publicado el ${formatDate(review.created_at)}</p>
                        </div>
                    </div>
                `;
                reviewsContainer.innerHTML += reviewHtml;
            });
        } else {
            reviewsContainer.innerHTML = '<p class="no-reviews">No hay reseñas disponibles para este evento.</p>';
        }
    })
    .catch(error => {
        console.error('Error al obtener reseñas del evento:', error);
        const reviewsContainer = document.getElementById('eventos-container');
        reviewsContainer.innerHTML = '<p>Hubo un problema al cargar las reseñas del evento.</p>';
    });
}


function formatDate(timestamp) {
const date = new Date(timestamp);
const formattedDate = date.toLocaleDateString('es-ES', {
    weekday: 'long',
    year: 'numeric',
    month: 'long',
    day: 'numeric',
    hour: 'numeric',
    minute: 'numeric'
});
return formattedDate;
}

function generateStarRating(rating) {
const fullStars = Math.floor(rating); // Número de estrellas completas
const halfStar = rating - fullStars >= 0.5; // Determinar si hay una estrella media

let starsHtml = '';
for (let i = 0; i < fullStars; i++) {
    starsHtml += '<i class="fas fa-star"></i>'; // Estrella completa
}
if (halfStar) {
    starsHtml += '<i class="fas fa-star-half-alt"></i>'; // Estrella media
}
const emptyStars = 5 - Math.ceil(rating); // Número de estrellas vacías
for (let i = 0; i < emptyStars; i++) {
    starsHtml += '<i class="far fa-star"></i>'; // Estrella vacía
}

return starsHtml;
}












// Mejores valoraciones
let isTopRatedUsersVisible = false;

function toggleTopRatedUsers() {
    const topRatedUsersContainer = document.getElementById('top-rated-users');

    if (isTopRatedUsersVisible) {
        // Si los usuarios están visibles, ocultarlos
        topRatedUsersContainer.innerHTML = ''; // Limpiar el contenedor
        isTopRatedUsersVisible = false;
    } else {
        // Si los usuarios están ocultos, mostrarlos
        showTopRatedUsers(); // Llamar a la función para cargar los usuarios
        isTopRatedUsersVisible = true;
    }
}

function showTopRatedUsers() {
    const topRatedUsersContainer = document.getElementById('top-rated-users');

    // Debugging to log the fetched data
    fetch('/valoracion/top-rated-users')
        .then(response => response.json())
        .then(data => {
            console.log('Fetched data:', data); // Log fetched data for debugging

            topRatedUsersContainer.innerHTML = ''; // Clear the container before displaying new results

            if (data.topRatedUsers && data.topRatedUsers.length > 0) {
                const eventRatingsMap = {}; // Object to store ratings grouped by event
                let displayedCount = 0; // Counter to track displayed ratings

                // Group ratings by event
                data.topRatedUsers.forEach(user => {
                    const evento_nombre = user.evento_nombre;

                    if (!eventRatingsMap[evento_nombre]) {
                        eventRatingsMap[evento_nombre] = [];
                    }

                    // Add user to event group with limit check
                    if (eventRatingsMap[evento_nombre].length < 3 && displayedCount < 9) {
                        eventRatingsMap[evento_nombre].push(user);
                        displayedCount++;
                    }
                });

                // Display the top users per event
                Object.keys(eventRatingsMap).forEach(evento_nombre => {
                    const usersForEvent = eventRatingsMap[evento_nombre];

                    // Sort users by rating in descending order
                    usersForEvent.sort((a, b) => b.rating - a.rating);

                    // Render the users as HTML cards
                    usersForEvent.forEach(user => {
                        const { name, rating, descripcion } = user;

                        // Calculate stars based on rating
                        const fullStars = Math.floor(rating);
                        const halfStar = rating - fullStars >= 0.5;
                        const emptyStars = 5 - fullStars - (halfStar ? 1 : 0);

                        // Handle null description
                        const descriptionText = descripcion ? descripcion : 'No hay descripción';

                        // Generate user card HTML with star icons and description
                        const userHtml = `
                            <div class="user-card">
                                <div class="user-info">Usuario: ${name}</div>
                                <div>Evento: ${evento_nombre}</div>
                                <div>Descripción: ${descriptionText}</div>
                                <br>
                                <div class="stars-container">
                                    ${'<i class="fas fa-star"></i>'.repeat(fullStars)}
                                    ${halfStar ? '<i class="fas fa-star-half-alt"></i>' : ''}
                                    ${'<i class="far fa-star"></i>'.repeat(emptyStars)}
                                </div>
                            </div>
                        `;

                        topRatedUsersContainer.innerHTML += userHtml;
                    });
                });
            } else {
                topRatedUsersContainer.innerHTML = '<p>No hay usuarios con mejor valoración.</p>';
            }
        })
        .catch(error => {
            console.error('Error al obtener los usuarios con mejor valoración:', error);
            topRatedUsersContainer.innerHTML = '<p>Hubo un problema al cargar los usuarios con mejor valoración.</p>';
        });
}
