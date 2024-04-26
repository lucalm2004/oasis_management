buscar.addEventListener("keyup", () => {
    const valor = buscar.value;
    const rol = document.getElementById("rol").value;
    ListarUsuarios(valor, rol);
});

rol = document.getElementById("rol");
rol.addEventListener("change", () => {
    const valor = buscar.value;
    const rol = document.getElementById("rol").value;

    ListarUsuarios(valor, rol);
});

ListarUsuarios('', '');

function ListarUsuarios(valor, rol) {
    var resultado = document.getElementById('resultado');
    var formdata = new FormData();
    var csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    formdata.append('_token', csrfToken);
    formdata.append('busqueda', valor);
    formdata.append('rol', rol);
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
obtenerRoles('');

function obtenerRoles() {
    fetch(`admin/crudusuarios/roles`)
        .then(response => response.json())
        .then(data => {
            var selectMarcador = document.getElementById('rol');
            selectMarcador.innerHTML = '';

            var blankOption = document.createElement('option');
            blankOption.value = ''; // Valor vacío
            blankOption.text = ''; // Texto descriptivo
            selectMarcador.appendChild(blankOption);


            data.forEach(rol => {
                var option = document.createElement('option');
                option.value = rol.id;
                option.text = rol.name;
                selectMarcador.appendChild(option);
            });
        })
        .catch(error => console.error('Error al obtener los marcadores:', error));
}