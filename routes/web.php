<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

Route::get('/', function () {
    return view('welcome');
});


Route::get('/google-auth/redirect', function () {
    return Socialite::driver('google')->redirect();
});
 
Route::get('/google-auth/callback-url', function () {
    $user_google = Socialite::driver('google')->user();
    // dd($user_google->id);
    $user = User::updateOrCreate([
        'google_id' => $user_google->id,
    ], [
        'name' => $user_google->name,
        'email' => $user_google->email,
        'google_id' => $user_google->id,
    ]);
    Auth::login($user);
    $rol = $user->rol; // Acceder al campo "rol" del usuario
    // dd($rol);
    return redirect('/dashboard');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';



////////////////////////David//////////////////////////

use App\Http\Controllers\WelcomeController;
use App\Http\Controllers\ValoracionController;
use App\Http\Controllers\DiscotecaController;
use App\Http\Controllers\ContactoController;

// Ruta para cerrar sesión
Route::post('/logout', function () {
    Auth::logout(); // Cerrar sesión del usuario
    return redirect('/'); // Redireccionar a la página de inicio u otra página
})->name('logout');

// Rutas públicas
Route::get('/', [WelcomeController::class, 'index'])->name('welcome');

// Rutas para valoraciones
Route::get('/valoracion', [ValoracionController::class, 'showValoracionPage'])->name('valoracion');
Route::get('/valoracion/form/{idEvento}', [ValoracionController::class, 'create'])->name('valoracion.create');
Route::post('/valoracion/store', [ValoracionController::class, 'store'])->name('valoracion.store');
Route::get('/eventos/{idEvento}/resenas', [ValoracionController::class, 'showResenas'])->name('eventos.resenas');
Route::get('/valoracion/top-rated-users', [ValoracionController::class, 'showTopRatedUsers'])->name('valoracion.topRatedUsers');

// Rutas para discotecas y eventos
Route::get('/discotecas/{id}/eventos', [DiscotecaController::class, 'getEventosByDiscoteca'])->name('discotecas.eventos');

// Ruta para cargar la vista de contacto
Route::get('/contacto', [ContactoController::class, 'index'])->name('contacto');

// Rutas protegidas con autenticación
Route::middleware('auth')->group(function () {
// Ruta para mostrar y editar el perfil
Route::get('/perfil', [ProfileController::class, 'edit'])->name('perfil');
Route::put('/profile/update', [ProfileController::class, 'update'])->name('profile.update');
});