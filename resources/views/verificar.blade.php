<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.9.2/html2pdf.bundle.min.js"></script>
    <script src="https://kit.fontawesome.com/a7409f463b.js" crossorigin="anonymous"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


    
</head>

<body style="background-color: #10101a; font-family: 'Open Sans', sans-serif; font-optical-sizing: auto; font-style: normal; font-variation-settings: 'wdth' 100; margin: 0; padding: 0;">

    <div class="container" style="background-color: #F5763B; text-align: center; margin-right: 25%; margin-left: 25%; margin-top: 10px; border-radius: 10px;">
        <img src="{{asset('img/logo.png')}}" alt="" style="width: 10%; padding: 1%;">
       
          
    </div>
    <div class="contenido" style="padding: 1% 4%; margin-top: 1%; text-align: justify; background-color: white; margin-right: 25%; margin-left: 25%; border-radius: 5px;">
        <h1 style="font-size: 50px; text-align: center;">VERIFICACIÓN DE ENTRADA</h1>
        
        
          
    <div class="inputs-container" style="display: flex; flex-wrap: wrap; margin-top: 20px; justify-content: space-between; gap: 10px;">
        <div class="input-column" style="flex: 1; padding: 10px;">
            <label for="discoteca" style="display: block; text-align: center; font-weight: bold;">Discoteca</label>
            <input type="text" id="discoteca" value="<?php echo $_GET['discoteca']?>" style="width: 100%; padding: 10px; margin-bottom: 10px; border-radius: 5px; text-align: center;" disabled>
            
            <label for="precioTotal" style="display: block; text-align: center; font-weight: bold;">Precio Total</label>
            <input type="text" id="precioTotal" value="<?php echo $_GET['precioTotal']?> €" style="width: 100%; padding: 10px; margin-bottom: 10px; border-radius: 5px; text-align: center;" disabled>
            
            <label for="codigo" style="display: block; text-align: center; font-weight: bold;">Código</label>
            <input type="text" id="codigo" value="<?php echo $_GET['codigo']?>" style="width: 100%; padding: 10px; margin-bottom: 10px; border-radius: 5px; text-align: center;" disabled>
        </div>
        <div class="input-column" style="flex: 1; padding: 10px;">
            <label for="nombreEvento" style="display: block; text-align: center; font-weight: bold;">Nombre del Evento</label>
            <input type="text" id="nombreEvento" value="<?php echo $_GET['nombreEvento']?>" style="width: 100%; padding: 10px; margin-bottom: 10px; border-radius: 5px; text-align: center;" disabled>
            
            <label for="totalEntradas" style="display: block; text-align: center; font-weight: bold;">Total de Entradas</label>
            <input type="text" id="totalEntradas" value="<?php echo $_GET['totalEntradas']?>" style="width: 100%; padding: 10px; margin-bottom: 10px; border-radius: 5px; text-align: center;" disabled>
            
            <label for="dia" style="display: block; text-align: center; font-weight: bold;">Día</label>
            <input type="text" id="dia" value="<?php echo $_GET['dia']?>" style="width: 100%; padding: 10px; margin-bottom: 10px; border-radius: 5px; text-align: center;" disabled>
        </div>
    </div>
    
    </div>
    

    <div class="ayuda" style="margin-top: 1%; padding: 4%; padding-top: 1%; padding-bottom: 1%; margin-bottom: 30px; text-align: justify; background-color: white; margin-right: 25%; margin-left: 25%; border-radius: 5px; text-align: center;">
        <h2 style="font-size: 30px;">¿Quieres verificar la entrada?</h2>
        <a id="verificarEntrada" style="display: inline-block; text-decoration: none; width: 100%;"><button
            class="acceder"
            style="padding: 1em; background: #ff5500; cursor: pointer; color: white; border: none; border-radius: 30px; font-weight: 600; margin-top: 5%; margin-bottom: 5%; font-size: 14px; display: block; margin-left: auto; margin-right: auto;">Verificar entrada</button></a>    </div>
</body>

<script>
    var verificarEntrada = document.getElementById("verificarEntrada");
verificarEntrada.addEventListener("click", function() {
    var csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    var codigo = document.getElementById("codigo").value;
    var discoteca = document.getElementById("discoteca").value;
    var precioTotal = document.getElementById("precioTotal").value;
    var nombreEvento = document.getElementById("nombreEvento").value;
    var totalEntradas = document.getElementById("totalEntradas").value;
    var dia = document.getElementById("dia").value;

    fetch('revisarentrada', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken
        },
        body: JSON.stringify({
            codigo: codigo,
            discoteca: discoteca,
            precioTotal: precioTotal,
            nombreEvento: nombreEvento,
            totalEntradas: totalEntradas,
            dia: dia
        })
    })
    .then(response => {
        if (!response.ok) {
            Swal.fire({
                title: "Ha ocurrido un error",
                text: "Error al aceptar el usuario",
                icon: "error"
            });
            return;
        }
        return response.json();
    })
    .then(data => {
        console.log(data);

        if (data.success) {
            Swal.fire({
                title: "Aceptado",
                text: "La entrada está correcta.",
                icon: "success"
            }).then(() => {
                mostrarSolicitud();
                listarPersonal('');
            });
        } else {
            Swal.fire({
                title: "¡Revisa la entrada!",
                text: data.message,
                icon: "error"
            });
        }
    })
    .catch(error => console.error('Error al revisar la entrada:', error));
});


</script>

</html>