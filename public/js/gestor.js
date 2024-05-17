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
                str += "    <input id='capacidad" + item.id + "' type='number' value='" + item
                    .capacidad + "'>"
                str += "    <input id='capacidadVip" + item.id + "' type='number' value='" + item
                    .capacidadVip + "'>"
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

    var capacidad = 'capacidad' + id;
    var capacidad = document.getElementById(capacidad).value;

    var capacidadVip = 'capacidadVip' + id;
    var capacidadVip = document.getElementById(capacidadVip).value;


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
    formdata.append('capacidad', capacidad);
    formdata.append('capacidadVip', capacidadVip);



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
        <input id="swal-playlist-capacidad" class="swal2-input" type='number' placeholder="Capacidad del evento">
        <input id="swal-playlist-capacidadvip" class="swal2-input" type='number' placeholder="Capacidad de mesas Vip">
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
            const capacidad = Swal.getPopup().querySelector('#swal-playlist-capacidad').value;
            const capacidadVip = Swal.getPopup().querySelector('#swal-playlist-capacidadvip').value;

            if (!nombre || !descripcion || !fechaInicio || !fechaFin || !djNombre || !
                playlistNombre || !capacidad || !capacidadVip) {
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

            // Aquí deberías enviar estos datos a la función añadirCat()
            añadirEvento(nombre, descripcion, fotoNombre, fechaInicio, fechaFin, djNombre,
                playlistNombre, capacidad, capacidadVip);
        }
    });

});



function añadirEvento(nombre, descripcion, fotoNombre, fechaInicio, fechaFin, djNombre, playlistNombre, capacidad, capacidadVip) {
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
    formdata.append('capacidad', capacidad);
    formdata.append('capacidadVip', capacidadVip);
    console.log(capacidad, capacidadVip)


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
                var fotoPerfil = item.foto ? `../img/fotoPerfil/${item.foto}` : '../img/foto.png';

                tabla += `
                    <div class='row'>
                        <i id='eliminar' onclick='eliminarPersonal(${item.id})' class='fa-solid fa-trash' style='color: #f5763b; float:right; margin-bottom: 5%'></i>   
                        <img style='height: 30%; width: 30%; border-radius: 50%;' src='${fotoPerfil}' alt=''>
                        <div id='informacion${item.id}'>
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