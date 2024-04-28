/* Filtros discotecas por nombre y por ciudad*/
buscar.addEventListener("keyup", () => {
    const valor = buscar.value;
    const ciudad = document.getElementById("ciudad").value;
    ListarDiscotecas(valor, ciudad);
});

ciudad = document.getElementById("ciudad");
ciudad.addEventListener("change", () => {
    const valor = buscar.value;
    const ciudad = document.getElementById("ciudad").value;

    ListarDiscotecas(valor, ciudad);
});

/* Que se muestren las discotecas dependiendo de los filtros */

ListarDiscotecas('', '');

function ListarDiscotecas(valor, ciudad) {
    var resultado = document.getElementById('resultado');
    var formdata = new FormData();
    var csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    formdata.append('_token', csrfToken);
    formdata.append('busqueda', valor);
    formdata.append('ciudad', ciudad);
    var ajax = new XMLHttpRequest();
    ajax.open('POST', 'admin2/cruddiscotecas');
    ajax.onload = function() {
        var str = "";
        if (ajax.status == 200) {
            var json = JSON.parse(ajax.responseText);
            var tabla = '';
            json.forEach(function(item) {
                str += "<tr>";
                str += "<td>" + item.id + "</td>";
                str += "<td>" + item.name + "</td>";
                str += "<td>" + item.image + "</td>";
                str += "<td>" + item.ciudad.name + "</td>";
                str += "<td>" + item.direccion + "</td>";
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

/* obteneer lista de ciudades para el filtro */
obtenerciudades('');

function obtenerciudades() {
    fetch(`admin2/cruddiscotecas/ciudades`)
        .then(response => response.json())
        .then(data => {
            var selectMarcador = document.getElementById('ciudad');
            selectMarcador.innerHTML = '';

            var blankOption = document.createElement('option');
            blankOption.value = '';
            blankOption.text = '';
            selectMarcador.appendChild(blankOption);


            data.forEach(ciudad => {
                var option = document.createElement('option');
                option.value = ciudad.id;
                option.text = ciudad.name;
                selectMarcador.appendChild(option);
            });
        })
        .catch(error => console.error('Error al obtener los marcadores:', error));
}

/* eliminar una discoteca */
function Eliminar(id) {
    var ciudad = document.getElementById("ciudad").value;
    console.log(id);
    var csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    Swal.fire({
        title: "Eliminar Discoteca",
        text: `¿Seguro que desea eliminar la discoteca?`,
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#d33",
        cancelButtonColor: "#0052CC",
        confirmButtonText: "Eliminar",
        cancelButtonText: "Cancelar"
    }).then((result) => {
        if (result.isConfirmed) {
            fetch(`admin2/cruddiscotecas/${id}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken
                    }
                })
                .then(response => {
                    console.log(response);
                    if (!response.ok) {

                        Swal.fire({
                            title: "Ha ocurrido un error",
                            text: "Error al eliminar la discoteca",
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
                            title: "Borrada",
                            text: "Discoteca eliminada correctamente",
                            icon: "success"
                        }).then(() => {
                            ListarDiscotecas('', ciudad);

                        });
                    } else {
                        Swal.fire({
                            title: "Error al eliminar la discoteca",
                            text: data.message,
                            icon: "error"
                        });
                    }
                })
                .catch(error => console.error('Error al eliminar la discoteca:', error));
        }
    });
}