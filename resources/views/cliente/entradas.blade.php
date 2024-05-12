<!-- entradas/disponibles.blade.php -->

<!DOCTYPE html>
<html>

<head>
    <title>Entradas Disponibles</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css"
        integrity="sha512-MV7K8+y+gLIBoVD59lQIYicR65iaqukzvf/nwasF0nqhPay5w/9lJmVM2hMDcnK1OnMGCdVK+iQrJ7lzPJQd1w=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>

    <style>
        /* Estilos generales */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #10101A;
            color: white;
        }

        header {
            background-color: #666;
            padding: 20px;
            color: white;
        }

        .header-container {
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .header-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
            width: 100%;
            max-width: 1200px;
        }

        /* Estilos para el botón de cerrar sesión */
        #logout-btn {
            padding: 10px 10px;
            background-color: #007bff;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .content-container {
            width: 100%;
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
            display: flex;
            flex-direction: column;
            align-items: center;
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
        }

        button {
            padding: 10px 10px;
            background-color: #007bff;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            margin-bottom: 20px;
        }

        a {
            display: inline-block;
            padding: 10px 15px;
            margin-top: 20px;
            color: #fff;
            text-decoration: none;
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }

        a:hover {
            text-decoration: underline;
        }

        /* Estilos específicos para la página */
        #detallesEvento,
        #tiposEntradaContainer,
        #carritoContainer {
            background-color: #161624;
            color: #f8f8f8;
            border-radius: 5px;
            padding: 20px;
            margin-bottom: 20px;
            flex-basis: calc(33.33% - 20px);
            box-sizing: border-box;
        }

        #tiposEntradaContainer {
            list-style-type: none;
            padding: 20px;
        }

        #tiposEntradaContainer li {
            margin-bottom: 10px;
        }

        input[type="number"] {
            width: 60px;
            padding: 5px;
        }

        /* Estilos para el modal de la playlist */
        .swal2-title {
            color: #333;
            font-size: 24px;
            margin-bottom: 10px;
        }

        .swal2-content {
            font-size: 16px;
        }

        .tipo-entrada-container {
            margin-bottom: 10px;
        }

        /* Clase para ocultar el carrito */
        .hidden {
            position: fixed;
            top: 0;
            right: -1000%;
            transition: transform 0.5s ease, right 0.5s ease;
            background-color: #fff;
            padding: 20px;
            z-index: 1000;
            overflow-y: auto;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
            margin-right: 100px;
            transform: translateX(100%);
        }

        .show-cart {
            right: 0;
            transition: transform 0.5s ease, left 0.5s ease;
            transform: translateX(0);
        }

        .header-content a i {
            margin-right: 5px;
        }

        /* Estilos para el icono de cerrar sesión */
        .header-content button i {
            margin-right: 5px;
        }

        /* Estilos personalizados para Sweet Alert */
        .personalizado-swal-container {
            font-family: Arial, sans-serif;
            border-radius: 10px;
        }

        .personalizado-swal-title {
            color: orange;
            font-size: 24px;
            margin-bottom: 10px;
        }

        .personalizado-swal-html-container {
            color: black;
            font-size: 18px;
        }

        .personalizado-swal-confirm-button {
            background-color: orange;
            border-color: orange;
            color: white;
            font-size: 16px;
            border-radius: 5px;
            padding: 10px 20px;
            transition: background-color 0.3s, border-color 0.3s, color 0.3s;
        }

        .personalizado-swal-confirm-button:hover {
            background-color: darkorange;
            border-color: darkorange;
        }

        .personalizado-swal-confirm-button:focus {
            outline: none;
        }

        /* .slideThree */
        .slideThree {
            width: 80px;
            height: 26px;
            background: #fff;
            /* Cambiado a blanco */
            margin: 20px auto;
            position: relative;
            border-radius: 50px;
            box-shadow: inset 0px 1px 1px rgba(0, 0, 0, 0.5), 0px 1px 0px rgba(255, 255, 255, 0.2);
            transition: background-color 0.4s ease;
            /* Transición para cambiar el color de fondo */
        }

        .slideThree:after {
            color: #000;
            position: absolute;
            right: 10px;
            z-index: 0;
            font: 12px/26px Arial, sans-serif;
            font-weight: bold;
            text-shadow: 1px 1px 0px rgba(255, 255, 255, .15);
        }

        .slideThree:before {
            color: #db9a17;
            position: absolute;
            left: 10px;
            z-index: 0;
            font: 12px/26px Arial, sans-serif;
            font-weight: bold;
        }

        label {
            display: block;
            width: 34px;
            height: 20px;
            cursor: pointer;
            position: absolute;
            top: 3px;
            left: 3px;
            z-index: 1;
            background: #000;
            background: linear-gradient(top, #db9a17 0%, #b36b00 40%, #e6bf5f 100%);
            border-radius: 50px;
            transition: all 0.4s ease;
            box-shadow: 0px 2px 5px 0px rgba(0, 0, 0, 0.3);
        }

        input[type=checkbox] {
            visibility: hidden;

            &:checked+label {
                left: 43px;
                background: #db9a17;
                /* Cambiar el color de fondo cuando se activa */
            }
        }

        hr {
            margin-top: 10%;
            margin-bottom: 10%;
        }
    </style>

</head>

<body>
    <header>
        <div class="header-container">

            <!-- Contenido del header -->
            <div class="header-content">
                <!-- Botón de volver -->
                <a href="{{ route('cliente.eventos', $evento->id_discoteca) }}"> <i class="fas fa-arrow-left"></i>
                    Volver
                    a los eventos</a>
                <h1>Entradas Disponibles</h1>
                <!-- Botón de cerrar sesión -->
                <form class="logout-form" action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" id="logout-btn">Cerrar Sesión <i class="fas fa-sign-out-alt"></i></button>
                </form>
            </div>
        </div>
    </header>

    <div class="content-container">
        <div style="position: fixed; right: 20px; cursor: pointer;">
            <button id="cart-icon"><i class="fas fa-shopping-cart fa-3x"></i></button>
        </div>


        <div id="carritoContainer" style="position: fixed; top: 25%; right: -100px; margin-right: 1.3%;" class="hidden">
            <!-- Aquí se mostrarán los productos en el carrito -->
        </div>



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
        <button onclick="enviarEntradasACarrito()"><i class="fas fa-shopping-cart"></i></button>


    </div>

    <br>
    <br>



    <script>
        document.addEventListener("DOMContentLoaded", function() {
            cargarDetallesEvento();
            cargarTiposEntrada();
            cargarCarrito();
        });



        function toggleCart() {
            var cart = document.getElementById('carritoContainer');
            var cartIcon = document.getElementById('cart-icon');

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

            detallesEvento.innerHTML += '<p>Nombre: ' + detalles.name + '</p>';
            detallesEvento.innerHTML += '<p>Descripción: ' + detalles.descripcion + '</p>';
            detallesEvento.innerHTML += '<p>Fecha de Inicio: ' + detalles.fecha_inicio + '</p>';
            detallesEvento.innerHTML += '<p>Fecha final: ' + detalles.fecha_final + '</p>';
            detallesEvento.innerHTML += '<p>DJ: ' + detalles.dj + '</p>';
            detallesEvento.innerHTML += '<p>Playlist:<a href="#" style="color: orange;" onclick="mostrarPlaylistAlert(' +
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
                    playlistHTML += '<p>' + cancion.name + '</p>';
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
                    tipoEntradaContainer.classList.add(
                        'tipo-entrada-container'); // Agregar una clase para los estilos CSS

                    // Crear un elemento de contador (input de tipo number)
                    var contadorElement = document.createElement('input');
                    contadorElement.type = 'number';
                    contadorElement.value = 0; // Inicializar el contador en 0
                    contadorElement.min = 0;
                    contadorElement.id = 'contadorTipo' + tipoEntrada.id; // Asignar un ID único para el contador
                    tipoEntradaContainer.appendChild(contadorElement);

                    // Mostrar la descripción y el precio del tipo de entrada
                    var descripcionPrecioElement = document.createElement('span');
                    descripcionPrecioElement.textContent = ' - ' + tipoEntrada.descripcion + ' - ' + tipoEntrada
                        .precio;
                    tipoEntradaContainer.appendChild(descripcionPrecioElement);

                    // Agregar un campo oculto para el ID del producto asociado al tipo de entrada
                    var idProductoInput = document.createElement('input');
                    idProductoInput.type = 'hidden';
                    idProductoInput.name = 'productos[' + tipoEntrada.id +
                        ']'; // Utiliza el ID del tipo de entrada como clave
                    idProductoInput.value = tipoEntrada.id_producto;
                    tipoEntradaContainer.appendChild(idProductoInput);

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

        document.getElementById('infoIcon').addEventListener('click', function() {
            // Llama a la función para mostrar el Sweet Alert
            mostrarAlerta();
        });

        // Define la función para mostrar el Sweet Alert
        function mostrarAlerta() {
            Swal.fire({
                title: 'Información Importante',
                text: 'La canción se mandará a revisar, si no es apropiada no se añadirá a la playlist del evento. La decisión será mandada por correo. Grácias por la atención.',
                icon: 'info',
                confirmButtonText: 'Aceptar'
            });
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
                    var productName = item.nombre_producto;
                    var precio = parseFloat(item.precio_total); // Convertir el precio a un número decimal
                    var eventoName = item.nombre_evento;

                    // Si el ID del producto es menor de 3, mostrar también el nombre del evento
                    if (item.id_producto < 3) {
                        productoElement.textContent = productName + ' de ' + eventoName + ' - ' +
                            precio.toFixed(2) + ' ';
                    } else {
                        // Si el ID del producto es mayor o igual a 3, solo mostrar el nombre del producto y el precio
                        productoElement.textContent = productName + ' de ' + eventoName + ' - ' + precio.toFixed(
                            2) + ' ';
                    }

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

                // Crear una división para actuar como espacio en blanco
                var espacioBlanco = document.createElement('div');
                carritoContainer.appendChild(espacioBlanco);

                // Agregar una línea de separación antes del texto "¿Quieres elegir una canción?"
                var lineaSeparacion = document.createElement('hr');
                carritoContainer.appendChild(lineaSeparacion);

                // Agregar el texto "¿Quieres elegir una canción?" al lado del section
                var textoCancion = document.createElement('p');
                textoCancion.innerHTML =
                    '<p>¿Quieres elegir una canción?</p> <i id="infoIcon" class="fas fa-info-circle" style="cursor: pointer;"></i>';
                carritoContainer.appendChild(textoCancion);

                // Agregar el section al final del carrito
                var sectionElement = document.createElement('section');
                sectionElement.setAttribute('title', '.slideThree');
                sectionElement.innerHTML =
                    '<div class="slideThree"><input type="checkbox" value="None" id="slideThree" name="check" /><label for="slideThree"></label></div>';
                carritoContainer.appendChild(sectionElement);

                // Obtener el checkbox
                var checkbox = document.getElementById('slideThree');

                // Crear un div con fondo blanco y tamaño fijo
                var infoDiv = document.createElement('div');
                infoDiv.style.backgroundColor = 'white';
                infoDiv.style.padding = '10px'; // Ajusta el relleno según sea necesario
                infoDiv.style.width = '300px'; // Establecer el ancho fijo 
                infoDiv.style.border = '1px solid #ccc'; // Añadir borde
                infoDiv.style.borderRadius = '5px'; // Añadir bordes redondeados
                infoDiv.style.boxShadow = '0 2px 4px rgba(0,0,0,0.1)'; // Añadir sombra
                infoDiv.style.marginTop = '20px'; // Añadir margen superior
                infoDiv.style.display = 'flex'; // Cambiar la visualización a flexbox
                infoDiv.style.alignItems = 'center'; // Centrar verticalmente los elementos hijos
                infoDiv.style.justifyContent = 'space-between'; // Espaciar uniformemente los elementos hijos

                // Agregar texto informativo
                var infoText = document.createElement('p');
                infoText.textContent = 'La canción se añadirá a la playlist del último evento del que compres entrada.';
                infoText.style.margin = '0'; // Elimina el margen predeterminado si lo hay
                infoText.style.color = '#b36b00'; // Cambia el color del texto a azul

                // Establecer estilos para el texto
                infoText.style.flex = '1'; // Estirar el texto para ocupar el espacio restante

                // Agregar el texto al div
                infoDiv.appendChild(infoText);

                // Agregar el div al final del contenedor del carrito
                carritoContainer.appendChild(infoDiv);
                // Escuchar el evento de cambio en el checkbox
                checkbox.addEventListener('change', function() {
                    if (checkbox.checked) {
                        // Mostrar SweetAlert con inputs para ingresar el nombre de la canción y del artista
                        Swal.fire({
                            title: 'Ingrese el nombre de la canción y del artista',
                            html: '<input id="nombreCancion" class="swal2-input" placeholder="Nombre de la canción">' +
                                '<input id="nombreArtista" class="swal2-input" placeholder="Nombre del artista">',
                            showCancelButton: true,
                            confirmButtonText: 'Guardar',
                            cancelButtonText: 'Cancelar',
                            preConfirm: () => {
                                const nombreCancion = Swal.getPopup().querySelector('#nombreCancion')
                                    .value;
                                const nombreArtista = Swal.getPopup().querySelector('#nombreArtista')
                                    .value;
                                if (!nombreCancion || !nombreArtista) {
                                    Swal.showValidationMessage(
                                        'Debe ingresar el nombre de la canción y del artista');
                                }
                                return {
                                    nombreCancion: nombreCancion,
                                    nombreArtista: nombreArtista
                                };
                            }
                        }).then((result) => {
                            if (result.isConfirmed) {
                                // Guardar el nombre de la canción y del artista en la base de datos
                                var nombreCancion = result.value.nombreCancion;
                                var nombreArtista = result.value.nombreArtista;
                                insertarCancionEnBaseDeDatos(nombreCancion,
                                    nombreArtista
                                ); // Llamar a la función para insertar la canción en la base de datos
                            } else {
                                // Desmarcar el checkbox si se cancela la operación
                                checkbox.checked = false;
                            }
                        });
                    }

                });
            } else {
                // Si el carrito está vacío, mostrar un mensaje indicando que está vacío
                carritoContainer.textContent = 'El carrito está vacío';
            }
        }

        // Función para insertar la canción en la base de datos
        function insertarCancionEnBaseDeDatos(nombreCancion, nombreArtista) {
            // Obtener el token CSRF de la etiqueta meta en el HTML
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            // Realizar una solicitud HTTP POST para insertar la canción en la base de datos
            fetch('/cliente/insertarCancion', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken // Añadir el token CSRF como encabezado
                    },
                    body: JSON.stringify({
                        nombreCancion: nombreCancion,
                        nombreArtista: nombreArtista
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Si la inserción es exitosa, muestra un mensaje de éxito
                        Swal.fire('¡Canción guardada!', data.message, 'success');
                        cargarCarrito();
                    } else {
                        // Si hay un error, muestra un mensaje de error
                        Swal.fire('Error', data.message, 'error');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    // Si hay un error, muestra un mensaje de error
                    Swal.fire('Error', 'Hubo un problema al intentar guardar la canción en la base de datos.', 'error');
                });
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
