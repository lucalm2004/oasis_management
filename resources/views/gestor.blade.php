
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Oasis Management - Gestor</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="shortcut icon" href="{{ asset('img/logo.png') }}" type="image/x-icon">

    <script src="https://kit.fontawesome.com/8e6d3dccce.js" crossorigin="anonymous"></script>
    {{-- <script src="https://kit.fontawesome.com/8e6d3dccce.js" crossorigin="anonymous"></script> --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="{{ asset('css/gestor.css') }}">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>

</head>

<body>
    
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
            echo $user->name; ?></span>
        </div>

        <div class="bx bx-menu" id="menu-icon"></div>

        <ul class="navegacion">
            <i class="fa-regular fa-bell" style="color: #F5763B; cursor: pointer; margin-top: 1.8%;" id="campana"></i>
            <a id="notificacion">0</a>
            <li><a id="primero" href="#discoteca">TU DISCOTECA</a></li>
            <li><a id="segundo" href="#quese">EVENTOS</a></li>
            <li><a id="tercero" href="#personal">PERSONAL</a></li>
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
    <div>
    
        <table id="tablaSolicitudes" style="display: none">
          <thead>
            <tr>
              <th>Email</th>
              <th>DNI</th>
              <th>CV</th>
              <th>Descargar</th>
              <th>Aceptar</th>
              <th>Rechazar</th>
            </tr>
          </thead>
          <tbody id="solicitudes">
          </tbody>
        </table>
      </div>

   

    <section class="inicio" id="discoteca">
   
       
        <div class="inicio-texto">
            <h5>Gestiona tu discoteca:</h5>
            <div id="discotecaInicio">
                <h1 id="discotecaName"></h1>
                <h6>Dirección de la discoteca: <span id="discotecaDireccion"></span></h6>
            </div>
            <p>Tienes un total de <span id="eventosCount"></span> eventos</p>
            <button class="login" id="formModificarDisco"><i class='fa-solid fa-pen-to-square' style='color: white; font-size: 200%;'></i></button>
            
            <div id="Modificardiscoteca" style="display: none">
                <span id="errorNameDiscoteca" style=" display: none; padding-bottom:2%"></span>
                <input id="nameDiscoteca" type="text" onkeyup="validarFormUpdateDiscoteca(<?php echo $discoteca->id; ?>)">
                
                <span id="errorDireccionDiscoteca" style=" display: none; padding-bottom:2%"></span>
                <input id="direccionDiscoteca" type="text" onkeyup="validarFormUpdateDiscoteca(<?php echo $discoteca->id; ?>)">
                
                <span id="errorCapacidadDiscoteca" style="display: none; padding-bottom:2%"></span>
                <input id="capacidadDiscoteca" type="number" onkeyup="validarFormUpdateDiscoteca(<?php echo $discoteca->id; ?>)">
                
                <button id="updateDiscoteca" class="login" onclick="handleUpdateDiscoteca(<?php echo $discoteca->id; ?>)">Update Discoteca</button>
            </div>
        </div>
        
        <div class="imagenini">
            <label id="modificarFotoLabel" style="display: none; border: none;" class='custom-file-upload'>
                <input id="swal-foto-discoteca" type="file" class="swal2-input" accept="image/*" title='' placeholder="Subir una foto">
                <img id="imagenPreview" src='' alt="">
            </label>
            <img id="imagenDiscoteca" src='' alt="">
        </div>
        
    </section>
    {{-- Reproductor --}}
    <div class='reproductor'>
        <nav>
            
            <img class="logos" draggable="false" src="img/oasisfy.svg" alt="Spotify">
            <hr class="hr1">
            <div>
                <p id="SongsOasisfy" class="home-p pages">
                    <i class="fa-solid fa-music"></i>
                    Canciones
                </p>
               
                <p id="playListOasisfy" class="pages">
                    <svg xmlns:xlink="http://www.w3.org/1999/xlink" xmlns="http://www.w3.org/2000/svg" role="img"
                        class="Svg-sc-1bi12j5-0 gSLhUO collection-active-icon" viewBox="0 0 24 24">
                        <path
                            d="M14.617 3.893l-1.827.814 7.797 17.513 1.827-.813-7.797-17.514zM3 22h2V4H3v18zm5 0h2V4H8v18z">
                        </path>
                    </svg>
                    Tus Playlist
                </p>
               
            </div>
            <hr class="hr2">
            <div id="canciones" class="list">
               {{-- aqui se listan las canciones --}}
            </div>
            <div id="playlist" style="display: none" class="list">
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
        </form>
        <i id="crearEvento" class="fa-solid fa-plus" style="color: #f5763b; float: right;"></i>
        <div class="container">
            <!-- Formulario de bÃºsqueda -->


            <div id="crudGestor" class="containerquese">

            </div>
    </section>


    <section class="quese" id="personal">
        <form action="" method="post" id="frmbusqueda">
            <div class="form-group">
                <i id="icono_buscar" class="fa-solid fa-magnifying-glass" style="color: #F5763B;"></i>
                <input type="text" name="buscar2" id="buscar2" placeholder="Buscar..." class="form-control">
            </div>
        </form>
  
        <div class="centro" style="margin-bottom: 20%">
            <h3>TU <span>PERSONAL</span> </h3>
        </div>
        
        <div class="container">
            <div id="personalTabla" class="containerquese">
                <!-- Aquí se listarán los usuarios -->
            </div>
        </div>
    </section>

    



</body>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
   

<script src="{{ asset('js/gestor.js') }}"></script>

</html>
