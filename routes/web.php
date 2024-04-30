<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CamareroController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Route::get('/dashboard', function () {
//     return view('dashboard');
// })->middleware(['auth', 'verified'])->name('dashboard');

// Route::middleware('auth')->group(function () {
//     Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
//     Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
//     Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
// });

Route::get('/camarero', function () {
    return view('listar_eventos/camarero');
})->name('camarero.listar_eventos');
Route::get('camarero/eventos', [CamareroController::class, 'listar_eventos']) ->name('listar_eventos');


require __DIR__.'/auth.php';