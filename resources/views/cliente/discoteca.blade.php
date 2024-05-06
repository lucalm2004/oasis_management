

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="{{ asset('img/logo.png') }}" type="image/x-icon">

    <title>Lista de Discotecas</title>
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css"
        integrity="sha512-MV7K8+y+gLIBoVD59lQIYicR65iaqukzvf/nwasF0nqhPay5w/9lJmVM2hMDcnK1OnMGCdVK+iQrJ7lzPJQd1w=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            font-family: Arial, Helvetica, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f8f8f8;
        }

        /* Nuevo estilo para el header */
        header {
            background-color: #666;
            padding: 20px;
            color: white;
        }

        /* Estilo para el contenedor del header */
        .header-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        /* Estilo para el mensaje de bienvenida */
        .welcome-message {
            flex: 1;
            text-align: center;
            margin-left: 260px;
            /* Para centrar el texto horizontalmente */
        }

        /* Estilo para los botones del header */
        .header-buttons {
            display: flex;
            align-items: center;
        }

        /* Estilo para el botón de cerrar sesión */
        .header-buttons form {
            margin-left: 20px;
        }

        /* Ajustes para el botón de cerrar sesión */
        #logout-btn {
            background-color: #007bff;
            border: none;
            color: white;
            padding: 10px 20px;
            text-align: center;
            text-decoration: none;
            display: inline-block;
            font-size: 16px;
            margin: 4px 2px;
            transition-duration: 0.4s;
            cursor: pointer;
            border-radius: 8px;
        }

        #logout-btn:hover {
            color: #ddd;
        }

        /* Create two columns/boxes that floats next to each other */
        #container {
            width: 100%;
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        /* Style the footer */
        /* footer {
            background-color: #777;
            padding: 10px;
            text-align: center;
            color: white;
            width: 100%;
        } */

        /* Style the rest of the content */
        h1,
        p {
            text-align: center;
            margin-bottom: 20px;
        }

        input[type="text"],
        select {
            width: 100%;
            padding: 10px;
            margin-bottom: 10px;
        }

        button[type="submit"] {
            width: 100%;
            padding: 10px;
            margin-top: 10px;
            background-color: #ffffff;
            color: white;
            border: none;
            cursor: pointer;
        }

        /* Estilos para el popup */
        #popup {
            display: none;
            /* Inicialmente oculto */
            position: fixed;
            top: 50%;
            /* Centrado verticalmente */
            left: 50%;
            /* Centrado horizontalmente */
            transform: translate(-50%, -50%);
            /* Centrado absoluto */
            background-color: white;
            padding: 20px;
            border: 1px solid black;
            border-radius: 5px;
            z-index: 1000;
            /* Valor alto para que esté por encima de otros elementos */
        }

        #mapid {
            width: 100%;
            height: 400px;
            /* Altura deseada para el mapa */
        }

        /* Estilos para las tarjetas */
        .card {
            width: 300px;
            padding: 10px;
            margin: 0.25rem;
            /* Ajusta el margen para reducir la separación */
            cursor: pointer;
            border-radius: 10px;
            background-color: #10102a;
            border: 1px solid #10102a;
            transition: all .2s linear;
            color: white;
            /* Establecer el color del texto en blanco */
        }

        .card:hover {
            border-color: aqua;
            transform: scale(1.01);
            background-color: rgba(235, 152, 78);
            box-shadow: 0 0px 5px 0px #cbc0c0;
        }

        .card h2 {
            color: #fff;
            font-size: 20px;
            margin: 0;
            padding: 10px;
        }

        .card p {
            padding: 15px;
            margin: 0;
            color: #333;
        }

        .card a {
            display: block;
            text-align: center;
            color: #fff;
            text-decoration: none;
            padding: 10px;
        }

        /* Estilos para el contenedor de las tarjetas */
        #discotecasContainer {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            /* Alinear los items al centro */
            gap: 20px;
            /* Espacio entre las tarjetas */
        }

        h1 {
            font-size: 24px;
            margin-bottom: 10px;
        }

        .button {
            background-color: #4CAF50;
            border: none;
            color: white;
            padding: 10px 20px;
            text-align: center;
            text-decoration: none;
            display: inline-block;
            font-size: 16px;
            margin: 4px 2px;
            transition-duration: 0.4s;
            cursor: pointer;
            border-radius: 8px;
        }

        .button:hover {
            background-color: #45a049;
            color: white;
        }

        .points {
            text-align: center;
            margin-top: 20px;
        }

        .points a {
            color: #007bff;
            text-decoration: none;
        }

        .points a:hover {
            text-decoration: underline;
        }

        #filtroNombre {
            width: 100%;
            padding: 10px;
            margin-top: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 16px;
            box-sizing: border-box;
        }

        #selectCiudad {
            width: 100%;
            padding: 10px;
            margin-top: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 16px;
            box-sizing: border-box;
        }

        /* Estilos para el botón de enviar */
        button[type="submit"] {
            background-color: #4CAF50;
            /* Color de fondo */
            color: white;
            /* Color del texto */
            padding: 10px 20px;
            /* Espaciado interno */
            border: none;
            /* Sin borde */
            border-radius: 5px;
            /* Borde redondeado */
            cursor: pointer;
            /* Cambio de cursor al pasar el ratón */
            font-size: 16px;
            /* Tamaño de la fuente */
        }

        /* Estilos para el botón de cerrar */
        button[type="button"] {
            background-color: #f44336;
            /* Color de fondo */
            color: white;
            /* Color del texto */
            padding: 10px 20px;
            /* Espaciado interno */
            border: none;
            /* Sin borde */
            border-radius: 5px;
            /* Borde redondeado */
            cursor: pointer;
            /* Cambio de cursor al pasar el ratón */
            font-size: 16px;
            /* Tamaño de la fuente */
            margin-top: 10px;
            /* Espacio superior */
        }

        .footer {
            background-image: url('/img/oasisn2.jpg');
            background-size: cover;
            background-position: center;
            padding: 60px 0;
            box-shadow: 0 -2px 4px rgba(0, 0, 0, 0.1);
        }

        .footer a {
            color: #ffffff;
        }

        .footer a:hover {
            color: #ffd54f;
        }
    </style>
</head>

<body>

    <header>
        <div class="header-container">
            <div class="welcome-message">
                <h1>Bienvenido, {{ $nombreUsuario }}</h1>
                <p>¡Esperamos que disfrutes explorando nuestras discotecas!</p>
            </div>
            <div class="header-buttons">
                <!-- Botón para introducir código -->
                <button class="button" onclick="mostrarPopup()">Chat</button>
                <!-- Botón de cerrar sesión -->
                <form class="logout-form" action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" id="logout-btn">Cerrar Sesión</button>
                </form>
            </div>
        </div>
    </header>

    <div id="container">

        <!-- Marcador de puntos del usuario -->
        <p class="points">Tienes <a href="#" id="puntosLink"><span id="puntosUsuario"></span> puntos.</a></p>

        <input type="text" id="filtroNombre" placeholder="Filtrar por nombre...">

        <select id="selectCiudad">
            <option value="">Todas las ciudades</option>
            <!-- Las opciones de ciudades se cargarán dinámicamente aquí -->
        </select>

        <br>
        <br>

        <!-- Contenedor para mostrar las discotecas -->
        <div id="discotecasContainer">
            <!-- Aquí se mostrarán las discotecas -->
        </div>

        <!-- Popup -->
        {{-- <dialog id='alert-dialog'> --}}
        <div id="popup">
            <h2>Introducir Código</h2>
            <form action="" method="POST">
                @csrf
                <label for="codigo">Código:</label><br>
                <input type="text" id="codigo" name="codigo"><br><br>
                <button type="submit">Enviar</button>
            </form>
            <button onclick="cerrarPopup()">Cerrar</button>
        </div>
        {{-- </dialog> --}}

        <br>
        <br>

        <div id="mapid"></div>

    </div>

    <footer class="footer mt-auto py-5 bg-dark" id="contact" style="background-image: url('/img/oasisn2.jpg');">
        {{-- <div class="container text-center">
            <h2 class="text-white mb-4 animateanimated animatefadeInUp">¿Listo para llevar tu negocio al siguiente
                nivel?</h2>
            <p class="text-white mb-4 animateanimated animatefadeInUp">Contáctanos para conocer cómo podemos colaborar
                juntos.</p>
            <div class="mt-4">
                <a href="mailto:oasis.management.daw@gmail.com"
                    class="btn btn-outline-light btn-lg animateanimated animatefadeInUp">
                    <i class="fas fa-envelope"></i> ¡Contáctanos ahora!
                </a>
            </div>
            <div class="mt-4">
                <a href="https://www.tiktok.com/@oasis_management2024?lang=es"
                    class="text-white mr-3 animateanimated animatefadeInUp">
                    <i class="fab fa-tiktok"></i> TikTok
                </a>
                <a href="https://www.instagram.com/oasis_management2024/"
                    class="text-white mr-3 animateanimated animatefadeInUp">
                    <i class="fab fa-instagram"></i> Instagram
                </a>
            </div>
            <!-- Logos de Discotecas -->
            <div id="slider" class="slider mt-5">
                <div class="slide-track d-flex justify-content-center align-items-center">
                    @foreach ($discotecas as $discoteca)
                        <div class="slide mr-3">
                            <img src="{{ asset('img/' . $discoteca->image) }}" alt="{{ $discoteca->name }}"
                                class="img-fluid">
                        </div>
                    @endforeach
                </div>
            </div>
        </div> --}}
        <p>Footer</p>
    </footer>

    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>

    <script>
        var map = L.map('mapid').setView([40.505, -100.09], 4); // Define la posición inicial del mapa y el nivel de zoom

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
        }).addTo(map); // Añade una capa de mapa base

        // Itera sobre las discotecas y agrega marcadores al mapa
        @foreach ($todasdiscotecas as $discoteca)
            L.marker([{{ $discoteca->lat }}, {{ $discoteca->long }}]).addTo(map)
                .bindPopup('{{ $discoteca->name }}');
        @endforeach
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
                    console.log(xhr.responseText);
                    // Verificar si la respuesta es un objeto vacío
                    if (xhr.responseText === '{}' || xhr.responseText === 'null') {
                        document.getElementById("puntosUsuario").textContent = "0";
                    } else {
                        var puntosUsuario = JSON.parse(xhr.responseText);
                        // Actualizar el contenido del elemento HTML con el número de puntos
                        if (puntosUsuario < 0) {
                            document.getElementById("puntosUsuario").textContent = "0";
                        } else {
                            document.getElementById("puntosUsuario").textContent = puntosUsuario;
                        }
                    }
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

        // Función para mostrar las discotecas en tarjetas con los nuevos estilos
        function mostrarDiscotecas(discotecas) {
            var discotecasContainer = document.getElementById('discotecasContainer');
            discotecasContainer.innerHTML = ''; // Limpiar el contenido anterior

            discotecas.forEach(function(discoteca) {
                // Crear un div para la tarjeta
                var cardDiv = document.createElement('div');
                cardDiv.classList.add('card');

                // Crear un encabezado para el nombre de la discoteca con los nuevos estilos
                var cardHeader = document.createElement('h2');
                cardHeader.classList.add('card-header'); // Agregar clase para el encabezado
                cardHeader.textContent = discoteca.name;

                // Crear un párrafo para la información adicional con los nuevos estilos
                var cardInfo = document.createElement('p');
                cardInfo.classList.add('card-info'); // Agregar clase para la información
                cardInfo.textContent = 'Dirección: ' + discoteca.direccion;

                // Crear un enlace para más detalles con los nuevos estilos
                var cardLink = document.createElement('a');
                cardLink.classList.add('card-link'); // Agregar clase para el enlace
                cardLink.href = '/cliente/' + discoteca.id + '/eventos';
                cardLink.innerHTML = 'Ver eventos <i class="fas fa-glass-cheers"></i>';

                // Agregar el encabezado, información y enlace a la tarjeta
                cardDiv.appendChild(cardHeader);
                cardDiv.appendChild(cardInfo);
                cardDiv.appendChild(cardLink);

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

</body>

</html>
