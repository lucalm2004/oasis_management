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
      
      <img src="{{asset('img/redondoLetrasNegras.png')}}" alt="" class="logo" style="cursor: pointer;" id="volverParaatras">
      <   <form method="POST" action="{{ route('login') }}">
        @csrf
        <div class="email">
            <label for="email" style="border-bottom: 1px solid;;opacity: 50%">Email Address</label>
            <div class="sec-2">
                <ion-icon name="mail-outline"></ion-icon>
                <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
            </div>
            <x-input-error :messages="$errors->get('email')" class="mt-2" />

        </div>
        <div class="password">
            <label for="password" style="border-bottom: 1px solid;opacity: 50%">Password</label>
            <div class="sec-2">
                <ion-icon name="lock-closed-outline"></ion-icon>
                <x-text-input id="password" class="block mt-1 w-full" type="password" name="password" style="width: 80%;" required autocomplete="current-password" />
                <ion-icon class="show-hide" id="eye_show1" name="eye-outline"></ion-icon>
            </div>
            <x-input-error :messages="$errors->get('password')" class="mt-2" />

        </div>                <div style="display: flex; gap:1%">

        <button  class="login">Login </button>
        <button onclick="window.location.href='google-auth/redirect'" class="gsi-material-button">
          <div class="gsi-material-button-state"></div>
          <div class="gsi-material-button-content-wrapper">
            <div class="gsi-material-button-icon">
              <svg version="1.1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 48 48" xmlns:xlink="http://www.w3.org/1999/xlink" style="display: block;">
                <path fill="#EA4335" d="M24 9.5c3.54 0 6.71 1.22 9.21 3.6l6.85-6.85C35.9 2.38 30.47 0 24 0 14.62 0 6.51 5.38 2.56 13.22l7.98 6.19C12.43 13.72 17.74 9.5 24 9.5z"></path>
                <path fill="#4285F4" d="M46.98 24.55c0-1.57-.15-3.09-.38-4.55H24v9.02h12.94c-.58 2.96-2.26 5.48-4.78 7.18l7.73 6c4.51-4.18 7.09-10.36 7.09-17.65z"></path>
                <path fill="#FBBC05" d="M10.53 28.59c-.48-1.45-.76-2.99-.76-4.59s.27-3.14.76-4.59l-7.98-6.19C.92 16.46 0 20.12 0 24c0 3.88.92 7.54 2.56 10.78l7.97-6.19z"></path>
                <path fill="#34A853" d="M24 48c6.48 0 11.93-2.13 15.89-5.81l-7.73-6c-2.15 1.45-4.92 2.3-8.16 2.3-6.26 0-11.57-4.22-13.47-9.91l-7.98 6.19C6.51 42.62 14.62 48 24 48z"></path>
                <path fill="none" d="M0 0h48v48H0z"></path>
              </svg>
            </div>
            <span class="gsi-material-button-contents">Log in with Google</span>
            <span style="display: none;">Log in with Google</span>
          </div>
        </div>
      </button>
{{--         <div class="footer"><span onclick="window.location.href='{{ route('register') }}'">Signup</span><span onclick="window.location.href='{{ route('password.request') }}'">Forgot Password?</span></div> --}}
</div>
</form>


</body>


<script>
  var eye_show1 = document.getElementById('eye_show1');
var password = document.getElementById('password');

// Manejador de evento para cambiar el tipo de contraseÃ±a
eye_show1.addEventListener('click', function() {
  if (password.type === 'password') {
      password.type = 'text';
  } else {
      password.type = 'password';
  }
});
var imagen = document.getElementById('volverParaatras');


        imagen.addEventListener('click', function() {

            window.location.href = '/';
        }); 
</script>
</html>
