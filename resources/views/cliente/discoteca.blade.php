<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Discotecas - Oasis Management</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet.awesome-markers/2.0.6/leaflet.awesome-markers.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/discoteca.css') }}">
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


<!-- Contenido Principal -->
<div id="container">
    <!-- Puntos del Usuario -->
    <p class="points">Tienes <a href="#" id="puntosLink"><span id="puntosUsuario"></span></a> puntos.</p>
    
    <div class="contenedor-input">
        <i class="fas fa-search lupa-naranja"></i>
        <input type="text" id="filtroNombre" placeholder="Filtrar por nombre...">
        <select id="selectCiudad">
            <option value="">Todas las ciudades</option>
            <!-- Opciones de ciudades se cargarán dinámicamente aquí -->
        </select>
    </div>

    <!-- Contenedor de Discotecas -->
    <div id="discotecasContainer">
        <!-- Las discotecas se mostrarán aquí -->
    </div>

    <!-- Mapa -->
    <div id="mapid" style="width: 100%; height: 400px; border-radius: 12px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);"></div>
</div>

<!-- Popup -->
<div id="popup">
    <h2>Introducir Código</h2>
    <form action="" method="POST">
        @csrf
        <label for="codigo">Código:</label><br>
        <input type="text" id="codigo" name="codigo"><br><br>
        <button type="submit">Enviar</button>
    </form>
    <br>
    <button onclick="cerrarPopup()" id="cerrarpop">Cerrar</button>
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

<!-- JavaScript al final del cuerpo del documento para optimización de carga -->
<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet.awesome-markers/2.0.6/leaflet.awesome-markers.js"></script>

<script>
// Inicialización del mapa
    var map = L.map('mapid').setView([40.505, -100.09], 4);

    // Capa base del mapa (OpenStreetMap)
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
    }).addTo(map);

    // Iterar sobre las discotecas y agregar marcadores con ventanas emergentes personalizadas
    @foreach ($discotecas as $discoteca)
        var marker = L.marker([{{ $discoteca->lat }}, {{ $discoteca->long }}]).addTo(map);

        var popupContent = `
            <div style="max-width: 300px; padding: 15px;">
                <img src="{{ asset('img/discotecas/' . $discoteca->image) }}" alt="{{ $discoteca->name }}" style="width: 100%; border-radius: 5px; margin-bottom: 10px;">
                <p style="color: grey; margin-bottom: 10px;">{{ $discoteca->description }}</p>
                <hr>
                <p style="margin-top: 10px;">{{ $discoteca->direccion }}</p>
                <div style="display: flex; justify-content: space-around; margin-top: 10px;">
                    <button type="button" class="btn btn-primary rounded-circle custom-btn" onclick="darFavorito({{ $discoteca->id }})">
                        <i class="fas fa-star"></i>
                    </button>
                    <button type="button" class="btn btn-info rounded-circle custom-btn" onclick="comoLlegar('{{ $discoteca->lat }}', '{{ $discoteca->long }}')">
                        <i class="fas fa-directions"></i>
                    </button>
                </div>
            </div>
        `;

        // Asigna el contenido de la ventana emergente al marcador y deshabilita el cierre automático
        marker.bindPopup(popupContent, { autoClose: false });
    @endforeach

    // Función para marcar como favorito
    function darFavorito(id) {
        console.log("Marcado como favorito: " + id);
        // Lógica para marcar como favorito con el ID del lugar
    }

    // Función para obtener direcciones de cómo llegar
    function comoLlegar(lat, long) {
        console.log("Obtener direcciones para: Latitud " + lat + ", Longitud " + long);
        // Lógica para obtener direcciones de cómo llegar usando latitud y longitud
    }
</script>



    <script>
        function mostrarPopup() {
            document.getElementById('popup').style.display = 'block';
        }

        function cerrarPopup() {
            document.getElementById('popup').style.display = 'none';
        }

        // Función que se ejecutará cuando se haga clic en el enlace de puntos
        document.getElementById("puntosLink").addEventListener("click", function(event) {
            // Evitar que el enlace siga su enlace predeterminado
            event.preventDefault();
            // Redirigir a la página de bonificaciones
            window.location.href = "{{ route('cliente.bonificacion') }}";
        });

        function cargarPuntosUsuario() {
            // Realizar una solicitud AJAX para obtener los puntos del usuario
            var xhr = new XMLHttpRequest();
            xhr.open("GET", "/cliente/mostrarpuntos", true);
            xhr.onreadystatechange = function() {
                if (xhr.readyState === 4 && xhr.status === 200) {
                    var puntosUsuario = JSON.parse(xhr.responseText);
                    // Verificar si puntosUsuario es un objeto y mostrar 0 en su lugar
                    if (typeof puntosUsuario === 'object' || puntosUsuario === null) {
                        puntosUsuario = 0;
                    }
                    // Actualizar el contenido del elemento HTML con el número de puntos
                    document.getElementById("puntosUsuario").textContent = puntosUsuario;
                }
            };
            xhr.send();
        }



        document.addEventListener("DOMContentLoaded", function() {
            cargarDatos();
            cargarCiudades();

            // Escuchar el evento de cambio en el selector de ciudad
            document.getElementById("selectCiudad").addEventListener("change", function() {
                cargarDatos();
            });

            // Escuchar el evento de entrada en el campo de filtro de nombre
            document.getElementById("filtroNombre").addEventListener("keyup", function() {
                cargarDatos();
            });
        });

        // Función para cargar y filtrar las discotecas por AJAX
        function cargarDatos() {
            var idCiudad = document.getElementById("selectCiudad").value;
            var filtroNombre = document.getElementById("filtroNombre").value.toLowerCase();

            console.log("ID Ciudad:", idCiudad);
            console.log("Filtro Nombre:", filtroNombre);

            var xhr = new XMLHttpRequest();

            xhr.onreadystatechange = function() {
                if (xhr.readyState === XMLHttpRequest.DONE) {
                    if (xhr.status === 200) {
                        console.log("Respuesta del servidor:", xhr.responseText);
                        mostrarDiscotecas(JSON.parse(xhr.responseText));
                    } else {
                        console.error('Error al cargar las discotecas:', xhr.status);
                    }
                }
            };

            var url = '/cliente/mostrardisco';

            // Agregar parámetro de filtro de ciudad si está presente
            if (idCiudad.trim() !== '') {
                url += '?ciudad=' + encodeURIComponent(idCiudad.trim());
            }

            // Agregar parámetro de filtro de nombre si está presente
            if (filtroNombre.trim() !== '') {
                url += (url.includes('?') ? '&' : '?') + 'nombre=' + encodeURIComponent(filtroNombre.trim());
            }

            console.log("URL de la solicitud AJAX:", url);

            xhr.open('GET', url, true);
            xhr.send();
        }

    function mostrarDiscotecas(discotecas) {
        var discotecasContainer = document.getElementById('discotecasContainer');
        discotecasContainer.innerHTML = ''; // Limpiar el contenido anterior

        discotecas.forEach(function(discoteca) {
        // Crear un div para la tarjeta
        var cardDiv = document.createElement('div');
        cardDiv.classList.add('card');

        // Establecer estilos para la tarjeta
        cardDiv.style.width = '300px';
        cardDiv.style.border = '1px solid #ddd';
        cardDiv.style.borderRadius = '8px';
        cardDiv.style.boxShadow = '0 2px 4px rgba(0, 0, 0, 0.1)';
        cardDiv.style.marginBottom = '40px'; // Aumentar el margen inferior
        cardDiv.style.overflow = 'hidden';
        cardDiv.style.backgroundColor = 'black'; // Fondo negro

        // Crear una imagen para la foto de la discoteca
        var cardImage = document.createElement('img');
        cardImage.classList.add('card-image'); // Agregar clase para la imagen
        cardImage.src = '{{ asset("img/discotecas/") }}' + '/' + discoteca.image; // Establecer la ruta completa de la imagen
        cardImage.alt = discoteca.name; // Establecer el texto alternativo de la imagen

        // Establecer estilos para la imagen
        cardImage.style.width = '100%';
        cardImage.style.height = '200px';
        cardImage.style.objectFit = 'cover';

        // Crear un encabezado para el nombre de la discoteca con los nuevos estilos
        var cardHeader = document.createElement('h2');
        cardHeader.classList.add('card-header'); // Agregar clase para el encabezado
        cardHeader.textContent = discoteca.name;

        // Establecer estilos para el encabezado
        cardHeader.style.fontSize = '20px';
        cardHeader.style.padding = '10px';
        cardHeader.style.backgroundColor = 'orange'; // Fondo naranja
        cardHeader.style.color = 'white'; // Texto blanco
        cardHeader.style.textAlign = 'center';
        cardHeader.style.borderBottom = '1px solid #ddd';
        cardHeader.style.cursor = 'pointer'; // Cambiar el cursor al puntero al pasar sobre el encabezado

        // Agregar un evento de clic al encabezado para redirigir a la página de eventos
        cardHeader.addEventListener('click', function() {
            window.location.href = '/cliente/' + discoteca.id + '/eventos';
        });

        // Agregar la imagen y el encabezado a la tarjeta
        cardDiv.appendChild(cardImage);
        cardDiv.appendChild(cardHeader);

        // Agregar la tarjeta al contenedor de discotecas
        discotecasContainer.appendChild(cardDiv);
    });
}


        // Función para cargar las ciudades disponibles
        function cargarCiudades() {
            var xhr = new XMLHttpRequest();

            xhr.onreadystatechange = function() {
                if (xhr.readyState === XMLHttpRequest.DONE) {
                    if (xhr.status === 200) {
                        // Parsear la respuesta JSON
                        var ciudades = JSON.parse(xhr.responseText);

                        // Obtener el select de ciudades
                        var selectCiudad = document.getElementById('selectCiudad');

                        // Limpiar el contenido existente en el select
                        selectCiudad.innerHTML = '';

                        // Agregar la opción de todas las ciudades
                        var optionTodas = document.createElement('option');
                        optionTodas.value = '';
                        optionTodas.textContent = 'Todas las ciudades';
                        selectCiudad.appendChild(optionTodas);

                        // Iterar sobre las ciudades y agregarlas al select
                        ciudades.forEach(function(ciudad) {
                            var optionCiudad = document.createElement('option');
                            optionCiudad.value = ciudad.id;
                            optionCiudad.textContent = ciudad.name;
                            selectCiudad.appendChild(optionCiudad);
                        });
                    } else {
                        console.error('Error al cargar las ciudades:', xhr.status);
                    }
                }
            };

            xhr.open('GET', '/cliente/ciudades', true);
            xhr.send();
        }

        // Llamar a la función cargarPuntosUsuario() cada 5 segundos (5000 milisegundos)
        setInterval(cargarPuntosUsuario, 2000);
        cargarPuntosUsuario();
    </script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        // Obtener el elemento del nombre de usuario en el menú
        var userDropdown = document.querySelector('.nav-link.dropdown-toggle');

        // Obtener el menú desplegable
        var dropdownMenu = document.querySelector('.dropdown-menu');

        // Función para alternar la visibilidad del menú desplegable
        function toggleDropdown() {
            dropdownMenu.classList.toggle('show');
        }

        // Manejar el evento de clic en el nombre de usuario
        userDropdown.addEventListener("click", function(event) {
            // Evitar el comportamiento predeterminado del enlace
            event.preventDefault();
            // Alternar la visibilidad del menú desplegable al hacer clic en el nombre de usuario
            toggleDropdown();
        });

        // Manejar clics fuera del menú desplegable para cerrarlo
        document.addEventListener("click", function(event) {
            if (!dropdownMenu.contains(event.target) && !userDropdown.contains(event.target)) {
                // Si el clic no está dentro del menú desplegable ni en el nombre de usuario, ocultar el menú desplegable
                dropdownMenu.classList.remove('show');
            }
        });
    });
</script>

</body>

</html>