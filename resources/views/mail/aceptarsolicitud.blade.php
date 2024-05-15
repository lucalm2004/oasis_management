
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body style="background-color: #10101a; font-family: 'Open Sans', sans-serif; font-optical-sizing: auto; font-style: normal; font-variation-settings: 'wdth' 100; margin: 0; padding: 0;">

    <div class="container" style="background-color: #F5763B; text-align: center;">
        <img src="https://i.pinimg.com/736x/6b/97/41/6b9741bde0e801b7cc247c34d9407f16.jpg" alt="Imagen de logo" style="max-width: 20%; max-height: 100%; border-radius: 50%; margin-top: 20px; margin-bottom: 20px;">
    </div>
    <div class="contenido" style=" padding: 4%; padding-top: 2%; margin-top: 2%; text-align: justify; background-color: white; margin-right: 2%; margin-left: 2%; border-radius: 5px; z-index: 99999;">
        <h1 style="font-size: 50px; text-align: center; color: black">Hola {{ $user_correo->name }}!</h1>
        @if ($user_correo->id_rol == 3)
        <p style="text-align: justify; color: black">Su solicitud de registar el club {{$discoteca_correo->name}} en Oasis, ha sido aceptada. ¡Ahora puedes acceder a todos nuestros servicios y funcionalidades!</p>
       @else
           <p style="text-align: justify; color: black">Su solicitud de demanda de empleo en el club {{$discoteca_correo->name}}, ha sido aceptada. ¡Ahora puedes acceder a todos nuestros servicios y funcionalidades!</p>
       @endif
        
        <a href="http://oasismanagement.com:8000/login" style="display: inline-block; text-decoration: none; width: 100%;"><button class="acceder" style="padding: 1em; background: #ff5500; color: white; border: none; border-radius: 30px; font-weight: 600; margin-top: 5%; margin-bottom: 5%; font-size: 14px; display: block; margin-left: auto; margin-right: auto;">Accede a oasis</button></a>
        <p style="text-align: justify; color: black">Atentamente,</p>
        <p style="text-align: justify; color: black">El equipo de Oasis</p>
    </div>

    <div class="ayuda" style="margin-top: 5%; padding: 4%; padding-top: 2%; margin-bottom: 100px; text-align: justify; background-color: white; margin-right: 2%; margin-left: 2%; border-radius: 5px; text-align: center;">
        <h2 style="font-size: 30px; color: black">Necesitas ayuda?</h2>
        <a href="http://oasismanagement.com:8000/contacto" style="text-decoration: none; color: #ff5500; font-size: 20px;">Estamos aqui listos para hablar</a>
    </div>

    <style>
        @media only screen and (max-width: 600px) {
            .contenido {
                margin-right: 0;
                margin-left: 0;
            }
        }
    </style>
</body>

</html>
