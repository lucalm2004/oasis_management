document.addEventListener('DOMContentLoaded', function() {
    const nameInput = document.getElementById('name');
    const emailInput = document.getElementById('email');
    const passwordInput = document.getElementById('password');
    const passwordConfirmationInput = document.getElementById('password_confirmation');

    // Función para mostrar un SweetAlert
    function showAlert(title, text, icon) {
        Swal.fire({
            title: title,
            text: text,
            icon: icon,
            confirmButtonText: 'Aceptar'
        });
    }

    nameInput.addEventListener('keyup', function() {
        const nameError = document.getElementById('nameError');
        nameError.textContent = '';

        // Validar caracteres especiales en el nombre
        if (!isValidName(nameInput.value)) {
            nameError.textContent = 'El nombre solo puede contener letras y números';
        } else if (nameInput.value.length < 3) {
            nameError.textContent = 'El nombre debe tener al menos 3 caracteres';
        }
    });

    emailInput.addEventListener('keyup', function() {
        const emailError = document.getElementById('emailError');
        emailError.textContent = '';

        // Validar formato de correo electrónico
        if (!isValidEmail(emailInput.value)) {
            emailError.textContent = 'Ingresa un correo electrónico válido';
        } else {
            // Validar dominio del correo electrónico
            if (!isValidDomain(emailInput.value)) {
                emailError.textContent = 'El dominio de correo electrónico no es válido (debe ser .com, .es o .org)';
            }
        }
    });

    passwordInput.addEventListener('keyup', function() {
        const passwordError = document.getElementById('passwordError');
        passwordError.textContent = '';

        if (passwordInput.value.length > 0 && passwordInput.value.length < 6) {
            passwordError.textContent = 'La contraseña debe tener al menos 6 caracteres';
        }
    });

    passwordConfirmationInput.addEventListener('keyup', function() {
        const passwordConfirmationError = document.getElementById('passwordConfirmationError');
        passwordConfirmationError.textContent = '';

        if (passwordInput.value !== passwordConfirmationInput.value) {
            passwordConfirmationError.textContent = 'Las contraseñas no coinciden';
        }
    });

    const profileForm = document.getElementById('profileForm');

    profileForm.addEventListener('submit', function(event) {
        const nameError = document.getElementById('nameError');
        const emailError = document.getElementById('emailError');
        const passwordError = document.getElementById('passwordError');
        const passwordConfirmationError = document.getElementById('passwordConfirmationError');

        nameError.textContent = '';
        emailError.textContent = '';
        passwordError.textContent = '';
        passwordConfirmationError.textContent = '';

        let hasError = false;

        if (!isValidName(nameInput.value)) {
            nameError.textContent = 'El nombre solo puede contener letras y números';
            hasError = true;
        } else if (nameInput.value.length < 3) {
            nameError.textContent = 'El nombre debe tener al menos 3 caracteres';
            hasError = true;
        }

        if (!isValidEmail(emailInput.value)) {
            emailError.textContent = 'Ingresa un correo electrónico válido';
            hasError = true;
        } else {
            if (!isValidDomain(emailInput.value)) {
                emailError.textContent = 'El dominio de correo electrónico no es válido (debe ser .com, .es o .org)';
                hasError = true;
            }
        }

        if (passwordInput.value.length > 0 && passwordInput.value.length < 6) {
            passwordError.textContent = 'La contraseña debe tener al menos 6 caracteres';
            hasError = true;
        }

        if (passwordInput.value !== passwordConfirmationInput.value) {
            passwordConfirmationError.textContent = 'Las contraseñas no coinciden';
            hasError = true;
        }

        if (hasError) {
            // Mostrar SweetAlert de error
            showAlert('Error', 'Por favor, corrige los errores en el formulario.', 'error');
            event.preventDefault(); // Evita que se envíe el formulario
        } else {
            // Mostrar SweetAlert de éxito al enviar el formulario
            showAlert('Éxito', '¡Perfil actualizado exitosamente!', 'success');
        }
    });

    // Función para validar el nombre
    function isValidName(name) {
        return /^[a-zA-Z0-9]+$/.test(name); // Solo letras y números permitidos
    }

    // Función para validar el formato de correo electrónico
    function isValidEmail(email) {
        return /^[a-zA-Z0-9_.-]+@[a-zA-Z0-9-]+\.[a-zA-Z0-9-.]+$/.test(email);
    }

    // Función para validar el dominio del correo electrónico
    function isValidDomain(email) {
        return /\.(com|es|org|edu|net)$/.test(email.split('@')[1]);
    }
});