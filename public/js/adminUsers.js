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

/* function Eliminar(id) {
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
                        EndLoading();
                        Swal.fire({
                            title: "An error has occurred.",
                            text: "Could not delete.",
                            icon: "error"
                        });
                        return;
                    }
                    return response.json();
                })
                .then(data => {

                    if (data.success == true) {
                        Swal.fire({
                            title: "Borrado",
                            text: "Usuario eliminado correctamente",
                            icon: "success"
                        }).then(() => {
                            listarUsers('', rol);

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
} */

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
                        EndLoading();
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
                // Crear el formulario con los datos recuperados
                Swal.fire({
                            title: "Modify User",
                            confirmButtonColor: "#0052CC",
                            confirmButtonText: "Save Changes",
                            cancelButtonText: "Cancel",
                            cancelButtonColor: "transparent",
                            showCancelButton: true,
                            animation: false,
                            html: `
                <form id="ModificarForm">
                <input type="hidden" name="id" value="${data.id}">
                <div class="rownew">
                    <div class="col-3">
                        <label class="estiloslabel" for="nombreModificar">Name</label>
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
                        <label class="estiloslabel" for="passwordModificar">Pwd</label>
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

        })
        .catch((error) => {
            Swal.fire({
                title: "Ha ocurrido un error.",
                text: error,
                icon: "error"
            });
        });
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

    // Validar nombre
    if (nombre === "") {
        nombreError.innerText = 'Please enter a name';
        nombreError.style.display = 'block';
    } else if (nombre.length < 3) {
        nombreError.innerText = 'Incorrect format';
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
        emailError.innerText = 'Please enter an email';
        emailError.style.display = 'block';
    } else if (!validarEmail(email)) {
        emailError.innerText = 'Please enter a valid email';
        emailError.style.display = 'block';
    } else {
        emailError.innerText = '';
        emailError.style.display = 'none';
    }

    // Validar contraseña
    if (password !== "") {
        if (password.length < 8) {
            passwordError.innerText = 'Please enter a valid password';
            passwordError.style.display = 'block';
        } else {
            passwordError.innerText = '';
            passwordError.style.display = 'none';
        }
    } else {
        passwordError.innerText = '';
        passwordError.style.display = 'none';
    }

    return {
        nombre: nombre,
        email: email,
        password: password,
        rol: rol
    };
}