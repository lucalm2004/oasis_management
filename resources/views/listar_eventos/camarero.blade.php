<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://kit.fontawesome.com/e2c3124098.js" crossorigin="anonymous"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="shortcut icon" href="{{ asset('img/logo.png') }}" type="image/x-icon">
    <title>Oasis Management - Mis Eventos</title>
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
    <br>
    <br>
    <div class="container">
        <h1 style="text-align: center">Eventos</h1>
        <form action="" method="post" id="frmbusqueda" class="mt-4">
            <div class="form-group">
                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text" style="color: #F5763B;"><i class="fas fa-search"></i></span>
                    </div>
                    <input type="text" name="buscar" id="buscar" placeholder="Buscar..." class="form-control">
                </div>
            </div>
            <div class="form-group">
                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text" style="color: #F5763B;"><i
                                class="fas fa-calendar-days"></i></span>
                    </div>
                    <select class="form-control" id="fecha_inc">
                        <option value="0">Seleccione Fecha...</option>
                        <option value="asc">Fecha ascendente</option>
                        <option value="desc">Fecha descendente</option>
                    </select>
                </div>
            </div>
        </form>

        <table class="table mt-4">
            <thead class="thead-dark">
                <tr>
                    <th>Evento</th>
                    <th>Descripción</th>
                    <th>Flyer</th>
                    <th>Fecha Inicio</th>
                    <th>Fecha Final</th>
                    <th>DJ</th>
                    <th>Playlist</th>
                    <th>Discoteca</th>
                </tr>
            </thead>
            <tbody id="resultado">

            </tbody>
        </table>
    </div>
    <br>
    <br>
    <br>
    <!-- Footer Section -->
    <footer class="footer mt-auto py-5 bg-dark" id="contact" style="background-image: url('/img/oasisn2.jpg');">
        <div class=" text-center">
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
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="{{ asset('js/eventos.js') }}"></script>
</body>

</html>
