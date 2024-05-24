<!DOCTYPE html>
<html lang="en">

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Oasis Managament - Entrada</title>
    <!-- HTML2CANVAS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"
        integrity="sha512-BNaRQnYJYiPSqHHDb58B0yaPfCu+Wgds8Gp/gU33kqBtgNS4tSPHuGibyoeqMV/TJlSKda6FXzoEyYGjTe+vXA=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
        <link rel="shortcut icon" href="{{ asset('img/logo.png') }}" type="image/x-icon">
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
    
    </script>
    
    
</body>

</html>
