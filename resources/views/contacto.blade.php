<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Oasis Management</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
    <link rel="stylesheet" href="css/contacto.css">
    <script src="js/contacto.js"></script>
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
    <div class="py-5"> <!-- Agregar padding después del contenedor -->
        <div class="container contacto-section border rounded p-4"> <!-- Agregar recuadro al container con padding -->
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <h2 class="text-center mb-4">Contáctanos</h2>
                    <div class="form-group">
                        <label for="mensaje">Mensaje</label>
                        <div class="rounded border p-3"> <!-- Agregar recuadro al textarea con padding -->
                            <textarea class="form-control bg-light" id="mensaje" rows="5" style="font-size: 16px;" placeholder="Escribe tu mensaje aquí"></textarea>
                        </div>
                        <button class="btn btn-primary mt-3" onclick="showContactMessage()">Copiar Mensaje</button>
                    </div>
                    <div class="mt-4">
                        <button class="btn btn-outline-light animate__animated animate__fadeInUp" onclick="sendEmail()">
                            <i class="fas fa-envelope"></i> Contacta con nosotros
                        </button>
                    </div>
                    <br>
                    <label for="mensaje">Escribe tu mensaje y pulsa "Copiar Mensaje" para copiarlo. Después, selecciona "Contacta con nosotros" para continuar.</label>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Footer Section -->
    <footer class="footer mt-auto py-5 bg-dark" id="contact">
        <div class="container text-center animate__animated animate__fadeInUp">
            <span class="text-white">¿Listo para empezar?</span>
            <div class="mt-4">
                <a href="mailto:oasis.management.daw@gmail.com" class="btn btn-outline-light animate__animated animate__fadeInUp"><i class="fas fa-envelope"></i> Contacta con nosotros</a>
            </div>
            <div class="mt-4">
                <a href="https://www.tiktok.com/@oasis_management2024?lang=es" class="text-white mr-3 animate__animated animate__fadeInUp"><i class="fab fa-tiktok"></i> TikTok</a>
                <a href="https://www.instagram.com/oasis_management2024/" class="text-white mr-3 animate__animated animate__fadeInUp"><i class="fab fa-instagram"></i> Instagram</a>
            </div>
            <!-- Logos de Discotecas -->
            <div id="slider" class="slider">
                <div class="slide-track">
                    @foreach ($discotecas as $discoteca)
                        <div class="slide">
                            <img src="{{ asset('img/' . $discoteca->image) }}" alt="{{ $discoteca->name }}" class="img-fluid">
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

</body>

</html>
