<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bonificaciones Disponibles para Canjear</title>
</head>

<body>
    <h1>Bonificaciones Disponibles para Canjear</h1>
    <div id="bonificacionesContainer"></div>

    <br><br>
    <a href="{{ route('cliente.discoteca') }}">Volver a la lista de discotecas</a>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            cargarBonificaciones();
        });

        function cargarBonificaciones() {
            var xhr = new XMLHttpRequest();

            xhr.onreadystatechange = function() {
                if (xhr.readyState === XMLHttpRequest.DONE) {
                    if (xhr.status === 200) {
                        mostrarBonificaciones(JSON.parse(xhr.responseText));
                    } else {
                        console.error('Error al cargar las bonificaciones:', xhr.status);
                    }
                }
            };

            xhr.open('GET', '/bonificaciones', true);
            xhr.send();
        }

        function mostrarBonificaciones(bonificaciones) {
            var bonificacionesContainer = document.getElementById('bonificacionesContainer');
            bonificacionesContainer.innerHTML = '';

            bonificaciones.forEach(function(bonificacion) {
                var bonificacionItem = document.createElement('div');
                bonificacionItem.textContent = bonificacion.name + ' - ' + bonificacion.puntos + ' puntos';

                bonificacionesContainer.appendChild(bonificacionItem);
            });
        }
    </script>
</body>

</html>
