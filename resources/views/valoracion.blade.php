<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Oasis Management</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
    <link rel="stylesheet" href="css/valoraciones.css">
    <script src="js/valoracion.js"></script>
    <link rel="shortcut icon" href="{{ asset('img/logo.png') }}" type="image/x-icon">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <!-- SweetAlert CDN -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-white">
        <div class="container">
            <a class="navbar-brand" href="{{ route('welcome') }}">
                <img src="/img/logonegro.png" class="logo mr-2" alt="Logo">
                <span class="font-weight-bold text-uppercase">
                    Oasis <span class="orange-text">Management</span>
                </span>
            </a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ml-auto">
                    <!-- Botón de perfil -->
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" role="button"
                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="fas fa-user"></i> Mi Perfil
                        </a>
                        <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                            @auth
                                <a class="dropdown-item" href="{{ route('perfil') }}">Ver Perfil</a>
                                                               <!-- Enlace para cerrar sesión -->
                                                               <form id="logoutForm" action="{{ route('logout') }}" method="POST">
                                                                @csrf
                                                                <button type="submit" class="dropdown-item">Cerrar Sesión</button>
                                                            </form>
                            @else
                                <a class="dropdown-item" href="{{ route('login') }}">Iniciar Sesión</a>
                                <a class="dropdown-item" href="{{ route('register') }}">Registrarse</a>
                            @endauth
                        </div>
                    </li>
                    <!-- Fin del botón de perfil -->
    
                    <!-- Enlace de contacto con emoticono -->
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('contacto') }}">
                            <i class="fas fa-envelope"></i> Contacto
                        </a>
                    </li>
    
                    <!-- Otros elementos del navbar -->
                    @if (Route::has('login'))
                        @auth
                        @else
                            <li class="nav-item">
                                <a href="/google-auth/redirect" class="nav-link"><i class="fab fa-google"></i> Login Google</a>
                            </li>
                            @if (Route::has('register'))
                                <li class="nav-item">
                                    <a href="{{ route('register') }}" class="nav-link"><i class="fas fa-user-plus"></i> Registrarse</a>
                                </li>
                            @endif
                        @endauth
                    @endif
                </ul>
            </div>
        </div>
    </nav>
    
    <div class="container mt-5">
        <h1>Valoración de Eventos</h1>
        <h3>¡Valora los eventos de tus discotecas favoritas y comparte tus experiencias!</h3>
            <!-- Contenedor principal -->
            <div class="container mt-5">
                <h1>Usuarios con Mejor Valoración</h1>
                <button onclick="toggleTopRatedUsers()" class="btn btn-info mt-3">
                    Ver Usuarios Mejor Valorados
                </button>
                <div id="top-rated-users" class="mt-4">
                    <!-- Aquí se mostrarán los usuarios con mejor valoración -->
                </div>
            </div>
            

        <div id="top-rated-users" class="mt-5">
            <!-- Aquí se mostrarán los usuarios con mejor valoración -->
        </div>
        <div class="row mt-4">
            @foreach ($discotecas as $discoteca)
                <div class="col-md-6 mb-4">
                    <div class="card">
                        <img src="{{ asset('img/discotecas/' . $discoteca->image) }}" class="card-img-top" alt="{{ $discoteca->name }}">
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
<footer class="footer mt-auto py-5 bg-dark" id="contact" style="background-image: url('/img/oasisn2.jpg');">
    <div class="container text-center">
        <h2 class="text-white mb-4 animate__animated animate__fadeInUp">¿Listo para llevar tu negocio al siguiente nivel?</h2>
        <p class="text-white mb-4 animate__animated animate__fadeInUp">Contáctanos para conocer cómo podemos colaborar juntos.</p>
        <div class="mt-4">
            <a href="mailto:oasis.management.daw@gmail.com" class="btn btn-outline-light btn-lg animate__animated animate__fadeInUp">
                <i class="fas fa-envelope"></i> ¡Contáctanos ahora!
            </a>
        </div>
        <div class="mt-4">
            <a href="https://www.tiktok.com/@oasis_management2024?lang=es" class="text-white mr-3 animate__animated animate__fadeInUp">
                <i class="fab fa-tiktok"></i> TikTok
            </a>
            <a href="https://www.instagram.com/oasis_management2024/" class="text-white mr-3 animate__animated animate__fadeInUp">
                <i class="fab fa-instagram"></i> Instagram
            </a>
        </div>
        <!-- Logos de Discotecas -->
        <div id="slider" class="slider mt-5">
            <div class="slide-track d-flex justify-content-center align-items-center">
                @foreach ($discotecas as $discoteca)
                    <div class="slide mr-3">
                        <img src="{{ asset('img/discotecas/' . $discoteca->image) }}" alt="{{ $discoteca->name }}" class="img-fluid">
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</footer>
   
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>



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
    </script>
</body>

</html>
