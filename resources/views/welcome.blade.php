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
    <style>
        .text-orange {
            color: orange;
            /* Define el color naranja */
        }
    </style>
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
    <!-- Sección de Números -->
    <section id="más-info" class="py-5 bg-light">
        <div class="container">
            <h2 class="h2 mb-4 text-center">Por Los Números</h2>
            <div class="row">
                <div class="col-md-4 text-center">
                    <h3 class="display-4 text-orange"><i class="fas fa-users"></i> {{ $numClubes }}</h3>
                    <p>Clubes Se Han Unido</p>
                </div>
                <div class="col-md-4 text-center">
                    <h3 class="display-4 text-orange"><i class="fas fa-smile"></i> {{ $numUsuarios }}</h3>
                    <p>Usuarios Felices</p>
                </div>
                <div class="col-md-4 text-center">
                    <h3 class="display-4 text-orange"><i class="fas fa-star"></i>
                        {{ number_format($mediaEstrellas, 2) }}</h3>
                    <p>Media de Estrellas</p>
                </div>
            </div>
        </div>
    </section>
    <style>
        .pagination-container {
            overflow: hidden;
        }

        .swiper-pagination {
            margin-top: 20px;
        }

        .content-box {
            background-color: transparent;
            padding: 30px;
            border-radius: 5px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
            margin-bottom: 50px;
            border: #fafafa;
            color: white;
            border-style: double;
            max-height: 91%;
            overflow: hidden;
        }

        .content-box h2 {
            color: #f58220;
        }

        .content-box p {
            color: #ffffff;
        }

        /* Estilo responsivo para mostrar tres contenedores juntos en una fila */
        @media (min-width: 992px) {
            .swiper-slide .row>div {
                flex: 0 0 33.333%;
                max-width: 33.333%;
            }
        }
    </style>
    </head>

    <body>

        <section class="py-5 animate__animated animate__fadeIn">
            <div class="container">
                <div class="swiper-container">
                    <div class="swiper-wrapper">
                        <div class="swiper-slide">
                            <div class="row align-items-center">
                                <div class="col-md-6 animated d-flex">
                                    <div class="content-box flex-grow-1">
                                        <img src="/img/dj.jpg" class="img-fluid mb-4" alt="Imagen 5">
                                        <h2><i class="fas fa-users"></i> Quiénes somos</h2>
                                        <p>Somos Oasis Management, una plataforma dedicada a la gestión de clubes de
                                            ocio nocturno con
                                            enfoque innovador.</p>
                                        <p>Nuestro objetivo es conectar a los amantes del entretenimiento nocturno con
                                            los mejores
                                            eventos, ofreciendo experiencias únicas.</p>
                                    </div>
                                </div>
                                <div class="col-md-6 animated d-flex">
                                    <div class="content-box flex-grow-1">
                                        <img src="/img/foto2.jpg" class="img-fluid mb-4" alt="Imagen 7">
                                        <h2><i class="fas fa-hand-holding-heart"></i> Lo que nos destaca</h2>
                                        <p>Destacamos por nuestra atención personalizada y capacidad para adaptarnos a
                                            las necesidades
                                            específicas de cada cliente.</p>
                                        <p>Además, ofrecemos una amplia gama de servicios adicionales para garantizar
                                            una experiencia
                                            inolvidable en cada evento.</p><br>
                                    </div>
                                </div>
                                <div class="col-md-6 animated d-flex">
                                    <div class="content-box flex-grow-1">
                                        <img src="/img/foto4.jpg" class="img-fluid mb-4" alt="Imagen de Promoción">
                                        <h2><i class="fas fa-gift"></i> Promociones exclusivas</h2>
                                        <p>Aprovecha nuestras promociones exclusivas y descuentos especiales para
                                            disfrutar al máximo en
                                            nuestros eventos.</p>
                                        <p>Regístrate para recibir ofertas personalizadas y beneficios adicionales como
                                            miembro de Oasis
                                            Management.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="py-5 bg-light animate__animated animate__fadeIn">
            <div class="container text-center">
                <i class="fas fa-sun weather-icon"></i> <!-- Icono de sol -->
                <h3 class="rating-title text-orange">Hoy es un buen día para salir?</h3>
                <p class="rating-description text-black">No te pierdas la oportunidad de disfrutar</p>
                <div class="rating-button">
                    <div id="weatherInfo" class="temperature mt-3 text-black"></div>
                </div>
            </div>
        </section>

        <section class="inicio-section py-5 animate__animated animate__delay-1s">
            <div class="container">
                <div class="row align-items">
                    <div class="col-md-6">
                        <h2 class="h2 mb-4 text-orange"><i class="fas fa-star text-orange"></i> La Era Oasis</h2>
                        <p class="lead mb-4 text-white">Explora la era de Oasis Management, gestionando clubes de ocio
                            nocturno con
                            innovación y dedicación total a nuestros clientes.</p>
                        <p class="lead mb-4 text-white">Descubre cómo conectamos a los amantes del entretenimiento
                            nocturno con
                            los mejores eventos, ofreciendo experiencias únicas.</p>
                    </div>
                    <div class="col-md-6 text-center">
                        <img src="/img/chat1.jpg" class="img-fluid mb-4" alt="Era Oasis Image">
                    </div>
                </div>
            </div>
        </section>

        <!-- Sección de Valorar discotecas -->
        <section id="más-info" class="py-5 bg-light animate__animated animate__fadeIn">
            <div class="container text-center">
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
        <!-- Sección de Preguntas Frecuentes (FAQ) -->
        <section class="py-5 ">
            <div class="container">
                <h2 class="h2 mb-4 text-orange"><i class="fas fa-question-circle text-orange"></i> Preguntas
                    Frecuentes
                </h2>

                <!-- Lista de Preguntas y Respuestas -->
                <div class="accordion" id="faqAccordion">

                    <!-- Pregunta 1 -->
                    <div class="card">
                        <div class="card-header" id="headingOne">
                            <h2 class="mb-0">
                                <button class="btn btn-link text-orange" type="button" data-toggle="collapse"
                                    data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                    ¿Cuáles son los beneficios de unirse a Oasis Management?
                                </button>
                            </h2>
                        </div>
                        <div id="collapseOne" class="collapse show" aria-labelledby="headingOne"
                            data-parent="#faqAccordion">
                            <div class="card-body">
                                Al unirte a Oasis Management, tendrás acceso a los mejores eventos nocturnos, podrás
                                reservar entradas fácilmente y disfrutar de promociones exclusivas. Además, nuestra
                                plataforma facilita la gestión de eventos en discotecas.
                            </div>
                        </div>
                    </div>

                    <!-- Pregunta 2 -->
                    <div class="card">
                        <div class="card-header" id="headingTwo">
                            <h2 class="mb-0">
                                <button class="btn btn-link text-orange collapsed" type="button"
                                    data-toggle="collapse" data-target="#collapseTwo" aria-expanded="false"
                                    aria-controls="collapseTwo">
                                    ¿Cómo puedo contactar al equipo de Oasis Management?
                                </button>
                            </h2>
                        </div>
                        <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo"
                            data-parent="#faqAccordion">
                            <div class="card-body">
                                Puedes contactarnos enviando un correo electrónico a oasis.management.daw@gmail.com o a
                                través de nuestros perfiles en redes sociales como TikTok e Instagram.
                            </div>
                        </div>
                    </div>

                    <!-- Pregunta 3 -->
                    <div class="card">
                        <div class="card-header" id="headingThree">
                            <h2 class="mb-0">
                                <button class="btn btn-link text-orange collapsed" type="button"
                                    data-toggle="collapse" data-target="#collapseThree" aria-expanded="false"
                                    aria-controls="collapseThree">
                                    ¿Cómo puedo valorar las discotecas asociadas?
                                </button>
                            </h2>
                        </div>
                        <div id="collapseThree" class="collapse" aria-labelledby="headingThree"
                            data-parent="#faqAccordion">
                            <div class="card-body">
                                Para valorar nuestras discotecas asociadas, puedes hacer clic en el botón "Valorar" en
                                nuestra página web. Si no estás autenticado, será necesario iniciar sesión para realizar
                                la valoración.
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- Footer Section -->
        <footer class="footer mt-auto py-5 bg-dark" id="contact"
            style="background-image: url('/img/oasisn2.jpg');">
            <div class="container text-center">
                <h2 class="text-white mb-4 animate__animated animate__fadeInUp">¿Listo para llevar tu negocio al
                    siguiente
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
        </script>
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
