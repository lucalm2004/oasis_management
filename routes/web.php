<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\AdminController;
use Illuminate\Support\Facades\Session;

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
    Session::put("usuarioId", $user->id);
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
    Route::get('/admin2', function () {
        return view('admin/cruddiscotecas');
    })->middleware(['auth', 'verified'])->name('admin.cruddiscotecas');
    Route::get('/admin3', function () {
        return view('admin/crudbonificaciones');
    })->middleware(['auth', 'verified'])->name('admin.crudbonificaciones');
    Route::get('/admin4', function () {
        return view('admin/crudciudades');
    })->middleware(['auth', 'verified'])->name('admin.crudciudades');
    Route::get('/admin5', function () {
        return view('admin/crudeventos');
    })->middleware(['auth', 'verified'])->name('admin.crudeventos');

    Route::controller(AdminController::class)->group(function () {

        /* CRUD DE USUARIOS */
        Route::post('admin/crudusuarios', 'showCrudUsers')->name('crud.showCrudUsers');
        Route::delete('admin/crudusuarios/{id}', 'EliminarUsers')->name('crud.EliminarUsers');
        Route::get('admin/crudusuarios/roles', 'showRoles')->name('crud.showRoles');
        Route::get('admin/crudusuarios/modadmin/{id}', 'editUsers')->name('crud.editUsers');
        Route::post('admin/crudusuarios/actualizar/{id}', 'actualizarUsers')->name('crud.actualizarUsers');
        Route::get('admin/crudusuarios/actualizar/{id}', 'actualizarUsers')->name('crud.actualizarUsers');
        Route::post('admin/crudusuarios/cambiarestado/{id}', 'cambiarEstado')->name('crud.cambiarEstado');
        Route::post('admin/crudusuarios/insertuser', 'storeUser')->name('crud.storeUser');
        Route::get('admin/crudusuarios/insertuser', 'storeUser')->name('crud.storeUser');
        Route::get('admin/crudusuarios/discotecas', 'showDiscotecas')->name('crud.showDiscotecas');
        Route::get('admin/crudusuarios/solcitudes', 'showSolicitudes')->name('crud.showSolicitudes');
        Route::post('admin/crudusuarios/solcitudesaceptar/{id}', 'AceptarSolicitudes')->name('crud.AceptarSolicitudes');
        Route::post('admin/crudusuarios/solcitudrechazar/{id}', 'RechazarSolicitudes')->name('crud.RechazarSolicitudes');

        /* CRUD DISCOTECAS */
        Route::post('admin2/cruddiscotecas', 'showCrudDiscotecas')->name('crud.showCrudDiscotecas');
        Route::delete('admin2/cruddiscotecas/{id}', 'EliminarDiscotecas')->name('crud.EliminarDiscotecas');
        Route::get('admin2/cruddiscotecas/ciudades', 'showCiudades')->name('crud.showCiudades');
        Route::post('admin2/cruddiscotecas/insertdiscoteca', 'storeDiscoteca')->name('crud.storeDiscoteca');
        Route::get('admin2/cruddiscotecas/modadmin/{id}', 'editDiscoteca')->name('crud.editDiscoteca');
        Route::post('admin2/cruddiscotecas/actualizar/{id}', 'actualizarDiscoteca')->name('crud.actualizarDiscoteca');
    

        /* CRUD BONIFICACIONES */
        Route::post('admi3/crudbonificaciones', 'showCrudBonificaciones')->name('crud.showCrudBonificaciones');
        Route::delete('admi3/crudbonificaciones/{id}', 'EliminarBonificaciones')->name('crud.EliminarBonificaciones');
        Route::get('admi3/crudbonificaciones/modadmin/{id}', 'editBonificaciones')->name('crud.editBonificaciones');
        Route::post('admi3/crudbonificaciones/actualizar/{id}', 'actualizarBonificaciones')->name('crud.actualizarBonificaciones');
        Route::post('admi3/crudbonificaciones/insertuser', 'storeBonificacion')->name('crud.storeBonificacion');
   

        /* CRUD CIUDADES */
        Route::post('admin4/crudciudades', 'showCrudCiudades')->name('crud.showCrudCiudades');
        Route::delete('admin4/crudciudades/{id}', 'EliminarCiudades')->name('crud.EliminarCiudades');
        Route::get('admi4/crudciudades/modadmin/{id}', 'editCiudades')->name('crud.editCiudades');
        Route::post('admin4/crudciudades/actualizar/{id}', 'actualizarCiudades')->name('crud.actualizarCiudades');
        Route::post('admin4/crudciudades/insertuser', 'storeCiudad')->name('crud.storeCiudad');

        /* CRUD EVENTOS */
        Route::post('admin5/crudeventos', 'showCrudEventos')->name('crud.showCrudEventos');
        Route::delete('admin5/crudeventos/{id}', 'EliminarEventos')->name('crud.EliminarEventos');
   
    });


    

});

require __DIR__.'/auth.php';
