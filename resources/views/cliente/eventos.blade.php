<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Oasis Management - Eventos</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet.awesome-markers/2.0.6/leaflet.awesome-markers.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/eventos.css') }}">
    <link rel="shortcut icon" href="{{ asset('img/logo.png') }}" type="image/x-icon">
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-white">
        <div class="container">
            <a class="navbar-brand" href="{{ route('cliente.discoteca') }}">
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
    <!-- Contenedor principal -->
    <div class="content-container">
        <!-- Detalles de la discoteca -->
        <h2>Detalles de la Discoteca</h2>
        <div id="detallesDiscoteca">
            <!-- Aquí se cargarán los detalles de la discoteca -->
        </div>

        <!-- Contenedor de búsqueda -->
        <div class="contenedor-input">
            <i class="fas fa-search lupa-naranja"></i>
            <input type="text" id="nombre" placeholder="Nombre del evento">
            <input type="date" id="diaInicio">
        </div>

        <!-- Título de la sección de eventos -->
        <h1>Eventos de {{ $discotecas->name }}</h1>

        <!-- Contenedor para los eventos -->
        <div id="eventosContainer" class="eventos-grid">
            <!-- Aquí se cargarán los eventos -->
        </div>
    </div>
    <br>

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
                            <img src="{{ asset('img/discotecas/' . $discotecas->image) }}" alt="{{ $discotecas->name }}" class="img-fluid">
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </footer>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/2.9.3/umd/popper.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/js/bootstrap.min.js"></script>


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

            xhr.open('GET', '/cliente/detallesdiscoteca/{{ $discotecas->id }}', true);
            xhr.send();
        }

        function mostrarDetallesDiscoteca(detalles) {
    var detallesDiscoteca = document.getElementById('detallesDiscoteca');
    detallesDiscoteca.innerHTML = '';

    // Contenedor principal
    var container = document.createElement('div');
    container.classList.add('detalles-container');

    // Contenedor de la imagen
    var imagenContainer = document.createElement('div');
    imagenContainer.classList.add('imagen-container');

    // Crear un elemento de imagen
    var imagen = document.createElement('img');
    imagen.src = '{{ asset("img/discotecas/") }}' + '/' + detalles.image;
    imagen.alt = detalles.name; // Establecer el texto alternativo de la imagen
    imagen.style.width = '200px'; // Establecer un ancho fijo para la imagen (opcional)

    // Agregar la imagen al contenedor de la imagen
    imagenContainer.appendChild(imagen);

    // Agregar el contenedor de la imagen al contenedor principal
    container.appendChild(imagenContainer);

    // Contenedor de la información
    var infoContainer = document.createElement('div');
    infoContainer.classList.add('info-container');

    // Agregar detalles de la discoteca con iconos
    infoContainer.innerHTML += '<p><i class="fas fa-user"></i>  ' + detalles.name + '</p>';
    infoContainer.innerHTML += '<p><i class="fas fa-map-marker-alt"></i>  ' + detalles.direccion + '</p>';

    if (detalles.ciudad) {
        // Agregar el nombre de la ciudad si está disponible en los detalles
        infoContainer.innerHTML += '<p><i class="fas fa-city"></i>  ' + detalles.ciudad.name + '</p>';
    } else {
        infoContainer.innerHTML += '<p><i class="fas fa-city"></i>  Desconocida</p>';
    }

    infoContainer.innerHTML += '<p><i class="fas fa-users"></i>  ' + detalles.capacidad + '</p>';

    // Agregar el contenedor de la información al contenedor principal
    container.appendChild(infoContainer);

    // Agregar el contenedor principal al elemento detallesDiscoteca
    detallesDiscoteca.appendChild(container);
}


        function cargarEventos() {
            var nombreEvento = document.getElementById("nombre").value;
            var diaInicio = document.getElementById("diaInicio").value;

            // Obtener la ID de la discoteca
            var idDiscoteca = '{{ $discotecas->id }}';

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


        function mostrarEventos(data) {
    console.log(data);
    var registroEntradas = <?php echo json_encode($registroEntradas); ?> ?? []; // Valor predeterminado de []
    var registroEntradasVIP = <?php echo json_encode($registroEntradasVIP); ?> ?? []; // Valor predeterminado de []
    var registro = 0; // Valor predeterminado de 0
    var registroVIP = 0; // Valor predeterminado de 0

    // Iterar sobre los registros de entradas y actualizar los valores de registro y registroVIP
    registroEntradas.forEach(function(entrada) {
        console.log(entrada);
        registro += entrada.total_entradas ?? 0; // Sumar total_entradas o 0 si es undefined
    });

    registroEntradasVIP.forEach(function(entradaVIP) {
        console.log(entradaVIP);
        registroVIP += entradaVIP.total_entradas ?? 0; // Sumar total_entradas o 0 si es undefined
        console.log(registroVIP);
    });

    var discoteca = data.discotecas;
    var eventos = data;
    
    var eventosContainer = document.getElementById('eventosContainer');
    eventosContainer.innerHTML = '';

    if (eventos.length > 0) {
        eventos.forEach(function(evento) {
            // Calcular las entradas disponibles
            var totalEntradasCompradas = registro;
            var totalEntradasCompradasVIP = registroVIP;
            var entradasDisponibles = evento.capacidad - totalEntradasCompradas;
            var entradasDisponiblesVIP = evento.capacidadVip - totalEntradasCompradasVIP;
            
            // Crear el contenido de la card
            var contenido;

            if ((entradasDisponibles > 0 && evento.capacidad !== null) || (entradasDisponiblesVIP > 0 && evento.capacidadVip !== null)) {
                contenido = `
                <div class="event-container">
                    <!-- Tarjeta de evento -->
                    <div class="event-card">
                        <div class="top">
                            <div class="clientDetails">
                                <div class="clientImage">
                                    <img src="{{ asset('img/flyer/${evento.flyer}') }}" alt="Flyer">
                                </div>
                                <div class="clientInfo">
                                    <h3 class="eventName">${evento.name}</h3>
                                    
                                </div>
                            </div>
                        </div>
                        <article>
                            <p class="review">${evento.descripcion}</p>
                            <p class="eventDate">${evento.fecha_inicio}</p>
                        </article>
                        <button class="button-entradas" onclick="mostrarEntradas(${evento.id})">
                                        <img src="{{ asset('img/entradas.png') }}" alt="Entradas" width="40px" height="40px">
                                        <span class="button-text">Ver Entradas</span>
                                    </button>
                    </div>
                </div>
                `;
            } else {
                contenido = `
                <div class="event-container">
                    <!-- Tarjeta de evento -->
                    <div class="event-card">
                        <div class="top">
                            <div class="clientDetails">
                                <div class="clientImage">
                                    <img src="{{ asset('img/flyer/${evento.flyer}') }}" alt="Flyer">
                                </div>
                                <div class="clientInfo">
                                    <h3 class="eventName">${evento.name}</h3>
                                    
                                </div>
                            </div>
                        </div>
                        <article>
                            <p class="review">${evento.descripcion}</p>
                            <p class="eventDate">${evento.fecha_inicio}</p>
                        </article>
                        <button class="button-entradas">
                                        
                                        <span class="button-text">Sold Out</span>
                                    </button>
                    </div>
                </div>
                `;
            }

            // Agregar el contenido a la card
            var eventoCard = document.createElement('div');
            eventoCard.classList.add('reviewItem');
            eventoCard.style.width = '300px';
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