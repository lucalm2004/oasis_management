<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\AdminController;

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
    Route::get('/admin', function () {
        return view('admin/crudusuarios');
    })->middleware(['auth', 'verified'])->name('admin.crudusuarios');

    Route::controller(AdminController::class)->group(function () {
        Route::post('admin/crudusuarios', 'showCrudUsers')->name('crud.showCrudUsers');
        Route::get('admin/crudusuarios/roles', 'showRoles')->name('crud.showRoles');
        Route::get('admin/crudusuarios/modadmin/{id}', 'editUsers')->name('crud.editUsers');
        Route::post('admin/crudusuarios/actualizar/{id}', 'actualizarUsers')->name('crud.actualizarUsers');
        Route::delete('admin/crudusuarios/{id}', 'EliminarUsers')->name('crud.EliminarUsers');
        Route::post('admin/crudusuarios/insertuser', 'storeUser')->name('crud.storeUser');
    });


    

});

require __DIR__.'/auth.php';
