<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Oasis Management</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
    <link rel="stylesheet" href="css/inicio.css">
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
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
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

    <!-- Slider de Imágenes con efecto parallax -->
    <div class="parallax">
        <div id="carouselExampleSlidesOnly" class="carousel slide" data-ride="carousel">
            <ol class="carousel-indicators">
                <li data-target="#carouselExampleSlidesOnly" data-slide-to="0" class="active"></li>
                <li data-target="#carouselExampleSlidesOnly" data-slide-to="1"></li>
                <li data-target="#carouselExampleSlidesOnly" data-slide-to="2"></li>
                <!-- Agrega más elementos li según el número de imágenes -->
            </ol>
            <div class="carousel-inner">
                <div class="carousel-item active">
                    <img src="/img/foto.jpg" class="d-block w-100" alt="Imagen 1">
                </div>
                <div class="carousel-item">
                    <img src="/img/foto2.jpg" class="d-block w-100" alt="Imagen 2">
                </div>
                <div class="carousel-item">
                    <img src="/img/foto3.jpg" class="d-block w-100" alt="Imagen 3">
                </div>
                <div class="carousel-item">
                    <img src="/img/chat.avif" class="d-block w-100" alt="Imagen 4">
                </div>
                <!-- Agrega más div.carousel-item según el número de imágenes -->
            </div>
            <a class="carousel-control-prev" href="#carouselExampleSlidesOnly" role="button" data-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="sr-only">Previous</span>
            </a>
            <a class="carousel-control-next" href="#carouselExampleSlidesOnly" role="button" data-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="sr-only">Next</span>
            </a>
        </div>
    </div>

    <!-- Contenido debajo del Slider -->
    <div class="container my-5">
        <div class="row">
            <div class="col-md-6">
                <img src="/img/foto4.jpg" class="img-fluid animate__animated animate__fadeInLeft" alt="Imagen 4">
            </div>
            <div class="col-md-6 mt-4 mt-md-0">
                <div class="content-box animate__animated animate__fadeInRight">
                    <h2><i class="fas fa-glass-cheers"></i> Tu puerta de acceso al mejor entretenimiento
                        nocturno</h2>
                    <p>¡Descubre los mejores eventos nocturnos cerca de ti!</p>
                    <p>Facilidad de reserva de entradas, la posibilidad de interactuar con otros asistentes a través
                        del chat y las oportunidades de ganar bonificaciones y premios exclusivos.</p>
                </div>
            </div>
        </div>
        <div class="row mt-5">
            <div class="col-md-6 order-md-2">
                <img src="/img/cubatas.jpg" class="img-fluid animate__animated animate__fadeInLeft" alt="Imagen 5">
            </div>
            <div class="col-md-6 order-md-1 mt-4 mt-md-0">
                <div class="content-box animate__animated animate__fadeInRight">
                    <h2><i class="fas fa-users"></i> Quiénes somos</h2>
                    <p>Somos Oasis Management, una plataforma dedicada a la gestión de clubes de ocio nocturno. Nos
                        destacamos por nuestra innovación y compromiso con nuestros clientes.</p>
                    <p>Nuestro objetivo es ofrecer una experiencia única y emocionante en el mundo del entretenimiento
                        nocturno, conectando a los amantes de la diversión con los mejores eventos y lugares.</p>
                </div>
            </div>
        </div>
        <div class="row mt-5">
            <div class="col-md-6">
                <img src="/img/chat1.jpg" class="img-fluid animate__animated animate__fadeInLeft" alt="Imagen 6">
            </div>
            <div class="col-md-6 mt-4 mt-md-0">
                <div class="content-box animate__animated animate__fadeInRight">
                    <h2><i class="fas fa-rocket"></i> Qué tenemos</h2>
                    <p>Contamos con una plataforma robusta y fácil de usar que simplifica la gestión de eventos en
                        discotecas. Además, ofrecemos un equipo comprometido y profesional para satisfacer todas las
                        necesidades de nuestros clientes.</p>
                </div>
            </div>
        </div>
        <div class="row mt-5">
            <div class="col-md-6 order-md-2">
                <img src="/img/cartera.png" class="img-fluid animate__animated animate__fadeInLeft" alt="Imagen 7">
            </div>
            <div class="col-md-6 order-md-1 mt-4 mt-md-0">
                <div class="content-box animate__animated animate__fadeInRight">
                    <h2><i class="fas fa-hand-holding-heart"></i> Qué hacemos para destacar</h2>
                    <p>Nos destacamos por nuestra atención personalizada y nuestra capacidad para adaptarnos a las
                        necesidades específicas de cada cliente. Además, ofrecemos una amplia gama de servicios
                        adicionales para garantizar una experiencia inolvidable en cada evento.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Contenedor para valorar discotecas -->
    <div class="container text-center my-5">
        <div class="rating-section">
            <h3 class="rating-title">Valora nuestras discotecas</h3>
            <p class="rating-description">¿Cómo calificarías tu experiencia en nuestras discotecas asociadas?</p>
            <div class="rating-button">
                @auth
                <a href="{{ route('valoracion') }}" class="btn btn-primary btn-lg">
                    <i class="fas fa-star"></i> Valorar
                </a>
                @else
                <button class="btn btn-primary btn-lg" id="valorarBtn">
                    <i class="fas fa-star"></i> Valorar
                </button>
                @endauth
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="footer mt-auto py-5 " id="contact">
        <div class="container text-center">
            <span class="text-white animate__animated animate__fadeInUp">¿Listo para empezar?</span>
            <div class="mt-4">
                <a href="mailto:oasis.management.daw@gmail.com"
                    class="btn btn-outline-light animate__animated animate__fadeInUp"><i
                        class="fas fa-envelope"></i> Contacta con nosotros</a>
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
            <div class="row mt-5">
                @foreach ($discotecas as $discoteca)
                <div class="col-6 col-md-3 mb-4 animate__animated animate__fadeIn">
                    <img src="{{ asset('img/' . $discoteca->image) }}" alt="{{ $discoteca->name }}"
                        class="img-fluid">
                </div>
                @endforeach
            </div>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <!-- Script de SweetAlert -->
    <script>
        
        document.getElementById('valorarBtn').addEventListener('click', function () {
            // Verificar si el usuario está autenticado
            @guest
                // Mostrar popup SweetAlert si el usuario no está autenticado
                Swal.fire({
                    icon: 'warning',
                    title: '¡Inicia sesión!',
                    text: 'Debes iniciar sesión para valorar nuestras discotecas.',
                    showCancelButton: true,
                    cancelButtonText: 'Cancelar',
                    confirmButtonText: 'Iniciar sesión',
                    reverseButtons: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Redirigir al usuario a la página de inicio de sesión
                        window.location.href = '{{ route("login") }}';
                    }
                });
            @else
                // Si el usuario está autenticado, redirigir a la página de valoración
                window.location.href = '{{ route("valoracion") }}';
            @endguest
        });
    </script>
</body>

</html>
