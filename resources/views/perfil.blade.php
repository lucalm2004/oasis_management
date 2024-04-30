<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Oasis Management</title>
    <!-- Bootstrap CSS -->
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <!-- Animate.css -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="/css/user.css">
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
                    <!-- Botón de perfil -->
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" role="button"
                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            @if (Auth::check())
                                <i class="fas fa-user"></i> {{ Auth::user()->name }}
                            @else
                                Iniciar Sesión
                            @endif
                        </a>
                        <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                            @auth
                                <a class="dropdown-item" href="{{ route('perfil') }}">Ver Perfil</a>
                                <a class="dropdown-item" href="{{ url('/logout') }}">Cerrar Sesión</a>
                            @else
                                <a class="dropdown-item" href="{{ route('login') }}">Iniciar Sesión</a>
                                <a class="dropdown-item" href="{{ route('register') }}">Registrarse</a>
                            @endauth
                        </div>
                    </li>
                    <!-- Fin del botón de perfil -->

                    <!-- Otros elementos del navbar -->
                    @if (Route::has('login'))
                        @auth
                            <li class="nav-item">
                                <a href="{{ url('/dashboard') }}" class="nav-link"><i class="fas fa-chart-line"></i>
                                    Dashboard</a>
                            </li>
                        @else
                            <li class="nav-item">
                                <a href="{{ route('login') }}" class="nav-link"><i class="fas fa-sign-in-alt"></i>
                                    Iniciar Sesión</a>
                            </li>
                            <li class="nav-item">
                                <a href="/google-auth/redirect" class="nav-link"><i class="fab fa-google"></i>
                                    Login Google</a>
                            </li>
                            @if (Route::has('register'))
                                <li class="nav-item">
                                    <a href="{{ route('register') }}" class="nav-link"><i
                                            class="fas fa-user-plus"></i> Registrarse</a>
                                </li>
                            @endif
                        @endauth
                    @endif
                </ul>
            </div>
        </div>
    </nav>

    <!-- Contenido principal -->
    <div class="container mt-4">
        <div class="card">
            <div class="card-body">
                <h2 class="card-title mb-4">Perfil de Usuario</h2>
                <!-- Puntos -->
                <div class="puntos-section">
                    <h4 class="text-center mb-4">Puntos</h4>
                    <div class="puntos">
                        <span>{{ $user->puntos }}</span>
                    </div>
                </div>

                <!-- Rol -->
                <div class="form-group">
                    <label for="rol">Rol:</label>
                    <input type="text" class="form-control" id="rol" value="{{ $user->rol->name ?? 'Sin Rol' }}"
                        readonly>
                </div>

                <form id="profileForm" method="POST" action="{{ route('profile.update') }}">
                    @csrf
                    @method('PUT')

                    <div class="form-group">
                        <label for="name">Nombre:</label>
                        <input type="text" class="form-control" id="name" name="name"
                            value="{{ old('name', $user->name) }}" required>
                        <div id="nameError" class="error-message"></div>
                    </div>

                    <div class="form-group">
                        <label for="email">Correo Electrónico:</label>
                        <input type="email" class="form-control" id="email" name="email"
                            value="{{ old('email', $user->email) }}" required>
                        <div id="emailError" class="error-message"></div>
                    </div>

                    <div class="form-group">
                        <label for="password">Nueva Contraseña:</label>
                        <input type="password" class="form-control" id="password" name="password"
                            placeholder="Dejar vacío para no cambiar">
                        <div id="passwordError" class="error-message"></div>
                    </div>

                    <div class="form-group">
                        <label for="password_confirmation">Confirmar Nueva Contraseña:</label>
                        <input type="password" class="form-control" id="password_confirmation"
                            name="password_confirmation">
                        <div id="passwordConfirmationError" class="error-message"></div>
                    </div>

                    <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                </form>

            </div>
        </div>
    </div>
    <br>
    <br>

    <!-- Footer Section -->
    <footer class="footer mt-auto py-5 bg-dark" id="contact">
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
   <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const nameInput = document.getElementById('name');
        const emailInput = document.getElementById('email');
        const passwordInput = document.getElementById('password');
        const passwordConfirmationInput = document.getElementById('password_confirmation');

        // Función para mostrar un SweetAlert
        function showAlert(title, text, icon) {
            Swal.fire({
                title: title,
                text: text,
                icon: icon,
                confirmButtonText: 'Aceptar'
            });
        }

        nameInput.addEventListener('keyup', function () {
            const nameError = document.getElementById('nameError');
            nameError.textContent = '';

            if (nameInput.value.length < 3) {
                nameError.textContent = 'El nombre debe tener al menos 3 caracteres';
            }
        });

        emailInput.addEventListener('keyup', function () {
            const emailError = document.getElementById('emailError');
            emailError.textContent = '';

            if (!isValidEmail(emailInput.value)) {
                emailError.textContent = 'Ingresa un correo electrónico válido';
            }
        });

        passwordInput.addEventListener('keyup', function () {
            const passwordError = document.getElementById('passwordError');
            passwordError.textContent = '';

            if (passwordInput.value.length > 0 && passwordInput.value.length < 6) {
                passwordError.textContent = 'La contraseña debe tener al menos 6 caracteres';
            }
        });

        passwordConfirmationInput.addEventListener('keyup', function () {
            const passwordConfirmationError = document.getElementById('passwordConfirmationError');
            passwordConfirmationError.textContent = '';

            if (passwordInput.value !== passwordConfirmationInput.value) {
                passwordConfirmationError.textContent = 'Las contraseñas no coinciden';
            }
        });

        function isValidEmail(email) {
            return /\S+@\S+\.\S+/.test(email);
        }

        const profileForm = document.getElementById('profileForm');

        profileForm.addEventListener('submit', function (event) {
            const nameError = document.getElementById('nameError');
            const emailError = document.getElementById('emailError');
            const passwordError = document.getElementById('passwordError');
            const passwordConfirmationError = document.getElementById('passwordConfirmationError');

            nameError.textContent = '';
            emailError.textContent = '';
            passwordError.textContent = '';
            passwordConfirmationError.textContent = '';

            if (nameInput.value.length < 3) {
                nameError.textContent = 'El nombre debe tener al menos 3 caracteres';
                showAlert('Error', 'Por favor, introduce un nombre válido.', 'error');
                event.preventDefault();
            }

            if (!isValidEmail(emailInput.value)) {
                emailError.textContent = 'Ingresa un correo electrónico válido';
                showAlert('Error', 'Por favor, introduce un correo electrónico válido.', 'error');
                event.preventDefault();
            }

            if (passwordInput.value.length > 0 && passwordInput.value.length < 6) {
                passwordError.textContent = 'La contraseña debe tener al menos 6 caracteres';
                showAlert('Error', 'La contraseña debe tener al menos 6 caracteres.', 'error');
                event.preventDefault();
            }

            if (passwordInput.value !== passwordConfirmationInput.value) {
                passwordConfirmationError.textContent = 'Las contraseñas no coinciden';
                showAlert('Error', 'Las contraseñas no coinciden.', 'error');
                event.preventDefault();
            }

            // Mostrar SweetAlert de éxito al enviar el formulario
            showAlert('Éxito', '¡Perfil actualizado exitosamente!', 'success');
        });
    });
</script>

</body>

</html>