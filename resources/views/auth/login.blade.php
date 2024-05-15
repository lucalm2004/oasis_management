<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Oasis Management - Login</title>
    <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/normalize/5.0.0/normalize.min.css">
    <link rel='stylesheet' href='https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&amp;display=swap'>
    <link rel="stylesheet" href="{{ asset('css/login.css') }}">
    <link rel="shortcut icon" href="{{ asset('img/logo.png') }}" type="image/x-icon">
<style>
  #eye_show1 {
    cursor: pointer;
}
</style>
</head>

<body>
  <div class="screen-1">
      <h2>Oasis | Login</h2>
      <img src="{{asset('img/redondoLetrasNegras.png')}}" alt="" class="logo">
      <form method="POST" action="{{ route('login') }}">
          @csrf
          <div class="email">
              <label for="email" style="border-bottom: 1px solid; opacity: 50%">Email Address</label>
              <div class="sec-2">
                  <ion-icon name="mail-outline" class="icono_correo"></ion-icon>
                  <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
              </div>
              <x-input-error :messages="$errors->get('email')" class="mt-2" />
          </div>
          <div class="password">
              <label for="password" style="border-bottom: 1px solid; opacity: 50%">Password</label>
              <div class="sec-2">
                  <ion-icon name="lock-closed-outline" class="icono_pwd"></ion-icon>
                  <x-text-input id="password" class="block mt-1 w-full" type="password" name="password" style="width: 80%;" required autocomplete="current-password" />
                  <ion-icon class="show-hide" id="eye_show1" name="eye-outline"></ion-icon>
              </div>
              <x-input-error :messages="$errors->get('password')" class="mt-2" />
          </div>
          <div style="display: flex; gap: 1%;" class="botones_login">
              <button class="login">Login</button>
              <div class="boton_google">
                  <button onclick="window.location.href='/google-auth/redirect'" class="gsi-material-button">
                      <div class="gsi-material-button-state"></div>
                      <div class="gsi-material-button-content-wrapper">
                          <div class="gsi-material-button-icon">
                              <svg version="1.1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 48 48" xmlns:xlink="http://www.w3.org/1999/xlink" style="display: block;">
                                  <!-- Icono de Google -->
                              </svg>
                          </div>
                          <span class="gsi-material-button-contents">Log in with Google</span>
                          <span style="display: none;">Log in with Google</span>
                      </div>
                  </button>
              </div>
          </div>
          <div class="footer">
              <span onclick="window.location.href='{{ route('register') }}'">Signup</span>
              <span onclick="window.location.href='{{ route('password.request') }}'">Forgot Password?</span>
          </div>
      </form>
  </div>
</body>


<script>
  var eye_show1 = document.getElementById('eye_show1');
var password = document.getElementById('password');

// Manejador de evento para cambiar el tipo de contraseña
eye_show1.addEventListener('click', function() {
  if (password.type === 'password') {
      password.type = 'text';
  } else {
      password.type = 'password';
  }
});
</script>
</html>