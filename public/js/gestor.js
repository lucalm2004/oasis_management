var playListOasisfy = document.getElementById("playListOasisfy");
var SongsOasisfy = document.getElementById("SongsOasisfy");

playListOasisfy.addEventListener("click", function() {
    var viewCanciones = document.getElementById('canciones');
    var viewPlaylist = document.getElementById('playlist');
    var playlist_musica = document.getElementById('playlist_musica');

    if (viewPlaylist.style.display === 'none') {
        viewCanciones.style.display = 'none';
        playlist_musica.style.display = 'none';
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
        playlist_musica.style.display = 'none';

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
    discoteca();
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
                str = "<div onclick='editarPlaylist(" + evento.id + ")'>";
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

function editarPlaylist(id) {
    document.getElementById('playlist').style.display = 'none';
    var resultado = document.getElementById('playlist_musica')
    document.getElementById('playlist_musica').style.display = 'flex';

    var formdata = new FormData();
    var csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    formdata.append('_token', csrfToken);
    formdata.append('id', id);


    var ajax = new XMLHttpRequest();
    ajax.open('POST', '/editarPlaylist');
    ajax.onload = async function() {
        var str = "";
        if (ajax.status == 200) {
            var data = JSON.parse(ajax.responseText);
            var tabla = "";

            // Array para almacenar todas las promesas de obtener carátulas
            var promesasCaratulas = [];

            // Construir todas las promesas para obtener las carátulas
            data.canciones.forEach(function(cancion) {
                promesasCaratulas.push(obtenerCaratula(cancion.name, cancion.name_artista));
            });

            // Esperar a que todas las promesas se completen
            const urlsCaratulas = await Promise.all(promesasCaratulas);

            // Mostrar canciones con sus carátulas
            data.canciones.forEach(function(cancion, index) {
                console.log(index)
                str = "<div id='viewCanciones'>";
                str += "<i id='eliminar' onclick='borrar(" + cancion.id +
                    "," + id + ")' class='fa-solid fa-trash' style='color: #f5763b; text-align: center; position: relative; border-radius: 10px; -webkit-appearance: none; color: white; -moz-appearance: none; appearance: none; background: #ff5500; padding: 2px 5px; font-size: 10px; border: 1px solid white; z-index: 99; margin-bottom: -17px; margin-left: 75%;'></i> ";
                str += "<img src='" + urlsCaratulas[index] + "' alt=''>";
                str += "<p>" + cancion.name + " | " + cancion.name_artista + "</p>";
                /*  str += "<a>Duracion: " + cancion.duracion + "</a>"; */
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

function borrar(id, idE) {
    var formdata = new FormData();
    var csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    formdata.append('_token', csrfToken);
    formdata.append('idCancion', id);
    formdata.append('idEvento', idE);


    var ajax = new XMLHttpRequest();
    ajax.open('POST', '/playlistUpdate');
    ajax.onload = function() {
        if (ajax.status == 200) {
            Swal.fire({
                position: "top-end",
                title: "La cancion se ha eliminado.",
                showConfirmButton: false,
                timer: 1500
            });
            listarEventos();
            listarCanciones();
            listarPlaylist();
            editarPlaylist(idE)
        } else {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Hubo un problema al añadir la cancion. Por favor, inténtalo de nuevo más tarde.'
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
                promesasCaratulas.push(obtenerCaratula(cancion.nombre_cancion, cancion.nombre_artista));
            });

            // Esperar a que todas las promesas se completen
            const urlsCaratulas = await Promise.all(promesasCaratulas);

            // Mostrar canciones con sus carátulas
            data.canciones.forEach(function(cancion, index) {
                str = "<div id='viewCanciones'>";
                str += "<select class='select' onchange='agregarCancionPlayList(" + cancion.id_cancion + ", this.value);'> <option>Playlist</option>";

                for (var i = 0; i < data.eventos.length; i++) {
                    str += "<option value=" + data.eventos[i].id + ">" + data.eventos[i].name_playlist + "</option>";
                }

                str += "</select>";
                str += "<img src='" + urlsCaratulas[index] + "' alt=''>";
                str += "<p>" + cancion.nombre_cancion + " | " + cancion.nombre_artista + "</p>";
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

function agregarCancionPlayList(idCancion, value) {
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
                var fechaActual = new Date();

                // Convertir la fecha actual a un formato compatible con el input de tipo datetime-local
                var fechaActualFormatoInput = fechaActual.toISOString().slice(0, 16);


                str = " <div class='row'>"
                if (item.fecha_final > fechaActualFormatoInput) {
                    str += "<i class='fa-solid fa-pen-to-square'  onclick='editar(" + item.id +
                        ")' id='eliminar' style='color: #f5763b; float: right; margin-left: 2%;'></i>"
                    str += "   <i id='eliminar' onclick='eliminar(" + item.id +
                        ")' class='fa-solid fa-trash' style='color: #f5763b; float:right; margin-bottom: 5%'></i> "


                }

                str += "   <div id='informacion" + item.id + "'>"
                str += "   <img id='imagenEvento' src='img/flyer/" + item.flyer + "'/>"
                str += "    <h3>" + item.name + "</h3>"
                str += "   <h5>" + item.dj + " | " + item.name_playlist + "</h5>"
                str += "   <p>" + item.descripcion + "</p>"
                str += "    <p><i class='fa-solid fa-clock' style='color: green;'></i> " + item
                    .fecha_inicio + "</p>"
                str += "    <p><i class='fa-solid fa-clock' style='color: red'></i> " + item
                    .fecha_final + "</p>"
                str += "</div>"

                // inputs
                str += "<div id='edicion" + item.id + "' style='display: none; justify-content: center; align-items: center; flex-wrap: wrap;'>";
                str += '<label id="label' + item.id + '" style="border: none;" class="custom-file-upload">' +
                    '<input id="imagenMod' + item.id + '" type="file" class="swal2-input" accept="image/*" title="" placeholder="Subir una foto">' +
                    '<img src="img/flyer/' + item.flyer + '" alt="">' +
                    '</label>';
                str += "<span id='errorNameEdit" + item.id + "' style='display: none; padding-bottom:2%; text-align: center;'></span>";
                str += "<input id='nameEdit" + item.id + "' type='text' value='" + item.name + "' onkeyup='validarFormUpdateEvento(" + item.id + ")'>";
                str += "<span id='errorDjEdit" + item.id + "' style='display: none; padding-bottom:2%; text-align: center;'></span>";
                str += "<input id='djEdit" + item.id + "' type='text' value='" + item.dj + "' onkeyup='validarFormUpdateEvento(" + item.id + ")'>";
                str += "<span id='errorPlayEdit" + item.id + "' style='display: none; padding-bottom:2%; text-align: center;'></span>";
                str += "<input id='playEdit" + item.id + "' type='text' value='" + item.name_playlist + "' onkeyup='validarFormUpdateEvento(" + item.id + ")'>";
                str += "<span id='errorDescEdit" + item.id + "' style='display: none; padding-bottom:2%; text-align: center;'></span>";
                str += "<input id='descEdit" + item.id + "' type='text' value='" + item.descripcion + "' onkeyup='validarFormUpdateEvento(" + item.id + ")'>";
                str += "<span id='errorCapacidadEdit" + item.id + "' style='display: none; padding-bottom:2%; text-align: center;'></span>";
                str += "<input id='capacidad" + item.id + "' type='number' value='" + item.capacidad + "' onkeyup='validarFormUpdateEvento(" + item.id + ")'>";
                str += "<span id='errorCapacidadVipEdit" + item.id + "' style='display: none; padding-bottom:2%; text-align: center;'></span>";
                str += "<input id='capacidadVip" + item.id + "' type='number' value='" + item.capacidadVip + "' onkeyup='validarFormUpdateEvento(" + item.id + ")'>";
                str += "<span id='errorInicioEdit" + item.id + "' style='display: none; padding-bottom:2%; text-align: center;'></span>";
                str += "<input id='inicioEdit" + item.id + "' type='datetime-local' value='" + item.fecha_inicio + "' onchange='validarFormUpdateEvento(" + item.id + ")'>";
                str += "<span id='errorFinalEdit" + item.id + "' style='display: none; padding-bottom:2%; text-align: center;'></span>";
                str += "<input id='finalEdit" + item.id + "' type='datetime-local' value='" + item.fecha_final + "' onchange='validarFormUpdateEvento(" + item.id + ")'>";

                str += "<button onclick='handleUpdateEvento(" + item.id + ")' id='updateEvento' class='login'>Update Evento</button>";
                str += "</div>";
                str += "</div>";

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


function validarFormUpdateEvento(id) {

    var nombre = document.getElementById('nameEdit' + id).value.trim();
    var dj = document.getElementById('djEdit' + id).value.trim();
    var playlist = document.getElementById('playEdit' + id).value.trim();
    var descripcion = document.getElementById('descEdit' + id).value.trim();
    var capacidad = document.getElementById('capacidad' + id).value.trim();
    var capacidadVip = document.getElementById('capacidadVip' + id).value.trim();
    var inicio = document.getElementById('inicioEdit' + id).value.trim();
    var final = document.getElementById('finalEdit' + id).value.trim();

    var nombreError = document.getElementById('errorNameEdit' + id);
    var djError = document.getElementById('errorDjEdit' + id);
    var playlistError = document.getElementById('errorPlayEdit' + id);
    var descripcionError = document.getElementById('errorDescEdit' + id);
    var capacidadError = document.getElementById('errorCapacidadEdit' + id);
    var capacidadVipError = document.getElementById('errorCapacidadVipEdit' + id);
    var inicioError = document.getElementById('errorInicioEdit' + id);
    var finalError = document.getElementById('errorFinalEdit' + id);

    // Validar nombre
    if (nombre === "") {
        nombreError.innerText = 'Por favor introduce un nombre';
        nombreError.style.display = 'block';
    } else if (nombre.length < 3) {
        nombreError.innerText = 'Formato Incorrecto';
        nombreError.style.display = 'block';
    } else {
        nombreError.innerText = '';
        nombreError.style.display = 'none';
    }

    // Validar DJ
    if (dj === "") {
        djError.innerText = 'Por favor introduce el nombre del DJ';
        djError.style.display = 'block';
    } else if (dj.length < 3) {
        djError.innerText = 'Formato Incorrecto';
        djError.style.display = 'block';
    } else {
        djError.innerText = '';
        djError.style.display = 'none';
    }

    // Validar Playlist
    if (playlist === "") {
        playlistError.innerText = 'Por favor introduce el nombre de la playlist';
        playlistError.style.display = 'block';
    } else if (playlist.length < 3) {
        playlistError.innerText = 'Formato Incorrecto';
        playlistError.style.display = 'block';
    } else {
        playlistError.innerText = '';
        playlistError.style.display = 'none';
    }

    // Validar Descripción
    if (descripcion === "") {
        descripcionError.innerText = 'Por favor introduce una descripción';
        descripcionError.style.display = 'block';
    } else if (descripcion.length < 10) {
        descripcionError.innerText = 'La descripción debe tener al menos 10 caracteres';
        descripcionError.style.display = 'block';
    } else {
        descripcionError.innerText = '';
        descripcionError.style.display = 'none';
    }

    // Validar capacidad
    if (capacidad === "" || isNaN(capacidad) || capacidad <= 0 || capacidad.length > 4) {
        capacidadError.innerText = 'Por favor introduce un valor numérico válido para la capacidad (máximo 4 cifras)';
        capacidadError.style.display = 'block';
    } else {
        capacidadError.innerText = '';
        capacidadError.style.display = 'none';
    }

    // Validar capacidad VIP
    if (capacidadVip === "" || isNaN(capacidadVip) || capacidadVip <= 0 || capacidadVip.length > 4) {
        capacidadVipError.innerText = 'Por favor introduce un valor numérico válido para la capacidad VIP (máximo 4 cifras)';
        capacidadVipError.style.display = 'block';
    } else {
        capacidadVipError.innerText = '';
        capacidadVipError.style.display = 'none';
    }

    // Obtener la fecha actual
    var fechaActual = new Date();

    // Convertir la fecha actual a un formato compatible con el input de tipo datetime-local
    var fechaActualFormatoInput = fechaActual.toISOString().slice(0, 16);

    // Validar fecha de inicio
    if (inicio === "") {
        inicioError.innerText = 'Por favor introduce una fecha de inicio';
        inicioError.style.display = 'block';
    } else if (inicio < fechaActualFormatoInput) {
        inicioError.innerText = 'La fecha de inicio no puede ser anterior a la fecha actual';
        inicioError.style.display = 'block';
    } else {
        inicioError.innerText = '';
        inicioError.style.display = 'none';
    }

    // Validar fecha de finalización
    // Validar fecha de finalización
    if (final === "") {
        finalError.innerText = 'Por favor introduce una fecha de finalización';
        finalError.style.display = 'block';
    } else if (final < inicio) {
        finalError.innerText = 'La fecha de finalización no puede ser anterior a la fecha de inicio';
        finalError.style.display = 'block';
    } else if (final < fechaActualFormatoInput) {
        finalError.innerText = 'La fecha de finalización no puede ser anterior a la fecha actual';
        finalError.style.display = 'block';
    } else {
        // Convertir las fechas de inicio y final a objetos Date
        var fechaInicio = new Date(inicio);
        var fechaFinal = new Date(final);

        // Calcular la diferencia en milisegundos
        var diferenciaMilisegundos = fechaFinal - fechaInicio;

        // Validar que la diferencia sea menor o igual a 8 horas (28800000 milisegundos)
        if (diferenciaMilisegundos > 28800000) {
            finalError.innerText = 'La fecha de finalización es demasiado grande';
            finalError.style.display = 'block';
        } else {
            finalError.innerText = '';
            finalError.style.display = 'none';
        }
    }

    return !nombreError.innerText && !djError.innerText && !playlistError.innerText &&
        !descripcionError.innerText && !capacidadError.innerText && !capacidadVipError.innerText &&
        !inicioError.innerText && !finalError.innerText;
}

function handleUpdateEvento(id) {
    if (validarFormUpdateEvento(id)) {
        update(id);
    }
}

function editar(id) {
    var id = id;
    var informacionId = 'informacion' + id;
    var elementoInformacion = document.getElementById(informacionId);
    var elementoEdicion = document.getElementById('edicion' + id);

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
    var nameEditValue = document.getElementById(nameEdit).value;

    var djEdit = 'djEdit' + id;
    var djEditValue = document.getElementById(djEdit).value;

    var playEdit = 'playEdit' + id;
    var playEditValue = document.getElementById(playEdit).value;

    var descEdit = 'descEdit' + id;
    var descEditValue = document.getElementById(descEdit).value;

    var inicioEdit = 'inicioEdit' + id;
    var inicioEditValue = document.getElementById(inicioEdit).value;

    var finalEdit = 'finalEdit' + id;
    var finalEditValue = document.getElementById(finalEdit).value;

    var capacidadInput = 'capacidad' + id;
    var capacidadValue = document.getElementById(capacidadInput).value;

    var capacidadVipInput = 'capacidadVip' + id;
    var capacidadVipValue = document.getElementById(capacidadVipInput).value;

    var flyerInput = 'imagenMod' + id;
    var flyer = document.getElementById(flyerInput).files[0];

    var formdata = new FormData();
    var csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    formdata.append('_token', csrfToken);
    formdata.append('nameEdit', nameEditValue);
    formdata.append('djEdit', djEditValue);
    formdata.append('descEdit', descEditValue);
    formdata.append('playEdit', playEditValue);
    formdata.append('inicioEdit', inicioEditValue);
    formdata.append('finalEdit', finalEditValue);
    formdata.append('id', id);
    formdata.append('capacidad', capacidadValue);
    formdata.append('capacidadVip', capacidadVipValue);
    if (flyer) {
        formdata.append('flyer', flyer);
    }
    console.log(formdata);

    var ajax = new XMLHttpRequest();
    ajax.open('POST', '/eventoUpdate');
    ajax.onload = function() {
        var response = JSON.parse(ajax.responseText);
        if (ajax.status == 200) {

            Swal.fire({
                position: "top-end",
                title: "El evento se ha modificado.",
                showConfirmButton: false,
                timer: 1500
            });
            listarEventos();
            listarCanciones();
            listarPlaylist();
        } else {
            if (response.error) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: response.error
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Hubo un problema al modificar el evento. Por favor, inténtalo de nuevo más tarde.'
                });
            }
        }
    };
    ajax.onerror = function() {
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Hubo un problema al modificar el evento. Por favor, inténtalo de nuevo más tarde.'
        });
    };
    ajax.send(formdata);
}




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
        <span id="errorNombreCrear" style='display: none; padding-bottom:2%; text-align: center;' class="error-message"></span>
        <input id="swal-nombre" class="swal2-input" placeholder="Nombre del evento">
        
        <span id="errorDescripcionCrear" style='display: none; padding-bottom:2%; text-align: center;' class="error-message"></span>
        <input id="swal-descripcion" class="swal2-input" placeholder="Descripción del evento">
        
        <span id="errorFechaInicioCrear" style='display: none; padding-bottom:2%; text-align: center;' class="error-message"></span>
        <input id="swal-fecha-inicio" type="datetime-local" class="swal2-input" placeholder="Fecha de inicio">
        
        <span id="errorFechaFinCrear" style='display: none; padding-bottom:2%; text-align: center;' class="error-message"></span>
        <input id="swal-fecha-fin" type="datetime-local" class="swal2-input" placeholder="Fecha de fin">
        
        <span id="errorDjNombreCrear" style='display: none; padding-bottom:2%; text-align: center;' class="error-message"></span>
        <input id="swal-dj-nombre" class="swal2-input" placeholder="Nombre del DJ">
        
        <span id="errorPlaylistNombreCrear" style='display: none; padding-bottom:2%; text-align: center;' class="error-message"></span>
        <input id="swal-playlist-nombre" class="swal2-input" placeholder="Nombre de la playlist">
        
        <span id="errorCapacidadCrear" style='display: none; padding-bottom:2%; text-align: center;' class="error-message"></span>
        <input id="swal-playlist-capacidad" class="swal2-input" type='number' placeholder="Capacidad del evento">
        
        <span id="errorCapacidadVipCrear" style='display: none; padding-bottom:2%; text-align: center;' class="error-message"></span>
        <input id="swal-playlist-capacidadvip" class="swal2-input" type='number' placeholder="Capacidad de mesas Vip">
        
        <span id="errorFotoCrear" style='display: none; padding-bottom:2%; text-align: center;' class="error-message"></span>
        <label class='custom-file-upload'>
            <input id="swal-foto" type="file" class="swal2-input" accept="image/*" title='' placeholder="Subir una foto">Subir foto
        </label>
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
            const capacidad = Swal.getPopup().querySelector('#swal-playlist-capacidad').value;
            const capacidadVip = Swal.getPopup().querySelector('#swal-playlist-capacidadvip').value;

            if (!nombre || !descripcion || !fechaInicio || !fechaFin || !djNombre || !
                playlistNombre || !capacidad || !capacidadVip) {
                Swal.showValidationMessage('Por favor completa todos los campos');

            }
            return {
                nombre,
                descripcion,
                fotoNombre,
                fechaInicio,
                fechaFin,
                djNombre,
                playlistNombre,
                capacidad,
                capacidadVip
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
                playlistNombre,
                capacidad,
                capacidadVip
            } = result.value;

            añadirEvento(nombre, descripcion, fotoNombre, fechaInicio, fechaFin, djNombre,
                playlistNombre, capacidad, capacidadVip);
        }
    });

    // Agregar eventos keyup para validar los campos
    document.getElementById('swal-nombre').addEventListener('keyup', validarNombre);
    document.getElementById('swal-descripcion').addEventListener('keyup', validarDescripcion);
    document.getElementById('swal-fecha-inicio').addEventListener('change', validarFechaInicio);
    document.getElementById('swal-fecha-fin').addEventListener('change', validarFechaFin);
    document.getElementById('swal-dj-nombre').addEventListener('keyup', validarDJNombre);
    document.getElementById('swal-playlist-nombre').addEventListener('keyup', validarPlaylistNombre);
    document.getElementById('swal-playlist-capacidad').addEventListener('keyup', validarCapacidad);
    document.getElementById('swal-playlist-capacidadvip').addEventListener('keyup', validarCapacidadVip);
    document.getElementById('swal-foto').addEventListener('change', validarFoto);
});

function validarNombre() {
    var nombre = document.getElementById('swal-nombre').value;
    var errorNombre = document.getElementById('errorNombreCrear');

    if (nombre.length < 3) {
        errorNombre.innerText = 'El nombre debe tener al menos 3 caracteres';
        errorNombre.style.display = 'block';
    } else {
        errorNombre.innerText = '';
        errorNombre.style.display = 'none';
    }
}

function validarDescripcion() {
    var descripcion = document.getElementById('swal-descripcion').value;
    var errorDescripcion = document.getElementById('errorDescripcionCrear');

    if (descripcion.length < 10) {
        errorDescripcion.innerText = 'La descripción debe tener al menos 10 caracteres';
        errorDescripcion.style.display = 'block';
    } else {
        errorDescripcion.innerText = '';
        errorDescripcion.style.display = 'none';
    }
}

function validarFechaInicio() {
    var fechaInicio = document.getElementById('swal-fecha-inicio').value;
    var errorFechaInicio = document.getElementById('errorFechaInicioCrear');
    var fechaActual = new Date();
    var fechaSeleccionada = new Date(fechaInicio);

    if (fechaInicio === "") {
        errorFechaInicio.innerText = 'Por favor introduce una fecha de inicio';
        errorFechaInicio.style.display = 'block';
    } else if (fechaSeleccionada < fechaActual) {
        errorFechaInicio.innerText = 'La fecha de inicio no puede ser anterior a la fecha actual';
        errorFechaInicio.style.display = 'block';
    } else {
        errorFechaInicio.innerText = '';
        errorFechaInicio.style.display = 'none';
    }
}

function validarFechaFin() {
    var fechaFin = document.getElementById('swal-fecha-fin').value;
    var errorFechaFin = document.getElementById('errorFechaFinCrear');
    var fechaInicio = document.getElementById('swal-fecha-inicio').value;
    var fechaActual = new Date();
    var fechaFinSeleccionada = new Date(fechaFin);
    var fechaInicioSeleccionada = new Date(fechaInicio);

    if (fechaFin === "") {
        errorFechaFin.innerText = 'Por favor introduce una fecha de fin';
        errorFechaFin.style.display = 'block';
        errorFechaFin.style.textAlign = 'center';
    } else if (fechaFinSeleccionada < fechaInicioSeleccionada) {
        errorFechaFin.innerText = 'La fecha de fin no puede ser anterior a la fecha de inicio';
        errorFechaFin.style.display = 'block';
        errorFechaFin.style.textAlign = 'center';
    } else if (fechaFinSeleccionada < fechaActual) {
        errorFechaFin.innerText = 'La fecha de fin no puede ser anterior a la fecha actual';
        errorFechaFin.style.display = 'block';
        errorFechaFin.style.textAlign = 'center';
    } else {
        // Calcular la diferencia en milisegundos
        var diferenciaMilisegundos = fechaFinSeleccionada - fechaInicioSeleccionada;

        // Validar que la diferencia sea menor o igual a 8 horas (28800000 milisegundos)
        if (diferenciaMilisegundos > 28800000) {
            errorFechaFin.innerText = 'La fecha de fin es demasiado grande';
            errorFechaFin.style.display = 'block';
            errorFechaFin.style.textAlign = 'center';
        } else {
            errorFechaFin.innerText = '';
            errorFechaFin.style.display = 'none';
        }
    }
}

function validarDJNombre() {
    var djNombre = document.getElementById('swal-dj-nombre').value;
    var errorDJNombre = document.getElementById('errorDjNombreCrear');

    if (djNombre.length < 3) {
        errorDJNombre.innerText = 'El nombre del DJ debe tener al menos 3 caracteres';
        errorDJNombre.style.display = 'block';
    } else {
        errorDJNombre.innerText = '';
        errorDJNombre.style.display = 'none';
    }
}

function validarPlaylistNombre() {
    var playlistNombre = document.getElementById('swal-playlist-nombre').value;
    var errorPlaylistNombre = document.getElementById('errorPlaylistNombreCrear');

    if (playlistNombre.length < 3) {
        errorPlaylistNombre.innerText = 'El nombre de la playlist debe tener al menos 3 caracteres';
        errorPlaylistNombre.style.display = 'block';
    } else {
        errorPlaylistNombre.innerText = '';
        errorPlaylistNombre.style.display = 'none';
    }
}

function validarCapacidad() {
    var capacidad = document.getElementById('swal-playlist-capacidad').value;
    var errorCapacidad = document.getElementById('errorCapacidadCrear');

    if (isNaN(capacidad) || capacidad <= 0 || capacidad.length > 4) {
        errorCapacidad.innerText = 'Por favor introduce un valor numérico válido para la capacidad (máximo 4 cifras)';
        errorCapacidad.style.display = 'block';
    } else {
        errorCapacidad.innerText = '';
        errorCapacidad.style.display = 'none';
    }
}

function validarCapacidadVip() {
    var capacidadVip = document.getElementById('swal-playlist-capacidadvip').value;
    var errorCapacidadVip = document.getElementById('errorCapacidadVipCrear');

    if (isNaN(capacidadVip) || capacidadVip <= 0 || capacidadVip.length > 4) {
        errorCapacidadVip.innerText = 'Por favor introduce un valor numérico válido para la capacidad VIP (máximo 4 cifras)';
        errorCapacidadVip.style.display = 'block';
    } else {
        errorCapacidadVip.innerText = '';
        errorCapacidadVip.style.display = 'none';
    }
}

function validarFoto() {
    var fotoInput = document.getElementById('swal-foto');
    var fotoNombre = fotoInput.files[0];
    var errorFoto = document.getElementById('errorFotoCrear');
    var allowedExtensions = /(\.jpg|\.jpeg|\.png|\.gif)$/i;

    if (!allowedExtensions.exec(fotoNombre.name)) {
        errorFoto.innerText = 'Formato de imagen no válido';
        errorFoto.style.display = 'block';
    } else {
        errorFoto.innerText = '';
        errorFoto.style.display = 'none';
    }
}


function añadirEvento(nombre, descripcion, fotoNombre, fechaInicio, fechaFin, djNombre, playlistNombre, capacidad, capacidadVip) {
    var formdata = new FormData();
    var csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    var userId = $('meta[name="user-id"]').attr('content');
    formdata.append('_token', csrfToken);
    formdata.append('nombre', nombre);
    formdata.append('descripcion', descripcion);
    formdata.append('fotoNombre', fotoNombre);
    formdata.append('fechaInicio', fechaInicio);
    formdata.append('fechaFin', fechaFin);
    formdata.append('djNombre', djNombre);
    formdata.append('playlistNombre', playlistNombre);
    formdata.append('capacidad', capacidad);
    formdata.append('capacidadVip', capacidadVip);

    var ajax = new XMLHttpRequest();
    ajax.open('POST', '/eventoNew');
    ajax.onload = function() {
        var response = JSON.parse(ajax.responseText);
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
            console.log('ok')
            crearGrupoEvento(nombre, fotoNombre);
        } else {
            if (response.error) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: response.error
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Hubo un problema al añadir el evento. Por favor, inténtalo de nuevo más tarde.'
                });
            }
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


//ver solicitudes 
//actualizar lista solicitudes casa segundo
setInterval(function() {
    mostrarSolicitud(); //actualizar mostrarSolictud()




}, 1000);

// MOSTRAR SOLICITUD 
mostrarSolicitud('');
//funcion para mostarr las solcitudes
// Función para mostrar las solicitudes
function mostrarSolicitud() {
    var solicitudes = document.getElementById('solicitudes'); // Obtener elemento con el id "solicitudes"
    var tabla = document.getElementById('tablaSolicitudes'); // Obtener elemento con el id "tablaSolicitudes"


    // Creamos ajax
    var ajax = new XMLHttpRequest();
    // Definimos el método y la URL
    ajax.open('GET', '/solicitudes', true);
    ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    ajax.onreadystatechange = function() {
        if (ajax.readyState == 4) {
            if (ajax.status == 200) {
                try {
                    var json = JSON.parse(ajax.responseText);
                    if (json.error) {
                        // Manejar el error, por ejemplo, mostrando un mensaje al usuario
                        console.error('Error en la respuesta del servidor:', json.error);
                    } else {
                        // Resto del código para manejar la respuesta exitosa
                        if (json.solicitudes.length === 0) {
                            document.getElementById("notificacion").innerHTML = "0";
                            solicitudes.innerHTML = "<p>Actualmente no hay solicitudes.</p>";
                            // Oculta la tabla y el encabezado si no hay solicitudes
                            tabla.style.display = 'none';

                        } else {
                            var tablaHTML = "";
                            json.solicitudes.forEach(function(item) {
                                // Construye una fila de la tabla con los datos del elemento actual
                                console.log(item);
                                var str = "<tr><td>" + item.email + "</td>";
                                str += "<td>" + item.DNI + "</td>";
                                str += "<td><a target='_blank' href=../doc/cv/" + item.cv + " style='color: black;'>" + item.cv + "</a></td>";
                                str += "<td><a download=" + item.cv + " href=../doc/cv/" + item.cv + " style='color: black;' ><i class='fa-solid fa-download' style='color: #F5763B;'></i></a></td>";
                                str += "<td><button type='button' id='aceptar' onclick='aceptarSolicitud(" + item.id + ")'><i class='fa-solid fa-circle-check' style='color: #45d408; cursor: pointer;'></i></button></td>";
                                str += "<td><button type='button' id='rechazar' onclick='rechazarSolicitud(" + item.id + ")'><i class='fa-solid fa-circle-xmark' style='color: #ff0000; cursor: pointer;' ></i></button></td>";
                                str += "</tr>";
                                tablaHTML += str;
                            });
                            solicitudes.innerHTML = tablaHTML;
                            console.log(json.count)
                            console.log("entra")
                            if (json.count === undefined || json.count === null) {
                                document.getElementById("notificacion").innerHTML = "0";
                                mostrarSolicitud();
                            } else {
                                document.getElementById("notificacion").innerHTML = json.count;
                            }


                            // Muestra la tabla y el encabezado si hay solicitudes
                            /*      tabla.style.display = 'block';
                                 h3Solicitud.style.display = 'block'; */
                        }
                    }
                } catch (e) {
                    console.error('Error al parsear JSON:', e);
                }
            } else {
                console.error('Error en la solicitud HTTP:', ajax.status);
            }
        }
    };
    ajax.send(); // Envía la solicitud HTTP al servidor 
}

var notificacion = document.getElementById("campana");
notificacion.addEventListener("click", function() {
    var solicitudes = document.getElementById('tablaSolicitudes');
    /*    var viewPlaylist = document.getElementById('playlist');
     */
    if (solicitudes.style.display === 'none') {
        /*  viewPlaylist.style.display = 'none'; */
        solicitudes.style.display = 'block';


    } else {
        /*  viewPlaylist.style.display = 'block'; */
        solicitudes.style.display = 'none';


    }
});


/* aceptar la solicitud del gestor */
function aceptarSolicitud(id) {
    var csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    Swal.fire({
        title: "Aceptar camarero",
        text: `¿Seguro que desea aceptar al camarero?`,
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#d33",
        cancelButtonColor: "#0052CC",
        confirmButtonText: "Aceptar",
        cancelButtonText: "Cancelar"
    }).then((result) => {
        if (result.isConfirmed) {
            fetch(`solcitudesaceptar/${id}`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken
                    }
                })
                .then(response => {
                    if (!response.ok) {

                        Swal.fire({
                            title: "Ha ocurrido un error",
                            text: "Error al aceptar el usuario",
                            icon: "error"
                        });
                        return;
                    }
                    return response.json();
                })
                .then(data => {
                    console.log(data);

                    if (data.success == true) {
                        Swal.fire({
                            title: "Aceptado",
                            text: "Camarero aceptado correctamente",
                            icon: "success"
                        }).then(() => {
                            mostrarSolicitud();
                            listarPersonal('');

                        });
                    } else {
                        Swal.fire({
                            title: "Error al eliminar el usuario",
                            text: data.message,
                            icon: "error"
                        });
                    }
                })
                .catch(error => console.error('Error al aceptar el gestor:', error));
        }
    });
}

/* rechazar la solicitud del gestor */
function rechazarSolicitud(id) {
    var csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    Swal.fire({
        title: "Rechazar camarero",
        text: `¿Seguro que desea rechazar el camarero?`,
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#d33",
        cancelButtonColor: "#0052CC",
        confirmButtonText: "Aceptar",
        cancelButtonText: "Cancelar"
    }).then((result) => {
        if (result.isConfirmed) {
            fetch(`solcitudrechazar/${id}`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken
                    }
                })
                .then(response => {
                    if (!response.ok) {

                        Swal.fire({
                            title: "Ha ocurrido un error",
                            text: "Error al rechazar el usuario",
                            icon: "error"
                        });
                        return;
                    }
                    return response.json();
                })
                .then(data => {
                    console.log(data);

                    if (data.success == true) {
                        Swal.fire({
                            title: "Rechazado",
                            text: "Camarero rechazado correctamente",
                            icon: "success"
                        }).then(() => {

                            mostrarSolicitud();
                            listarPersonal('');

                        });
                    } else {
                        Swal.fire({
                            title: "Error al rechazar el usuario",
                            text: data.message,
                            icon: "error"
                        });
                    }
                })
                .catch(error => console.error('Error al aceptar el gestor:', error));
        }
    });
}



/* mostrar personal */

/* Filtros artistas */
var buscar2 = document.getElementById("buscar2");
document.getElementById("buscar2").addEventListener("keyup", () => {
    const valor = buscar2.value;
    listarPersonal(valor);
});

listarPersonal('');

function listarPersonal(valor) {
    var resultado = document.getElementById('personalTabla');
    var formdata = new FormData();
    var csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    formdata.append('_token', csrfToken);
    formdata.append('busqueda', valor);
    var ajax = new XMLHttpRequest();
    ajax.open('POST', '/personal', true);
    ajax.onload = function() {
        if (ajax.status == 200) {
            var json = JSON.parse(ajax.responseText);
            var tabla = '';
            json.forEach(function(item) {
                var fotoPerfil = item.foto ? `../img/profiles/${item.foto}` : '../img/profiles/foto.png';

                tabla += `
                    <div class='row'>
                        <i id='eliminar' onclick='eliminarPersonal(${item.id})' class='fa-solid fa-trash' style='color: #f5763b; float:right; margin-bottom: 5%'></i>   
                        
                        <div id='informacion${item.id}'>
                            <img style='height: 30%; width: 30%; border-radius: 50%;' src='${fotoPerfil}' alt=''>
                            <h3>${item.name}</h3>
                            <h5>${item.email}</h5>
                        </div>
                    </div>`;
            });
            resultado.innerHTML = tabla;
        } else {
            resultado.innerText = 'Error';
        }
    }
    ajax.send(formdata);
}



function eliminarPersonal(id) {

    var csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    Swal.fire({
        title: "Eliminar personal",
        text: `¿Seguro que desea eliminar el personal?`,
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#d33",
        cancelButtonColor: "#0052CC",
        confirmButtonText: "Eliminar",
        cancelButtonText: "Cancelar"
    }).then((result) => {
        if (result.isConfirmed) {
            fetch(`eliminarPersonal/${id}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken
                    }
                })
                .then(response => {
                    if (!response.ok) {

                        Swal.fire({
                            title: "Ha ocurrido un error",
                            text: "Error al eliminar personal",
                            icon: "error"
                        });
                        return;
                    }
                    return response.json();
                })
                .then(data => {
                    console.log(data);

                    if (data.success == true) {
                        Swal.fire({
                            title: "Borrado",
                            text: "Personal eliminado correctamente",
                            icon: "success"
                        }).then(() => {
                            listarPersonal('');


                        });
                    } else {
                        Swal.fire({
                            title: "Error al eliminar el personal",
                            text: data.message,
                            icon: "error"
                        });
                    }
                })
                .catch(error => console.error('Error al eliminar el personal:', error));
        }
    });

}

var formModificarDisco = document.getElementById("formModificarDisco");
formModificarDisco.addEventListener("click", function() {
    var discotecaInicio = document.getElementById("discotecaInicio");
    var Modificardiscoteca = document.getElementById("Modificardiscoteca");
    var imagenDiscoteca = document.getElementById("imagenDiscoteca");
    var modificarFotoLabel = document.getElementById("modificarFotoLabel");

    if (discotecaInicio.style.display === "none") {
        discotecaInicio.style.display = "block";
        Modificardiscoteca.style.display = "none";
        imagenDiscoteca.style.display = "block";
        modificarFotoLabel.style.display = "none";
    } else {
        discotecaInicio.style.display = "none";
        Modificardiscoteca.style.display = "block";
        imagenDiscoteca.style.display = "none";
        modificarFotoLabel.style.display = "block";


    }
});




function discoteca() {
    var resultado = document.getElementById('discotecaInicio');

    var formdata = new FormData();
    var csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    formdata.append('_token', csrfToken);

    var ajax = new XMLHttpRequest();
    ajax.open('GET', '/discoteca');
    ajax.onload = function() {
        if (ajax.status == 200) {
            var json = JSON.parse(ajax.responseText);
            console.log(json);

            // Actualizar datos de la discoteca
            document.getElementById('discotecaName').innerHTML = json.name;
            document.getElementById('discotecaDireccion').innerHTML = json.direccion;

            // Actualizar formulario de modificación
            document.getElementById('nameDiscoteca').value = json.name;
            document.getElementById('direccionDiscoteca').value = json.direccion;
            document.getElementById('capacidadDiscoteca').value = json.capacidad;
            var nombreImagen = json.image;

            // Establecer la fuente de las imágenes
            document.getElementById('imagenDiscoteca').src = '../img/discotecas/' + nombreImagen;
            document.getElementById('imagenPreview').src = '../img/discotecas/' + nombreImagen;

            // Aquí puedes agregar el código para mostrar eventos si los necesitas
        } else {
            resultado.innerText = "Error";
        }
    };
    ajax.send(formdata);
}

function validarFormUpdateDiscoteca(id) {
    var nombre = document.getElementById('nameDiscoteca').value.trim();
    var direccion = document.getElementById('direccionDiscoteca').value.trim();
    var capacidad = document.getElementById('capacidadDiscoteca').value.trim();

    var nombreError = document.getElementById('errorNameDiscoteca');
    var direccionError = document.getElementById('errorDireccionDiscoteca');
    var capacidadError = document.getElementById('errorCapacidadDiscoteca');

    // Validar nombre
    if (nombre === "") {
        nombreError.innerText = 'Por favor introduce un nombre';
        nombreError.style.display = 'block';
    } else if (nombre.length < 3) {
        nombreError.innerText = 'Formato Incorrecto';
        nombreError.style.display = 'block';
    } else {
        nombreError.innerText = '';
        nombreError.style.display = 'none';
    }

    // Validar dirección
    if (direccion === "") {
        direccionError.innerText = 'Por favor introduce una dirección';
        direccionError.style.display = 'block';
    } else if (direccion.length < 8) {
        direccionError.innerText = 'Introduce una dirección que sea válida';
        direccionError.style.display = 'block';
    } else {
        direccionError.innerText = '';
        direccionError.style.display = 'none';
    }

    // Validar capacidad
    if (capacidad === "" || isNaN(capacidad) || capacidad <= 0) {
        capacidadError.innerText = 'Por favor introduce un valor numérico válido';
        capacidadError.style.display = 'block';
    } else {
        capacidadError.innerText = '';
        capacidadError.style.display = 'none';
    }

    return !nombreError.innerText && !direccionError.innerText && !capacidadError.innerText;
}

function handleUpdateDiscoteca(id) {
    if (validarFormUpdateDiscoteca()) {
        updateDiscoteca(id);
    }
}

function updateDiscoteca(id) {
    var name = document.getElementById("nameDiscoteca").value.trim();
    var direccion = document.getElementById("direccionDiscoteca").value.trim();
    var capacidad = document.getElementById("capacidadDiscoteca").value.trim();
    var imagen = document.getElementById("swal-foto-discoteca").files[0];

    var formdata = new FormData();
    var csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    formdata.append('_token', csrfToken);
    formdata.append('name', name);
    formdata.append('direccion', direccion);
    formdata.append('capacidad', capacidad);
    formdata.append('id', id);
    if (imagen) {
        formdata.append('imagen', imagen);
    }

    var ajax = new XMLHttpRequest();
    ajax.open('POST', '/discotecaUpdate');
    ajax.onload = function() {
        if (ajax.status == 200) {
            Swal.fire({
                position: "top-end",
                title: "Discoteca Modificada.",
                showConfirmButton: false,
                timer: 1500
            });
            discoteca();
            document.getElementById("discotecaInicio").style.display = "block";
            document.getElementById("Modificardiscoteca").style.display = "none";
            document.getElementById("imagenDiscoteca").style.display = "block";
            document.getElementById("modificarFotoLabel").style.display = "none";
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