const listar_evento = document.getElementById('buscar');
window.onload = () => {

    const buscar = document.getElementById('buscar');
    const fecha_inc = document.getElementById('fecha_inc');

    buscar.addEventListener("keyup", () => {
        const buscarValor = buscar.value;
        const fecha_incValor = fecha_inc.value;
        if (buscarValor == "" && fecha_incValor == "") {
            ListarEventos("", "");
        } else {
            ListarEventos(buscarValor, fecha_incValor);
        }
    });
    ListarEventos("", "");
};


fecha_inc.addEventListener("click", () => {
    const buscarValor = buscar.value;
    const fecha_incValor = fecha_inc.value;
    if (buscarValor == "" && fecha_incValor == "") {
        ListarEventos("", "");
    } else {
        ListarEventos(buscarValor, fecha_incValor);
    }
});
ListarEventos("", "");


const buscar = document.getElementById('buscar');

function ListarEventos(buscar, fecha_inc) {
    var ajax = new XMLHttpRequest();
    var formdata = new FormData();
    console.log(buscar);
    // Agrega el token CSRF al FormData
    var csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    formdata.append('_token', csrfToken);
    formdata.append('busqueda', buscar);
    formdata.append('fecha', fecha_inc);
    ajax.open('POST', 'eventos'); // Cambiado a mÃ©todo POST para enviar datos
    var resultado = document.getElementById('crudCamarero');

    ajax.onload = function() {
        if (ajax.status >= 200 && ajax.status < 300) {
            var json = JSON.parse(ajax.responseText);
            var listar_eventos = json.listar_eventos;
            var count = 0;
            console.log(json);
            var tabla = "";

            listar_eventos.forEach(function(item) {
                str = " <div class='row'>"
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


window.onload = function() {

    ListarEventos('');

    listarPlaylist('');
};
var playListOasisfy = document.getElementById("playListOasisfy");
var SongsOasisfy = document.getElementById("SongsOasisfy");

playListOasisfy.addEventListener("click", function() {

    var viewPlaylist = document.getElementById('playlist');
    var playlist_musica = document.getElementById('playlist_musica');

    if (viewPlaylist.style.display === 'none') {

        playlist_musica.style.display = 'none';
        viewPlaylist.style.display = 'flex';
        playListOasisfy.classList.add('home-p');
        SongsOasisfy.classList.remove('home-p');
    } else {

        viewPlaylist.style.display = 'none';

        SongsOasisfy.classList.add('home-p');
        playListOasisfy.classList.remove('home-p');
    }
});

SongsOasisfy.addEventListener("click", function() {

    var viewPlaylist = document.getElementById('playlist');

    if (viewCanciones.style.display === 'none') {
        viewPlaylist.style.display = 'none';

        playlist_musica.style.display = 'none';

        SongsOasisfy.classList.add('home-p');
        playListOasisfy.classList.remove('home-p');
    } else {
        viewPlaylist.style.display = 'flex';

        playListOasisfy.classList.add('home-p');
        SongsOasisfy.classList.remove('home-p');
    }
});



function oasisfy() {

    var viewPlaylist = document.getElementById('playlist');

    if (elementoEdicion.style.display === 'none') {
        elementoInformacion.style.display = 'none';
        elementoEdicion.style.display = 'flex';
    } else {
        elementoInformacion.style.display = 'block';
        elementoEdicion.style.display = 'none';
    }
}

function listarPlaylist() {
    var resultado = document.getElementById('playlist');
    var formdata = new FormData();
    var csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    formdata.append('_token', csrfToken);

    var ajax = new XMLHttpRequest();
    ajax.open('POST', '/playlistView2');
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
    console.log(id);

    var formdata = new FormData();
    var csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    formdata.append('_token', csrfToken);
    formdata.append('id', id);


    var ajax = new XMLHttpRequest();
    ajax.open('POST', '/editarPlaylist2');
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