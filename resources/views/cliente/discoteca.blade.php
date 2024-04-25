<!-- discotecas/index.blade.php -->

<!DOCTYPE html>
<html>
    <head>
        <title>Lista de Discotecas</title>
        <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
        <style>
            #mapid { height: 400px; }
        </style>
    </head>
<body>

<h1>Lista de Discotecas</h1>

<!-- Botón para introducir código -->
<button onclick="mostrarPopup()">Introducir Código</button>

<!-- Marcador de puntos del usuario -->
<span id="puntosUsuario" style="font-weight: bold; color: blue;">{{ $puntosUsuario }}</span> puntos

@foreach ($todasdiscotecas as $discoteca)
    <p><a href="{{ route('cliente.eventos', $discoteca->id) }}">{{ $discoteca->name }}</a></p>
@endforeach



<!-- Popup -->
<div id="popup" style="display: none; position: fixed; top: 50%; left: 50%; transform: translate(-50%, -50%); background-color: white; padding: 20px; border: 1px solid black;">
    <h2>Introducir Código</h2>
    <form action="" method="POST">
        @csrf
        <label for="codigo">Código:</label><br>
        <input type="text" id="codigo" name="codigo"><br><br>
        <button type="submit">Enviar</button>
    </form>
    <button onclick="cerrarPopup()">Cerrar</button>
</div>

<div id="mapid"></div>

<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>

<script>
    var map = L.map('mapid').setView([40.505, -100.09], 4); // Define la posición inicial del mapa y el nivel de zoom

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
    }).addTo(map); // Añade una capa de mapa base

    // Itera sobre las discotecas y agrega marcadores al mapa
    @foreach ($todasdiscotecas as $discoteca)
        L.marker([{{ $discoteca->lat }}, {{ $discoteca->long }}]).addTo(map)
            .bindPopup('{{ $discoteca->name }}');
    @endforeach
</script>

<script>
    function mostrarPopup() {
        document.getElementById('popup').style.display = 'block';
    }

    function cerrarPopup() {
        document.getElementById('popup').style.display = 'none';
    }
</script>

</body>
</html>
