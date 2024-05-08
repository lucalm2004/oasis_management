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
<style>
  #eye_show1 {
    cursor: pointer;
}
</style>
</head>

<body>
    <div class="screen-1">
      <h2>Oasis | Reset Password</h2>
        <img src="{{asset('img/redondoLetrasNegras.png')}}" alt="" class="logo">
        <form method="POST" action="{{ route('login') }}">
            @csrf
            <div class="email">
                <label for="email" style="border-bottom: 1px solid;;opacity: 50%">Email Address</label>
                <div class="sec-2">
                    <ion-icon name="mail-outline"></ion-icon>
                    <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
                </div>
                <x-input-error :messages="$errors->get('email')" class="mt-2" />

            </div>
                          <div style="display: flex; gap:1%">

            <button  class="login">Reset Your Password </button>
            
            </div>
            <div class="footer"><span onclick="window.location.href='{{ route('login') }}'">Already
                Registred?</span><span onclick="window.location.href='{{ route('register') }}'">Signup</span></div>
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
</script>
</html>
