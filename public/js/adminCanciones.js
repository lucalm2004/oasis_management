/* Filtros usuarios */
buscar.addEventListener("keyup", () => {
    const valor = buscar.value;
    const artista = document.getElementById("artista").value;
    ListarCanciones(valor, artista);
});

artista = document.getElementById("artista");
artista.addEventListener("change", () => {
    const valor = buscar.value;
    const artista = document.getElementById("artista").value;

    ListarCanciones(valor, artista);
});
/* Que se muestren los usuarios dependiendo de los filtros */

ListarCanciones('', '');

function ListarCanciones(valor, artista) {
    var resultado = document.getElementById('resultado');
    var formdata = new FormData();
    var csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    formdata.append('_token', csrfToken);
    formdata.append('busqueda', valor);
    formdata.append('artista', artista);
    var ajax = new XMLHttpRequest();
    ajax.open('POST', 'admin6/crudcanciones');
    ajax.onload = function() {
        var str = "";
        if (ajax.status == 200) {
            var json = JSON.parse(ajax.responseText);
            var tabla = '';
            json.forEach(function(item) {
                str += "<tr>";
                str += "<td>" + item.id + "</td>";
                str += "<td>" + item.name + "</td>";
                str += "<td>" + item.nombre_artista + "</td>";
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
obtenerRoles('');

function obtenerRoles() {
    fetch(`admin6/crudcanciones/artistas`)
        .then(response => response.json())
        .then(data => {
            var selectMarcador = document.getElementById('artista');
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


/* eliminar canciones */

function Eliminar(id) {
    var artista = document.getElementById("artista").value;
    var csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    Swal.fire({
        title: "Eliminar canción",
        text: `¿Seguro que desea eliminar la canción?`,
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#d33",
        cancelButtonColor: "#0052CC",
        confirmButtonText: "Eliminar",
        cancelButtonText: "Cancelar"
    }).then((result) => {
        if (result.isConfirmed) {
            fetch(`admi6/crudcanciones/${id}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken
                    }
                })
                .then(response => {
                    if (!response.ok) {

                        Swal.fire({
                            title: "Ha ocurrido un error",
                            text: "Error al eliminar la canción",
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
                            text: "Canción eliminada correctamente",
                            icon: "success"
                        }).then(() => {
                            ListarCanciones('', artista);

                        });
                    } else {
                        Swal.fire({
                            title: "Error al eliminar la canción",
                            text: data.message,
                            icon: "error"
                        });
                    }
                })
                .catch(error => console.error('Error al eliminar la bonificación:', error));
        }
    });
}

/* Editar Caciones */

/* Que se muestre el formulario de modificar */
function Editar(id) {
    // Obtener los datos de la usuario a partir del ID
    fetch(`admi6/crudcanciones/modadmin/${id}`)
        .then(response => response.json())
        .then(data => {
            obtenerArtistasMod(data.artista.name);
            // Crear el formulario con los datos recuperados
            Swal.fire({
                title: "Modificar Canción",
                confirmButtonColor: "#0052CC",
                confirmButtonText: "Guardar Cambios",
                cancelButtonText: "Cancelar",
                cancelButtonColor: "transparent",
                showCancelButton: true,
                animation: false,
                html: `
                <form id="ModificarForm">
                <input type="hidden" name="id" value="${data.canciones.id}">
               
                        <label class="estiloslabel" for="nombreModificar">Nombre</label>
                   
                        <span class="error" id="nombreErrorModificar"></span>
                        <input type="text" name="nombre" value="${data.canciones.name}" id="nombreModificar" class="estilosinput">
                 
                        <label class="estiloslabel" for="artistaModificar">Artista</label>
                  
                        <span class="error" id="artistaModificarError"></span>
                        <select name="artista" id="artistaModificar" class="estilosinput">
                    
                        </select>
        
                  
                </form>
                <style>.swal2-cancel {color: black !important;}</style>
                `,
                preConfirm: () => {
                    return validarFormularioMod();
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    enviarFormularioModificado(data.canciones.id, result.value);
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

function obtenerArtistasMod(idArtista) {
    fetch(`admin6/crudcanciones/artistas`)
        .then(response => response.json())
        .then(data => {
            var selectMarcador = document.getElementById('artistaModificar');
            selectMarcador.innerHTML = '';

            var blankOption = document.createElement('option');
            blankOption.value = ''; // Valor vacío
            blankOption.text = ''; // Texto descriptivo
            selectMarcador.appendChild(blankOption);


            data.forEach(artista => {
                var option = document.createElement('option');
                option.value = artista.name;
                option.text = artista.name;
                if (artista.name === idArtista) {
                    option.selected = true; // Seleccionar el rol correspondiente al usuario
                }
                selectMarcador.appendChild(option);
            });
        })
        .catch(error => console.error('Error al obtener los marcadores:', error));

}


/* validar form de modificar */

function validarFormularioMod() {
    var nombre = document.getElementById('nombreModificar').value;
    var nombreError = document.getElementById('nombreErrorModificar');
    var artista = document.getElementById('artistaModificar').value;


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
        artista: artista

    };
}

/* enviar formulario para actualizar */

async function enviarFormularioModificado(id, formData) {

    var nombreCancion = formData.nombre;
    var nombreArtista = formData.artista;
    var csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');


    try {
        // Obtener la carátula usando await
        const caratula = await obtenerCaratula(nombreCancion, nombreArtista);

        // Realizar una solicitud HTTP POST para insertar la canción en la base de datos
        const response = await fetch(`admi6/crudcanciones/actualizar/${id}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken // Añadir el token CSRF como encabezado
            },
            body: JSON.stringify({
                nombreCancion: caratula.nombreCancion,
                nombreArtista: caratula.nombreArtista
            })
        });

        const data = await response.json();

        if (response.ok && data.success) {
            // Si la inserción es exitosa, muestra un mensaje de éxito
            Swal.fire('¡Canción guardada!', data.message, 'success');
            ListarCanciones('', '');
        } else {
            // Si hay un error, muestra un mensaje de error
            Swal.fire('No se ha podido modificar la canción', data.message || 'Error desconocido', 'error');
        }
    } catch (error) {
        console.error('Error:', error);
        // Si hay un error, muestra un mensaje de error
        Swal.fire('Error', 'Comprueba el nombre de la canción o el artista agregado, no se ha encontrado la canción.', 'error');
    }
}


/* crear una nueva cancion */

var crearCancion = document.getElementById("crearCancion");
crearCancion.addEventListener("click", function() {
    mostrarFormulario();

});

function mostrarFormulario() {
    obtenerArtistasCrear('');
    Swal.fire({
        title: "Crear Canción",
        confirmButtonColor: "#0052CC",
        confirmButtonText: "Create",
        html: `
        <form id="CrearForm">
       
                <label class="estiloslabel" for="nombreCrear">Nombre</label>
            
                <span class="error" id="nombreErrorCrear"></span>
                <input type="text" name="nombre" id="nombreCrear" class="estilosinput">
           
                <label class="estiloslabel" for="artistaCrear">Artista</label>
         
                <span class="error" id="artistaCrearError"></span>
                <select name="artista" id="artistaCrear" class="estilosinput">
            
                </select>
           
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


function obtenerArtistasCrear() {
    fetch(`admin6/crudcanciones/artistas`)
        .then(response => response.json())
        .then(data => {
            var selectMarcador = document.getElementById('artistaCrear');
            selectMarcador.innerHTML = '';

            var blankOption = document.createElement('option');
            blankOption.value = ''; // Valor vacío
            blankOption.text = ''; // Texto descriptivo
            selectMarcador.appendChild(blankOption);


            data.forEach(rol => {
                var option = document.createElement('option');
                option.value = rol.name;
                option.text = rol.name;
                selectMarcador.appendChild(option);
            });
        })
        .catch(error => console.error('Error al obtener los marcadores:', error));
}



/* validar formulario crar bonificacion */

function validarFormulario() {

    var nombre = document.getElementById('nombreCrear').value;
    var artista = document.getElementById('artistaCrear').value;

    var nombreError = document.getElementById('nombreErrorCrear');
    var artistaError = document.getElementById('artistaCrearError');


    // Validar nombre
    if (nombre === "") {
        nombreError.innerText = 'Por favor ingresa un nombre';
        nombreError.style.display = 'block';
    } else if (nombre.length < 2) {
        nombreError.innerText = 'Formato Incorrecto';
        nombreError.style.display = 'block';
    } else {
        nombreError.innerText = '';
    }



    if (artista === "") {
        artistaError.innerText = 'Por favor ingrese un artista';
        artistaError.style.display = 'block';

    } else {
        artistaError.innerText = '';
        artistaError.style.display = 'none';
    }



    var formularioValido = !nombreError.innerText && !artistaError.innerText;
    /*  enviarBtn.disabled = !formularioValido; */

    return formularioValido;
}

/* enviar los campos para insertarlos */



async function enviarFormulario() {
    var nombreCancion = document.getElementById('nombreCrear').value;
    var nombreArtista = document.getElementById('artistaCrear').value;
    console.log(nombreCancion);
    console.log(nombreArtista);

    var csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    try {
        // Obtener la carátula usando await
        const caratula = await obtenerCaratula(nombreCancion, nombreArtista);

        // Realizar una solicitud HTTP POST para insertar la canción en la base de datos
        const response = await fetch('admi6/crudcanciones/insercancion', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken // Añadir el token CSRF como encabezado
            },
            body: JSON.stringify({
                nombreCancion: caratula.nombreCancion,
                nombreArtista: caratula.nombreArtista
            })
        });

        const data = await response.json();

        if (response.ok && data.success) {
            // Si la inserción es exitosa, muestra un mensaje de éxito
            Swal.fire('¡Canción guardada!', data.message, 'success');
            ListarCanciones('', '');
        } else {
            // Si hay un error, muestra un mensaje de error
            Swal.fire('No se ha podido insertar la canción', data.message || 'Error desconocido', 'error');
        }
    } catch (error) {
        console.error('Error:', error);
        // Si hay un error, muestra un mensaje de error
        Swal.fire('Error', 'Comprueba el nombre de la canción o el artista agregado, no se ha encontrado la canción.', 'error');
    }
}


async function obtenerCaratula(cancion, artista) {
    try {
        const apiKey = 'ed420dfe24230d66234f98cdc646d658';
        const url =
            `http://ws.audioscrobbler.com/2.0/?method=track.getInfo&api_key=${apiKey}&artist=${encodeURIComponent(artista)}&track=${encodeURIComponent(cancion)}&format=json`;

        const response = await fetch(url);
        const data = await response.json();

        if (data.error) {
            throw new Error(data.message);
        }

        const {
            name: nombreCancion,
            artist: {
                name: nombreArtista
            }
        } = data.track;


        return {
            nombreCancion,
            nombreArtista
        };
    } catch (error) {
        console.error('Error al obtener la carátula:', error);
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
    var h3Solicitud = document.getElementById('h3solicitud'); // Obtener elemento con el id "h3solicitud"

    // Creamos ajax
    var ajax = new XMLHttpRequest();
    // Definimos el método y la URL
    ajax.open('GET', 'admin/crudusuarios/solcitudes', true);
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
                            h3Solicitud.style.display = 'none';
                        } else {
                            var tablaHTML = "";
                            json.solicitudes.forEach(function(item) {
                                // Construye una fila de la tabla con los datos del elemento actual
                                var str = "<tr><td>" + item.id + "</td>";
                                str += "<td>" + item.email + "</td>";
                                str += "<td>" + item.DNI + "</td>";
                                str += "<td>" + item.nombre_discoteca + "</td>";
                                str += "<td><button type='button' id='aceptar' onclick='aceptarSolicitud(" + item.id + ")'><i class='fa-solid fa-circle-check' style='color: #45d408;'></i></button></td>";
                                str += "<td><button type='button' id='rechazar' onclick='rechazarSolicitud(" + item.id + ")'><i class='fa-solid fa-circle-xmark' style='color: #ff0000;'></i></button></td>";
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
        title: "Aceptar gestor",
        text: `¿Seguro que desea aceptar el gestor?`,
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#d33",
        cancelButtonColor: "#0052CC",
        confirmButtonText: "Aceptar",
        cancelButtonText: "Cancelar"
    }).then((result) => {
        if (result.isConfirmed) {
            fetch(`admin/crudusuarios/solcitudesaceptar/${id}`, {
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
                            text: "Gestor aceptado correctamente",
                            icon: "success"
                        }).then(() => {

                            mostrarSolicitud();

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
        title: "Rechazar gestor",
        text: `¿Seguro que desea rechazar el gestor?`,
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#d33",
        cancelButtonColor: "#0052CC",
        confirmButtonText: "Aceptar",
        cancelButtonText: "Cancelar"
    }).then((result) => {
        if (result.isConfirmed) {
            fetch(`admin/crudusuarios/solcitudrechazar/${id}`, {
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
                            title: "Rechazado",
                            text: "Gestor rechazado correctamente",
                            icon: "success"
                        }).then(() => {

                            mostrarSolicitud();

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