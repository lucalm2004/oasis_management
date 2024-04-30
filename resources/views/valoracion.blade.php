<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Valoración de Discotecas - Oasis Management</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/valoraciones.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <!-- SweetAlert CDN -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
</head>

<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="#">
                <img src="/img/logonegro.png" class="logo mr-2" alt="Logo">
                <span class="font-weight-bold text-uppercase">Oasis Management</span>
            </a>
            <!-- Authentication Logic with Laravel -->
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ml-auto">
                    @if (Route::has('login'))
                        @auth
                            <li class="nav-item">
                                <a href="{{ url('/dashboard') }}" class="nav-link"><i
                                        class="fas fa-chart-line"></i> Dashboard</a>
                            </li>
                        @else
                            <li class="nav-item">
                                <a href="{{ route('login') }}" class="nav-link"><i class="fas fa-sign-in-alt"></i> Log
                                    in</a>
                            </li>
                            <li class="nav-item">
                                <a href="/google-auth/redirect" class="nav-link"><i class="fab fa-google"></i> Login
                                    Google</a>
                            </li>
                            @if (Route::has('register'))
                                <li class="nav-item">
                                    <a href="{{ route('register') }}" class="nav-link"><i class="fas fa-user-plus"></i>
                                        Register</a>
                                </li>
                            @endif
                        @endauth
                    @endif
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-5">
        <h1>Valoración de Discotecas</h1>
        <div class="row mt-4">
            @foreach ($discotecas as $discoteca)
                <div class="col-md-6 mb-4">
                    <div class="card">
                        <img src="{{ asset('img/' . $discoteca->image) }}" class="card-img-top" alt="{{ $discoteca->name }}">
                        <div class="card-body">
                            <h5 class="card-title">{{ $discoteca->name }}</h5>
                            <p class="card-text">{{ $discoteca->direccion }}</p>
                            <!-- Valoración -->
                            <div class="rating">
                                <span class="rating-num">{{ number_format($discoteca->valoracionMedia(), 1) }}</span>
                                @for ($i = 1; $i <= 5; $i++)
                                    @if ($i <= $discoteca->valoracionMedia())
                                        <i class="fas fa-star"></i>
                                    @elseif ($i - 0.5 <= $discoteca->valoracionMedia())
                                        <i class="fas fa-star-half-alt"></i>
                                    @else
                                        <i class="far fa-star"></i>
                                    @endif
                                @endfor
                            </div>
                            <button onclick="showEventsAndRate({{ $discoteca->id }})" class="btn btn-primary mt-3">Valorar</button>
                            <button onclick="showReviews({{ $discoteca->id }})" class="btn btn-secondary mt-3">Ver Reseñas del Evento</button>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Contenedor para mostrar los eventos de la discoteca seleccionada -->
        <div id="eventos-container" class="mt-5">
            <!-- Aquí se mostrarán los eventos -->
        </div>
    </div>
    <!-- Footer Section -->
    <footer class="footer mt-auto py-5 bg-dark" id="contact">
        <div class="container text-center">
            <span class="text-white animate__animated animate__fadeInUp">¿Listo para empezar?</span>
            <div class="mt-4">
                <a href="mailto:oasis.management.daw@gmail.com"
                    class="btn btn-outline-light animate__animated animate__fadeInUp"><i class="fas fa-envelope"></i>
                    Contacta con nosotros</a>
            </div>
            <div class="mt-4">
                <a href="https://www.tiktok.com/@oasis_management2024?lang=es"
                    class="text-white mr-3 animate__animated animate__fadeInUp"><i class="fab fa-tiktok"></i>
                    TikTok</a>
                <a href="https://www.instagram.com/oasis_management2024/"
                    class="text-white mr-3 animate__animated animate__fadeInUp"><i class="fab fa-instagram"></i>
                    Instagram</a>
            </div>
            <!-- Logos de Discotecas -->
            <div id="slider">
                <div class="slide-track">
                    @foreach ($discotecas as $discoteca)
                        <div class="slide">
                            <img src="{{ asset('img/' . $discoteca->image) }}" alt="{{ $discoteca->name }}"
                                class="img-fluid">
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </footer>
    <!-- Bootstrap JS -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <script>
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



        function submitRating(eventId, discotecaId) {
            const rating = document.getElementById(`rating-${eventId}`).value;
            const descripcion = document.getElementById(`descripcion-${eventId}`).value;

            fetch('/valoracion/store', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                },
                body: JSON.stringify({ eventId, rating, descripcion })
            })
            .then(response => {
                if (response.ok) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Valoración enviada',
                        text: '¡Gracias por tu valoración!',
                        timer: 2000,
                        timerProgressBar: true,
                        showConfirmButton: false
                    });
                    setTimeout(() => {
                        window.location.reload();
                    }, 2000);
                } else {
                    throw new Error('Hubo un problema al enviar la valoración.');
                }
            })
            .catch(error => {
                console.error('Error al enviar la valoración:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Hubo un problema al enviar la valoración. Por favor, inténtalo nuevamente.',
                    timer: 3000,
                    timerProgressBar: true,
                    showConfirmButton: false
                });
            });
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
                                </div>
                            </div>
                        </div>
                    `;
                    reviewsContainer.innerHTML += reviewHtml;
                });
            } else {
                reviewsContainer.innerHTML = '<p>No hay reseñas disponibles para este evento.</p>';
            }
        })
        .catch(error => {
            console.error('Error al obtener reseñas del evento:', error);
            const reviewsContainer = document.getElementById('eventos-container');
            reviewsContainer.innerHTML = '<p>Hubo un problema al cargar las reseñas del evento.</p>';
        });
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

    </script>
</body>

</html>
