
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Oasis Management - Administrar Eventos</title>
  <!-- Se ha de añadir el token para poder usarlo en el formdata de AJAX -->
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">
  <link href="{{ asset('css/admin.css') }}" rel="stylesheet">
  <link rel="shortcut icon" href="{{ asset('img/logo.png') }}" type="image/x-icon">
  <script src="https://kit.fontawesome.com/e2c3124098.js" crossorigin="anonymous"></script>
</head>
<body>
  <header class="header">
    <div class="logo">
      <a href="{{ asset('principal') }}"><img src="{{ asset('img/logo_oasis.png') }}" alt="Imagen de logo"></a>
      <div class="nav-container">
        <ul class="nav-list" style="padding-top: 15px;">
          <a href="{{ route('admin.crudusuarios') }}">Usuarios | </a>
          <a href="{{ route('admin.cruddiscotecas') }}">Discotecas | </a>
          <a href="{{ route('admin.crudbonificaciones') }}">Bonificaciones | </a>
          <a href="{{ route('admin.crudciudades') }}">Ciudades | </a>
          <a href="{{ route('admin.crudeventos') }}">Eventos |</a>
          <a href="{{ route('admin.crudcanciones') }}">Canciones |</a>
          <a href="{{ route('admin.crudartistas') }}">Artistas |</a>
          <a href="{{ route('admin.crudentradas') }}">Registro Entradas</a>
          <i class="fa-regular fa-bell" style="color: #F5763B; cursor: pointer;" id="campana"></i>
          <a id="notificacion">0</a>
          <form method="POST" action="{{ route('logout') }}" id="logout" style="float: right; padding-left: 300px">
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

  <div>
    
    <table id="tablaSolicitudes" style="display: none">
      <thead>
        <tr>
          <th>ID</th>
          <th>Email</th>
          <th>DNI</th>
          <th>Discoteca</th>
          <th>Aceptar</th>
          <th>Rechazar</th>
        </tr>
      </thead>
      <tbody id="solicitudes">
      </tbody>
    </table>
  </div>
  <h1 style="text-align: center">Eventos</h1>
  <div class="container">
    <!-- Formulario de búsqueda -->
    <form action="" method="post" id="frmbusqueda">
      <div class="form-group">
        <i id="icono_buscar" class="fa-solid fa-magnifying-glass" style="color: #F5763B;"></i>
        <input type="text" name="buscar" id="buscar" placeholder="Buscar..." class="form-control">
      </div>
      {{-- <label for="discoteca">Discoteca</label> --}}
      <i class="fa-solid fa-compact-disc" style="color: #F5763B;" id="icono_discoteca"></i>
      <select name="discoteca" id="discoteca" class="button-40">
        <option value=""></option>
      </select>
    </form>
    
    <!-- Tabla con los datos del CRUD a mostrar -->
    <table>
      <thead>
        <tr>
          <th>ID</th>
          <th>Nombre</th>
          <th>Descripción</th>
          <th>Flyer</th>
          <th>Fecha Inicio/Final</th>
          <th>Discoteca</th>
          <th>Eliminar</th>
        </tr>
      </thead>
      <tbody id="resultado">
      </tbody>
    </table>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
  <script src="{{ asset('js/adminEvento.js') }}"></script>
</body>
</html>
