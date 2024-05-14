<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Oasis Management - Bonificaciones Disponibles para Canjear</title>
    <link rel="shortcut icon" href="{{ asset('img/logo.png') }}" type="image/x-icon">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css">

    <!-- GSAP Animation Library -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.9.1/gsap.min.js"></script>

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Rubik:wght@300;400;500;600;700;800;900&display=swap');

        body {
            background-image: url('/img/atardecer.png');
            background-size: cover;
            background-position: center;
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            margin: 0;
            padding: 0;
            font-family: 'Rubik', sans-serif;
        }

        .container {
            width: 90%;
            /* Ajusta el ancho del contenedor al 90% del viewport */
            max-width: 1200px;
            /* Establece un ancho máximo para el contenedor */
            padding: 40px;
            color: white;
            background-color: rgba(9, 9, 33, 0.9);
            border-radius: 20px;
            text-align: center;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.3);
            margin: 20px auto;
            /* Añade un margen alrededor del contenedor y lo centra horizontalmente */
        }

        h1 {
            font-size: 3rem;
            margin-bottom: 30px;
            background: linear-gradient(45deg, #ffffff, #f9d423);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .card {
            background-color: rgba(255, 255, 255, 0.1);
            border: none;
            border-radius: 20px;
            padding: 30px;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            color: white;
            margin-bottom: 20px;
            /* Añade un espacio entre las tarjetas */
            cursor: pointer;
            width: 100%;
            /* Ajusta el ancho del card al 100% del contenedor */
            max-width: 400px;
            /* Establece un ancho máximo para el card */
            margin-left: auto;
            /* Centra el card horizontalmente */
            margin-right: auto;
        }

        .card:hover {
            background-color: rgba(235, 152, 78, 0.8);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.4);
            transform: translateY(-10px) scale(1.05);
            /* Eleva y escala la tarjeta al hacer hover */
        }

        .icon {
            font-size: 2rem;
            margin-right: 10px;
            transition: transform 0.3s ease;
        }

        .icon:hover {
            transform: scale(1.2);
        }

        .card-title {
            font-size: 1.8rem;
            font-weight: bold;
            margin-bottom: 20px;
        }

        .review {
            font-size: 1.2rem;
            line-height: 1.6;
            margin-bottom: 20px;
        }

        .points {
            font-size: 1.5rem;
            font-weight: bold;
            margin-top: 30px;
        }

        .btn {
            margin-top: 40px;
            padding: 12px 24px;
            font-size: 1.2rem;
            letter-spacing: 1px;
            text-transform: uppercase;
            border-radius: 30px;
            transition: background-color 0.3s ease, color 0.3s ease;
        }

        .btn:hover {
            background-color: rgba(255, 255, 255, 0.2);
            color: white;
        }

        /* Estilos adicionales */
        .parallax-shadow {
            transition: transform 0.3s ease;
        }

        .parallax-shadow:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.3);
        }

        .custom-bg {
            transition: background-color 0.3s ease;
        }

        .custom-bg:hover {
            background-color: rgba(255, 255, 255, 0.2);
        }

        /* Media Query para dispositivos pequeños */
        @media (max-width: 768px) {
            .container {
                padding: 20px;
            }

            h1 {
                font-size: 2.5rem;
                margin-bottom: 20px;
            }

            .card {
                padding: 20px;
                max-width: 100%;
                /* Ajusta el ancho máximo del card al 100% en dispositivos pequeños */
            }

            .btn {
                padding: 10px 20px;
                font-size: 1rem;
            }
        }
    </style>
</head>

<body>
    <div class="container">

        <h1>Bonificaciones Disponibles</h1>
        <div class="row" id="bonificacionesContainer"></div>
        <a href="{{ route('cliente.discoteca') }}" class="btn btn-primary custom-bg">Volver a la lista de discotecas</a>
    </div>

    <!-- Bootstrap JS, jQuery, Popper.js -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"
        integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"
        integrity="sha384-dtFUnYTAoXv19iWRynrP9+kXtV9z6k+gf2W6ZWrLvgF99mVn1eYHugRYQM0H1Xai" crossorigin="anonymous">
    </script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"
        integrity="sha384-0pKIo0R4JfQn6dFwbW3hsO+9KcEwiPr/J5dd6G3adAtfMviqnlegjfzwMnqYyyi7" crossorigin="anonymous">
    </script>

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
                var bonificacionCard = document.createElement('div');
                bonificacionCard.classList.add('col-md-6', 'col-lg-4', 'mb-4', 'animated-card');

                var contenido = `
                    <div class="card parallax-shadow" onclick="animateCard(this)">
                        <div class="card-body">
                            <i class="fas fa-gift icon"></i>
                            <div class="d-flex justify-content-center align-items-center mb-4">
                                <h4 class="card-title">${bonificacion.name}</h4>
                            </div>
                            <div>
                                <p class="review">${bonificacion.descripcion}</p>
                                <p class="points"><i class="fas fa-coins icon"></i>${bonificacion.puntos} puntos</p>
                            </div>
                        </div>
                    </div>
                `;

                bonificacionCard.innerHTML = contenido;
                bonificacionesContainer.appendChild(bonificacionCard);

                // Animación de entrada con GSAP
                gsap.from(bonificacionCard, {
                    opacity: 0,
                    y: 50,
                    duration: 0.6,
                    ease: "power2.out"
                });
            });
        }

        function animateCard(card) {
            // Comprobar si la tarjeta ya está girando
            if (!card.classList.contains('is-animating')) {
                // Agregar una clase para marcar que la tarjeta está en proceso de animación
                card.classList.add('is-animating');

                // Animación de rotación con GSAP
                gsap.to(card, {
                    rotationY: '+=360', // Girar 360 grados adicionales cada vez que se hace clic
                    duration: 0.85,
                    ease: "power2.out",
                    onComplete: function() {
                        // Eliminar la clase de animación al completar la rotación
                        card.classList.remove('is-animating');
                    }
                });

                // Cargar la animación Lottie
                const animation = lottie.loadAnimation({
                    container: card,
                    renderer: 'svg',
                    loop: false,
                    autoplay: true,
                    path: 'https://assets2.lottiefiles.com/packages/lf20_2eNqyP.json'
                });
            }
        }
    </script>
</body>

</html>
