<!DOCTYPE html>
<html lang="en">

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Take Screenshots With Javascript</title>
    <!-- HTML2CANVAS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"
        integrity="sha512-BNaRQnYJYiPSqHHDb58B0yaPfCu+Wgds8Gp/gU33kqBtgNS4tSPHuGibyoeqMV/TJlSKda6FXzoEyYGjTe+vXA=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
        <meta name="csrf-token" content="{{ csrf_token() }}">

    <style>
        * {
            padding: 0;
            margin: 0;
            box-sizing: border-box;
        }

        .hide {
            display: none;
        }
    </style>
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet" />
    <!-- Stylsheet -->
    <link rel="stylesheet" href="style.css" />
</head>

<body>
    <div class="wrapper">
        <div id="container">
            <?php echo $_POST['html']?>
        </div>
    </div>
   

    <!-- Script -->
    <script>
        window.onload = async () => {
            const container = document.getElementById("container");
            
// ObtÃ©n las dimensiones del contenedor
            const containerWidth = container.offsetWidth;
            const containerHeight = container.offsetHeight;
            
            // Calcula las coordenadas para capturar desde el centro
            const startX = containerWidth / 2 - 215; // 400/2 - 200 = 0 (desde el centro)
            // const startY = containerHeight / 2 - 300; // 600/2 - 300 = 0 (desde el centro)
            
            // Captura la imagen desde el centro
            const canvas = await html2canvas(container, { width: 430, height: 800, x: startX });            
            const imageData = canvas.toDataURL();
    
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    
            fetch('/capture-screenshot', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken // Incluir el token CSRF en el encabezado
                },
                body: JSON.stringify({ capturedImage: imageData }),
            })
            .then(response => response.json())
            .then(data => console.log(data))
            .catch(error => console.error('Error:', error));
        };
    </script>
    
    
</body>

</html>
