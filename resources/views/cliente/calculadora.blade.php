<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Oasis Managament - Love Calculator</title>
    <link rel="shortcut icon" href="{{ asset('img/logo.png') }}" type="image/x-icon">
    <link rel="stylesheet" href="{{ asset('css/calculadora.css') }}">
</head>
<body>
    <div class="card-container">
        <h2 id="result"></h2>
        <a href="#" class="hero-image-container">
            <img class="hero-image heartbeat" src="https://upload.wikimedia.org/wikipedia/commons/thumb/f/f1/Heart_coraz%C3%B3n.svg/1200px-Heart_coraz%C3%B3n.svg.png" alt="Heart"/>
        </a>
        <main class="main-content">
            <h1><a href="#">CALCULADORA DE AMOR</a></h1>
            <input type="text" id="maleName" class="animated-border-input" placeholder="Introduce un nombre..." />
            <br>
            <input type="text" id="femaleName" class="animated-border-input" placeholder="Introduce otro nombre..." />
            <button id="calculateButton" class="btn">Calcula</button>
            <button class="btn"><a href="{{route('chatify')}}" style="text-decoration: none">Volver</a></button>
        </main>
    </div>

    
      <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
      <script src="{{ asset('js/calculadora_amor.js') }}"></script>
</body>
</html>