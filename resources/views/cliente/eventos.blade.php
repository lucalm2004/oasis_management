<!DOCTYPE html>
<html>

<head>
    <title>Eventos de {{ $discoteca->name }}</title>
    <link rel="stylesheet" href="{{ asset('css/eventos.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css"
        integrity="sha512-MV7K8+y+gLIBoVD59lQIYicR65iaqukzvf/nwasF0nqhPay5w/9lJmVM2hMDcnK1OnMGCdVK+iQrJ7lzPJQd1w=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />

</head>

<body>
    <header>
        <div class="header-container">
            <div class="header-buttons">
                <a href="{{ route('cliente.discoteca') }}"><i class="fas fa-arrow-left"></i> Volver a la lista de discotecas</a>
            </div>
            <h1>Eventos de {{ $discoteca->name }}</h1>
            <div class="header-buttons">
                <form class="logout-form" action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit"><i class="fas fa-sign-out-alt"></i> Cerrar Sesión</button>
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