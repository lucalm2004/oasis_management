<!-- discotecas/eventos.blade.php -->

<!DOCTYPE html>
<html>

<head>
    <title>Eventos de {{ $discoteca->name }}</title>
</head>

<body>

    <h1>Eventos de {{ $discoteca->name }}</h1>

    <!-- Detalles de la discoteca -->
    <h2>Detalles de la Discoteca</h2>
    <p>Nombre: {{ $discoteca->name }}</p>
    <p>Dirección: {{ $discoteca->direccion }}</p>
    <p>Capacidad: {{ $discoteca->capacidad }}</p>
    <!-- Agrega más detalles según sea necesario -->

    @if ($eventos->isNotEmpty())
        <h2>Lista de Eventos</h2>
        @foreach ($eventos as $evento)
            <a href="{{ route('cliente.entradas', $evento->id) }}">{{ $evento->name }}</a> -
            {{ $evento->fecha_inicio }}
        @endforeach
    @else
        <p>No hay eventos disponibles para esta discoteca.</p>
    @endif

    <!-- Botón de volver -->
    <br>
    <br>
    <a href="{{ route('cliente.discoteca') }}">Volver a la lista de discotecas</a>

</body>

</html>
