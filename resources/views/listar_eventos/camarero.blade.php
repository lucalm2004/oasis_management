<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Oasis Management - Camarero</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="shortcut icon" href="{{ asset('img/logo.png') }}" type="image/x-icon">

    <script src="https://kit.fontawesome.com/8e6d3dccce.js" crossorigin="anonymous"></script>
    {{-- <script src="https://kit.fontawesome.com/8e6d3dccce.js" crossorigin="anonymous"></script> --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>

</head>

<body id="content">
    
    {{-- Head1 --}}
    <header class="logo">
       
       
        <div class="textlogo">
          
            Bienvenido <span><?php
            use Illuminate\Support\Facades\DB;
            use Illuminate\Support\Facades\Auth;
            $user = Auth::user();
            $idUsuario = $user->id;
            $idDiscoteca = DB::table('users_discotecas')->where('id_users', $idUsuario)->value('id_discoteca');
            
            
            $discoteca = DB::table('discotecas')->where('id', $idDiscoteca)->first();

            $usergestor = DB::table('users')
            ->select('users.*', 'users_discotecas.id_discoteca')
            ->leftJoin('users_discotecas', 'users.id', '=', 'users_discotecas.id_users')
            ->where('users_discotecas.id_discoteca', '=', $discoteca->id)
            ->where('users.id_rol', '=', 3)
            ->first();

            echo $user->name; ?></span>
            
        </div>

        <div class="bx bx-menu" id="menu-icon"></div>

        <ul class="navegacion">
            <li><a id="primero" href="#discoteca">TU DISCOTECA</a></li>
            <li><a id="segundo" href="#quese">EVENTOS</a></li>
      
            <form method="POST" action="{{ route('logout') }}" id="logout">
                @csrf

                <x-dropdown-link :href="route('logout')"
                    onclick="event.preventDefault();
                                this.closest('form').submit();">
                    {{ __('LOG OUT') }}
                </x-dropdown-link>
            </form>
            {{-- <li ><a id="tercero" href="#quese">¿QUE HACEMOS?</a></li>
        <li ><a id="cuarto" href="#contacto">CONTACTO</a></li>
        <li ><a id="quinto" href="#experiencias">EMPRESAS</a></li> --}}
        </ul>

    </header>
    
    <section class="inicio" id="discoteca">
   

      <div class="inicio-texto">
        

          <h5>Trabaja en:</h5>
          <h1><?php echo $discoteca->name; ?></h1>
          <h6>Gestor de la discoteca: <span><?php echo $usergestor->name; ?></span></h6>
          <h7>Direccion de la discoteca: <span><?php echo $discoteca->direccion; ?></span></h7>
          <p>Tienes un total de <span id="eventosCount"></span> eventos</p>
        
      </div>
      <div class="imagenini">
          <img src='img/discotecas/<?php echo $discoteca->image; ?>' alt="">
      </div>
  </section>
  {{-- Reproductor --}}
  <div class='reproductor'>
      <nav>
          
          <img class="logos" draggable="false" src="img/oasisfy.svg" alt="Spotify">
          <hr class="hr1">
          <div>
             
             
              <p id="playListOasisfy" class="pages">
                  <svg xmlns:xlink="http://www.w3.org/1999/xlink" xmlns="http://www.w3.org/2000/svg" role="img"
                      class="Svg-sc-1bi12j5-0 gSLhUO collection-active-icon" viewBox="0 0 24 24">
                      <path
                          d="M14.617 3.893l-1.827.814 7.797 17.513 1.827-.813-7.797-17.514zM3 22h2V4H3v18zm5 0h2V4H8v18z">
                      </path>
                  </svg>
                  Playlist {{ $discoteca->name}}
              </p>
             
          </div>
          <hr class="hr2">
        
          <div id="playlist" class="list">
              {{-- aqui se listan las canciones --}}
           </div>
           <div id="playlist_musica" style="display: none" class="list">
           </div>
       
      </nav>
  </div>
  

  {{-- eventos --}}
  <section class="quese" id="quese">
      <div class="centro">
          <h3>TUS <span>EVENTOS</span> </h3>
      </div>
      <form action="" method="post" id="frmbusqueda">
          <div class="form-group">
              <i id="icono_buscar" class="fa-solid fa-magnifying-glass" style="color: #F5763B;"></i>
              <input type="text" name="buscar" id="buscar" placeholder="Buscar..." class="form-control">
          </div>
          <i id="icono_fecha" class="fa-solid fa-calendar-days" style="color: #F5763B;"></i>
          <select class="form-control status_id" id="fecha_inc">
              <option value="0">Seleccione Fecha...</option>
              <option value="asc">Fecha ascendente</option>
              <option value="desc">Fecha descendente</option>
          </select>
      </form>
      
      <div class="container">
          <!-- Formulario de bÃºsqueda -->


          <div id="crudCamarero" class="containerquese">

          </div>
          
  </section>
  <div class="loader-container">
    <div class="loader"></div>
</div>
    <script src="{{ asset('js/eventos.js') }}"></script>

    <script>
         
      window.addEventListener('load', function() {
            var randomTime = Math.floor(Math.random() * 1000) + 1000; // Genera un tiempo entre 1000 ms y 2000 ms
            setTimeout(function() {
                document.querySelector('.loader-container').style.display = 'none';
                document.querySelector('#content').style.display = 'block';
                document.body.style.overflow = 'auto';
            }, randomTime);
        });
 
    </script>
</div>
</body>

</html>