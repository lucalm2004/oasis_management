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
            <li>{{ $entrada->descripcion }} - {{ $entrada->precio }} 
                <button onclick="comprarEntrada({{ $entrada->id }})">Comprar</button>
            </li>
        @endforeach
    </ul>
@else
    <p>No hay entradas disponibles para este evento.</p>
@endif

<!-- Botón de volver -->
<a href="{{ route('cliente.eventos', $evento->discoteca->id) }}">Volver a los eventos de {{ $evento->discoteca->name }}</a>


<script>
    var entradasTipo3Seleccionadas = 0; // Contador de entradas de tipo 3 seleccionadas

    function comprarEntrada(tipoEntrada) {
        // Si es una entrada de tipo 3 y ya se ha seleccionado una, no se puede comprar más
        if (tipoEntrada === 3 && entradasTipo3Seleccionadas >= 1) {
            alert('Solo puedes comprar una entrada de este tipo.');
            return;
        }

        // Aquí puedes agregar la lógica para realizar la compra de la entrada con el ID proporcionado
        window.location.href = "{{ route('cliente.carrito') }}";

        // Si es una entrada de tipo 3, incrementa el contador
        if (tipoEntrada === 3) {
            entradasTipo3Seleccionadas++;
        }
    }
</script>
</body>
</html>
