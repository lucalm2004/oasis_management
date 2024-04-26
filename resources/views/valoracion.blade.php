<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Valoración de Discotecas - Oasis Management</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/valoraciones.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
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

    <div class="container my-5">
        <h1 class="mt-5">Valoración de Discotecas</h1>

        @if ($discotecas->isEmpty())
            <p class="mt-3">No hay discotecas disponibles para valorar en este momento.</p>
        @else
            <div class="row mt-4">
                @foreach ($discotecas as $discoteca)
                    <div class="col-md-6 mb-4">
                        <div class="card">
                            <img src="{{ asset('img/' . $discoteca->image) }}" class="card-img-top"
                                alt="{{ $discoteca->name }}">
                            <div class="card-body">
                                <h5 class="card-title">{{ $discoteca->name }}</h5>
                                <p class="card-text">{{ $discoteca->description }}</p>
                                <a href="{{ route('valoracion.form', ['idEvento' => $discoteca->id]) }}"
                                    class="btn btn-primary">Valorar</a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

<!-- HTML con el slider -->
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
