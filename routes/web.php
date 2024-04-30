<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\ClienteController;
use Illuminate\Support\Facades\Session;


Route::get('/', function () {
    return view('welcome');
});


Route::get('/google-auth/redirect', function () {
    return Socialite::driver('google')->redirect();
});

Route::get('/cliente', [ClienteController::class, 'index'])->name('cliente.discoteca');
Route::get('/cliente/{id}/eventos', [ClienteController::class, 'eventos'])->name('cliente.eventos');
Route::get('/cliente/entradas/{id}', [ClienteController::class, 'mostrar'])->name('cliente.entradas');
Route::get('/cliente/carrito', [ClienteController::class, 'carrito'])->name('cliente.carrito');
Route::get('/cliente/bonificacion', [ClienteController::class, 'bonificacion'])->name('cliente.bonificacion');
Route::get('/bonificaciones', [ClienteController::class, 'fetchBonificaciones'])->name('bonificaciones.ajax');
Route::get('/cliente/mostrardisco', [ClienteController::class, 'mostrardisco'])->name('cliente.mostrardisco');
Route::get('/cliente/mostrareventos/{id_discoteca}', [ClienteController::class, 'mostrareventos'])->name('cliente.mostrareventos');
Route::get('/cliente/mostrarpuntos', [ClienteController::class, 'mostrarpuntos']);
Route::get('/cliente/ciudades', [ClienteController::class, 'ciudades']);
Route::get('/cliente/detallesdiscoteca/{id}', [ClienteController::class, 'mostrarDetallesDiscoteca']);
Route::get('/cliente/tiposentrada/{id}', [ClienteController::class, 'mostrarTiposEntrada']);
Route::get('/cliente/detallesevento/{id}', [ClienteController::class, 'mostrarDetallesEvento']);
Route::get('/cliente/logout', [ClienteController::class, 'logout'])->name('logout');
Route::get('/cliente/mostrarCancionesEvento/{id}', [ClienteController::class, 'mostrarCancionesEvento'])->name('mostrarCancionesEvento');

Route::post('/cliente/insertarEnCarrito', [ClienteController::class, 'insertarEnCarrito'])->name('cliente.insertarEnCarrito');

//Poner funcion de controlador playlist

Route::get('/google-auth/callback-url', function () {
    $user_google = Socialite::driver('google')->user();
    
    $user = User::updateOrCreate([
        'google_id' => $user_google->id,
    ], [
        'name' => $user_google->name,
        'email' => $user_google->email,
        'google_id' => $user_google->id,
    ]);
    Auth::login($user);
    $rol = $user->rol; // Acceder al campo "rol" del usuario
    Session::put("usuarioId", $user->id);
    // dd($user->id);
    // dd($rol);
    // return redirect('cliente.discoteca');
    // return redirect()->route('cliente.discoteca');
    return view('cliente.discoteca');
});

Route::get('/dashboard', function () {
    return redirect('/cliente');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
