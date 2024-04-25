<!-- entradas/disponibles.blade.php -->

<!DOCTYPE html>
<html>
<head>
    <title>Entradas Disponibles</title>
</head>
<body>

<h1>Entradas Disponibles</h1>

<!-- Detalles del evento -->
<h2>Detalles del Evento</h2>
<p>Nombre: {{ $evento->name }}</p>
<p>Fecha de Inicio: {{ $evento->fecha_inicio }}</p>
<!-- Agrega más detalles según sea necesario -->

@if ($tiposEntradas->isNotEmpty())
    <h2>Lista de Entradas Disponibles</h2>
    <ul>
        @foreach ($tiposEntradas as $entrada)
            <li>{{ $entrada->nombre }} - {{ $entrada->precio }}</li>
        @endforeach
    </ul>
@else
    <p>No hay entradas disponibles para este evento.</p>
@endif

<!-- Botón de volver -->
<a href="{{ route('cliente.eventos', $evento->discoteca->id) }}">Volver a los eventos de {{ $evento->discoteca->name }}</a>

</body>
</html>
