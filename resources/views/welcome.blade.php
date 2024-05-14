<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Oasis Management</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
    <link rel="stylesheet" href="css/inicio.css">
    <script src="js/inicio.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/animejs/3.2.1/anime.min.js"></script>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="shortcut icon" href="{{ asset('img/logo.png') }}" type="image/x-icon">

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
                                <a href="/google-auth/redirect" class="nav-link"><i class="fab fa-google"></i> Login
                                    Google</a>
                            </li>
                            @if (Route::has('register'))
                                <li class="nav-item">
                                    <a href="{{ route('register') }}" class="nav-link"><i class="fas fa-user-plus"></i>
                                        Registrarse</a>
                                </li>
                            @endif
                        @endauth
                    @endif
                </ul>
            </div>
        </div>
    </nav>


    <!-- Sección de Inicio -->
    <section class="inicio-section py-5 animate__animated animate__delay-1s">
        <div class="container">
            <div class="row align-items">
                <div class="col-md-6 text text-md-left">
                    <h2 class="h4 text-uppercase text-muted mb-3 animate__animated animate__fadeInUp">¡Hola, somos!</h2>
                    <h1 class="display-4 font-weight-bold mb-4 animate__animated animate__fadeInLeft"><span
                            class="text-white">Oasis</span> <span class="text-orange">Management</span></h1>
                    <p class="lead mb-4 animate__animated animate__fadeInLeft">Gestionamos clubes de ocio nocturno con
                        innovación y dedicación total a nuestros clientes.</p>
                    <a href="mailto:oasis.management.daw@gmail.com"
                        class="btn btn-outline-light btn-lg animate__animated animate__fadeInUp">
                        <i class="fas fa-envelope"></i> ¡Contáctanos ahora!
                    </a>
                    <br>
                    <a class="btn btn-primary btn-lg" href="{{ route('login') }}">
                        <i class="fas fa-cocktail"></i> Nuestras discotecas
                    </a>

                </div>
                <div class="col-md-6 text-center mb-4 mb-md-0">
                    <br>
                    <img src="/img/logonegro.png" alt="Logo Oasis Management" class="img-fluid logo-img">
                </div>
            </div>
        </div>
    </section>


    <section class="full-width-section py-5 animate__animated animate__delay-1s">
        <div class="container-fluid"> <!-- Usar container-fluid para ocupar todo el ancho -->
            <div class="row justify-content-center"> <!-- Centrar el contenido horizontalmente -->
                <div class="col-md-9 col-lg-7 animated">
                    <!-- Ajustar el tamaño de la columna para diferentes tamaños de pantalla -->
                    <div class="content-box text-center">
                        <!-- Verifica la ruta y la extensión de la imagen -->
                        <img src="/img/gente.avif" class="img-fluid mb-4" alt="Imagen 4">
                        <h2 class="h2 mb-4"><i class="fas fa-glass-cheers"></i> Descubre el mejor entretenimiento
                            nocturno</h2>
                        <p class="lead mb-4">Explora los eventos nocturnos más destacados cerca de ti.</p>
                        <p class="lead mb-4">Reserva fácilmente entradas, interactúa con otros asistentes a través del
                            chat y disfruta de bonificaciones exclusivas.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>



    <!-- Sección de Quiénes somos -->
    <section class="py-5 animate__animated animate__fadeIn">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-6 animated">
                    <div class="content-box">
                        <img src="/img/dj.jpg" class="img-fluid mb-4" alt="Imagen 5">
                        <h2><i class="fas fa-users"></i> Quiénes somos</h2>
                        <p>Somos Oasis Management, una plataforma dedicada a la gestión de clubes de ocio nocturno con
                            enfoque innovador.</p>
                        <p>Nuestro objetivo es conectar a los amantes del entretenimiento nocturno con los mejores
                            eventos, ofreciendo experiencias únicas.</p>
                    </div>
                </div>
                <div class="col-md-6 animated">
                    <div class="content-box">
                        <img src="/img/chica.jpg" class="img-fluid mb-4" alt="Imagen 6">
                        <h2><i class="fas fa-rocket"></i> Nuestra plataforma</h2>
                        <p>En Oasis Management, nuestra plataforma facilita la gestión eficiente de eventos en
                            discotecas.</p>
                        <p>Ofrecemos una interfaz robusta y fácil de usar para organizar y promover eventos, desde la
                            gestión de invitaciones hasta la reserva de entradas.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Sección de Lo que nos destaca -->
    <section class="py-5 animate__animated animate__fadeIn">
        <div class="container">
            <div class="row">
                <div class="col-md-6 animated">
                    <div class="content-box">
                        <img src="/img/foto2.jpg" class="img-fluid mb-4" alt="Imagen 7">
                        <h2><i class="fas fa-hand-holding-heart"></i> Lo que nos destaca</h2>
                        <p>Destacamos por nuestra atención personalizada y capacidad para adaptarnos a las necesidades
                            específicas de cada cliente.</p>
                        <p>Además, ofrecemos una amplia gama de servicios adicionales para garantizar una experiencia
                            inolvidable en cada evento.</p><br><br>
                    </div>
                </div>
                <div class="col-md-6 animated">
                    <div class="content-box">
                        <img src="/img/foto4.jpg" class="img-fluid mb-4" alt="Imagen de Promoción">
                        <h2><i class="fas fa-gift"></i> Promociones exclusivas</h2>
                        <p>Aprovecha nuestras promociones exclusivas y descuentos especiales para disfrutar al máximo en
                            nuestros eventos.</p>
                        <p>Regístrate para recibir ofertas personalizadas y beneficios adicionales como miembro de Oasis
                            Management.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Sección de Valorar discotecas -->
    <section id="valorar" class="container text-center my-5 animate__animated animate__fadeInUp">
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
    </section>

    <!-- Footer Section -->
    <footer class="footer mt-auto py-5 bg-dark" id="contact" style="background-image: url('/img/oasisn2.jpg');">
        <div class="container text-center">
            <h2 class="text-white mb-4 animate__animated animate__fadeInUp">¿Listo para llevar tu negocio al siguiente
                nivel?</h2>
            <p class="text-white mb-4 animate__animated animate__fadeInUp">Contáctanos para conocer cómo podemos
                colaborar juntos.</p>
            <div class="mt-4">
                <a href="mailto:oasis.management.daw@gmail.com"
                    class="btn btn-outline-light btn-lg animate__animated animate__fadeInUp">
                    <i class="fas fa-envelope"></i> ¡Contáctanos ahora!
                </a>
            </div>
            <div class="mt-4">
                <a href="https://www.tiktok.com/@oasis_management2024?lang=es"
                    class="text-white mr-3 animate__animated animate__fadeInUp">
                    <i class="fab fa-tiktok"></i> TikTok
                </a>
                <a href="https://www.instagram.com/oasis_management2024/"
                    class="text-white mr-3 animate__animated animate__fadeInUp">
                    <i class="fab fa-instagram"></i> Instagram
                </a>
            </div>
            <!-- Logos de Discotecas -->
            <div id="slider" class="slider mt-5">
                <div class="slide-track d-flex justify-content-center align-items-center">
                    @foreach ($discotecas as $discoteca)
                        <div class="slide mr-3">
                            <img src="{{ asset('img/discotecas/' . $discoteca->image) }}"
                                alt="{{ $discoteca->name }}" class="img-fluid">
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </footer>
    <!-- Alerta de Cookies -->
    <div id="cookieBanner">
        <p>Este sitio web utiliza cookies para mejorar la experiencia del usuario. ¿Aceptas nuestras cookies?</p>
        <button id="cookieAcceptButton">Aceptar</button>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <!-- Script de SweetAlert -->
    <script>
        // Volver a ejecutar la animación al desplazarse por la página
        window.addEventListener('scroll', function() {
            animateOnScroll();
        });

        document.addEventListener('DOMContentLoaded', function() {
            const valorarBtn = document.getElementById('valorarBtn');

            if (valorarBtn) {
                valorarBtn.addEventListener('click', function() {
                    // Verificar si el usuario está autenticado
                    const authenticated =
                    @json(auth()->check()); // Esto asume que estás usando Laravel y Blade

                    if (!authenticated) {
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
                                window.location.href = '{{ route('login') }}';
                            }
                        });
                    } else {
                        // Si el usuario está autenticado, redirigir a la página de valoración
                        window.location.href = '{{ route('valoracion') }}';
                    }
                });
            }
        });

        // Animación para el título principal
        anime({
            targets: '.display-4',
            translateY: [-50, 0],
            opacity: [0, 1],
            easing: 'easeOutExpo',
            duration: 1500,
            delay: anime.stagger(200)
        });

        // Animación para los elementos de la sección de Quiénes somos
        anime({
            targets: '.animated',
            translateX: [-50, 0],
            opacity: [0, 1],
            easing: 'easeOutExpo',
            duration: 1500,
            delay: anime.stagger(200)
        });
    </script>

</body>

</html>
