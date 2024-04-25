<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\ClienteController;

Route::get('/', function () {
    return view('welcome');
});


Route::get('/google-auth/redirect', function () {
    return Socialite::driver('google')->redirect();
});

Route::get('/cliente', [ClienteController::class, 'index'])->name('cliente.discoteca');
Route::get('/cliente/{id}/eventos', [ClienteController::class, 'eventos'])->name('cliente.eventos');
Route::get('/cliente/entradas/{id}', [ClienteController::class, 'mostrar'])->name('cliente.entradas');

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
    // dd($rol);
    // return redirect('cliente.discoteca');
    // return redirect()->route('cliente.discoteca');
    return redirect('/cliente');
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
