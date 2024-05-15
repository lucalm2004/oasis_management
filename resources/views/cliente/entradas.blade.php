<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Entradas Disponibles</title>
    <!-- Estilos y Librerías -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/leaflet.awesome-markers/2.0.6/leaflet.awesome-markers.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/entradas.css') }}">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body>

    <!-- Barra de Navegación -->
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
                    <!-- Elementos del Navbar -->
                    <!-- Botón de Perfil -->
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
                    <!-- Enlace de Contacto -->
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('contacto') }}">
                            <i class="fas fa-envelope"></i> Contacto
                        </a>
                    </li>
                    <!-- Elemento del Carrito -->
                    <li class="nav-item">
                        <a id="cart-icon" class="nav-link" href="#">
                            <i class="fas fa-shopping-cart"></i>
                        </a>
                    </li>
                    <!-- Otros Elementos -->
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


    <!-- Contenido Principal -->
    <div class="content-container">

        <div id="carritoContainer" style="position: fixed; top: 10%; right: -100px; margin-right: 1.3%;" class="hidden">
            <!-- Aquí se mostrarán los productos en el carrito -->
        </div>

        <!-- Detalles del Evento -->
        <h2>Detalles del Evento</h2>
        <div id="detallesEvento">
            <!-- Aquí se cargarán los detalles del evento -->
        </div>

        <!-- Tipos de Entrada Disponibles -->
        <div class="section">
            <h2 class="section-title">Tipos de Entrada Disponibles</h2>
            <ul id="tiposEntradaContainer" class="entry-types-list">
                <!-- Aquí se cargarán los tipos de entrada -->
            </ul>

            <!-- Botón para enviar las entradas seleccionadas -->
            <button onclick="enviarEntradasACarrito()" class="add-to-cart-button">
                <i class="fas fa-shopping-cart"></i> Añadir al Carrito
            </button>
        </div>

        <!-- Scripts -->
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/2.9.3/umd/popper.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

        <script>
            document.addEventListener("DOMContentLoaded", function() {
                cargarDetallesEvento();
                cargarTiposEntrada();
                cargarCarrito();
            });

            function toggleCart() {
                var cart = document.getElementById('carritoContainer');
                var cartIcon = document.querySelector('.cart-icon');


                // Toggle para mostrar u ocultar el carrito
                if (cart.classList.contains('hidden')) {
                    cart.classList.remove('hidden');
                    cart.classList.add('show-cart');
                    cart.style.right = '0'; // Mostrar el carrito
                    cartIcon.style.right = '320px'; // Mover el icono del carrito hacia la derecha
                } else {
                    // cart.classList.toggle('show');
                    cart.classList.remove('show-cart');
                    cart.classList.add('hidden');
                    cart.style.right = '-300px'; // Ocultar el carrito
                    cartIcon.style.right = '20px'; // Restaurar la posición del icono del carrito

                }
            }




            // Agregar un controlador de eventos al botón del carrito
            var cartButton = document.getElementById('cart-icon');
            cartButton.addEventListener('click', function() {
                toggleCart(); // Mostrar u ocultar el carrito al hacer clic en el botón
            });

            function cargarDetallesEvento() {
                var xhr = new XMLHttpRequest();

                xhr.onreadystatechange = function() {
                    if (xhr.readyState === XMLHttpRequest.DONE) {
                        if (xhr.status === 200) {
                            mostrarDetallesEvento(JSON.parse(xhr.responseText));
                        } else {
                            console.error('Error al cargar los detalles del evento:', xhr.status);
                        }
                    }
                };

                xhr.open('GET', '/cliente/detallesevento/{{ $evento->id }}', true);
                xhr.send();
            }

            function mostrarDetallesEvento(detalles) {
                var detallesEvento = document.getElementById('detallesEvento');
                detallesEvento.innerHTML = '';

                detallesEvento.innerHTML += '<p class="animated fadeIn"><i class="fas fa-user"></i> Nombre: ' + detalles.name +
                    '</p>';
                detallesEvento.innerHTML += '<p class="animated fadeIn"><i class="fas fa-info-circle"></i> Descripción: ' +
                    detalles.descripcion + '</p>';
                detallesEvento.innerHTML += '<p class="animated fadeIn"><i class="far fa-calendar-alt"></i> Fecha de Inicio: ' +
                    detalles.fecha_inicio + '</p>';
                detallesEvento.innerHTML += '<p class="animated fadeIn"><i class="far fa-calendar-alt"></i> Fecha final: ' +
                    detalles.fecha_final + '</p>';
                detallesEvento.innerHTML += '<p class="animated fadeIn"><i class="fas fa-headphones"></i> DJ: ' + detalles.dj +
                    '</p>';
                detallesEvento.innerHTML +=
                    '<p class="animated fadeIn"><i class="fas fa-music"></i> Playlist: <a href="#" style="color: orange;" onclick="mostrarPlaylistAlert(' +
                    detalles.id + ')">' + detalles.name_playlist + '</a></p>';
                // Agrega más detalles según sea necesario
            }



            function mostrarPlaylistAlert(eventoId) {
                // Realizar una solicitud AJAX para obtener las canciones del evento
                var xhr = new XMLHttpRequest();
                xhr.onreadystatechange = function() {
                    if (xhr.readyState === XMLHttpRequest.DONE) {
                        if (xhr.status === 200) {
                            var canciones = JSON.parse(xhr.responseText);
                            mostrarCancionesEnAlerta(canciones);
                        } else {
                            console.error('Error al cargar las canciones:', xhr.status);
                        }
                    }
                };
                xhr.open('GET', '/cliente/mostrarCancionesEvento/' + eventoId, true);
                xhr.send();
            }

            function mostrarCancionesEnAlerta(canciones) {
                var playlistHTML = '';

                if (canciones.length > 0) {
                    // Si hay canciones, construir el HTML de la playlist
                    canciones.forEach(function(cancion) {
                        playlistHTML += '<p class="playlist-item">' + cancion.name + '</p>';
                    });
                } else {
                    // Si no hay canciones, mostrar un mensaje indicando que no hay canciones en la playlist
                    playlistHTML = '<p>No hay canciones en esta playlist.</p>';
                }

                // Sweet Alert personalizado
                Swal.fire({
                    title: 'Playlist',
                    html: playlistHTML,
                    icon: 'info',
                    confirmButtonText: 'Aceptar',
                    customClass: {
                        // Clase para el contenedor del Sweet Alert
                        container: 'personalizado-swal-container',
                        // Clase para el título del Sweet Alert
                        title: 'personalizado-swal-title',
                        // Clase para el contenido HTML del Sweet Alert
                        htmlContainer: 'personalizado-swal-html-container',
                        // Clase para el botón de confirmación del Sweet Alert
                        confirmButton: 'personalizado-swal-confirm-button'
                    },
                    // Estilo personalizado para el botón de confirmación
                    buttonsStyling: false,
                    // Color de fondo personalizado para el botón de confirmación
                    customClass: {
                        confirmButton: 'btn-naranja'
                    }
                });
            }

            // Estilo CSS personalizado para el botón de confirmación
            const style = document.createElement('style');
            style.innerHTML = `
    .btn-naranja {
        background-color: orange !important;
        border-color: orange !important;
    }

    .playlist-item {
        color: white; /* Aplicar color blanco al texto de las canciones */
    }
`;
            document.head.appendChild(style);


            function cargarTiposEntrada() {
                var xhr = new XMLHttpRequest();

                xhr.onreadystatechange = function() {
                    if (xhr.readyState === XMLHttpRequest.DONE) {
                        if (xhr.status === 200) {
                            mostrarTiposEntrada(JSON.parse(xhr.responseText));
                        } else {
                            console.error('Error al cargar los tipos de entrada:', xhr.status);
                        }
                    }
                };

                xhr.open('GET', '/cliente/tiposentrada/{{ $evento->id }}', true);
                xhr.send();
            }

            function mostrarTiposEntrada(tiposEntrada) {
                var tiposEntradaContainer = document.getElementById('tiposEntradaContainer');
                tiposEntradaContainer.innerHTML = '';

                if (tiposEntrada.length > 0) {
                    tiposEntrada.forEach(function(tipoEntrada) {
                        // Crear un contenedor para el tipo de entrada
                        var tipoEntradaContainer = document.createElement('div');
                        tipoEntradaContainer.classList.add('tipo-entrada-container');

                        // Mostrar la descripción y el precio del tipo de entrada
                        var descripcionPrecioElement = document.createElement('div');
                        descripcionPrecioElement.classList.add('descripcion-precio');
                        descripcionPrecioElement.textContent = tipoEntrada.descripcion + ' - ' + tipoEntrada.precio;
                        tipoEntradaContainer.appendChild(descripcionPrecioElement);

                        // Contenedor para los botones de incremento y decremento
                        var botonContainer = document.createElement('div');
                        botonContainer.classList.add('boton-container');

                        // Botón de decremento
                        var decrementButton = document.createElement('button');
                        decrementButton.textContent = '-';
                        decrementButton.classList.add('contador-button');
                        decrementButton.addEventListener('click', function() {
                            var currentValue = parseInt(contadorElement.textContent) || 0;
                            contadorElement.textContent = currentValue > 0 ? currentValue - 1 : 0;
                        });
                        botonContainer.appendChild(decrementButton);

                        // Contador de cantidad
                        var contadorElement = document.createElement('div');
                        contadorElement.classList.add('contador');
                        contadorElement.textContent = '0';
                        botonContainer.appendChild(contadorElement);

                        // Botón de incremento
                        var incrementButton = document.createElement('button');
                        incrementButton.textContent = '+';
                        incrementButton.classList.add('contador-button');
                        incrementButton.addEventListener('click', function() {
                            var currentValue = parseInt(contadorElement.textContent) || 0;
                            contadorElement.textContent = currentValue + 1;
                        });
                        botonContainer.appendChild(incrementButton);

                        tipoEntradaContainer.appendChild(botonContainer);

                        // Botón "Agregar al Carrito"
                        var addToCartButton = document.createElement('button');
                        addToCartButton.textContent = 'Agregar al Carrito';
                        addToCartButton.classList.add('add-to-cart-button');
                        addToCartButton.addEventListener('click', function() {
                            var cantidad = parseInt(contadorElement.textContent) || 0;
                            if (cantidad > 0) {
                                alert('Se agregaron ' + cantidad + ' entradas al carrito.');
                            } else {
                                alert('Por favor selecciona al menos una entrada.');
                            }
                        });
                        tipoEntradaContainer.appendChild(addToCartButton);

                        // Agregar el contenedor del tipo de entrada al contenedor principal
                        tiposEntradaContainer.appendChild(tipoEntradaContainer);
                    });
                } else {
                    tiposEntradaContainer.innerHTML = '<p>No hay tipos de entrada disponibles para este evento.</p>';
                }
            }



            function enviarEntradasACarrito() {
                // Obtener el ID del evento
                var idEvento = "{{ $evento->id }}";
                var contador1 = document.getElementById('contadorTipo1');
                var contador2 = document.getElementById('contadorTipo2');
                var contador3 = document.getElementById('contadorTipo3');

                // Obtener todos los contadores de tipo de entrada
                var contadores = document.querySelectorAll('[id^="contadorTipo"]');

                // Crear un formulario dinámicamente
                var form = document.createElement('form');
                form.method = 'POST';
                form.action = '/cliente/insertarEnCarrito'; // Establecer la acción del formulario

                // Agregar un campo oculto para el token CSRF
                var csrfToken = document.createElement('input');
                csrfToken.type = 'hidden';
                csrfToken.name = '_token';
                csrfToken.value = '{{ csrf_token() }}';
                form.appendChild(csrfToken);

                // Agregar el formulario al cuerpo del documento
                document.body.appendChild(form);

                // Agregar un campo oculto para el ID del evento
                var inputIdEvento = document.createElement('input');
                inputIdEvento.type = 'hidden';
                inputIdEvento.name = 'id_evento';
                inputIdEvento.value = idEvento;
                form.appendChild(inputIdEvento);

                // Iterar sobre los contadores y agregar un campo oculto para cada tipo de entrada seleccionada
                contadores.forEach(function(contador) {
                    var tipoEntradaId = contador.id.replace('contadorTipo', '');
                    var cantidadSeleccionada = parseInt(contador.value);
                    if (cantidadSeleccionada > 0) {
                        for (var i = 0; i < cantidadSeleccionada; i++) {
                            var inputTipoEntrada = document.createElement('input');
                            inputTipoEntrada.type = 'hidden';
                            inputTipoEntrada.name =
                                'entradas[]'; // Utiliza un array para enviar múltiples entradas del mismo tipo
                            inputTipoEntrada.value = tipoEntradaId;
                            form.appendChild(inputTipoEntrada);
                        }
                    }
                });

                // Enviar el formulario mediante AJAX
                var xhr = new XMLHttpRequest();

                // Manejar la respuesta de la solicitud AJAX
                // Manejar la respuesta de la solicitud AJAX
                xhr.onreadystatechange = function() {
                    if (xhr.readyState === 4) {
                        if (xhr.status === 200) {
                            // Parsear la respuesta JSON
                            var response = JSON.parse(xhr.responseText);

                            // Verificar si la inserción en el carrito fue exitosa
                            if (response.success === true) {
                                // Función para establecer el valor de los contadores a cero

                                // Mostrar un SweetAlert con el mensaje de éxito
                                Swal.fire({
                                    icon: 'success',
                                    title: '¡Éxito!',
                                    text: 'Entradas insertadas en el carrito con éxito',
                                    confirmButtonText: 'Aceptar'
                                }).then((result) => {
                                    // Redirigir a la vista de carrito si el usuario hace clic en "Aceptar"
                                    if (result.isConfirmed) {
                                        // window.location.href = '/cliente/carrito';
                                        contador1.value = 0;
                                        contador2.value = 0;
                                        contador3.value = 0;

                                        // Llamar a la función cargarCarrito
                                        cargarCarrito();
                                    }
                                });
                            } else {
                                // Si hay un error, mostrar un mensaje de error al usuario
                                console.error('Error al insertar las entradas en el carrito');
                            }
                        }
                    }
                };


                // Abrir la conexión y enviar la solicitud AJAX
                xhr.open('POST', form.action, true);
                xhr.setRequestHeader('X-CSRF-TOKEN', '{{ csrf_token() }}');
                xhr.send(new FormData(form));
            }

            function mostrarCarrito(carrito) {
                var carritoContainer = document.getElementById('carritoContainer');
                var precioTotal = 0; // Inicializar el precio total

                carritoContainer.innerHTML = ''; // Limpiar el contenido previo del carrito

                // Crear un elemento para el título del carrito
                var carritoTitle = document.createElement('h2');
                carritoTitle.textContent = 'Carrito';
                carritoContainer.appendChild(carritoTitle);

                if (carrito.length > 0) {
                    // Si hay productos en el carrito, mostrar cada producto y sumar el precio total
                    carrito.forEach(function(item) {
                        var productoElement = document.createElement('div');
                        var productName = item.name;
                        var precio = parseFloat(item.precio_total); // Convertir el precio a un número decimal

                        // Obtener el nombre del evento
                        var eventoName = item.nombre_evento;

                        // Mostrar el nombre del producto y su precio
                        productoElement.textContent = productName + ' - Precio: ' + precio.toFixed(2) + ' ';

                        // Agregar un botón de eliminación para cada producto
                        var eliminarButton = document.createElement('button');
                        eliminarButton.innerHTML = '<i class="fas fa-trash-alt"></i>';
                        eliminarButton.addEventListener('click', function() {
                            console.log(item.id);
                            eliminarProductoCarrito(item
                                .id); // Llamar a la función para eliminar el producto del carrito
                        });

                        productoElement.appendChild(
                            eliminarButton); // Agregar el botón de eliminación al elemento del producto

                        carritoContainer.appendChild(productoElement);

                        precioTotal += precio; // Sumar el precio al precio total
                    });

                    // Mostrar el precio total al final del carrito
                    var precioTotalElement = document.createElement('div');
                    precioTotalElement.textContent = 'Precio Total: ' + precioTotal.toFixed(2);
                    carritoContainer.appendChild(precioTotalElement);
                } else {
                    // Si el carrito está vacío, mostrar un mensaje indicando que está vacío
                    carritoContainer.textContent = 'El carrito está vacío';
                }
            }



            function eliminarProductoCarrito(productoId) {
                var xhr = new XMLHttpRequest();
                xhr.onreadystatechange = function() {
                    if (xhr.readyState === XMLHttpRequest.DONE) {
                        if (xhr.status === 200) {
                            var response = JSON.parse(xhr.responseText);
                            if (response.success) {
                                cargarCarrito();
                            }
                        } else {
                            console.error('Error al eliminar el producto del carrito:', xhr.status);
                        }
                    }
                };

                xhr.open('DELETE', '/cliente/carrito/' + productoId, true);

                // Agregar el token CSRF al encabezado de la solicitud
                xhr.setRequestHeader('X-CSRF-TOKEN', '{{ csrf_token() }}');

                xhr.send();
            }
            // Función para cargar el carrito
            function cargarCarrito() {
                var xhr = new XMLHttpRequest();

                xhr.onreadystatechange = function() {
                    if (xhr.readyState === XMLHttpRequest.DONE) {
                        if (xhr.status === 200) {
                            mostrarCarrito(JSON.parse(xhr.responseText));
                        } else {
                            console.error('Error al cargar el carrito:', xhr.status);
                        }
                    }
                };

                xhr.open('GET', '/cliente/carrito', true);
                xhr.send();
            }
        </script>

</body>

</html>
