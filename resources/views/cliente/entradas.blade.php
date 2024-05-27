<!DOCTYPE html>
<html>

<head>
    <title>Oasis Management - Entradas Disponibles</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="{{ asset('css/entradas.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css"
        integrity="sha512-MV7K8+y+gLIBoVD59lQIYicR65iaqukzvf/nwasF0nqhPay5w/9lJmVM2hMDcnK1OnMGCdVK+iQrJ7lzPJQd1w=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/leaflet.awesome-markers/2.0.6/leaflet.awesome-markers.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css">
    <link rel="shortcut icon" href="{{ asset('img/logo.png') }}" type="image/x-icon">

    <style>
        /* Estilos generales */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #10101A;
            color: white;
        }

        /* Estilos para el navbar */
        .navbar {
            padding: 1rem 0;
        }

        .navbar-brand {
            font-size: 1.5rem;
        }

        .navbar-toggler {
            border: none;
        }

        .navbar-nav .nav-link {
            padding: 0.5rem 1rem;
        }

        .dropdown-menu {
            min-width: 10rem;
        }

        .dropdown-item {
            padding: 0.5rem 1rem;
        }

        .dropdown-item:hover {
            background-color: #f8f9fa;
        }

        .orange-text {
            color: orange;
        }

        .logo {
            height: 40px;
        }

        /* Estilos para los títulos */
        h1,
        h2 {
            font-size: 2.5rem;
            margin-bottom: 1rem;
            text-align: center;
            color: orange;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);
        }

        /* Estilos para el botón de cerrar sesión */
        button,
        #logout-btn {
            padding: 10px 20px;
            background-color: #007bff;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            box-shadow: 2px 2px 4px rgba(0, 0, 0, 0.2);
        }

        #logout-btn {
            margin-bottom: 20px;
        }

        /* Estilos para el contenido */
        .content-container {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
            text-align: center;
        }

        /* Estilos para los iconos del carrito */
        .cart-icon-container {
            position: relative;
            top: 20px;
            right: 20px;
            z-index: 1000;
        }

        .cart-icon-button {
            background-color: transparent;
            border: none;
            cursor: pointer;
        }

        /* Estilos para las secciones */
        .section {
            margin-bottom: 40px;
            text-align: left;
        }

        /* Estilos para la lista de tipos de entrada */
        .tipos-entrada-container,
        .tipo-entrada-container {
            list-style: none;
            padding: 20px;
        }

        .tipos-entrada-container li,
        .tipo-entrada-container {
            background-color: #1f1f2e;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 15px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        /* Estilos para el campo de contador */
        .tipo-entrada-container input[type="number"] {
            width: 50px;
            padding: 5px;
            text-align: center;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        /* Estilos para la descripción y el precio */
        .descripcion-precio {
            flex-grow: 1;
            font-size: 16px;
            color: #fff;
        }

        /* Estilos para la descripción y precio del tipo de entrada */
        .tipo-entrada-container span {
            flex-grow: 1;
            margin-left: 10px;
            font-size: 16px;
        }

        /* Estilos para los botones de incremento/decremento */
        .contador-button {
            background-color: #007bff;
            color: #fff;
            border: none;
            padding: 8px;
            font-size: 16px;
            cursor: pointer;
            border-radius: 5px;
            margin: 0 5px;
        }

        /* Estilos para el contador */
        .contador {
            font-size: 16px;
            color: #fff;
            padding: 8px;
            border: 1px solid #007bff;
            border-radius: 5px;
            width: 40px;
            text-align: center;
        }

        /* Estilos para el botón "Agregar al Carrito" */
        .add-to-cart-button {
            background-color: #007bff;
            color: #fff;
            border: none;
            padding: 8px 15px;
            font-size: 16px;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .add-to-cart-button:hover {
            background-color: #0056b3;
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
            text-align: left;
        }

        #tiposEntradaContainer {
            list-style-type: none;
        }

        input[type="number"] {
            width: 60px;
            padding: 5px;
        }

        .boton-container {
            display: flex;
            align-items: center;
        }

        /* Estilos para el modal de la playlist */
        .swal2-container {
            font-family: 'Rubik', sans-serif;
        }

        .swal2-popup {
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
            background-color: #1f1f2e;
        }

        .swal2-title {
            color: #FFA500;
            font-size: 28px;
            margin-bottom: 20px;
            text-align: center;
        }

        .swal2-content {
            color: #fff;
            font-size: 18px;
            text-align: center;
            margin-bottom: 20px;
        }

        .swal2-actions {
            display: flex;
            justify-content: center;
        }

        .swal2-button {
            padding: 10px 20px;
            margin: 0 10px;
            font-size: 16px;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .swal2-button:hover {
            background-color: #FFA500;
            color: #fff;
        }

        .swal2-close {
            color: #FFA500;
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
            transition: transform 0.5s ease, left 0.5s ease transform: translateX(0);
        }

        /* Media queries para hacer la interfaz responsive */
        @media screen and (max-width: 991px) {
            .navbar-collapse {
                background-color: #161624;
                box-shadow: 0px 5px 10px rgba(0, 0, 0, 0.2);
                padding: 1rem;
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 100vh;
                overflow-y: auto;
                transform: translateX(-100%);
                transition: transform 0.3s ease;
                z-index: 999;
            }

            .navbar-collapse.show {
                transform: translateX(0);
            }

            .navbar-nav {
                flex-direction: column;
                align-items: center;
            }

            .navbar-nav .nav-link {
                padding: 1rem;
                color: #FFFFFF;
                text-align: center;
            }

            .navbar-toggler {
                display: block;
                position: absolute;
                top: 1rem;
                right: 1rem;
                z-index: 1000;
            }
        }

        @media screen and (max-width: 768px) {
            .content-container {
                padding: 10px;
            }

            #detallesEvento,
            #tiposEntradaContainer {
                margin-bottom: 10px;
            }
        }

        /* Estilos específicos para el menú responsive */
        #responsiveMenuContainer {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            z-index: 1000;
            background-color: #FFFFFF;
            box-shadow: 0px 5px 10px rgba(0, 0, 0, 0.2);
        }

        @media screen and (max-width: 991px) {
            .navbar-collapse {
                position: absolute;
                top: 100%;
                left: 0;
                width: 100%;
                background-color: #FFFFFF;
                box-shadow: 0px 5px 10px rgba(0, 0, 0, 0.2);
                z-index: 999;
            }

            .navbar-nav {
                flex-direction: column;
                align-items: center;
            }

            .navbar-nav .nav-link {
                padding: 1rem;
                color: #000000;
                text-align: center;
            }

            .navbar-toggler {
                display: block;
                position: absolute;
                top: 1rem;
                right: 1rem;
                z-index: 1001;
            }
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
            border: none
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
            /* max-height: 500px;
    overflow-y: auto; */
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

        /* Estilos CSS para el contenido interno del carrito */
        .carrito-content {
            max-height: 230px;
            /* Establecer una altura máxima para el contenido interno */
            overflow-y: auto;
            /* Agregar desplazamiento vertical cuando el contenido excede la altura máxima */
            padding-left: 3px;
        }

        ::-webkit-scrollbar {
            height: 6px;
        }

        ::-webkit-scrollbar-track {
            background: transparent;
        }

        ::-webkit-scrollbar-thumb {
            background-color: rgba(111, 111, 111, 0.60);
            border-radius: 40px;
            -webkit-border-radius: 40px;
            -moz-border-radius: 40px;
            -ms-border-radius: 40px;
            -o-border-radius: 40px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background-color: rgba(111, 111, 111, 0.80);
        }

        .select {
            text-align: center;
            position: relative;
            border-radius: 10px;
            -webkit-appearance: none;
            color: white;
            -moz-appearance: none;
            appearance: none;
            background: orange;
            padding: 1px 5px;
            font-size: 10px;
            border: 1px solid white;
            z-index: 99;
            margin-bottom: -17px;
            margin-left: 15px;
        }

        .bonificaciones {
            background-color: #10101A;
            /* Color de fondo */
            border: 1px solid #2E2E42;
            /* Borde */
            border-radius: 8px;
            /* Bordes redondeados */
            padding: 20px;
            /* Espaciado interno */
            margin-top: 20px;
            /* Margen superior */
        }

        .bonificaciones h2 {
            margin-top: 0;
            /* Elimina el margen superior del título */
            font-size: 1.5em;
            /* Tamaño de fuente del título */
            color: #ffa600;
            /* Color del texto */
            text-align: center;
            /* Alineación del texto */
        }

        .bonificaciones div {
            margin-bottom: 10px;
            /* Espacio entre cada bonificación */
            padding: 15px;
            /* Espaciado interno */
            background-color: #1E1E2B;
            /* Color de fondo de cada bonificación */
            border: 1px solid #2E2E42;
            /* Borde */
            border-radius: 5px;
            /* Bordes redondeados */
        }

        .bonificaciones p {
            margin: 0;
            /* Elimina el margen del mensaje de no bonificaciones */
            color: #FFFFFF;
            /* Color del texto */
        }


        #checkout {
            margin-top: 5%;
            width: 100%;
            padding: 1em;
            background: #ff5500;
            color: white;
            border: none;
            border-radius: 30px;
            font-weight: 600;
            margin-bottom: 5%;
            padding: 4%;
            font-size: 14px;
        }

        #checkout:hover {
            -webkit-transition: 0.2s;
            -moz-transition: 0.2s;
            -ms-transition: 0.2s;
            -o-transition: 0.2s;
            -webkit-opacity: 0.5;
            -moz-opacity: 0.5;
            opacity: 0.8;
        }

        /* Estilos para el contenedor de tipos de entrada */
        .tipos-entrada-container {
            list-style-type: none;
            /* Quita los estilos de viñeta de la lista */
            padding: 0;
            /* Elimina el relleno predeterminado de la lista */
            margin: 0;
            /* Elimina el margen predeterminado de la lista */
        }

        /* Estilos para cada tipo de entrada */
        .tipo-entrada-container {
            margin-bottom: 10px;
            /* Espacio entre cada tipo de entrada */
            border: 1px solid #ccc;
            /* Borde del contenedor */
            padding: 10px;
            /* Espacio interno del contenedor */
            border-radius: 5px;
            /* Bordes redondeados */
            background-color: #313b46;
            /* Color de fondo */
        }

        .tipo-entrada-container span {
            font-weight: bold;
            /* Texto en negrita */
        }

        /* Estilos para los botones de incremento y decremento */
        .boton {
            display: inline-block;
            background-color: orange;
            color: white;
            border: none;
            border-radius: 4px;
            padding: 5px 10px;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .boton:hover {
            background-color: darkorange;
        }

        .boton-menos {
            margin-right: 5px;
            /* Ajusta el margen derecho para que esté más cerca del número */
        }

        .boton-mas {
            margin-left: 5px;
            /* Ajusta el margen izquierdo para que esté más cerca del número */
        }

        .tipo-entrada-container input[type="number"] {
            width: 50px;
            /* Ancho del campo de entrada de número */
            margin-right: 10px;
            /* Espacio a la derecha del campo de entrada */
        }

        /* Estilos cuando se pasa el ratón sobre un tipo de entrada */
        .tipo-entrada-container:hover {
            background-color: #ff910a;
            /* Cambia el color de fondo al pasar el ratón */
        }


        /* Media queries para hacer la interfaz responsive */
        @media screen and (max-width: 768px) {

            /* Estilos para pantalla con ancho máximo de 768px */
            .header-container {
                flex-direction: column;
                align-items: flex-start;
            }

            .logout-form {
                margin-top: 10px;
                margin-left: 0;
            }

            .content-container {
                padding: 10px;
            }

            #carritoContainer {
                right: -250px;
            }


            #detallesEvento,
            #tiposEntradaContainer {
                margin-bottom: 10px;
            }
        }

        /* Media query para ajustar el ancho del carrito */
        @media screen and (max-width: 1329px) {
            #carritoContainer {
                width: 300px;
                /* Cambiar el ancho a medida que el ancho de la pantalla sea igual o menor a 1329px */
            }
        }

        /* Media query para ajustar el ancho del carrito */
        @media screen and (max-width: 1055px) {
            #carritoContainer {
                width: 200px;
                /* Cambiar el ancho a medida que el ancho de la pantalla sea igual o menor a 1329px */
            }
        }

        /* Media queries para hacer la interfaz responsive */
        @media screen and (max-width: 768px) {

            /* Estilos para pantalla con ancho máximo de 768px */
            .content-container {
                padding: 10px;
            }

            /* Ajustes para el contenedor de bonificaciones */
            .bonificaciones {
                padding: 15px;
            }
        }

        @media screen and (max-width: 576px) {

            /* Estilos para pantalla con ancho máximo de 576px */
            .bonificaciones h2 {
                font-size: 1.2em;
            }
        }

        .fixed-cart {
            position: fixed;
            top: 130px;
            /* Ajusta esta posición según tus necesidades */
            right: 10px;
        }

        .carrito-container {
            max-height: 400px;
            /* Ajusta esta altura máxima según tus necesidades */
            overflow-y: auto;
            /* Habilitar el desplazamiento vertical cuando el contenido excede la altura máxima */
        }
    </style>

</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-white">
        <div class="container">
            <a class="navbar-brand" href="{{ URL::previous() }}">
                <img src="/img/logo_oasis.png" class="logo mr-2" alt="Logo">
                <span class="font-weight-bold text-uppercase">
                  {{--   Oasis <span class="orange-text">Management</span> --}}
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

    <div class="content-container">
        <div class="fixed-cart">
            <div style="margin-right: 20px; cursor: pointer;">
                <button id="cart-icon"><i class="fas fa-shopping-cart fa-3x"></i></button>
            </div>
        </div>



        <div id="carritoContainer" style="position: fixed; top: 25%; right: -100px; margin-right: 1.3%;" class="hidden">
            <!-- Aquí se mostrarán los productos en el carrito -->
        </div>

        <!-- Detalles del evento -->
        <h2>Detalles del Evento</h2>
        <div id="detallesEvento">
            <!-- Aquí se cargarán los detalles del evento -->
        </div>
        <div class="puntos-section">
            <a href="{{route('cliente.bonificacion')}}" class="text-decoration-none">
                <div id="bonificaciones-container" class="bonificaciones">
                    <!-- Aquí se mostrarán las bonificaciones -->
                </div>
            </a>
        </div>


        <br>
        <!-- Lista de Tipos de Entrada -->
        <h2>Tipos de Entrada Disponibles</h2>
        <ul id="tiposEntradaContainer" class="tipos-entrada-container">
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
            cargarBonificaciones();
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
                    var tipoEntradaDiv = document.createElement('div');
                    tipoEntradaDiv.classList.add('tipo-entrada-container');

                    var contadorWrapper = document.createElement('div');
                    contadorWrapper.classList.add('contador-wrapper');

                    var botonMenos = document.createElement('button');
                    botonMenos.innerHTML = '<i class="fas fa-minus"></i>';
                    botonMenos.classList.add('boton');
                    botonMenos.classList.add('boton-menos');
                    botonMenos.addEventListener('click', function() {
                        if (parseInt(contadorElement.value) > 0) {
                            contadorElement.value = parseInt(contadorElement.value) - 1;
                        }
                    });
                    contadorWrapper.appendChild(botonMenos);

                    var contadorElement = document.createElement('input');
                    contadorElement.type = 'number';
                    contadorElement.value = 0;
                    contadorElement.min = 0;
                    contadorElement.id = 'contadorTipo' + tipoEntrada.id;
                    contadorWrapper.appendChild(contadorElement);

                    var botonMas = document.createElement('button');
                    botonMas.innerHTML = '<i class="fas fa-plus"></i>';
                    botonMas.classList.add('boton');
                    botonMas.classList.add('boton-mas');
                    botonMas.addEventListener('click', function() {
                        contadorElement.value = parseInt(contadorElement.value) + 1;
                    });
                    contadorWrapper.appendChild(botonMas);

                    tipoEntradaDiv.appendChild(contadorWrapper);

                    var descripcionPrecioElement = document.createElement('span');
                    descripcionPrecioElement.textContent = ' - ' + tipoEntrada.descripcion + ' - ' + tipoEntrada
                        .precio;
                    tipoEntradaDiv.appendChild(descripcionPrecioElement);

                    var idProductoInput = document.createElement('input');
                    idProductoInput.type = 'hidden';
                    idProductoInput.name = 'productos[' + tipoEntrada.id + ']';
                    idProductoInput.value = tipoEntrada.id_producto;
                    tipoEntradaDiv.appendChild(idProductoInput);

                    tiposEntradaContainer.appendChild(tipoEntradaDiv);

                });

                // Añadir evento de cambio a cada input
                inputElements.forEach(function(inputElement, index, array) {
                    inputElement.addEventListener('input', function() {
                        // Si el valor del tercer input es mayor que 0, deshabilitar el primero y el segundo
                        if (array[2] && array[2].value > 0) {
                            array[0].disabled = true;
                            array[1].disabled = true;
                        } else {
                            array[0].disabled = false;
                            array[1].disabled = false;
                        }

                        // Si el valor del primer o segundo input es mayor que 0, deshabilitar el tercero
                        if ((array[0] && array[0].value > 0) || (array[1] && array[1].value > 0)) {
                            array[2].disabled = true;
                        } else {
                            array[2].disabled = false;
                        }
                    });
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
                        var tipoEntradaInput = document.createElement('input');
                        tipoEntradaInput.type = 'hidden';
                        tipoEntradaInput.name = 'tipoEntrada[]';
                        
                        // Asignar el valor correcto a tipoEntrada en función de tipoEntradaId
                        var tipoEntrada = (tipoEntradaId === '1' || tipoEntradaId === '2') ? 0 : 1;
                        tipoEntradaInput.value = tipoEntrada;
                        console.log(tipoEntradaInput);
                        form.appendChild(tipoEntradaInput);

                        var inputTipoEntrada = document.createElement('input');
                        inputTipoEntrada.type = 'hidden';
                        inputTipoEntrada.name = 'entradas[]'; // Utiliza un array para enviar múltiples entradas del mismo tipo
                        inputTipoEntrada.value = tipoEntradaId;
                        form.appendChild(inputTipoEntrada);
                    }
                }
            });

            // Crear y enviar la solicitud AJAX
            var xhr = new XMLHttpRequest();
                xhr.onreadystatechange = function() {
                    if (xhr.readyState === 4) {
                        var response = JSON.parse(xhr.responseText);
                        if (xhr.status === 200) {
                            // Verificar si la inserción en el carrito fue exitosa
                            if (response.success === true) {
                                // Mostrar un SweetAlert con el mensaje de éxito
                                Swal.fire({
                                    icon: 'success',
                                    title: '¡Éxito!',
                                    text: 'Entradas insertadas en el carrito con éxito',
                                    confirmButtonText: 'Aceptar'
                                }).then((result) => {
                                    // Redirigir a la vista de carrito si el usuario hace clic en "Aceptar"
                                    if (result.isConfirmed) {
                                        // Resetear contadores
                                        if (contador1 || contador2) {
                                            contador1.value = 0;
                                            contador2.value = 0;
                                        }

                                        if (contador3) {
                                            contador3.value = 0;
                                        }

                                        // Llamar a la función cargarCarrito
                                        cargarCarrito();
                                    }
                                });
                            } else {
                                // Mostrar un SweetAlert con el mensaje de error
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error',
                                    text: response.error || 'Hubo un error al insertar las entradas en el carrito',
                                    confirmButtonText: 'Aceptar'
                                });
                            }
                        } else {
                            // Mostrar un SweetAlert con el mensaje de error del servidor
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: response.error || 'Hubo un error al procesar la solicitud',
                                confirmButtonText: 'Aceptar'
                            });
                        }
                    }
                };

                // Manejar errores de la solicitud AJAX
                xhr.onerror = function() {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Hubo un error de red al realizar la solicitud',
                        confirmButtonText: 'Aceptar'
                    });
                };

                // Abrir la conexión y enviar la solicitud AJAX
                xhr.open('POST', form.action, true);
                xhr.setRequestHeader('X-CSRF-TOKEN', '{{ csrf_token() }}');
                xhr.send(new FormData(form));
            }


            function mostrarCarrito(carrito) {
            var carritoContainer = document.getElementById('carritoContainer');
            carritoContainer.classList.add('carrito-container');
            var precioTotal = 0; // Inicializar el precio total
            // var nombreCancion = "";



            carritoContainer.innerHTML = ''; // Limpiar el contenido previo del carrito

            // Crear un elemento para el título del carrito
            var carritoTitle = document.createElement('h2');
            carritoTitle.textContent = 'Carrito';
            carritoContainer.appendChild(carritoTitle);

            if (carrito.length > 0) {
                // Crear un div para contener el contenido del carrito
                var carritoContent = document.createElement('div');
                carritoContent.classList.add('carrito-content');

                // Crear un div para cada producto y agregar el botón de eliminación
                carrito.forEach(function(item) {
                    var productoElement = document.createElement('div');
                    var productName = item.nombre_producto;
                    var precio = parseFloat(item.precio_total); // Convertir el precio a un número decimal
                    var eventoName = item.nombre_evento;

                    if (item.id_producto < 3) {
                        productoElement.textContent = productName + ' de ' + eventoName + ' - ' + precio.toFixed(
                            2) + ' ';
                    } else {
                        productoElement.textContent = productName + ' de ' + eventoName + ' - ' + precio.toFixed(
                            2) + ' ';
                    }

                    var eliminarButton = document.createElement('button');
                    eliminarButton.innerHTML = '<i class="fas fa-trash-alt"></i>';
                    eliminarButton.addEventListener('click', function() {
                        console.log(item.id);
                        eliminarProductoCarrito(item.id);
                    });

                    productoElement.appendChild(eliminarButton);
                    carritoContent.appendChild(productoElement);
                    precioTotal += precio; // Sumar el precio al precio total
                });



                // Agregar el contenido del carrito al contenedor del carrito
                carritoContainer.appendChild(carritoContent);

                // Crear un espacio en blanco entre elementos
                var espacioBlanco = document.createElement('div');
                espacioBlanco.style.height = '20px'; // Establecer la altura del espacio en blanco según sea necesario
                carritoContainer.appendChild(espacioBlanco);

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
                var textoCancion = document.createElement('div');
                textoCancion.innerHTML =
                    '<p>¿Quieres elegir una canción?</p> <i id="infoIcon" class="fas fa-info-circle" style="cursor: pointer;"></i>';
                carritoContainer.appendChild(textoCancion);



                // Agregar el evento click al elemento infoIcon
                var infoIcon = document.getElementById('infoIcon');
                infoIcon.addEventListener('click', function() {
                    // Mostrar SweetAlert cuando se haga clic en el ícono de información
                    Swal.fire({
                        title: 'Información Importante',
                        text: 'La canción se mandará a revisar, si no es apropiada no se añadirá a la playlist del evento. La decisión será mandada por correo. Grácias por la atención.',
                        icon: 'info',
                        confirmButtonText: 'Aceptar'
                    });
                });

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
                infoDiv.style.margin =
                '20px auto'; // Centrar horizontalmente con margen automático y añadir margen superior
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
                var nombreCancion;

                // Asegúrate de definir cancionesSeleccionadas antes de intentar usarla
                var cancionesSeleccionadas = JSON.parse(localStorage.getItem('cancionesSeleccionadas')) || [];

                checkbox.addEventListener('change', function() {
                    if (checkbox.checked) {
                        // Mostrar SweetAlert con inputs para ingresar el nombre de la canción y del artista
                        Swal.fire({
                            title: 'Ingrese el nombre de la canción y del artista',
                            html: '<input id="nombreCancion" name="nombreCancion" class="swal2-input" placeholder="Nombre de la canción">' +
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
                                // Ahora que la variable cancionesSeleccionadas está definida, puedes usarla sin problemas
                                cancionesSeleccionadas.push({
                                    nombre: nombreCancion,
                                    artista: nombreArtista
                                });
                                // localStorage.setItem('cancionesSeleccionadas', JSON.stringify(
                                //     cancionesSeleccionadas));
                                insertarCancionEnBaseDeDatos(nombreCancion, nombreArtista);
                            } else {
                                // Desmarcar el checkbox si se cancela la operación
                                checkbox.checked = false;
                            }
                        });
                    }
                });

                console.log(carrito)
                var updateButton = document.createElement('button');
                updateButton.id = 'checkout';
                updateButton.textContent = 'Comprar Ahora';
                updateButton.addEventListener('click', function() {
                    // Almacenar los datos relevantes en el almacenamiento local
                    localStorage.setItem('carrito', JSON.stringify(carrito));
                    localStorage.setItem('precioTotal', precioTotal);

                    // Obtener el nombre de la discoteca
                    var discotecaNombre = document.getElementById('discoteca').value;

                    // Redirigir a la pÃ¡gina de checkout incluyendo el nombre de la discoteca en la URL
                    window.location.href = '/cliente/checkout?discoteca=' + encodeURIComponent(
                        discotecaNombre);


                });

                carritoContainer.appendChild(updateButton);

                // Crear un enlace que lleve a la página web de Last.fm
                var enlaceLastFM = document.createElement('a');
                enlaceLastFM.setAttribute('href', 'https://www.last.fm/');
                enlaceLastFM.textContent = 'Visitar Last.fm y buscar las canciones disponibles.';
                enlaceLastFM.setAttribute('target', '_blank'); // Abre el enlace en una nueva pestaña
                carritoContainer.appendChild(enlaceLastFM);
            } else {
                // Si el carrito está vacío, mostrar un mensaje indicando que está vacío
                carritoContainer.textContent = 'El carrito está vacío';
            }
        }

        // Función para mostrar las bonificaciones en la interfaz de usuario
        function mostrarBonificaciones(data) {
            var bonificacionesContainer = document.getElementById('bonificaciones-container');

            // Limpiar el contenedor de bonificaciones antes de mostrar las nuevas bonificaciones
            bonificacionesContainer.innerHTML = '';

            // Agregar título de bonificaciones disponibles
            var bonificacionesTitle = document.createElement('h2');
            bonificacionesTitle.textContent = 'Bonificaciones disponibles';
            bonificacionesContainer.appendChild(bonificacionesTitle);

            // Verificar si se recibieron bonificaciones
            if (data && data.length > 0) {
                // Iterar sobre cada bonificación y crear un elemento para mostrarla
                data.forEach(function(bonificacion) {
                    // Crear un elemento de bonificación
                    var bonificacionDiv = document.createElement('div');
                    bonificacionDiv.textContent = bonificacion.name;

                    // Agregar el elemento de bonificación al contenedor
                    bonificacionesContainer.appendChild(bonificacionDiv);
                });
            } else {
                // Si no se recibieron bonificaciones, mostrar un mensaje indicando que no hay bonificaciones disponibles
                var noBonificacionesMessage = document.createElement('p');
                noBonificacionesMessage.textContent = 'No hay bonificaciones disponibles';

                // Agregar el mensaje al contenedor
                bonificacionesContainer.appendChild(noBonificacionesMessage);
            }
        }

        async function obtenerCaratula(cancion, artista) {
            try {
                const apiKey = 'ed420dfe24230d66234f98cdc646d658';
                const url =
                    `http://ws.audioscrobbler.com/2.0/?method=track.getInfo&api_key=${apiKey}&artist=${encodeURIComponent(artista)}&track=${encodeURIComponent(cancion)}&format=json`;

                const response = await fetch(url);
                const data = await response.json();

                if (data.error) {
                    throw new Error(data.message);
                }

                const {
                    name: nombreCancion,
                    artist: {
                        name: nombreArtista
                    }
                } = data.track;


                return {
                    nombreCancion,
                    nombreArtista
                };
            } catch (error) {
                console.error('Error al obtener la carátula:', error);
                return null;
            }
        }


        // Función para insertar la canción en la base de datos
        async function insertarCancionEnBaseDeDatos(nombreCancion, nombreArtista) {
            // Obtener el token CSRF de la etiqueta meta en el HTML
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            try {
                // Obtener la carátula usando await
                const caratula = await obtenerCaratula(nombreCancion, nombreArtista);

                // Realizar una solicitud HTTP POST para insertar la canción en la base de datos
                const response = await fetch('/cliente/insertarCancion', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken // Añadir el token CSRF como encabezado
                    },
                    body: JSON.stringify({
                        nombreCancion: caratula.nombreCancion,
                        nombreArtista: caratula.nombreArtista
                    })
                });

                const data = await response.json();

                if (data.success) {
                    // Si la inserción es exitosa, muestra un mensaje de éxito
                    Swal.fire('¡Canción guardada!', data.message, 'success');
                    cargarCarrito();
                } else {
                    // Si hay un error, muestra un mensaje de error
                    Swal.fire('No se ha podido insertar la canción', data.message, 'error');
                }
            } catch (error) {
                console.error('Error:', error);
                // Si hay un error, muestra un mensaje de error
                Swal.fire('Error',
                    'Comprueba el nombre de la cancion o el artista agregado, no se a encontrado la cancion.',
                    'error');
            }
        }



        function eliminarProductoCarrito(productoId) {
            var xhr = new XMLHttpRequest();
            xhr.onreadystatechange = function() {
                if (xhr.readyState === XMLHttpRequest.DONE) {
                    if (xhr.status === 200) {
                        var response = JSON.parse(xhr.responseText);
                        if (response.success) {
                            // Eliminar el producto de localStorage
                            eliminarProductoDeLocalStorage(productoId);
                            // Recargar el carrito
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

        function eliminarProductoDeLocalStorage(productoId) {
            var carrito = JSON.parse(localStorage.getItem('carrito')) || [];
            carrito = carrito.filter(function(producto) {
                return producto.id !== productoId;
            });
            localStorage.setItem('carrito', JSON.stringify(carrito));
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

        // Función para cargar el carrito
        function cargarBonificaciones() {
            var xhr = new XMLHttpRequest();

            xhr.onreadystatechange = function() {
                if (xhr.readyState === XMLHttpRequest.DONE) {
                    if (xhr.status === 200) {
                        mostrarBonificaciones(JSON.parse(xhr.responseText));
                    } else {
                        console.error('Error al cargar el carrito:', xhr.status);
                    }
                }
            };

            xhr.open('GET', '/cliente/bonificaciones', true);
            xhr.send();
        }
    </script>

</body>

</html>
