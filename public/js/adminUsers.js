/* Filtros usuarios */
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
/* Que se muestren los usuarios dependiendo de los filtros */

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
                str += "<td><button onclick='CambiarEstado(" + item.id + ")'>" + (item.habilitado == 1 ? "Habilitado" : "Deshabilitado") + "</button></td>";
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

function Eliminar(id) {
    var rol = document.getElementById("rol").value;
    var csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    Swal.fire({
        title: "Eliminar usuario",
        text: `¿Seguro que desea eliminar el usuario?`,
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#d33",
        cancelButtonColor: "#0052CC",
        confirmButtonText: "Eliminar",
        cancelButtonText: "Cancelar"
    }).then((result) => {
        if (result.isConfirmed) {
            fetch(`admin/crudusuarios/${id}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken
                    }
                })
                .then(response => {
                    if (!response.ok) {

                        Swal.fire({
                            title: "Ha ocurrido un error",
                            text: "Error al eliminar el usuario",
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
                            title: "Borrado",
                            text: "Usuario eliminado correctamente",
                            icon: "success"
                        }).then(() => {
                            ListarUsuarios('', rol);

                        });
                    } else {
                        Swal.fire({
                            title: "Error al eliminar el usuario",
                            text: data.message,
                            icon: "error"
                        });
                    }
                })
                .catch(error => console.error('Error al eliminar el usuario:', error));
        }
    });
}

/* Cambiar estado del usuario */
function CambiarEstado(id) {
    var rol = document.getElementById("rol").value;
    var csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    Swal.fire({
        title: "Cambiar estado usuario",
        text: `¿Seguro que desea cambiar el estado?`,
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#d33",
        cancelButtonColor: "#0052CC",
        confirmButtonText: "Cambiar",
        cancelButtonText: "Cancelar"
    }).then((result) => {
        if (result.isConfirmed) {
            fetch(`admin/crudusuarios/cambiarestado/${id}`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken
                    }
                })
                .then(response => {
                    if (!response.ok) {

                        Swal.fire({
                            title: "Ha ocurrido un error al cambiar el estado.",
                            text: "No se ha podido cambiar",
                            icon: "error"
                        });
                        return;
                    }
                    return response.json();
                })
                .then(data => {

                    if (data.success == true) {
                        Swal.fire({
                            title: "Cambiar Estado",
                            text: "Estado del usuario actualizado correctamente",
                            icon: "success"
                        }).then(() => {
                            ListarUsuarios('', rol);

                        });
                    } else {
                        Swal.fire({
                            title: "Error al cambiar el estado del usuario",
                            text: data.message,
                            icon: "error"
                        });
                    }
                })
                .catch(error => console.error('Error al cambiar el estado del usuario:', error));
        }
    });
}


/* Que se muestre el formulario de modificar */
function Editar(id) {
    // Obtener los datos de la usuario a partir del ID
    fetch(`admin/crudusuarios/modadmin/${id}`)
        .then(response => response.json())
        .then(data => {

                var displayStyle = (data.id_rol === 3 || data.id_rol === 4) ? "block" : "none";

                console.log(data.id_discoteca);
                // Crear el formulario con los datos recuperados
                Swal.fire({
                            title: "Modificar Usuario",
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
                <div class="rownew">
                    <div class="col-3">
                        <label class="estiloslabel" for="emailModificar">Email</label>
                    </div>
                    <div class="col-9">
                        <span class="form-error-label" id="emailErrorModificar"></span>
                        <input type="text" name="email" value="${data.email }" id="emailModificar" class="estilosinput">
                    </div>
                </div>
                <div class="rownew">
                    <div class="col-3">
                        <label class="estiloslabel" for="passwordModificar">Contraseña</label>
                    </div>
                    <div class="col">
                        <span clas-9s="form-error-label" id="passwordErrorModificar"></span>
                        <input type="password" name="password" id="passwordModificar" class="estilosinput">
                    </div>
                </div>
                ${data.DNI ? `
                <div class="rownew">
                    <div class="col-3">
                        <label class="estiloslabel" for="dniModificar">DNI</label>
                    </div>
                    <div class="col-9">
                    <span clas-9s="form-error-label" id="dniErrorModificar"></span>
                        <input type="text" name="dni" value="${data.DNI}" id="dniModificar" class="estilosinput">
                    </div>
                </div>` : ''}
                <div class="rownew">
                    <div class="col-3">
                        <label class="estiloslabel" for="rolModificar">Rol</label>
                    </div>
                    <div class="col-9">
                        <select name="rol" id="rolModificar" class="estilosinput">
                        
                        </select>
                    </div>
                </div>
                <div class="col-3" id="discotecasFieldMod" style="display: ${displayStyle}">
                <label class="estiloslabel" for="discotecaModificar" >Discoteca</label>
                <div class="col-9">
                    <span class="form-error-label" id="discotecaErrorModificar"></span>
                    <select name="discoteca" id="discotecaModificar" class="estilosinput">
                    
                    </select>
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

            // Cargar los roles en el desplegable y seleccionar el correspondiente
            obtenerRolesModificar(data.id_rol);

            // Agregar evento keyup al formulario después de crearlo
            document.getElementById('ModificarForm').addEventListener('keyup', validarFormularioMod);
            document.getElementById('ModificarForm').addEventListener('change', validarFormularioMod);
            document.getElementById('rolModificar').addEventListener('change', mostrarDiscotecaMod);
            obtenerDiscotecasMod(data.id_discoteca);
            

        })
        .catch((error) => {
            Swal.fire({
                title: "Ha ocurrido un error.",
                text: error,
                icon: "error"
            });
        });
}


function mostrarDiscotecaMod(){
    var selectedRol = document.getElementById("rolModificar").value;
    var discotecasField = document.getElementById("discotecasFieldMod");

    if (selectedRol === "4") {
        discotecasField.style.display = "block";

    } else if(selectedRol === "3"){
        discotecasField.style.display = "block";

    }else{
        discotecasField.style.display = "none";
    

    }
    obtenerDiscotecasMod('');

}
function obtenerDiscotecasMod(idDiscotecaSeleccionado){
    fetch(`admin/crudusuarios/discotecas`)
    .then(response => response.json())
    .then(data => {
        var selectMarcador = document.getElementById('discotecaModificar');
        selectMarcador.innerHTML = '';

        var blankOption = document.createElement('option');
        blankOption.value = ''; // Valor vacío
        blankOption.text = ''; // Texto descriptivo
        selectMarcador.appendChild(blankOption);


        data.forEach(discoteca => {
            var option = document.createElement('option');
            option.value = discoteca.id;
            option.text = discoteca.name;
            if (discoteca.id === idDiscotecaSeleccionado) {
                option.selected = true; // Seleccionar el rol correspondiente al usuario
            }
            selectMarcador.appendChild(option);
        });
    })
    .catch(error => console.error('Error al obtener los marcadores:', error));

}




/* se cargen los roles form modificar del usuario y se seleccione el correcto */
function obtenerRolesModificar(idRolSeleccionado) {
    fetch(`admin/crudusuarios/roles`)
        .then(response => response.json())
        .then(data => {
            var selectRol = document.getElementById('rolModificar');
            selectRol.innerHTML = '';

            data.forEach(rol => {
                var option = document.createElement('option');
                option.value = rol.id;
                option.text = rol.name;
                if (rol.id === idRolSeleccionado) {
                    option.selected = true; // Seleccionar el rol correspondiente al usuario
                }
                selectRol.appendChild(option);
            });
        })
        .catch(error => console.error('Error al obtener los roles:', error));
}

function validarFormularioMod() {
    var nombre = document.getElementById('nombreModificar').value;
    var email = document.getElementById('emailModificar').value;
    var password = document.getElementById('passwordModificar').value;
    var rol = document.getElementById('rolModificar').value;
    var nombreError = document.getElementById('nombreErrorModificar');
    var emailError = document.getElementById('emailErrorModificar');
    var passwordError = document.getElementById('passwordErrorModificar');
    var dniError = document.getElementById('dniErrorModificar');
    var dni = document.getElementById('dniModificar');
    var discoteca = document.getElementById("discotecaModificar");
   
    
    var discotecaValue = discoteca ? discoteca.value : null;
    var dniValue = dni ? dni.value : null;

    // Validar nombre
    if (nombre === "") {
        nombreError.innerText = 'Por favor ingresa un nombre';
        nombreError.style.display = 'block';
    } else if (nombre.length < 3) {
        nombreError.innerText = 'Formato Incorrecto';
        nombreError.style.display = 'block';
    } else {
        nombreError.innerText = '';
    }

    // Validar email
    function validarEmail(email) {
        const regex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return regex.test(email);
    }

    if (email === "") {
        emailError.innerText = 'Por favor ingrese un correo electrónico';
        emailError.style.display = 'block';
    } else if (!validarEmail(email)) {
        emailError.innerText = 'Por favor introduzca un correo electrónico válida';
        emailError.style.display = 'block';
    } else {
        emailError.innerText = '';
        emailError.style.display = 'none';
    }

    // Validar contraseña
    if (password !== "") {
        if (password.length < 8) {
            passwordError.innerText = 'Por favor introduce una contraseña válida';
            passwordError.style.display = 'block';
        } else {
            passwordError.innerText = '';
            passwordError.style.display = 'none';
        }
    } else {
        passwordError.innerText = '';
        passwordError.style.display = 'none';
    }

    //Validar DNI
    if (dni) {
        if (dniValue === "") {
            dniError.innerText = 'Por favor introduce un DNI';
        } else {
            var formatoDni = /^\d{8}[a-zA-Z]$/.test(dniValue);
            if (!formatoDni) {
                dniError.textContent = "El DNI debe tener 8 dígitos seguidos de una letra";
            } else {
                var numerosDNI = dniValue.slice(0, 8);
                var letraCalculada = calcularLetraDNI(numerosDNI);
                if (letraCalculada !== dniValue.slice(-1).toUpperCase()) {
                    dniError.textContent = "La letra del DNI no es válida";
                } else {
                    dniError.textContent = "";
                }
            }
        }
    } else {
        // Si no hay campo de DNI, establecer el valor del DNI como null
        dniValue = null;
    }
    // Función para calcular la letra del DNI
    function calcularLetraDNI(numerosDNI) {
        var letras = "TRWAGMYFPDXBNJZSQVHLCKE";
        var resto = numerosDNI % 23;
        return letras.charAt(resto);
    }
  
     

    return {
        nombre: nombre,
        email: email,
        password: password,
        dni: dniValue,
        discoteca: discotecaValue,
        rol: rol
    };
}

function enviarFormularioModificado(id, formData) {
    var rol2 = document.getElementById("rol").value;
    var nombre = formData.nombre;
    var email = formData.email;
    var password = formData.password;
    var rol = formData.rol;
    var dni = formData.dni;
    var discoteca = formData.discoteca;
    var bodyData = {
        nombre: nombre,
        email: email,
        password: password,
        dni: dni,
        discoteca: discoteca,
        rol: rol
    };

    // Obtener el token CSRF del documento HTML
    var token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    // Incluir el token CSRF en el encabezado de la solicitud
    var headers = {
        'Content-Type': 'application/json',
        'Accept': 'application/json',
        'X-CSRF-TOKEN': token
    };

    fetch(`admin/crudusuarios/actualizar/${id}`, {
            method: 'POST',
            headers: headers,
            body: JSON.stringify(bodyData),
        })
        .then(response => {
            if (response.ok) {
                Swal.fire({
                    title: "Success",
                    text: "Changes saved successfully",
                    icon: "success"
                }).then(() => {
                    Swal.close();
                    ListarUsuarios('', rol2);
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

/* Crear usurio */
var crearUser = document.getElementById("CrearUser");
crearUser.addEventListener("click", function() {
    mostrarFormulario();

});

function mostrarFormulario() {
    obtenerRolesCrear('');
    Swal.fire({
        title: "Crear Usuario",
        confirmButtonColor: "#0052CC",
        confirmButtonText: "Crear",
        html: `
        <form id="CrearForm" enctype="multipart/form-data">
        <div class="rownew">
            <div class="col-3">
                <label class="estiloslabel" for="nombreCrear">Name</label>
            </div>
            <div class="col-9">
                <span class="form-error-label" id="nombreErrorCrear"></span>
                <input type="text" name="nombre" id="nombreCrear" class="estilosinput">
            </div>
        </div>
        <div class="rownew">
            <div class="col-3">
                <label class="estiloslabel" for="emailCrear">Email</label>
            </div>
            <div class="col-9">
                <span class="form-error-label" id="emailErrorCrear"></span>
                <input type="text" name="email" id="emailCrear" class="estilosinput">
            </div>
        </div>
        <div class="rownew">
            <div class="col-3">
                <label class="estiloslabel" for="passwordCrear">Pwd</label>
            </div>
            <div class="col">
                <span clas-9s="form-error-label" id="passwordErrorCrear"></span>
                <input type="password" name="password" id="passwordCrear" class="estilosinput">
            </div>
        </div>
        <div class="rownew">
            <div class="col-3">
                <label class="estiloslabel" for="rolCrear">Rol</label>
            </div>
            <div class="col-9">
                <span class="form-error-label" id="rolErrorCrear"></span>
                <select name="rol" id="rolCrear" class="estilosinput">
                
                </select>
            </div>
            
        </div>
        <div class="rownew">
            <div id="dniField" style="display: none;">
                <label for="dniCrear">DNI:</label>
                <br>
                <span class="form-error-label" id="dniErrorCrear"></span>
                <input type="text" id="dniCrear" name="dni">
            </div>
        </div>
        <div class="col-3" id="discotecasField" style="display: none">
        <label class="estiloslabel" for="discotecaCrear" >Discoteca</label>
        <div class="col-9">
            <span class="form-error-label" id="discotecaErrorCrear"></span>
            <select name="discoteca" id="discotecaCrear" class="estilosinput">
            
            </select>
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
    document.getElementById('CrearForm').addEventListener('change', validarFormulario);
    document.getElementById("rolCrear").addEventListener("change", mostrarDNI);
    document.getElementById("rolCrear").addEventListener("change", mostrarDiscoteca);
}

function mostrarDNI() {
    var selectedRol = document.getElementById("rolCrear").value;
    var dniField = document.getElementById("dniField");

    // Si el rol seleccionado es "Gestor", mostrar el campo DNI. De lo contrario, ocultarlo.
    if (selectedRol === "3") {
        dniField.style.display = "block";
    } else {
        dniField.style.display = "none";
    }
}

function mostrarDiscoteca(){
    var selectedRol = document.getElementById("rolCrear").value;
    var discotecasField = document.getElementById("discotecasField");

    if (selectedRol === "4") {
        discotecasField.style.display = "block";

    } else if(selectedRol === "3"){
        discotecasField.style.display = "block";

    }else{
        discotecasField.style.display = "none";
    

    }
    obtenerDiscotecas('');
    obtenerciudades('');

}

function obtenerDiscotecas(){
    fetch(`admin/crudusuarios/discotecas`)
    .then(response => response.json())
    .then(data => {
        var selectMarcador = document.getElementById('discotecaCrear');
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




function obtenerRolesCrear() {
    fetch(`admin/crudusuarios/roles`)
        .then(response => response.json())
        .then(data => {
            var selectMarcador = document.getElementById('rolCrear');
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
    var email = document.getElementById('emailCrear').value;
    var password = document.getElementById('passwordCrear').value;
    var rol = document.getElementById('rolCrear').value;
    var dni = document.getElementById('dniCrear');
    var discoteca = document.getElementById('discotecaCrear');
    var discotecaValue = discoteca ? discoteca.value : null;
    var dniValue = dni ? dni.value : null;


    // Validar campos según tus criterios de validación
    var nombreError = document.getElementById('nombreErrorCrear');
    var emailError = document.getElementById('emailErrorCrear');
    var passwordError = document.getElementById('passwordErrorCrear');
    var rolError = document.getElementById('rolErrorCrear');
    var dniError = document.getElementById('dniErrorCrear');
    var discotecaError = document.getElementById('discotecaErrorCrear');

    
   
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

    // Validar email
    function validarEmail(email) {
        const regex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return regex.test(email);
    }

    if (email === "") {
        emailError.innerText = 'Por favor introduce un email';
        emailError.style.display = 'block';
    } else if (!validarEmail(email)) {
        emailError.innerText = 'Introduce un email que sea válido';
        emailError.style.display = 'block';
    } else {
        emailError.innerText = '';
        emailError.style.display = 'none';
    }

    // Validar contraseña
    if (password === "") {
        passwordError.innerText = 'Por favor introduce una contraseña válida';
        passwordError.style.display = 'block';


    } else if (password.length < 8) {
        passwordError.innerText = 'Por favor introduce una contraseña válida';
        passwordError.style.display = 'block';


    } else {
        passwordError.innerText = '';
        passwordError.style.display = 'none';
    }

    if (rol === "") {
        rolError.innerText = 'Por favor seleccione un rol';
        rolError.style.display = 'block';
    } else {
        rolError.innerText = '';
    }

 

    if (document.getElementById('dniField').style.display === 'block') {
        if (dniValue === "") {
            dniError.innerText = 'Por favor introduce un DNI';
        } else {
            var formatoDni = /^\d{8}[a-zA-Z]$/.test(dniValue);
            if (!formatoDni) {
                dniError.textContent = "El DNI debe tener 8 dígitos seguidos de una letra";
            } else {
                var numerosDNI = dniValue.slice(0, 8);
                var letraCalculada = calcularLetraDNI(numerosDNI);
                if (letraCalculada !== dniValue.slice(-1).toUpperCase()) {
                    dniError.textContent = "La letra del DNI no es válida";
                } else {
                    dniError.textContent = "";
                }
            }
        }

        
    }

     


    // Función para calcular la letra del DNI
    function calcularLetraDNI(numerosDNI) {
        var letras = "TRWAGMYFPDXBNJZSQVHLCKE";
        var resto = numerosDNI % 23;
        return letras.charAt(resto);
    }
    // Habilitar o deshabilitar el botón de enviar según la validez del formulario
    /* var enviarBtn = document.getElementById('btnEnviar'); */
    var formularioValido = !nombreError.innerText && !emailError.innerText && !passwordError.innerText && !rolError.innerText && !dniError.innerText && !discotecaError.innerText;
    /*  enviarBtn.disabled = !formularioValido; */

    return formularioValido;
}

function enviarFormulario() {
    var nombre = document.getElementById('nombreCrear').value;
    var email = document.getElementById('emailCrear').value;
    var password = document.getElementById('passwordCrear').value;
    var rol = document.getElementById('rolCrear').value;
    var dni = document.getElementById('dniCrear').value;
    var discoteca = document.getElementById('discotecaCrear').value;

   
    // Si el campo DNI está vacío, establecerlo como null
    dni = dni === '' ? null : dni;
    discoteca = discoteca === '' ? null : discoteca;
  

    console.log(nombre);
    console.log(email);
    console.log(password);
    console.log(rol);
    console.log(dni);
    console.log(discoteca);
   
    



    var csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    var rol2 = document.getElementById("rol").value;

    console.log(nombre);
  
    fetch('admin/crudusuarios/insertuser', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            },
            body: JSON.stringify({
                nombres: nombre,
                email: email,
                password: password,
                dni: dni,
                discoteca: discoteca,
                rol: rol,
                
        
            })
         
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
                    text: "Usuario creado correctamente",
                    icon: "success"
                }).then(() => {
                    ListarUsuarios('', rol2);
                });
           
        })
        .catch(error => {
            console.error('Error:', error);
            Swal.fire('Error', 'Error al crear el usuario', 'error');
        });
}


/* ver si hay alguna solicitud */
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
                        if (json.length === 0) {
                            solicitudes.innerHTML = "<p>Actualmente no hay solicitudes.</p>";
                            // Oculta la tabla y el encabezado si no hay solicitudes
                            tabla.style.display = 'none';
                            h3Solicitud.style.display = 'none';
                        } else {
                            var tablaHTML = "";
                            json.forEach(function(item) {
                                // Construye una fila de la tabla con los datos del elemento actual
                                var str = "<tr><td>" + item.id+ "</td>";
                                str += "<td>" + item.email + "</td>";
                                str += "<td>" + item.DNI + "</td>";
                                str += "<td>" + item.nombre_discoteca + "</td>";
                                str += "<td><button type='button' id='aceptar' onclick='aceptarSolicitud(" + item.id + ")'>Aceptar</button></td>";
                                str += "<td><button type='button' id='rechazar' onclick='rechazarSolicitud(" + item.id + ")'>Rechazar</button></td>";
                                str += "</tr>";
                                tablaHTML += str;
                            });
                            solicitudes.innerHTML = tablaHTML;
                            // Muestra la tabla y el encabezado si hay solicitudes
                            tabla.style.display = 'block';
                            h3Solicitud.style.display = 'block';
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

/* aceptar la solicitud del gestor */
function aceptarSolicitud(id) {
    var rol = document.getElementById("rol").value;
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
                            ListarUsuarios('', rol);
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
    var rol = document.getElementById("rol").value;
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
                            ListarUsuarios('', rol);
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