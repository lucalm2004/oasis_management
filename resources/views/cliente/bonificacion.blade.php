

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css"
        integrity="sha512-MV7K8+y+gLIBoVD59lQIYicR65iaqukzvf/nwasF0nqhPay5w/9lJmVM2hMDcnK1OnMGCdVK+iQrJ7lzPJQd1w=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <title>Bonificaciones Disponibles para Canjear</title>
    <link rel="shortcut icon" href="{{ asset('img/logo.png') }}" type="image/x-icon">

   <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: sans-serif;
        }

        body {
            background-image: url('/img/atardecer.png');
            /* Cambia 'background.jpg' por la ruta de tu imagen de fondo */
            background-size: cover;
            background-position: center;
            /* Ajusta el tamaño y la posición de la imagen de fondo */
        }

        .container {
            width: 100%;
            height: 100vh;
            color: white;
            display: flex;
            align-items: center;
            flex-direction: column;
            justify-content: center;
            background-color: rgba(9, 9, 33, 0.7);
            /* Agrega un color de fondo semitransparente para mejorar la legibilidad del texto */
        }

        h1 {
            font-size: 2rem;
            margin-bottom: 1rem;
            text-align: center;
        }

        .bonificaciones-wrapper {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            /* Para distribuir las cards uniformemente */
        }

        .card {
            width: 300px;
            padding: 10px;
            margin: 0.25rem;
            /* Ajusta el margen para reducir la separación */
            cursor: pointer;
            border-radius: 10px;
            background-color: #10102a;
            border: 1px solid #10102a;
            transition: all .2s linear;
            color: white;
            /* Establecer el color del texto en blanco */
        }

        .card:hover {
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

        .top ul {
            display: flex;
            list-style: none;
        }

        .top ul li {
            padding-left: 4px;
        }

        article p {
            font-size: 15px;
            font-weight: 100;
            margin-bottom: 1rem;
            font-family: system-ui;
        }

        /* Estilos para el enlace */
        a {
            color: rgba(235, 152, 78);
            text-decoration: none;
            font-size: 1rem;
            padding: 0.5rem 1rem;
            border-radius: 5px;
            background-color: rgba(255, 255, 255, 0.1);
            /* Fondo semitransparente */
            transition: background-color 0.3s ease, color 0.3s ease;
        }

        a:hover {
            background-color: rgba(223, 146, 130);
            /* Cambio de fondo al pasar el ratón */
            color: white;
        }
    </style>
</head>

<body>

    <div class="container">
        <h1>Bonificaciones Disponibles para Canjear</h1>
        <div class="bonificaciones-wrapper" id="bonificacionesContainer"></div>

        <br><br>
        <a href="{{ route('cliente.discoteca') }}">Volver a la lista de discotecas</a>
    </div>

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
                bonificacionItem.classList.add(
                'card'); // Agregar la clase 'card' para aplicar los estilos de las cards

                var contenido = `
                    <div class="top">
                        <div class="clientImage">
                            <img src="./client.png" alt="">
                            <span>${bonificacion.name}</span>
                        </div>
                    </div>
                    <article>
                        <p class="review">${bonificacion.descripcion}</p>
                        <p>${bonificacion.puntos} puntos</p>
                    </article>
                `;

                bonificacionItem.innerHTML = contenido;
                bonificacionesContainer.appendChild(bonificacionItem);
            });
        }
    </script>
</body>

</html>
