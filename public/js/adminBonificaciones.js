/* Filtros usuarios */
buscar.addEventListener("keyup", () => {
    const valor = buscar.value;
    if (valor == "") {
        listarBonificaciones('');
    } else {
        listarBonificaciones(valor);
    }
});


/* Que se muestren los usuarios dependiendo de los filtros */

listarBonificaciones('');

function listarBonificaciones(valor) {
    var resultado = document.getElementById('resultado');
    var formdata = new FormData();
    var csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    formdata.append('_token', csrfToken);
    formdata.append('busqueda', valor);
    var ajax = new XMLHttpRequest();
    ajax.open('POST', 'admi3/crudbonificaciones');
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
                str += "<td>" + item.puntos + "</td>";
                str += "<td><button onclick='Editar(" + item.id + ")'><i class='fa-solid fa-pen-to-square' style='color: #320aa9;'></i></button></td>";
                str += "<td><button onclick='Eliminar(" + item.id + ")'><i class='fa-regular fa-trash-can' style='color: #ff0000;'></i></button></td>";
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

/* eliminar bonificaciones */

function Eliminar(id) {
    var csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    Swal.fire({
        title: "Eliminar bonificación",
        text: `¿Seguro que desea eliminar la bonificación?`,
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#d33",
        cancelButtonColor: "#0052CC",
        confirmButtonText: "Eliminar",
        cancelButtonText: "Cancelar"
    }).then((result) => {
        if (result.isConfirmed) {
            fetch(`admi3/crudbonificaciones/${id}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken
                    }
                })
                .then(response => {
                    if (!response.ok) {

                        Swal.fire({
                            title: "Ha ocurrido un error",
                            text: "Error al eliminar la bonificación",
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
                            text: "Bonificación eliminada correctamente",
                            icon: "success"
                        }).then(() => {
                            listarBonificaciones('');

                        });
                    } else {
                        Swal.fire({
                            title: "Error al eliminar la bonificación",
                            text: data.message,
                            icon: "error"
                        });
                    }
                })
                .catch(error => console.error('Error al eliminar la bonificación:', error));
        }
    });
}


/* Que se muestre el formulario de modificar */
function Editar(id) {
    // Obtener los datos de la usuario a partir del ID
    fetch(`admi3/crudbonificaciones/modadmin/${id}`)
        .then(response => response.json())
        .then(data => {
            // Crear el formulario con los datos recuperados
            Swal.fire({
                title: "Modificar Bonificación",
                confirmButtonColor: "#0052CC",
                confirmButtonText: "Guardar Cambios",
                cancelButtonText: "Cancelar",
                cancelButtonColor: "transparent",
                showCancelButton: true,
                animation: false,
                html: `
                <form id="ModificarForm">
                <input type="hidden" name="id" value="${data.id}">
               
                        <label class="estiloslabel" for="nombreModificar">Nombre</label>
                   
                        <span class="error" id="nombreErrorModificar"></span>
                        <input type="text" name="nombre" value="${data.name}" id="nombreModificar" class="estilosinput">
                 
                        <label class="estiloslabel" for="descripcionModificar">Descripción</label>
                  
                        <span class="error" id="descripcionModificarError"></span>
                        <input type="text" name="descripcion" value="${data.descripcion}" id="descripcionModificar" class="estilosinput">
                
               
                        <label class="estiloslabel" for="puntosModificar">Puntos</label>
                    
                        <span class="error" id="puntosModificarError"></span>
                        <input type="number" name="puntos" id="puntosModificar" class="estilosinput" value="${data.puntos}">
                  
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
            document.getElementById('ModificarForm').addEventListener('change', validarFormularioMod);

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
    var descripcion = document.getElementById('descripcionModificar').value;
    var puntos = document.getElementById('puntosModificar').value;
    var nombreError = document.getElementById('nombreErrorModificar');
    var descripcionError = document.getElementById('descripcionModificarError');
    var puntosError = document.getElementById('puntosModificarError');

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



    if (descripcion === "") {
        descripcionError.innerText = 'Por favor ingrese una descripción';
        descripcionError.style.display = 'block';
    } else if (descripcion.length < 10) {
        descripcionError.innerText = 'Por favor introduzca una descripción válida';
        descripcionError.style.display = 'block';
    } else {
        descripcionError.innerText = '';
        descripcionError.style.display = 'none';
    }

    // Validar puntos
    if (puntos === "" || isNaN(puntos) || puntos.length > 4) {
        puntosError.innerText = 'Por favor introduce un valor numérico válido';
        puntosError.style.display = 'block';
    } else {
        puntosError.innerText = '';
        puntosError.style.display = 'none';
    }

    return {
        nombre: nombre,
        descripcion: descripcion,
        puntos: puntos
    };
}

/* enviar formulario para actualizar */

function enviarFormularioModificado(id, formData) {
    var nombre = formData.nombre;
    var descripcion = formData.descripcion;
    var puntos = formData.puntos;

    var bodyData = {
        nombre: nombre,
        descripcion: descripcion,
        puntos: puntos
    };

    // Obtener el token CSRF del documento HTML
    var token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    // Incluir el token CSRF en el encabezado de la solicitud
    var headers = {
        'Content-Type': 'application/json',
        'Accept': 'application/json',
        'X-CSRF-TOKEN': token
    };

    fetch(`admi3/crudbonificaciones/actualizar/${id}`, {
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
                    listarBonificaciones('');
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
var crearBonificacion = document.getElementById("crearBonificacion");
crearBonificacion.addEventListener("click", function() {
    mostrarFormulario();

});

function mostrarFormulario() {
    Swal.fire({
        title: "Crear Bonificación",
        confirmButtonColor: "#0052CC",
        confirmButtonText: "Create",
        html: `
        <form id="CrearForm">
       
                <label class="estiloslabel" for="nombreCrear">Nombre</label>
            
                <span class="error" id="nombreErrorCrear"></span>
                <input type="text" name="nombre" id="nombreCrear" class="estilosinput">
           
                <label class="estiloslabel" for="descripcionCrear">Descripción</label>
         
                <span class="error" id="descripcionCrearError"></span>
                <input type="text" name="email" id="descripcionCrear" class="estilosinput">
          
                <label class="estiloslabel" for="puntosCrear">Puntos</label>
          
                <span class="error" id="puntosCrearError"></span>
                <input type="number" name="puntos" id="puntosCrear" class="estilosinput">
           
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
    document.getElementById('CrearForm').addEventListener('change', validarFormulario);

}




/* validar formulario crar bonificacion */

function validarFormulario() {

    var nombre = document.getElementById('nombreCrear').value;
    var descripcion = document.getElementById('descripcionCrear').value;
    var puntos = document.getElementById('puntosCrear').value;

    var nombreError = document.getElementById('nombreErrorCrear');
    var descripcionError = document.getElementById('descripcionCrearError');
    var puntosError = document.getElementById('puntosCrearError');


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



    if (descripcion === "") {
        descripcionError.innerText = 'Por favor ingrese una descripción';
        descripcionError.style.display = 'block';
    } else if (descripcion.length < 10) {
        descripcionError.innerText = 'Por favor introduzca una descripción válida';
        descripcionError.style.display = 'block';
    } else {
        descripcionError.innerText = '';
        descripcionError.style.display = 'none';
    }

    // Validar puntos
    if (puntos === "" || isNaN(puntos) || puntos.length > 4) {
        puntosError.innerText = 'Por favor introduce un valor numérico válido';
        puntosError.style.display = 'block';
    } else {
        puntosError.innerText = '';
        puntosError.style.display = 'none';
    }
    // Habilitar o deshabilitar el botón de enviar según la validez del formulario
    /* var enviarBtn = document.getElementById('btnEnviar'); */
    var formularioValido = !nombreError.innerText && !descripcionError.innerText && !puntosError.innerText;
    /*  enviarBtn.disabled = !formularioValido; */

    return formularioValido;
}

/* enviar los campos para insertarlos */

function enviarFormulario() {
    var nombre = document.getElementById('nombreCrear').value;
    var descripcion = document.getElementById('descripcionCrear').value;
    var puntos = document.getElementById('puntosCrear').value;

    var csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    fetch('admi3/crudbonificaciones/insertuser', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            },
            body: JSON.stringify({
                nombre: nombre,
                descripcion: descripcion,
                puntos: puntos

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
                listarBonificaciones('');

            });
        })
        .catch(error => {
            console.error('Error:', error);
            Swal.fire('Error', 'Error al crear la bonificación', 'error');
        });
}