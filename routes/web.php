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




use App\Http\Controllers\WelcomeController;
use App\Http\Controllers\ValoracionController;

Route::get('/valoracion', [ValoracionController::class, 'showValoracionPage'])->name('valoracion');
Route::get('/valoracion/form/{idEvento}', [ValoracionController::class, 'create'])->name('valoracion.form');

// Ruta por defecto
Route::get('/', [WelcomeController::class, 'index']);
