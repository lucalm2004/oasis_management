<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://kit.fontawesome.com/e2c3124098.js" crossorigin="anonymous"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="{{asset('css/style.css')}}">
    <link rel="shortcut icon" href="{{ asset('img/logo.png') }}" type="image/x-icon">
    <title>Oasis Management - Mis Eventos</title>
 
</head>


<body>

    <header class="header">
      <div class="logo">
        <a href="{{ asset('principal') }}"><img src="{{ asset('img/logo_oasis.png') }}" alt="Imagen de logo"></a>
      <div class="nav-container">
        
        
        <ul class="nav-list" style="padding-top: 15px;">
        
          <form method="POST" action="{{ route('logout') }}" id="logout" style="float: right; padding-left: 300px;">
            @csrf

            <x-dropdown-link :href="route('logout')"
              onclick="event.preventDefault();
                                this.closest('form').submit();">
              {{ __('Log Out') }}
            </x-dropdown-link>
          </form>
        </ul>
      </div>
    </div>
         
    </header>
    
    <h1 style="text-align: center">Eventos</h1>
  <div class="container">

    <form action="" method="post" id="frmbusqueda">
        <div class="form-group">
            <i id="icono_buscar" class="fa-solid fa-magnifying-glass" style="color: #F5763B;"></i>
            <input type="text" name="buscar" id="buscar"  placeholder="Buscar..." class="form-control">
    </form>
    
    <i id="icono_fecha" class="fa-solid fa-calendar-days" style="color: #F5763B;"></i>
    <select class="form-control status_id" id="fecha_inc">
        <option value="0">Seleccione Fecha...</option>
        <option value="asc">Fecha ascendente</option>
        <option value="desc">Fecha descendente</option>
    </select>

    
</div>
<br>
<br>
    <table>
        <thead>
            <tr>
                <th>Evento</th>
                <th>DescripciÃ³n</th>
                <th>Flyer</th>
                <th>Fecha Inicio</th>
                <th>Fecha Final</th>
                <th>Dj</th>
                <th>Playlist</th>
                <th>Discoteca</th>
            </tr>
        </thead>
        <tbody id="resultado">

        </tbody>
    </table>
    <script src="{{ asset('js/eventos.js') }}"></script>
</div>
</body>

</html>