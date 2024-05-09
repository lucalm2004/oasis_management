 // Función para mostrar SweetAlert al hacer clic en "Contacta con nosotros"
 function showContactMessage() {
     Swal.fire({
         icon: 'success',
         title: 'Mensaje Copiado',
         text: '¡Gracias por querer contactarnos! Dirigete a "Contacta con nosotros" donde ya hemos copiado el menaje por ti.',
         confirmButtonText: 'Entendido'
     });
 }
 // Función para enviar correo con el mensaje prellenado
 function sendEmail() {
     var mensaje = document.getElementById("mensaje").value;
     var correo = 'oasis.management.daw@gmail.com';
     var subject = 'Mensaje de contacto';
     var body = encodeURIComponent(mensaje);
     var mailtoLink = 'mailto:' + correo + '?subject=' + subject + '&body=' + body;
     window.location.href = mailtoLink;
 }
 window.onscroll = function() {
     var navbar = document.querySelector('.navbar');
     if (window.scrollY > 20) {
         navbar.classList.add('scrolled');
     } else {
         navbar.classList.remove('scrolled');
     }
 };