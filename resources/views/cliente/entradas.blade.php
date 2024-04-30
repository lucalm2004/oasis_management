<!-- entradas/disponibles.blade.php -->

<!DOCTYPE html>
<html>

<head>
    <title>Entradas Disponibles</title>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

</head>

<body>

    <h1>Entradas Disponibles</h1>

    <!-- Detalles del evento -->
    <h2>Detalles del Evento</h2>
    <div id="detallesEvento">
        <!-- Aquí se cargarán los detalles del evento -->
    </div>

    <!-- Lista de Tipos de Entrada -->
    <h2>Tipos de Entrada Disponibles</h2>
    <ul id="tiposEntradaContainer">
        <!-- Aquí se cargarán los tipos de entrada -->
    </ul>

    <!-- Botón para enviar las entradas seleccionadas -->
    <button onclick="enviarEntradasACarrito()">Agregar al carrito</button>

    <br>
    <br>

    <!-- Botón de volver -->
    <a href="{{ route('cliente.eventos', $evento->id_discoteca) }}">Volver a los eventos</a>

    <br>
    <br>



    <script>
        document.addEventListener("DOMContentLoaded", function() {
            cargarDetallesEvento();
            cargarTiposEntrada();
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

            detallesEvento.innerHTML += '<p>Nombre: ' + detalles.name + '</p>';
            detallesEvento.innerHTML += '<p>Descripción: ' + detalles.descripcion + '</p>';
            detallesEvento.innerHTML += '<p>Fecha de Inicio: ' + detalles.fecha_inicio + '</p>';
            detallesEvento.innerHTML += '<p>Fecha final: ' + detalles.fecha_final + '</p>';
            detallesEvento.innerHTML += '<p>DJ: ' + detalles.dj + '</p>';
            detallesEvento.innerHTML += '<p>Playlist: <a href="#" onclick="mostrarPlaylistAlert(' + detalles.id + ')">' +
                detalles.name_playlist +
                '</a></p>';
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
                    playlistHTML += '<p>' + cancion.name + '</p>';
                });
            } else {
                // Si no hay canciones, mostrar un mensaje indicando que no hay canciones en la playlist
                playlistHTML = '<p>No hay canciones en esta playlist.</p>';
            }

            Swal.fire({
                title: 'Playlist',
                html: playlistHTML,
                icon: 'info',
                confirmButtonText: 'Aceptar'
            });
        }


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
                    // Crear un elemento de lista para cada tipo de entrada
                    var tipoEntradaElement = document.createElement('li');

                    // Crear un elemento de contador (input de tipo number)
                    var contadorElement = document.createElement('input');
                    contadorElement.type = 'number';
                    contadorElement.value = 0; // Inicializar el contador en 0
                    contadorElement.min = 0;
                    contadorElement.id = 'contadorTipo' + tipoEntrada.id; // Asignar un ID único para el contador
                    tipoEntradaElement.appendChild(contadorElement);

                    // Mostrar la descripción y el precio del tipo de entrada
                    tipoEntradaElement.innerHTML += tipoEntrada.descripcion + ' - ' + tipoEntrada.precio;

                    // Agregar el tipo de entrada al contenedor
                    tiposEntradaContainer.appendChild(tipoEntradaElement);
                });
            } else {
                tiposEntradaContainer.innerHTML = '<p>No hay tipos de entrada disponibles para este evento.</p>';
            }
        }

        function enviarEntradasACarrito() {
            // Obtener todos los contadores de tipo de entrada
            var contadores = document.querySelectorAll('[id^="contadorTipo"]');

            // Inicializar la cantidad total de entradas seleccionadas
            var totalEntradas = 0;

            // Inicializar un objeto para almacenar las entradas seleccionadas
            var entradasSeleccionadas = {};

            // Iterar sobre los contadores y almacenar las cantidades seleccionadas
            contadores.forEach(function(contador) {
                var tipoEntradaId = contador.id.replace('contadorTipo', '');
                var cantidadSeleccionada = parseInt(contador.value);
                if (cantidadSeleccionada > 0) {
                    // Sumar la cantidad seleccionada al total de entradas
                    totalEntradas += cantidadSeleccionada;
                    entradasSeleccionadas[tipoEntradaId] = cantidadSeleccionada;
                }
            });

            // Convertir el objeto en una cadena JSON para enviarlo al servidor
            var entradasJSON = JSON.stringify(entradasSeleccionadas);

            // Realizar una solicitud AJAX para insertar las entradas en la tabla carrito
            fetch("{{ route('cliente.insertarEnCarrito') }}", {
                    method: 'POST',
                    body: JSON.stringify({
                        entradas: entradasJSON
                    }),
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                })
                .then(response => {
                    // Manejar la respuesta según sea necesario (redireccionar, mostrar mensaje, etc.)
                    if (response.ok) {
                        // Redirigir a la página de carrito o realizar otra acción necesaria
                        window.location.href = "{{ route('cliente.carrito') }}";
                    } else {
                        console.error('Error al insertar en el carrito:', response.status);
                        // Mostrar mensaje de error o realizar otra acción adecuada
                    }
                })
                .catch(error => {
                    console.error('Error al insertar en el carrito:', error);
                    // Mostrar mensaje de error o realizar otra acción adecuada
                });
        }
    </script>

</body>

</html>
