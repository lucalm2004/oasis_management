<!DOCTYPE html>
<html>

<head>
    <title>Eventos de {{ $discoteca->name }}</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css"
        integrity="sha512-MV7K8+y+gLIBoVD59lQIYicR65iaqukzvf/nwasF0nqhPay5w/9lJmVM2hMDcnK1OnMGCdVK+iQrJ7lzPJQd1w=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <style>
        body {
            font-family: Arial, Helvetica, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #10101A;
            color: white;
        }

        /* Estilos para el botón "Ver Entradas" */


        .content-container {
            width: 100%;
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
            display: flex;
            flex-direction: column;
            align-items: center;
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
            background-color: #161624;
            border: 1px solid #161624;
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
            background-color: #666;
            padding-bottom: 20px;
            padding-top: 20px;
            color: white;
        }

        .header-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .header-buttons a,
        .header-buttons button {
            background-color: transparent;
            border: none;
            color: white;
            font-size: 1rem;
            cursor: pointer;
            text-decoration: none;
            transition: color 0.3s ease;
        }

        /* Estilos para el icono de flecha */
        .header-buttons a i {
            margin-right: 5px;
        }

        /* Estilos para el icono de cerrar sesión */
        .header-buttons button i {
            margin-right: 5px;
        }

        /* Alineación del texto en el header */
        h1 {
            margin: 0;
            /* Eliminar el margen por defecto del h1 */
        }

        /* Estilos para el contenedor de detalles de la discoteca */
        #detallesDiscoteca {
            margin-bottom: 20px;
            padding: 20px;
            background-color: #161624;
            border-radius: 10px;
            color: white;
        }

        /* Estilos para los filtros */
        label {
            display: block;
            margin-bottom: 5px;
            color: #fff;
        }

        input[type="text"],
        input[type="date"] {
            display: inline-block;
            /* Cambio a display inline-block */
            width: auto;
            /* Eliminamos el width 100% */
            padding: 8px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            background-color: #ffffff;
            float: left;
            margin-left: 15px;
            /* Alineamos los inputs horizontalmente */
        }

        input[type="text"] {
            margin-right: 10px;
            /* Agregamos margen derecho para separar los inputs */
        }

        /* Estilos para el botón de volver */
        a {
            display: inline-block;
            padding: 10px 15px;
            margin-top: 20px;
            /* background-color: #007bff; */
            color: #fff;
            text-decoration: none;
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }

        /* Style the footer */
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

        #logout-btn {
            padding: 10px 20px;
            background-color: #007bff;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            margin-right: 20px;
        }

        h1,
        p {
            text-align: center;
            margin-bottom: 20px;
        }

        .contenedor-input {
            display: flex;
            align-items: center;
        }
        .lupa-naranja {
            color: #F5763B;
            margin-right: 15px;
            /* Espacio entre el icono y el input */
        }
    </style>
</head>

<body>

    <header>
        <div class="header-container">
            <div class="header-buttons">
                <a href="{{ route('cliente.discoteca') }}"><i class="fas fa-arrow-left"></i> Volver a la lista de
                    discotecas</a>
            </div>
            <h1>Eventos de {{ $discoteca->name }}</h1>
            <div class="header-buttons">
                <!-- Botón de cerrar sesión -->
                <form class="logout-form" action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" id="logout-btn"><i class="fas fa-sign-out-alt"></i> Cerrar Sesión</button>
                </form>
            </div>
        </div>
    </header>
    <div class="content-container">
        <!-- Detalles de la discoteca -->
        <h2>Detalles de la Discoteca</h2>
        <br>
        <div id="detallesDiscoteca">
            <!-- Aquí se cargarán los eventos -->
        </div>

        <br>
        <br>


        <div class="contenedor-input">
            <i class="fas fa-search lupa-naranja"></i>
            <input type="text" id="nombre" placeholder="Nombre del evento">
            <input type="date" id="diaInicio">
        </div>

        <br>
        <br>

        <h1>Eventos de {{ $discoteca->name }}</h1>

        <div id="eventosContainer">
            <!-- Aquí se cargarán los eventos -->
        </div>

        <!-- Botón de volver -->
        <br>
        <br>



        <div class="reviewSection">

        </div>


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
                        <img src="{{ asset('img/(${evento.flyer})') }}" alt="">
                        <span>${evento.name}</span>
                    </div>
                    <button onclick="mostrarEntradas(${evento.id})"><img src="{{ asset('img/entradas.png') }}" alt="Entradas" width="40px" height="40px"></button>
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