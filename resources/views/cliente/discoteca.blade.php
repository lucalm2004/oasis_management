<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Discotecas</title>
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }

        #container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }

        h1 {
            font-size: 24px;
            color: #333;
            margin-bottom: 10px;
        }

        p {
            font-size: 16px;
            color: #666;
            margin-bottom: 20px;
        }

        button {
            background-color: #007bff;
            color: #fff;
            border: none;
            padding: 10px 20px;
            font-size: 16px;
            cursor: pointer;
        }

        button:hover {
            background-color: #0056b3;
        }

        form {
            margin-top: 20px;
        }

        #logout-btn {
            background-color: #dc3545;
        }

        #logout-btn:hover {
            background-color: #bd2130;
        }

        #puntosUsuario {
            font-weight: bold;
            color: blue;
            text-decoration: none;
        }

        #puntosUsuario:hover {
            text-decoration: underline;
        }

        input[type="text"] {
            padding: 10px;
            font-size: 16px;
            border: 1px solid #ccc;
            border-radius: 5px;
            margin-bottom: 10px;
        }

        select {
            padding: 10px;
            font-size: 16px;
            border: 1px solid #ccc;
            border-radius: 5px;
            margin-bottom: 10px;
        }

        #discotecasContainer {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 20px;
        }

        .discoteca {
            background-color: #fff;
            border: 1px solid #ccc;
            padding: 10px;
            border-radius: 5px;
        }

        #popup {
            display: none;
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background-color: white;
            padding: 20px;
            border: 1px solid black;
            z-index: 999;
        }

        #mapid {
            height: 400px;
        }
    </style>
</head>
<body>

<div id="container">

    <h1>Bienvenido, {{ $nombreUsuario }}</h1>
    <p>¡Esperamos que disfrutes explorando nuestras discotecas!</p>

    <h1>Lista de Discotecas</h1>

    <!-- Botón para introducir código -->
    <button onclick="mostrarPopup()">Chat</button>

    <!-- Botón de cerrar sesión -->
    <form id="logout-form" action="{{ route('logout') }}" method="POST">
        @csrf
        <button type="submit" id="logout-btn">Cerrar Sesión</button>
    </form>

    <!-- Marcador de puntos del usuario -->
    <p>Tienes <a href="#" id="puntosLink"><span id="puntosUsuario"></span> puntos.</a></p>

    <br>
    <br>

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

    <br>
    <br>

    <!-- Popup -->
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

    <div id="mapid"></div>

</div>

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
                    var puntosUsuario = JSON.parse(xhr.responseText);
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

        // Función para mostrar las discotecas en el contenedor
        function mostrarDiscotecas(discotecas) {
            console.log("Discotecas sin filtrar:", discotecas);

            var discotecasContainer = document.getElementById('discotecasContainer');
            discotecasContainer.innerHTML = '';

            discotecas.forEach(function(discoteca) {
                var discotecaLink = document.createElement('a');
                discotecaLink.href = '/cliente/' + discoteca.id + '/eventos';
                discotecaLink.textContent = discoteca.name;

                var discotecaElement = document.createElement('div');
                discotecaElement.appendChild(discotecaLink);

                discotecasContainer.appendChild(discotecaElement);
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
