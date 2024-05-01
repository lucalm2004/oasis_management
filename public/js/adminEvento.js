/* Filtros discotecas por nombre y por ciudad*/
buscar.addEventListener("keyup", () => {
    const valor = buscar.value;
    const discoteca = document.getElementById("discoteca").value;
    listarEventos(valor, discoteca);
});

discoteca = document.getElementById("discoteca");
discoteca.addEventListener("change", () => {
    const valor = buscar.value;
    const discoteca = document.getElementById("discoteca").value;

    listarEventos(valor, discoteca);
});


/* Que se muestren los usuarios dependiendo de los filtros */

listarEventos('', '');

function listarEventos(valor, discoteca) {
    var resultado = document.getElementById('resultado');
    var formdata = new FormData();
    var csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    formdata.append('_token', csrfToken);
    formdata.append('busqueda', valor);
    formdata.append('discoteca', discoteca);
    var ajax = new XMLHttpRequest();
    ajax.open('POST', 'admin5/crudeventos');
    ajax.onload = function() {
        var str = "";
        if (ajax.status == 200) {
            var json = JSON.parse(ajax.responseText);
            var tabla = '';
            json.forEach(function(item) {
                str += "<tr>";
                str += "<td>" + item.id + "</td>";
                str += "<td>" + item.name + "</td>";
                str += "<td>" + item.descripcion + "</td>";
                str += "<td><img id='imageP' src='../img/discotecas/" + item.flyer + "'" + " alt='Imagen' class='imgtamaño'></td>";
                str += "<td>" + item.fecha_inicio + "</td>";
                str += "<td>" + item.fecha_final + "</td>";
                str += "<td>" + item.name_playlist + "</td>";
                str += "<td>" + item.dj + "</td>";
                str += "<td>" + item.nombre_discoteca + "</td>";
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

obtenerDiscotecasMod('');

function obtenerDiscotecasMod() {
    fetch(`admin/crudusuarios/discotecas`)
        .then(response => response.json())
        .then(data => {
            var selectMarcador = document.getElementById('discoteca');
            selectMarcador.innerHTML = '';

            var blankOption = document.createElement('option');
            blankOption.value = ''; // Valor vacío
            blankOption.text = ''; // Texto descriptivo
            selectMarcador.appendChild(blankOption);


            data.forEach(discoteca => {
                var option = document.createElement('option');
                option.value = discoteca.id;
                option.text = discoteca.name;
                selectMarcador.appendChild(option);
            });
        })
        .catch(error => console.error('Error al obtener los marcadores:', error));

}

/* eliminar una discoteca */
function Eliminar(id) {
    var discoteca2 = document.getElementById("discoteca").value;
    console.log(id);
    var csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    Swal.fire({
        title: "Eliminar Eventos",
        text: `¿Seguro que desea eliminar el evento?`,
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
                            text: "Error al eliminar el evento",
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
                            text: "Evento eliminado correctamente",
                            icon: "success"
                        }).then(() => {
                            listarEventos('', discoteca2);

                        });
                    } else {
                        Swal.fire({
                            title: "Error al eliminar el evento",
                            text: data.message,
                            icon: "error"
                        });
                    }
                })
                .catch(error => console.error('Error al eliminar la discoteca:', error));
        }
    });
}