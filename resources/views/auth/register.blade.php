<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Oasis Management - Login</title>
    <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/normalize/5.0.0/normalize.min.css">
    <link rel='stylesheet'
        href='https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&amp;display=swap'>
    <link rel="stylesheet" href="{{ asset('css/login.css') }}">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/animate.css/3.2.0/animate.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <script src="https://kit.fontawesome.com/8e6d3dccce.js" crossorigin="anonymous"></script>
    <link rel="shortcut icon" href="{{ asset('img/logo.png') }}" type="image/x-icon">
    {{-- <script src="https://kit.fontawesome.com/a7409f463b.js" crossorigin="anonymous"></script> --}}
    <style>
        #password {
            width: 85%;
        }

        #password_confirmation {
            width: 85%;
        }

        .screen-1 .login {
            /* height: 40px!important; */
            width: 50% !important;
        }

        .gsi-material-button .gsi-material-button-contents {
            font-size: 12px !important;
        }

        .gsi-material-button {
            height: 45px !important;
        }

        #eye_show1 {
            cursor: pointer;
        }

        #eye_show2 {
            cursor: pointer;
        }

        #animatedModal {
            background-color: rgba(57, 190, 185, 0.0) !important
        }
        .toast-message{
            margin-left: 5%!important;
        }
        #help{
            position: absolute;
    top: 95vh;
    left: 95vw;
    font-size: 30px;
    /* background-color: white; */
    /* padding: 2%; */
    /* border-radius: 50%; */
        }
    </style>
</head>

<body>

    <i id="help" class="fa-sharp fa-solid fa-circle-question"></i>


    <div class="screen-1">
        <h2>Oasis | Register</h2>

        <img src="{{ asset('img/redondoLetrasNegras.png') }}" alt="" class="logo">
        <form method="POST" action="{{ route('register') }}">
            @csrf
            <div class="email">
                <label for="email" style="border-bottom: 1px solid;;opacity: 50%">Nombre y Apellidos</label>
                <div class="sec-2">
                    <i class="fa-regular fa-pen-to-square"></i>
                    <x-text-input id="name" class="block mt-1 w-full" type="text" name="name"
                        :value="old('name')" required autofocus autocomplete="name" />
                </div>
                <x-input-error :messages="$errors->get('name')" class="mt-2" />

            </div>
            <div class="password">
                <label for="email" style="border-bottom: 1px solid;;opacity: 50%">Email</label>
                <div class="sec-2">
                    <ion-icon name="mail-outline"></ion-icon>
                    <x-text-input id="email" class="block mt-1 w-full" type="email" name="email"
                        :value="old('email')" required autocomplete="username" />
                </div>
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>

            <div class="password">
                <label for="password" style="border-bottom: 1px solid;opacity: 50%">Password</label>
                <div class="sec-2">
                    <ion-icon name="lock-closed-outline"></ion-icon>
                    <x-text-input id="password" class="block mt-1 w-full" type="password" name="password" required
                        autocomplete="new-password" />
                    <ion-icon class="show-hide" id="eye_show1" name="eye-outline"></ion-icon>
                </div>
                <x-input-error :messages="$errors->get('password')" class="mt-2" />

            </div>

            <div class="password">
                <label for="password" style="border-bottom: 1px solid;opacity: 50%">Repet Password</label>
                <div class="sec-2">
                    <ion-icon name="lock-closed-outline"></ion-icon>
                    <x-text-input id="password_confirmation" class="block mt-1 w-full" type="password"
                        name="password_confirmation" required autocomplete="new-password" />

                    <ion-icon class="show-hide" id="eye_show2" name="eye-outline"></ion-icon>
                </div>
                <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />

            </div>
            <div style="display: flex; gap:2%">

                <button class="login">Sign in</button>
                <button onclick="window.location.href='/google-auth/redirect'" class="gsi-material-button">
                    <div class="gsi-material-button-state"></div>
                    <div class="gsi-material-button-content-wrapper">
                        <div class="gsi-material-button-icon">
                            <svg version="1.1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 48 48"
                                xmlns:xlink="http://www.w3.org/1999/xlink" style="display: block;">
                                <path fill="#EA4335"
                                    d="M24 9.5c3.54 0 6.71 1.22 9.21 3.6l6.85-6.85C35.9 2.38 30.47 0 24 0 14.62 0 6.51 5.38 2.56 13.22l7.98 6.19C12.43 13.72 17.74 9.5 24 9.5z">
                                </path>
                                <path fill="#4285F4"
                                    d="M46.98 24.55c0-1.57-.15-3.09-.38-4.55H24v9.02h12.94c-.58 2.96-2.26 5.48-4.78 7.18l7.73 6c4.51-4.18 7.09-10.36 7.09-17.65z">
                                </path>
                                <path fill="#FBBC05"
                                    d="M10.53 28.59c-.48-1.45-.76-2.99-.76-4.59s.27-3.14.76-4.59l-7.98-6.19C.92 16.46 0 20.12 0 24c0 3.88.92 7.54 2.56 10.78l7.97-6.19z">
                                </path>
                                <path fill="#34A853"
                                    d="M24 48c6.48 0 11.93-2.13 15.89-5.81l-7.73-6c-2.15 1.45-4.92 2.3-8.16 2.3-6.26 0-11.57-4.22-13.47-9.91l-7.98 6.19C6.51 42.62 14.62 48 24 48z">
                                </path>
                                <path fill="none" d="M0 0h48v48H0z"></path>
                            </svg>
                        </div>
                        <span class="gsi-material-button-contents">Sign in with Google</span>
                        <span style="display: none;">Sign in with Google</span>
                    </div>
            </div>
            </button>
            <div class="footer"><span onclick="window.location.href='{{ route('login') }}'">Already
                    Registred?</span><span id="demo01" href="#animatedModal">¿Tienes una discoteca?</span></div>
    </div>
    </form>

    <!--Call your modal-->

    <!--DEMO01-->
    <div id="animatedModal">
        <!--THIS IS IMPORTANT! to close the modal, the class name has to match the name given on the ID  class="close-animatedModal" -->
        <div class="modal-content">
            <div id="modal" class="modal">
                <div class="container">
                    <div class="close-animatedModal" style="margin-left:95%;/* margin-top:2%; */"><svg id="cerrar"
                            width="24" height="24" viewBox="0 0 24 24" fill="none"
                            xmlns="http://www.w3.org/2000/svg" class="ltr-4z3qvp e1svuwfo1" data-name="X"
                            aria-labelledby="preview-modal-81266975" data-uia="previewModal-closebtn" role="button"
                            aria-label="close" tabindex="0"
                            style="
                        /* position: absolute; */
                    ">
                            <path fill-rule="evenodd" clip-rule="evenodd"
                                d="M10.5858 12L2.29291 3.70706L3.70712 2.29285L12 10.5857L20.2929 2.29285L21.7071 3.70706L13.4142 12L21.7071 20.2928L20.2929 21.7071L12 13.4142L3.70712 21.7071L2.29291 20.2928L10.5858 12Z"
                                fill="currentColor"></path>
                        </svg></div>
                    <div class="text">

                        Unete a Oasis | Management </div>
                    <form id="registerForm" action="{{ route('registerGestor') }}" method="POST">
                        @csrf

                        <div class="form-row">
                            <div class="input-data">
                                <input type="text" name="nombre_completo" required>
                                <div class="underline"></div>
                                <label for="">Nombre completo</label>
                            </div>
                            <div class="input-data">
                                <input type="text" name="dni_nie" required>
                                <div class="underline"></div>
                                <label for="">DNI/NIE</label>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="input-data">
                                <input type="text" name="email" required>
                                <div class="underline"></div>
                                <label for="">Email</label>
                            </div>
                            <div class="input-data">
                                <input type="password" name="password" required>
                                <div class="underline"></div>
                                <label for="">Password</label>
                            </div>
                            <div class="input-data">
                                <input type="password" name="password_confirmation" required>
                                <div class="underline"></div>
                                <label for="">Repetir Password</label>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="input-data">
                                <input type="text" name="discoteca" required>
                                <div class="underline"></div>
                                <label for="">Discoteca</label>
                            </div>
                            <div class="input-data">
                                <input type="text" name="direccion" required>
                                <div class="underline"></div>
                                <label for="">Dirección</label>
                            </div>
                            <div class="input-data">
                                <div class="dropdown">
                                    <div class="select">
                                        <span id="ciudadSpan">Ciudad</span>
                                        <i class="fa fa-chevron-left"></i>
                                    </div>
                                    <input type="hidden" id="ciudadInput" name="ciudad" value="">
                                    <input type="hidden" name="gender">
                                    <input type="hidden" name="ciudad_id" id="ciudad_id_input">
                                    <ul id="ciudadesSelect" class="dropdown-menu">
                                        <?php
                                            use App\Models\Ciudad;
                                            $ciudades = Ciudad::all();
                                            foreach ($ciudades as $ciudad): ?>
                                        <li value="<?php echo $ciudad->id; ?>"><?php echo $ciudad->name; ?></li>
                                        <?php endforeach; ?>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="form-row submit-btn">
                            <div class="input-data">
                                <div class="inner"></div>
                                <input id="submits" type="submit" value="submit">
                            </div>
                        </div>
                    </form>


                </div>
            </div>
        </div>
    </div>

    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
    <script src="animaciones/animatedModal.min.js"></script>
</body>

<script>
    // document.getElementById('submits').onclick = function(){

    // }
    $(document).ready(function() {
        $('#registerForm').submit(function(event) {
            event.preventDefault();

            $.ajax({
                url: $(this).attr('action'),
                method: "POST",
                data: $(this).serialize(),
                success: function(response) {
                    toastr.success('¡Registro exitoso!');
                    // Aquí puedes redirigir al usuario a otra página si es necesario
                },
                error: function(xhr) {
                    var errors = xhr.responseJSON.errors;
                    var errorList = '<ul>';
                    $.each(errors, function(index, error) {
                        errorList += '<li>' + error + '</li>';
                    });
                    errorList += '</ul>';
                    toastr.error(errorList, 'Errores', {
                        positionClass: 'toast-top-right',
                        timeOut: 5000 // Duración del mensaje en milisegundos (por ejemplo, 5000 = 5 segundos)
                    });
                }
            });
        });
    });

    $("#demo01").animatedModal();


    // var cerrar = document.getElementById('cerrar');

    // cerrar.onclick = function() {
    //     document.getElementById('modal').style.display = 'none'; 
    // };


    /*Dropdown Menu*/
    $('.dropdown').click(function() {
        $(this).attr('tabindex', 1).focus();
        $(this).toggleClass('active');
        $(this).find('.dropdown-menu').slideToggle(300);
        var ciudadSpan = document.getElementById("ciudadSpan").innerText;
        // Asignar el valor del span al input
        document.getElementById("ciudadInput").value = ciudadSpan;
    });
    $('.dropdown').focusout(function() {
        $(this).removeClass('active');
        $(this).find('.dropdown-menu').slideUp(300);
    });
    $('.dropdown .dropdown-menu li').click(function() {
        $(this).parents('.dropdown').find('span').text($(this).text());
        $(this).parents('.dropdown').find('input').attr('value', $(this).attr('id'));
    });
    /*End Dropdown Menu*/


    $('.dropdown-menu li').click(function() {
        var input = '<strong>' + $(this).parents('.dropdown').find('input').val() + '</strong>',
            msg = '<span class="msg">Hidden input value: ';
        $('.msg').html(msg + input + '</span>');
    });

    var eye_show1 = document.getElementById('eye_show1');
    var password = document.getElementById('password');

    // Manejador de evento para cambiar el tipo de contraseña
    eye_show1.addEventListener('click', function() {
        if (password.type === 'password') {
            password.type = 'text';
        } else {
            password.type = 'password';
        }
    });


    var eye_show2 = document.getElementById('eye_show2');
    var password2 = document.getElementById('password_confirmation');

    // Manejador de evento para cambiar el tipo de contraseña
    eye_show2.addEventListener('click', function() {
        if (password2.type === 'password') {
            password2.type = 'text';
        } else {
            password2.type = 'password';
        }
    });
</script>
