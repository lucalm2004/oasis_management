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
                str += "<td><img id='imageP' src='../img/discotecas/" + item.image + "'" + " alt='Imagen' class='imgtamaño'></td>";
                str += "<td>" + item.nombre_ciudad + "</td>";
                str += "<td>" + item.direccion + "</td>";
                str += "<td>" + item.nombre_usuario + "</td>";
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

/* crear discoteca */

/* Crear usurio */
var crearDiscoteca = document.getElementById("CrearDiscoteca");
crearDiscoteca.addEventListener("click", function() {
    mostrarFormulario();

});


function mostrarFormulario() {
    obtenerCiudadesCrear('');
    Swal.fire({
        title: "Crear Discoteca",
        confirmButtonColor: "#0052CC",
        confirmButtonText: "Crear",
        html: `
        <form id="CrearForm" enctype="multipart/form-data">
       
                <label class="estiloslabel" for="nombreCrear">Nombre</label>
        
                <span class="error" id="nombreErrorCrear"></span>
                <input type="text" name="nombre" id="nombreCrear" class="estilosinput">
        
                <label class="estiloslabel" for="direccionCrear">Dirección</label>
          
                <span class="error" id="direccionErrorCrear"></span>
                <input type="text" name="direccion" id="direccionCrear" class="estilosinput">
           
                <label class="estiloslabel" for="ciudadCrear">Ciudad</label>
            
                <span class="error" id="ciudadErrorCrear"></span>
                <select name="ciudad" id="ciudadCrear" class="estilosinput">
                
                </select>
                <br>
                <br>
         
                <label class="estiloslabel" for="capacidadCrear">Capacidad</label>
           
                <span class="error" id="capacidadErrorCrear"></span>
                <input type="number" name="capacidad" id="capacidadCrear" class="estilosinput">
      
            
                <label class="estiloslabel" for="imagenCrear">Imagen</label>
          
                <span class="error" id="imagenErrorCrear"></span>
                <label class='custom-file-upload'><input type="file" name="imagen" id="imagenCrear" class="estilosinput">Subir Archivo</label>
        
        
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

function obtenerCiudadesCrear() {
    fetch(`admin2/cruddiscotecas/ciudades`)
        .then(response => response.json())
        .then(data => {
            var selectMarcador = document.getElementById('ciudadCrear');
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

function validarFormulario() {

    var nombre = document.getElementById('nombreCrear').value;
    var direccion = document.getElementById('direccionCrear').value;
    var ciudad = document.getElementById('ciudadCrear').value;
    var capacidad = document.getElementById('capacidadCrear').value;
    var imagen = document.getElementById('imagenCrear').value;




    // Validar campos según tus criterios de validación
    var nombreError = document.getElementById('nombreErrorCrear');
    var direccionError = document.getElementById('direccionErrorCrear');
    var ciudadError = document.getElementById('ciudadErrorCrear');
    var capacidadError = document.getElementById('capacidadErrorCrear');
    var imagenError = document.getElementById('imagenErrorCrear');




    // Validar nombre
    if (nombre === "") {
        nombreError.innerText = 'Por favor introduce un nombre';
        nombreError.style.display = 'block';
    } else if (nombre.length < 3) {
        nombreError.innerText = 'Formato Incorrecto';
        nombreError.style.display = 'block';
    } else {
        nombreError.innerText = '';
    }



    if (direccion === "") {
        direccionError.innerText = 'Por favor introduce una dirección';
        direccionError.style.display = 'block';
    } else if (direccion.length < 8) {
        direccionError.innerText = 'Introduce una dirección que sea válida';
        direccionError.style.display = 'block';
    } else {
        direccionError.innerText = '';
        direccionError.style.display = 'none';
    }

    // Validar contraseña
    if (ciudad === "") {
        ciudadError.innerText = 'Por favor selecciona una ciudad';
        ciudadError.style.display = 'block';


    } else {
        ciudadError.innerText = '';
        ciudadError.style.display = 'none';


    }

    // Validar puntos
    if (capacidad === "" || isNaN(capacidad) || capacidad.length > 4) {
        capacidadError.innerText = 'Por favor introduce un valor numérico válido';
        capacidadError.style.display = 'block';
    } else {
        capacidadError.innerText = '';
        capacidadError.style.display = 'none';
    }

    var extensionesValidas = ['jpg', 'jpeg', 'png', 'gif'];
    var extension = imagen.split('.').pop().toLowerCase();
    if (imagen === "") {
        imagenError.innerText = 'Please select an image';
        imagenError.style.display = 'block';
    } else if (!extensionesValidas.includes(extension)) {
        imagenError.innerText = 'The image must have a valid format (jpg, jpeg, png, gif).';
        imagenError.style.display = 'block';
    } else {
        imagenError.innerText = '';
        imagenError.style.display = 'none';
    }


    // Habilitar o deshabilitar el botón de enviar según la validez del formulario
    /* var enviarBtn = document.getElementById('btnEnviar'); */
    var formularioValido = !nombreError.innerText && !direccionError.innerText && !capacidadError.innerText && !ciudadError.innerText && !imagenError.innerText;
    /*  enviarBtn.disabled = !formularioValido; */

    return formularioValido;
}

function enviarFormulario() {
    var nombre = document.getElementById('nombreCrear').value;
    var direccion = document.getElementById('direccionCrear').value;
    var ciudad = document.getElementById('ciudadCrear').value;
    var capacidad = document.getElementById('capacidadCrear').value;
    var imageInput = document.getElementById('imagenCrear');
    var imagen = imageInput.files[0]; // Obtener el archivo de imagen seleccionado
    /* var imagen = document.getElementById('imagenCrear').files[0]; */ // Obtenemos el archivo de imagen

    var formData = new FormData(); // Creamos un objeto FormData
    formData.append('nombre', nombre);
    formData.append('direccion', direccion);
    formData.append('ciudad', ciudad);
    formData.append('capacidad', capacidad);
    formData.append('imagen', imagen); // Agregamos el archivo de imagen al FormData

    var csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    fetch('admin2/cruddiscotecas/insertdiscoteca', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': csrfToken
            },
            body: formData // Utilizamos el FormData en lugar de JSON.stringify()
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Error al crear el usuario: ' + response.statusText);
            }
            return response.json();
        })
        .then(data => {
            Swal.fire({
                title: "Creado",
                text: "Discoteca creada correctamente",
                icon: "success"
            }).then(() => {
                ListarDiscotecas('', '');
            });
        })
        .catch(error => {
            console.error('Error:', error);
            Swal.fire('Error', 'Error al crear el usuario', 'error');
        });
}

function Editar(id) {
    // Obtener los datos de la usuario a partir del ID
    fetch(`admin2/cruddiscotecas/modadmin/${id}`)
        .then(response => response.json())
        .then(data => {
            // Crear el formulario con los datos recuperados
            Swal.fire({
                title: "Modificar Discoteca",
                confirmButtonColor: "#0052CC",
                confirmButtonText: "Guardar Cambios",
                cancelButtonText: "Cancelar",
                cancelButtonColor: "transparent",
                showCancelButton: true,
                animation: false,
                html: `
                        <form id="ModificarForm" enctype="multipart/form-data">
                            <label class="estiloslabel" for="nombreModificar">Nombre</label>
                                <span class="error" id="nombreErrorModificar"></span>
                                <input type="text" name="nombre" id="nombreModificar" class="estilosinput" value="${data.name}">
                            
                                <label class="estiloslabel" for="direccionModificar">Dirección</label>
                          
                                <span class="error" id="direccionErrorModificar"></span>
                                <input type="text" name="direccion" id="direccionModificar" class="estilosinput" value="${data.direccion}">
                          
                                <label class="estiloslabel" for="ciudadModificar">Ciudad</label>
                          
                                <span class="error" id="ciudadErrorModificar"></span>
                                <select name="ciudad" id="ciudadModificar" class="estilosinput">
                                    
                                </select>
                                <br>
                                <br>
                                <label class="estiloslabel" for="capacidadModificar">Capacidad</label>
                        
                                <span class="error" id="capacidadErrorModificar"></span>
                                <input type="number" name="capacidad" id="capacidadModificar" class="estilosinput" value="${data.capacidad}">
                           
                                <label class="estiloslabel" for="imagenModificar">Imagen</label>
                            
                                <span class="form-error-label" id="imagenErrorModificar"></span>
                                <label class='custom-file-upload'><input type="file" name="imagen" id="imagenModificar" class="estilosinput">Subir Archivo</label>
                            
                            <!-- Mostrar la imagen -->
                            <br>
                        
                            <img id="imageP" src="../img/discotecas/${data.image}" alt="Imagen" class="imgtamaño">
                       
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
            obtenerCiudadesMod(data.id_ciudad);


        })
        .catch((error) => {
            Swal.fire({
                title: "Ha ocurrido un error.",
                text: error,
                icon: "error"
            });
        });
}

function obtenerCiudadesMod(idSeleccionado) {
    fetch(`admin2/cruddiscotecas/ciudades`)
        .then(response => response.json())
        .then(data => {
            var selectMarcador = document.getElementById('ciudadModificar');
            selectMarcador.innerHTML = '';

            data.forEach(rol => {
                var option = document.createElement('option');
                option.value = rol.id;
                option.text = rol.name;
                if (rol.id === idSeleccionado) {
                    option.selected = true; // Seleccionar el rol correspondiente al usuario
                }
                selectMarcador.appendChild(option);
            });
        })
        .catch(error => console.error('Error al obtener los marcadores:', error));

}


function validarFormularioMod() {
    var nombre = document.getElementById('nombreModificar').value;
    var direccion = document.getElementById('direccionModificar').value;
    var ciudad = document.getElementById('ciudadModificar').value;
    var capacidad = document.getElementById('capacidadModificar').value;
    var imagen = document.getElementById('imagenModificar').files[0]



    // Validar campos según tus criterios de validación
    var nombreError = document.getElementById('nombreErrorModificar');
    var direccionError = document.getElementById('direccionErrorModificar');
    var ciudadError = document.getElementById('ciudadErrorModificar');
    var capacidadError = document.getElementById('capacidadErrorModificar');


    // Validar nombre
    if (nombre === "") {
        nombreError.innerText = 'Por favor introduce un nombre';
        nombreError.style.display = 'block';
    } else if (nombre.length < 3) {
        nombreError.innerText = 'Formato Incorrecto';
        nombreError.style.display = 'block';
    } else {
        nombreError.innerText = '';
    }



    if (direccion === "") {
        direccionError.innerText = 'Por favor introduce una dirección';
        direccionError.style.display = 'block';
    } else if (direccion.length < 8) {
        direccionError.innerText = 'Introduce una dirección que sea válida';
        direccionError.style.display = 'block';
    } else {
        direccionError.innerText = '';
        direccionError.style.display = 'none';
    }

    // Validar contraseña
    if (ciudad === "") {
        ciudadError.innerText = 'Por favor selecciona una ciudad';
        ciudadError.style.display = 'block';


    } else {
        ciudadError.innerText = '';
        ciudadError.style.display = 'none';


    }

    // Validar puntos
    if (capacidad === "" || isNaN(capacidad) || capacidad.length > 4) {
        capacidadError.innerText = 'Por favor introduce un valor numérico válido';
        capacidadError.style.display = 'block';
    } else {
        capacidadError.innerText = '';
        capacidadError.style.display = 'none';
    }


    return {
        nombre: nombre,
        direccion: direccion,
        ciudad: ciudad,
        capacidad: capacidad,
        imagen: imagen,

    };
}

function enviarFormularioModificado(id, formData) {
    var ciudad2 = document.getElementById("ciudad").value;
    var direccion = formData.direccion;
    var ciudad = formData.ciudad;
    var capacidad = formData.capacidad;
    var imagen = formData.imagen;
    var nombre = formData.nombre;

    // Crear un nuevo objeto FormData
    var formData = new FormData();
    formData.append('nombre', nombre);
    formData.append('direccion', direccion);
    formData.append('ciudad', ciudad);
    formData.append('capacidad', capacidad);
    formData.append('imagen', imagen);

    // Obtener el token CSRF del documento HTML
    var token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    // Incluir el token CSRF en el encabezado de la solicitud
    var headers = {
        'X-CSRF-TOKEN': token
    };

    fetch(`admin2/cruddiscotecas/actualizar/${id}`, {
            method: 'POST',
            headers: headers,
            body: formData, // Usar el objeto FormData aquí
        })
        .then(response => {
            if (response.ok) {
                Swal.fire({
                    title: "Success",
                    text: "Changes saved successfully",
                    icon: "success"
                }).then(() => {
                    Swal.close();
                    ListarDiscotecas('', ciudad2);
                });
            } else {
                Swal.fire({
                    title: "Error",
                    text: "Failed to save changes",
                    icon: "error"
                });
            }
        })
        .catch(error => {
            Swal.fire({
                title: "Error",
                text: "An error occurred while processing the request",
                icon: "error"
            });
            console.error('Error:', error);
        });
}