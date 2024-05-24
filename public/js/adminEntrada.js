/* Filtros discotecas por nombre y por ciudad*/
buscar.addEventListener("keyup", () => {
    const valor = buscar.value;
    const discoteca = document.getElementById("discoteca").value;
    listarRegistro(valor, discoteca);
});

discoteca = document.getElementById("discoteca");
discoteca.addEventListener("change", () => {
    const valor = buscar.value;
    const discoteca = document.getElementById("discoteca").value;

    listarRegistro(valor, discoteca);
});


/* Que se muestren los usuarios dependiendo de los filtros */

listarRegistro('', '');

function listarRegistro(valor, discoteca) {
    var resultado = document.getElementById('resultado');
    var formdata = new FormData();
    var csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    formdata.append('_token', csrfToken);
    formdata.append('busqueda', valor);
    formdata.append('discoteca', discoteca);
    var ajax = new XMLHttpRequest();
    ajax.open('POST', 'admin8/crudentrada');
    ajax.onload = function() {
        var str = "";
        if (ajax.status == 200) {
            var json = JSON.parse(ajax.responseText);
            var tabla = '';
            json.forEach(function(item) {
                str += "<tr>";
                str += "<td>" + item.id + "</td>";
                str += "<td>" + item.nombre_discoteca + " - " + item.evento_name + "</td>";
                str += "<td>" + item.total_entradas + "</td>";
                str += "<td>" + item.precio_total + "</td>";
                str += "<td>" + item.fecha + "</td>";
                if (item.tipo_entrada === 0) {
                    str += "<td>Normal</td>";

                } else {
                    str += "<td>VIP</td>";

                }

                str += "<td>" + item.email_usuario + "</td>";
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