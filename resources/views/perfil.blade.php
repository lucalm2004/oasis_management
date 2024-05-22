<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Oasis Management</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
    <link rel="stylesheet" href="css/user.css">
    <link rel="shortcut icon" href="{{ asset('img/logo.png') }}" type="image/x-icon">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
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
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" role="button"
                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="fas fa-user"></i> Mi Perfil
                        </a>
                        <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                            @auth
                                <a class="dropdown-item" href="{{ route('perfil') }}">Ver Perfil</a>
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
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('contacto') }}">
                            <i class="fas fa-envelope"></i> Contacto
                        </a>
                    </li>
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

    <div class="container mt-4">
        <div class="card">
            <div class="card-body">
                <h2 class="card-title mb-4">Perfil de Usuario</h2>
                <div class="d-flex align-items-center mb-4 position-relative">
                    <img id="profileImage"
                        src="{{ $user->foto ? asset('img/profiles/' . $user->foto) : asset('img/profiles/foto.png') }}"
                        class="rounded-circle mr-3" alt="Foto de Perfil"
                        style="width: 150px; height: 150px; cursor: pointer;">

                    <div class="puntos-section">
                        <a href="{{ route('bonificacion') }}" class="text-decoration-none">
                            <h4 class="text-center mb-2">Puntos</h4>
                        </a>
                        <div class="monedas">
                            <span>{{ $user->puntos ?? '0' }}</span>
                        </div>
                    </div>
                </div>

                <form id="profileForm" method="POST" action="{{ route('profile.update') }}"
                    enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <input type="hidden" id="cameraImage" name="cameraImage">

                    <div class="form-group">
                        <button type="button" class="btn btn-secondary" id="cameraButton"><i class="fas fa-camera"></i>
                            Abrir Cámara</button>
                        <button type="button" class="btn btn-primary" id="captureButton"
                            style="display: none;">Capturar Foto</button>
                        <video id="camera" width="300" height="200" style="display: none;" autoplay></video>
                        <canvas id="canvas" width="300" height="200" style="display: none;"></canvas>
                    </div>

                    <div class="form-group">
                        <label for="foto" class="label-for-file">
                            <span class="label-icon"><i class="fas fa-upload"></i></span>
                            <span class="label-text">Subir foto</span>
                        </label>
                        <input type="file" class="form-control-file" id="fotoInput" name="foto">
                        <img id="previewImageFile" src="#" alt="Vista previa de la imagen"
                            style="width: 200px; height: 200px; margin-top: 10px; display: none;">
                    </div>

                    <div class="form-group">
                        <i class="fas fa-user"></i>
                        <label for="name">Nombre:</label>
                        <input type="text" class="form-control" id="name" name="name"
                            value="{{ old('name', $user->name) }}" required>
                        <div id="nameError" class="error-message"></div>
                    </div>

                    <div class="form-group">
                        <i class="fas fa-envelope"></i>
                        <label for="email">Correo Electrónico:</label>
                        <input type="email" class="form-control" id="email" name="email"
                            value="{{ old('email', $user->email) }}" required>
                        <div id="emailError" class="error-message"></div>
                    </div>

                    <div class="form-group">
                        <i class="fas fa-lock"></i>
                        <label for="password">Nueva Contraseña:</label>
                        <input type="password" class="form-control" id="password" name="password">
                        <div id="passwordError" class="error-message"></div>
                    </div>

                    <div class="form-group">
                        <i class="fas fa-lock"></i>
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
                <a href="https://www.instagram.com/oasis.management2024/"
                    class="text-white mr-3 animate__animated animate__fadeInUp">
                    <i class="fab fa-instagram"></i> Instagram
                </a>
                <a href="https://www.linkedin.com/in/oasis-management-2024/"
                    class="text-white animate__animated animate__fadeInUp">
                    <i class="fab fa-linkedin"></i> LinkedIn
                </a>
            </div>
            <div class="marquee-container animate__animated animate__fadeInUp">
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

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <script>
        function previewImage(event) {
            var reader = new FileReader();
            reader.onload = function() {
                var imgElement = document.getElementById('previewImageFile');
                imgElement.src = reader.result;
                imgElement.style.display = 'block';
            };
            reader.readAsDataURL(event.target.files[0]);
        }
        document.getElementById('fotoInput').addEventListener('change', function(event) {
            previewImage(event);
        });

        document.getElementById('profileImage').addEventListener('click', function() {
            document.getElementById('fotoInput').click();
        });

        const cameraButton = document.getElementById('cameraButton');
        const captureButton = document.getElementById('captureButton');
        const video = document.getElementById('camera');
        const canvas = document.getElementById('canvas');
        const cameraImageInput = document.getElementById('cameraImage');

        cameraButton.addEventListener('click', async function() {
            const stream = await navigator.mediaDevices.getUserMedia({
                video: true
            });
            video.srcObject = stream;
            video.style.display = 'block';
            captureButton.style.display = 'inline-block';
        });

        captureButton.addEventListener('click', function() {
            const context = canvas.getContext('2d');
            context.drawImage(video, 0, 0, canvas.width, canvas.height);
            const dataUrl = canvas.toDataURL('image/png');
            cameraImageInput.value = dataUrl;
            const previewImage = document.getElementById('previewImageFile');
            previewImage.src = dataUrl;
            previewImage.style.display = 'block';
            video.style.display = 'none';
            captureButton.style.display = 'none';
            const stream = video.srcObject;
            const tracks = stream.getTracks();
            tracks.forEach(track => track.stop());
        });
    </script>
</body>

</html>
