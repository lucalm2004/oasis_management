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

    ajax.onload = function() {
        if (ajax.status >= 200 && ajax.status < 300) {
            var json = JSON.parse(ajax.responseText);
            var listar_eventos = json.listar_eventos;
            console.log(json);
            var tabla = "";

            listar_eventos.forEach(function(item) {
                var str = "<tr>";

                str += "<td>" + item.nombre_evento + "</td>";
                str += "<td>" + item.descripcion_evento + "</td>";
                str += "<td><img id='imageP' src='../img/flyer/" + item.flyer + "'" + " alt='Imagen' class='imgtamaño'></td>";
                str += "<td>" + item.fecha_inicio + "</td>";
                str += "<td>" + item.fecha_final + "</td>";
                str += "<td>" + item.dj + "</td>";
                str += "<td>" + item.name_playlist + "</td>";
                str += "<td>" + item.nombre_discoteca + "</td>";
                str += "</tr>";
                tabla += str;
            });
            document.getElementById("resultado").innerHTML = tabla;
        } else {
            document.getElementById("resultado").innerHTML = 'Error';
        }
    };
    ajax.send(formdata);
}