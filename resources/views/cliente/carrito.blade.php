<!-- carrito.blade.php -->

<!DOCTYPE html>
<html>

<head>
    <title>Carrito de Compras</title>
</head>

<body>

    <h1>Carrito de Compras</h1>

    <!-- Lista de entradas seleccionadas -->
    <h2>Entradas Seleccionadas</h2>
    <ul id="entradasSeleccionadasContainer">
        <!-- Aquí se cargarán las entradas seleccionadas -->
    </ul>

    <!-- Botón para proceder al pago -->
    <button onclick="procederAlPago()">Proceder al Pago</button>

    <br>
    <br>

    <!-- Botón de volver -->
    <a href="{{ route('cliente.discoteca') }}">Volver a ver discotecas</a>

    <br>
    <br>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            cargarEntradasSeleccionadas();
        });

        function cargarEntradasSeleccionadas() {
            // Obtener las entradas seleccionadas de la URL
            var urlParams = new URLSearchParams(window.location.search);
            var entradasJSON = urlParams.get('entradas');

            if (entradasJSON) {
                var entradasSeleccionadas = JSON.parse(decodeURIComponent(entradasJSON));

                var entradasSeleccionadasContainer = document.getElementById('entradasSeleccionadasContainer');
                entradasSeleccionadasContainer.innerHTML = '';

                for (var tipoEntradaId in entradasSeleccionadas) {
                    var cantidad = entradasSeleccionadas[tipoEntradaId];

                    // Crear un elemento de lista para cada tipo de entrada seleccionada
                    var entradaSeleccionadaElement = document.createElement('li');
                    entradaSeleccionadaElement.textContent = 'Tipo de entrada ID: ' + tipoEntradaId + ', Cantidad: ' + cantidad;

                    // Agregar la entrada seleccionada al contenedor
                    entradasSeleccionadasContainer.appendChild(entradaSeleccionadaElement);
                }
            } else {
                // Mostrar un mensaje si no hay entradas seleccionadas
                entradasSeleccionadasContainer.innerHTML = '<p>No hay entradas seleccionadas en el carrito.</p>';
            }
        }

        function procederAlPago() {
            // Aquí podrías implementar la lógica para procesar el pago
            // Por ejemplo, redirigir a una página de pago o mostrar un formulario de pago
            alert('Implementar lógica para el pago aquí.');
        }
    </script>

</body>

</html>
