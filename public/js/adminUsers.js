/* Listar Usuarios con filtros */
buscar.addEventListener("keyup", () => {
    const valor = buscar.value;
    if (valor == "") {
        ListarUsuarios('');
    } else {
        ListarUsuarios(valor);
    }
});

ListarUsuarios('');

function ListarUsuarios(valor) {
    var resultado = document.getElementById('resultado');
    var formdata = new FormData();
    var csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    formdata.append('_token', csrfToken);
    formdata.append('busqueda', valor);
    var ajax = new XMLHttpRequest();
    ajax.open('POST', 'admin/crudusuarios');
    ajax.onload = function() {
        var str = "";
        if (ajax.status == 200) {
            var json = JSON.parse(ajax.responseText);
            var tabla = '';
            json.forEach(function(item) {
                str += "<tr>";
                str += "<td>" + item.id + "</td>";
                str += "<td>" + item.name + "</td>";
                str += "<td>" + item.email + "</td>";
                str += "<td>" + item.rol.name + "</td>";
                str += "<td><button onclick='CambiarEstado(" + item.id + ", " + item.habilitado + ")'>" + (item.habilitado == 1 ? "Habilitado" : "Deshabilitado") + "</button></td>";
                str += "<td><button onclick='Editar(" + item.id + ")'>Editar</button></td>";
                str += "<td><button onclick='Eliminar(" + item.id + ")'>Eliminar</button></td>";
                str += "</tr>";
            });
            tabla += str;
            resultado.innerHTML = tabla;
        } else {
            resultado.innerText = 'Error';
        }
    }
    ajax.send(formdata);
}

// Función para obtener los marcadores asociados a un tag seleccionado
function obtenerRoles() {
    console.log(tagId);
    fetch(`crudgymkhana/marcadores/${tagId}`)
        .then(response => response.json())
        .then(data => {
            console.log(data);
            // Limpiar opciones anteriores del segundo select
            var selectMarcador = document.getElementById('markerSeleccionado');
            selectMarcador.innerHTML = '';

            // Agregar una opción en blanco al segundo select
            var blankOption = document.createElement('option');
            blankOption.value = ''; // Valor vacío
            blankOption.text = ''; // Texto descriptivo
            selectMarcador.appendChild(blankOption);

            // Iterar sobre los marcadores obtenidos y agregarlos al segundo select
            data.forEach(marcador => {
                var option = document.createElement('option');
                option.value = marcador.id;
                option.text = marcador.name; // Suponiendo que el nombre del marcador se almacena en la propiedad 'nombre'
                selectMarcador.appendChild(option);
            });
        })
        .catch(error => console.error('Error al obtener los marcadores:', error));
}