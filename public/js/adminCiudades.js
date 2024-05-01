/* Filtros usuarios */
buscar.addEventListener("keyup", () => {
    const valor = buscar.value;
    if (valor == "") {
        listarCiudades('');
    } else {
        listarCiudades(valor);
    }
});


/* Que se muestren los usuarios dependiendo de los filtros */

listarCiudades('');

function listarCiudades(valor) {
    var resultado = document.getElementById('resultado');
    var formdata = new FormData();
    var csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    formdata.append('_token', csrfToken);
    formdata.append('busqueda', valor);
    var ajax = new XMLHttpRequest();
    ajax.open('POST', 'admin4/crudciudades');
    ajax.onload = function() {
        var str = "";
        if (ajax.status == 200) {
            var json = JSON.parse(ajax.responseText);
            var tabla = '';
            json.forEach(function(item) {
                str += "<tr>";
                str += "<td>" + item.id + "</td>";
                str += "<td>" + item.name + "</td>";
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


/* eliminar ciudades */

function Eliminar(id) {
    var csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    Swal.fire({
        title: "Eliminar ciudad",
        text: `¿Seguro que desea eliminar la ciudad?`,
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#d33",
        cancelButtonColor: "#0052CC",
        confirmButtonText: "Eliminar",
        cancelButtonText: "Cancelar"
    }).then((result) => {
        if (result.isConfirmed) {
            fetch(`admin4/crudciudades/${id}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken
                    }
                })
                .then(response => {
                    if (!response.ok) {

                        Swal.fire({
                            title: "Ha ocurrido un error",
                            text: "Error al eliminar la ciudad",
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
                            text: "Ciudad eliminada correctamente",
                            icon: "success"
                        }).then(() => {
                            listarCiudades('');

                        });
                    } else {
                        Swal.fire({
                            title: "Error al eliminar la ciudad",
                            text: data.message,
                            icon: "error"
                        });
                    }
                })
                .catch(error => console.error('Error al eliminar la ciudad:', error));
        }
    });
}

/* Que se muestre el formulario de modificar */
function Editar(id) {
    // Obtener los datos de la usuario a partir del ID
    fetch(`admi4/crudciudades/modadmin/${id}`)
        .then(response => response.json())
        .then(data => {
            // Crear el formulario con los datos recuperados
            Swal.fire({
                title: "Modificar Ciudad",
                confirmButtonColor: "#0052CC",
                confirmButtonText: "Guardar Cambios",
                cancelButtonText: "Cancelar",
                cancelButtonColor: "transparent",
                showCancelButton: true,
                animation: false,
                html: `
                <form id="ModificarForm">
                <input type="hidden" name="id" value="${data.id}">
                <div class="rownew">
                    <div class="col-3">
                        <label class="estiloslabel" for="nombreModificar">Nombre</label>
                    </div>
                    <div class="col-9">
                        <span class="form-error-label" id="nombreErrorModificar"></span>
                        <input type="text" name="nombre" value="${data.name}" id="nombreModificar" class="estilosinput">
                    </div>
                </div>
                
            
                </form>
                <style>.swal2-cancel {color: black !important;}</style>
                `,
                preConfirm: () => {
                    return validarFormularioMod();
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    enviarFormularioModificado(data.id, result.value);
                }
            });



            // Agregar evento keyup al formulario después de crearlo
            document.getElementById('ModificarForm').addEventListener('keyup', validarFormularioMod);


        })
        .catch((error) => {
            Swal.fire({
                title: "Ha ocurrido un error.",
                text: error,
                icon: "error"
            });
        });
}

/* validar form de modificar */

function validarFormularioMod() {
    var nombre = document.getElementById('nombreModificar').value;

    var nombreError = document.getElementById('nombreErrorModificar');


    // Validar nombre
    if (nombre === "") {
        nombreError.innerText = 'Por favor ingresa un nombre';
        nombreError.style.display = 'block';
    } else if (nombre.length < 5) {
        nombreError.innerText = 'Formato Incorrecto';
        nombreError.style.display = 'block';
    } else {
        nombreError.innerText = '';
    }



    return {
        nombre: nombre,

    };
}

/* enviar formulario para actualizar */

function enviarFormularioModificado(id, formData) {
    var nombre = formData.nombre;


    var bodyData = {
        nombre: nombre,

    };

    // Obtener el token CSRF del documento HTML
    var token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    // Incluir el token CSRF en el encabezado de la solicitud
    var headers = {
        'Content-Type': 'application/json',
        'Accept': 'application/json',
        'X-CSRF-TOKEN': token
    };

    fetch(`admin4/crudciudades/actualizar/${id}`, {
            method: 'POST',
            headers: headers,
            body: JSON.stringify(bodyData),
        })
        .then(response => {
            if (response.ok) {
                Swal.fire({
                    title: "Success",
                    text: "Cambios guardados correctamente",
                    icon: "success"
                }).then(() => {
                    Swal.close();
                    listarCiudades('');
                });
            } else {
                Swal.fire({
                    title: "Error",
                    text: "Ha ocurrido un problema al guardar los cambios",
                    icon: "error"
                });
            }
        })
        .catch(error => {
            Swal.fire({
                title: "Error",
                text: "Ha ocurrido un problema al guardar los cambios",
                icon: "error"
            });
            console.error('Error:', error);
        });
}

/* Crear usurio */
var crearCiudad = document.getElementById("crearCiudad");
crearCiudad.addEventListener("click", function() {
    mostrarFormulario();

});

function mostrarFormulario() {
    Swal.fire({
        title: "Crear Ciudad",
        confirmButtonColor: "#0052CC",
        confirmButtonText: "Crear",
        html: `
        <form id="CrearForm">
        <div class="rownew">
            <div class="col-3">
                <label class="estiloslabel" for="nombreCrear">Nombre</label>
            </div>
            <div class="col-9">
                <span class="form-error-label" id="nombreErrorCrear"></span>
                <input type="text" name="nombre" id="nombreCrear" class="estilosinput">
            </div>
        </div>
        
       
        </form>

        `,
        preConfirm: () => {
            return validarFormulario(); // Llama a la función validarFormulario antes de cerrar la ventana modal
        }
    }).then((result) => {
        if (result.isConfirmed) {
            // El formulario es válido, puedes enviarlo
            enviarFormulario();
        }
    });
    document.getElementById('CrearForm').addEventListener('keyup', validarFormulario);


}




/* validar formulario crar bonificacion */

function validarFormulario() {

    var nombre = document.getElementById('nombreCrear').value;


    var nombreError = document.getElementById('nombreErrorCrear');



    // Validar nombre
    if (nombre === "") {
        nombreError.innerText = 'Por favor ingresa un nombre';
        nombreError.style.display = 'block';
    } else if (nombre.length < 5) {
        nombreError.innerText = 'Formato Incorrecto';
        nombreError.style.display = 'block';
    } else {
        nombreError.innerText = '';
    }




    // Habilitar o deshabilitar el botón de enviar según la validez del formulario
    /* var enviarBtn = document.getElementById('btnEnviar'); */
    var formularioValido = !nombreError.innerText;
    /*  enviarBtn.disabled = !formularioValido; */

    return formularioValido;
}

/* enviar los campos para insertarlos */

function enviarFormulario() {
    var nombre = document.getElementById('nombreCrear').value;


    var csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    fetch('admin4/crudciudades/insertuser', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            },
            body: JSON.stringify({
                nombre: nombre


            })
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Error al crear la bonificación: ' + response.statusText);
            }
            return response.json();
        })
        .then(data => {


            Swal.fire({
                title: "Creada",
                text: "Bonificación creada correctamente",
                icon: "success"
            }).then(() => {
                listarCiudades('');

            });
        })
        .catch(error => {
            console.error('Error:', error);
            Swal.fire('Error', 'Error al crear la bonificación', 'error');
        });
}