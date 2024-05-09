
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
            <h5>Gestiona tu discoteca:</h5>
            <h1><?php echo $discoteca->name; ?></h1>
            <h6>direccion de la discoteca: <span><?php echo $discoteca->direccion; ?></span></h6>
            <p>Tienes un total de <span id="eventosCount"></span> eventos</p>
            <button class="login">Update Discoteca </button>
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



</body>
<script>
var playListOasisfy = document.getElementById("playListOasisfy");
var SongsOasisfy = document.getElementById("SongsOasisfy");

playListOasisfy.addEventListener("click", function() {
    var viewCanciones = document.getElementById('canciones');
    var viewPlaylist = document.getElementById('playlist');

    if (viewPlaylist.style.display === 'none') {
        viewCanciones.style.display = 'none';
        viewPlaylist.style.display = 'flex';
        playListOasisfy.classList.add('home-p');
        SongsOasisfy.classList.remove('home-p');
    } else {
        viewCanciones.style.display = 'flex';
        viewPlaylist.style.display = 'none';
        SongsOasisfy.classList.add('home-p');
        playListOasisfy.classList.remove('home-p');
    }
});

SongsOasisfy.addEventListener("click", function() {
    var viewCanciones = document.getElementById('canciones');
    var viewPlaylist = document.getElementById('playlist');

    if (viewCanciones.style.display === 'none') {
        viewPlaylist.style.display = 'none';
        viewCanciones.style.display = 'flex';
        SongsOasisfy.classList.add('home-p');
        playListOasisfy.classList.remove('home-p');
    } else {
        viewPlaylist.style.display = 'flex';
        viewCanciones.style.display = 'none';
        playListOasisfy.classList.add('home-p');
        SongsOasisfy.classList.remove('home-p');
    }
});

    
function oasisfy() {
        var viewCanciones = document.getElementById('viewCanciones');
        var viewPlaylist = document.getElementById('playlist');

        if (elementoEdicion.style.display === 'none') {
            elementoInformacion.style.display = 'none';
            elementoEdicion.style.display = 'flex';
        } else {
            elementoInformacion.style.display = 'block';
            elementoEdicion.style.display = 'none';
        }
    }


    window.onload = function() {

        listarEventos();
        listarCanciones();
        listarPlaylist();
    };

    function listarPlaylist() {
    var resultado = document.getElementById('playlist');
    var formdata = new FormData();
    var csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    formdata.append('_token', csrfToken);

    var ajax = new XMLHttpRequest();
    ajax.open('POST', '/playlistView');
    ajax.onload = function() {
        var str = "";
        if (ajax.status == 200) {
            var json = JSON.parse(ajax.responseText);
            var tabla = "";
            for (var i = 0; i < json.eventos.length; i++) {
                var evento = json.eventos[i];
                var numCanciones = json.cancionesPorEvento[evento.id] || 0; // Obtener el número de canciones o establecerlo como 0 si no hay
                str = "<div>";
                str += "<img style='height: 100px' src='img/flyer/" + evento.flyer + "' alt=''>";
                str += "<p>" + evento.name_playlist + "</p>";
                str += "<a>DJ: " + evento.dj + "</a>";
                str += "<a>Total de Canciones: " + numCanciones + "</a>";
                str += "</div>";
                tabla += str;
            }
            resultado.innerHTML = tabla;

        } else {
            resultado.innerText = "Error";
        }
    };
    ajax.send(formdata);
}


async function listarCanciones() {
    var resultado = document.getElementById('canciones');
    var formdata = new FormData();
    var csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    formdata.append('_token', csrfToken);

    var ajax = new XMLHttpRequest();
    ajax.open('POST', '/cancionesView');
    ajax.onload = async function() {
        var str = "";
        if (ajax.status == 200) {
            var data = JSON.parse(ajax.responseText);
            var tabla = "";

            // Array para almacenar todas las promesas de obtener carátulas
            var promesasCaratulas = [];

            // Construir todas las promesas para obtener las carátulas
            data.canciones.forEach(function(cancion) {
                promesasCaratulas.push(obtenerCaratula(cancion.name, cancion.artista));
            });

            // Esperar a que todas las promesas se completen
            const urlsCaratulas = await Promise.all(promesasCaratulas);

            // Mostrar canciones con sus carátulas
            data.canciones.forEach(function(cancion, index) {
                str = "<div id='viewCanciones'>";
                str += "<select class='select' onchange='agregarCancionPlayList("+cancion.id+", this.value);'> <option>Playlist</option>";

                for (var i = 0; i < data.eventos.length; i++) {
                    str += "<option value="+data.eventos[i].id+">" + data.eventos[i].name_playlist + "</option>";
                }

                str += "</select>";
                str += "<img src='"+urlsCaratulas[index]+"' alt=''>";
                str += "<p>"+cancion.name+" | "+cancion.artista+"</p>";
                str += "<a>Duracion: "+cancion.duracion+"</a>";
                str += "</div>";
                tabla += str;
            });

            // Mostrar eventos de la discoteca
            resultado.innerHTML = tabla;

        } else {
            resultado.innerText = "Error";
        }
    };
    ajax.send(formdata);
}

function agregarCancionPlayList(idCancion, value){
    var formdata = new FormData();
        var csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        formdata.append('_token', csrfToken);
        formdata.append('idCancion', idCancion);
        formdata.append('idEvento', value);

        
    var ajax = new XMLHttpRequest();
        ajax.open('POST', '/cancionUpdate');
        ajax.onload = function() {
            if (ajax.status == 200) {
                Swal.fire({
                    position: "top-end",
                    title: "La cancion se ha agregado.",
                    showConfirmButton: false,
                    timer: 1500
                });
                listarEventos();
                listarCanciones();
                listarPlaylist();
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Duplicada',
                    text: 'La cancion ya esta añadida a la playlist.'
                });
            }
        };
        ajax.onerror = function() {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Hubo un problema al añadir la cancion. Por favor, inténtalo de nuevo más tarde.'
            });
        };
        ajax.send(formdata);
}


    $(function() {
        var nav = $("nav");
        var maxLeft = 1600; // Límite máximo de posición izquierda
        var maxTop = $(document).height(); // Límite máximo de posición superior (alto del documento)

        nav.draggable({
            drag: function(event, ui) {
                // Verificar y ajustar la posición si es necesario
                ui.position.left = Math.min(ui.position.left, maxLeft);
                ui.position.top = Math.min(ui.position.top, maxTop);
            }
        });
    });

/* Filtros eventos */
var buscar = document.getElementById('buscar')
buscar.addEventListener("keyup", () => {
    const valor = buscar.value;
    if (valor == "") {
        listarEventos('');
    } else {
        listarEventos(valor);
    }

});

    function listarEventos(valor) {
        var resultado = document.getElementById('crudGestor');
        var formdata = new FormData();
        var csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        formdata.append('_token', csrfToken);
        formdata.append('valor', valor);

        var ajax = new XMLHttpRequest();
        ajax.open('POST', '/eventosView');
        ajax.onload = function() {
            var str = "";
            var count = 0;
            if (ajax.status == 200) {
                var json = JSON.parse(ajax.responseText);
                var tabla = "";
                json.forEach(function(item) {
                    str = " <div class='row'>"
                    str += "<i class='fa-solid fa-pen-to-square'  onclick='editar(" + item.id +
                        ")' id='eliminar' style='color: #f5763b; float: right; margin-left: 2%;'></i>"
                    str += "   <i id='eliminar' onclick='eliminar(" + item.id +
                        ")' class='fa-solid fa-trash' style='color: #f5763b; float:right; margin-bottom: 5%'></i> "
                    str += "   <img src='img/flyer/" + item.flyer + "'/>"
                    str += "   <div id='informacion" + item.id + "'>"
                    str += "    <h3>" + item.name + "</h3>"
                    str += "   <h5>" + item.dj + " | " + item.name_playlist + "</h5>"
                    str += "   <p>" + item.descripcion + "</p>"
                    str += "    <p><i class='fa-solid fa-clock' style='color: green;'></i> " + item
                        .fecha_inicio + "</p>"
                    str += "    <p><i class='fa-solid fa-clock' style='color: red'></i> " + item
                        .fecha_final + "</p>"
                    str += "</div>"

                    // inputs
                    str += "<div id='edicion" + item.id +
                        "' style='display: none;justify-content: center; align-items: center; flex-wrap: wrap;'>"
                    str += "    <input id='nameEdit" + item.id + "' type='text' value='" + item.name + "'>"
                    str += "    <input id='djEdit" + item.id + "' type='text' value='" + item.dj + "'>"
                    str += "    <input id='playEdit" + item.id + "' type='text' value='" + item
                        .name_playlist + "'>"
                    str += "    <input id='descEdit" + item.id + "' type='text' value='" + item
                        .descripcion + "'>"
                    str += "<input id='inicioEdit" + item.id + "' type='datetime-local' value='" + item
                        .fecha_inicio + "'>";
                    str += "<input id='finalEdit" + item.id + "' type='datetime-local' value='" + item
                        .fecha_final + "'>";
                    str += "<button onclick='update(" + item.id +
                        ")' id='updateEvento' class='login'>Update Evento</button>"
                    str += "</div>"
                    str += "</div>"

                    tabla += str;


                    count++;
                });
                resultado.innerHTML = tabla;
                document.getElementById('eventosCount').innerHTML = count;

            } else {
                resultado.innerText = "Error";
            }
        };
        ajax.send(formdata);
    }

    function editar(id) {
        var id = id;
        var informacionId = 'informacion' + id;
        var elementoInformacion = document.getElementById(informacionId);

        var edicionId = 'edicion' + id;
        var elementoEdicion = document.getElementById(edicionId);

        if (elementoEdicion.style.display === 'none') {
            elementoInformacion.style.display = 'none';
            elementoEdicion.style.display = 'flex';
        } else {
            elementoInformacion.style.display = 'block';
            elementoEdicion.style.display = 'none';
        }
    }

    function update(id) {
        var nameEdit = 'nameEdit' + id;
        var nameEdit = document.getElementById(nameEdit).value;

        var djEdit = 'djEdit' + id;
        var djEdit = document.getElementById(djEdit).value;

        var playEdit = 'playEdit' + id;
        var playEdit = document.getElementById(playEdit).value;

        var descEdit = 'descEdit' + id;
        var descEdit = document.getElementById(descEdit).value;

        var inicioEdit = 'inicioEdit' + id;
        var inicioEdit = document.getElementById(inicioEdit).value;

        var finalEdit = 'finalEdit' + id;
        var finalEdit = document.getElementById(finalEdit).value;


        var formdata = new FormData();
        var csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        formdata.append('_token', csrfToken);
        formdata.append('nameEdit', nameEdit);
        formdata.append('djEdit', djEdit);
        formdata.append('descEdit', descEdit);
        formdata.append('playEdit', playEdit);
        formdata.append('inicioEdit', inicioEdit);
        formdata.append('finalEdit', finalEdit);
        formdata.append('id', id);


        var ajax = new XMLHttpRequest();
        ajax.open('POST', '/eventoUpdate');
        ajax.onload = function() {
            if (ajax.status == 200) {
                Swal.fire({
                    position: "top-end",
                    title: "El evento se ha creado.",
                    showConfirmButton: false,
                    timer: 1500
                });
                listarEventos();
                listarCanciones();
        listarPlaylist();
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Hubo un problema al añadir el evento. Por favor, inténtalo de nuevo más tarde.'
                });
            }
        };
        ajax.onerror = function() {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Hubo un problema al añadir el evento. Por favor, inténtalo de nuevo más tarde.'
            });
        };
        ajax.send(formdata);



    };


    function eliminar(id) {
        var formdata = new FormData();
        var csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        formdata.append('_token', csrfToken);
        formdata.append('id', id);


        var ajax = new XMLHttpRequest();
        ajax.open('POST', '/borrarEvento');
        ajax.onload = function() {
            Swal.fire({
                icon: "success",
                title: "El evento se a eliminado",
                showConfirmButton: false,
                timer: 1500
            });
            listarEventos();
            listarCanciones();
        listarPlaylist();
        };
        ajax.send(formdata);
    }


    var crearEvento = document.getElementById("crearEvento");

    crearEvento.addEventListener("click", function() {
        Swal.fire({
            title: 'Nuevo Evento',
            html: `
        <input id="swal-nombre" class="swal2-input" placeholder="Nombre del evento">
        <input id="swal-descripcion" class="swal2-input" placeholder="Descripción del evento">
        <input id="swal-fecha-inicio" type="datetime-local" class="swal2-input" placeholder="Fecha de inicio">
        <input id="swal-fecha-fin" type="datetime-local" class="swal2-input" placeholder="Fecha de fin">
        <input id="swal-dj-nombre" class="swal2-input" placeholder="Nombre del DJ">
        <input id="swal-playlist-nombre" class="swal2-input" placeholder="Nombre de la playlist">
        <label class='custom-file-upload'><input id="swal-foto" type="file" class="swal2-input" accept="image/*" title='' placeholder="Subir una foto">Subir foto</label>

    `,
            showCancelButton: true,
            confirmButtonText: 'Confirmar',
            cancelButtonText: 'Cancelar',
            showLoaderOnConfirm: true,
            preConfirm: () => {
                const nombre = Swal.getPopup().querySelector('#swal-nombre').value;
                const descripcion = Swal.getPopup().querySelector('#swal-descripcion').value;
                const fotoInput = Swal.getPopup().querySelector('#swal-foto');
                const fotoNombre = fotoInput.files[0];
                const fechaInicio = Swal.getPopup().querySelector('#swal-fecha-inicio').value;
                const fechaFin = Swal.getPopup().querySelector('#swal-fecha-fin').value;
                const djNombre = Swal.getPopup().querySelector('#swal-dj-nombre').value;
                const playlistNombre = Swal.getPopup().querySelector('#swal-playlist-nombre').value;
                console.log(fotoNombre);
                if (!nombre || !descripcion || !fechaInicio || !fechaFin || !djNombre || !
                    playlistNombre) {
                    Swal.showValidationMessage('Por favor completa todos los campos');
                }

                // Retornar un objeto con todos los datos ingresados
                return {
                    nombre,
                    descripcion,
                    fotoNombre,
                    fechaInicio,
                    fechaFin,
                    djNombre,
                    playlistNombre
                };
            }
        }).then((result) => {
            if (result.isConfirmed) {
                const {
                    nombre,
                    descripcion,
                    fotoNombre,
                    fechaInicio,
                    fechaFin,
                    djNombre,
                    playlistNombre
                } = result.value;

                // Aquí deberías enviar estos datos a la función añadirCat()
                añadirEvento(nombre, descripcion, fotoNombre, fechaInicio, fechaFin, djNombre,
                    playlistNombre);
            }
        });

    });



    function añadirEvento(nombre, descripcion, fotoNombre, fechaInicio, fechaFin, djNombre, playlistNombre) {
        var formdata = new FormData();
        var csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        formdata.append('_token', csrfToken);
        formdata.append('nombre', nombre);
        formdata.append('descripcion', descripcion);
        formdata.append('fotoNombre', fotoNombre);
        // console.log(fotoNombre)
        formdata.append('fechaInicio', fechaInicio);
        formdata.append('fechaFin', fechaFin);
        formdata.append('djNombre', djNombre);
        formdata.append('playlistNombre', playlistNombre);

        var ajax = new XMLHttpRequest();
        ajax.open('POST', '/eventoNew');
        ajax.onload = function() {
            if (ajax.status == 200) {
                Swal.fire({
                    position: "top-end",
                    title: "El evento se ha creado.",
                    showConfirmButton: false,
                    timer: 1500
                });
                listarEventos();
                listarCanciones();
        listarPlaylist();
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Hubo un problema al añadir el evento. Por favor, inténtalo de nuevo más tarde.'
                });
            }
        };
        ajax.onerror = function() {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Hubo un problema al añadir el evento. Por favor, inténtalo de nuevo más tarde.'
            });
        };
        ajax.send(formdata);
    }

    async function obtenerCaratula(cancion, artista) {
    try {
        const apiKey = 'ed420dfe24230d66234f98cdc646d658';
        const url = `http://ws.audioscrobbler.com/2.0/?method=track.getInfo&api_key=${apiKey}&artist=${encodeURIComponent(artista)}&track=${encodeURIComponent(cancion)}&format=json`;
        // console.log(url);
        
        const response = await fetch(url);
        const data = await response.json();
        
        if (data.error) {
            throw new Error(data.message);
        }
        
        const { album } = data.track;
        const imageUrl = album.image.find(img => img.size === 'extralarge')['#text'];
        
        return imageUrl;
    } catch (error) {
        console.error('Error al obtener la carÃ¡tula:', error);
        return null;
    }
    
    
    }
</script>

</html>
