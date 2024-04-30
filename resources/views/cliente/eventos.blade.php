<!DOCTYPE html>
<html>

<head>
    <title>Eventos de {{ $discoteca->name }}</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css"
        integrity="sha512-MV7K8+y+gLIBoVD59lQIYicR65iaqukzvf/nwasF0nqhPay5w/9lJmVM2hMDcnK1OnMGCdVK+iQrJ7lzPJQd1w=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <style>
        body {
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-color: #090921;
            color: white;
            font-family: sans-serif;
        }

        .content-container {
            max-width: 800px;
            width: 100%;
            padding: 20px;
            box-sizing: border-box;
        }

        h2 {
            font-size: 2rem;
            margin-bottom: 1rem;
            text-align: center;
        }

        .reviewSection {
            padding: 1rem;
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            justify-content: space-around;
        }

        .reviewItem {
            width: 300px;
            padding: 10px;
            margin: 1rem;
            cursor: pointer;
            border-radius: 10px;
            background-color: #10102a;
            border: 1px solid #10102a;
            transition: all .2s linear;
            color: white;
        }

        .reviewItem:hover {
            border-color: aqua;
            transform: scale(1.01);
            background-color: rgba(235, 152, 78);
            box-shadow: 0 0px 5px 0px #cbc0c0;
        }

        .top {
            margin-bottom: 1rem;
            display: flex;
            flex-direction: row;
            align-items: center;
            justify-content: space-between;
        }

        .clientImage {
            display: flex;
            flex-direction: row;
            align-items: center;
        }

        .clientImage span {
            margin-left: 10px;
        }

        .clientImage img {
            width: 40px;
        }

        article p {
            font-size: 15px;
            font-weight: 100;
            margin-bottom: 1rem;
            font-family: system-ui;
        }

        #eventosContainer {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
        }

        @media screen and (max-width:700px) {
            .container {
                height: auto;
            }
        }

        @media screen and (max-width:375px) {
            .reviewSection {
                padding: 0;
            }

            .reviewItem {
                width: 100%;
            }

            .clientImage {
                margin-bottom: 0.6rem;
            }

            .top {
                align-items: center;
                flex-direction: column;
                justify-content: center;
            }
        }

        /* Estilos del header */
        header {
            background-color: #090921;
            color: white;
            padding: 20px 0;
        }

        .header-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
        }

        .header-buttons .button,
        .header-buttons #logout-btn {
            background-color: transparent;
            border: none;
            color: white;
            font-size: 1rem;
            cursor: pointer;
            margin-left: 20px;
            transition: color 0.3s ease;
        }

        .header-buttons .button:hover,
        .header-buttons #logout-btn:hover {
            color: aqua;
        }

        /* Estilos para el contenedor de detalles de la discoteca */
        #detallesDiscoteca {
            margin-bottom: 20px;
            padding: 20px;
            background-color: #1a1a1a;
            border-radius: 10px;
        }

        /* Estilos para los filtros */
        label {
            display: block;
            margin-bottom: 5px;
            color: #fff;
        }

        input[type="text"],
        input[type="date"] {
            width: 100%;
            padding: 8px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            background-color: #f4f4f4;
        }

        /* Estilos para el botón de volver */
        a {
            display: inline-block;
            padding: 10px 20px;
            margin-top: 20px;
            background-color: #007bff;
            color: #fff;
            text-decoration: none;
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }

        a:hover {
            background-color: #0056b3;
        }

    </style>
</head>

<body>
    <div class="content-container">
        <header>
            <div class="header-container">
                <div class="header-buttons">
                    <!-- Botón de cerrar sesión -->
                    <form class="logout-form" action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" id="logout-btn">Cerrar Sesión</button>
                    </form>
                </div>
            </div>
        </header>
        <!-- Detalles de la discoteca -->
        <h2>Detalles de la Discoteca</h2>
        <div id="detallesDiscoteca">
            <!-- Aquí se cargarán los eventos -->
        </div>

        <!-- Filtro por nombre de evento -->
        <label for="nombre">Filtrar por nombre del evento:</label>
        <input type="text" id="nombre" placeholder="Nombre del evento">

        <!-- Filtro por día de inicio -->
        <label for="diaInicio">Filtrar por día de inicio:</label>
        <input type="date" id="diaInicio">

        <br>
        <br>

        <h1>Eventos de {{ $discoteca->name }}</h1>

        <div id="eventosContainer">
            <!-- Aquí se cargarán los eventos -->
        </div>

        <!-- Botón de volver -->
        <br>
        <br>
        <a href="{{ route('cliente.discoteca') }}">Volver a la lista de discotecas</a>


        <div class="reviewSection">

        </div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            cargarDetallesDiscoteca();
            cargarEventos();
        });

        // Escuchar el evento de entrada en el campo de filtro por nombre del evento
        document.getElementById("nombre").addEventListener("keyup", function() {
            cargarEventos();
        });

        // Escuchar el evento de cambio en el campo de filtro por día de inicio
        document.getElementById("diaInicio").addEventListener("change", function() {
            cargarEventos();
        });

        function cargarDetallesDiscoteca() {
            var xhr = new XMLHttpRequest();

            xhr.onreadystatechange = function() {
                if (xhr.readyState === XMLHttpRequest.DONE) {
                    if (xhr.status === 200) {
                        mostrarDetallesDiscoteca(JSON.parse(xhr.responseText));
                    } else {
                        console.error('Error al cargar los detalles de la discoteca:', xhr.status);
                    }
                }
            };

            xhr.open('GET', '/cliente/detallesdiscoteca/{{ $discoteca->id }}', true);
            xhr.send();
        }

        function mostrarDetallesDiscoteca(detalles) {
            var detallesDiscoteca = document.getElementById('detallesDiscoteca');
            detallesDiscoteca.innerHTML = '';

            detallesDiscoteca.innerHTML += '<p>Nombre: ' + detalles.name + '</p>';
            detallesDiscoteca.innerHTML += '<p>Dirección: ' + detalles.direccion + '</p>';
            detallesDiscoteca.innerHTML += '<p>Capacidad: ' + detalles.capacidad + '</p>';
            // Agrega más detalles según sea necesario
        }

        function cargarEventos() {
            var nombreEvento = document.getElementById("nombre").value;
            var diaInicio = document.getElementById("diaInicio").value;

            // Obtener la ID de la discoteca
            var idDiscoteca = '{{ $discoteca->id }}';

            var xhr = new XMLHttpRequest();

            xhr.onreadystatechange = function() {
                if (xhr.readyState === XMLHttpRequest.DONE) {
                    if (xhr.status === 200) {
                        mostrarEventos(JSON.parse(xhr.responseText));
                    } else {
                        console.error('Error al cargar los eventos:', xhr.status);
                    }
                }
            };

            var url = '/cliente/mostrareventos/' + idDiscoteca; // Agregar la ID de la discoteca a la URL

            // Agregar el parámetro de filtro por nombre del evento si se proporciona
            if (nombreEvento.trim() !== '') {
                url += '?nombre=' + encodeURIComponent(nombreEvento.trim());
            }

            // Agregar el parámetro de filtro por día de inicio si se proporciona
            if (diaInicio.trim() !== '') {
                url += (url.includes('?') ? '&' : '?') + 'diaInicio=' + encodeURIComponent(diaInicio.trim());
            }

            xhr.open('GET', url, true);
            xhr.send();
        }


        function mostrarEventos(eventos) {
            var eventosContainer = document.getElementById('eventosContainer');
            eventosContainer.innerHTML = '';

            if (eventos.length > 0) {
                eventos.forEach(function(evento) {
                    // Crear un contenedor para la card del evento
                    var eventoCard = document.createElement('div');
                    eventoCard.classList.add('reviewItem'); // Añadir la clase de la card
                    eventoCard.style.width = '300px'; // Establecer el ancho de la card

                    // Crear el contenido de la card
                    var contenido = `
                <div class="top">
                    <div class="clientImage">
                        <button onclick="mostrarEntradas(${evento.id})">Ver Entradas</button>
                        <img src="./client.png" alt="">
                        <span>${evento.name}</span>
                    </div>
                </div>
                <article>
                    <p class="review">${evento.descripcion}</p>
                    <p>${evento.fecha_inicio}</p>
                </article>
            `;

                    // Agregar el contenido a la card
                    eventoCard.innerHTML = contenido;

                    // Agregar la card al contenedor de eventos
                    eventosContainer.appendChild(eventoCard);
                });
            } else {
                eventosContainer.innerHTML = '<p>No hay eventos disponibles para esta discoteca.</p>';
            }
        }

        function mostrarEntradas(eventoId) {
            window.location.href = `/cliente/entradas/${eventoId}`;
        }
    </script>

</body>

</html>
