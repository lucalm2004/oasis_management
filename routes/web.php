<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\googlecontroller;
use App\Http\Controllers\registerGestorController;
use App\Http\Controllers\eventosController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\perfilController;
use App\Http\Controllers\WelcomeController;
use App\Http\Controllers\ValoracionController;
use App\Http\Controllers\DiscotecaController;
use App\Http\Controllers\ContactoController;
use App\Http\Controllers\CamareroController;
use App\Http\Controllers\registerCamareroController;

use App\Http\Middleware\AdminOnly;
use App\Http\Middleware\ClientOnly;
use App\Http\Middleware\GestorOnly;
use App\Http\Middleware\CamareroOnly;



Route::get('/', function () {
    return view('welcome');
});
Route::post('/registerGestor', [registerGestorController::class, 'index'])->name('registerGestor');
Route::post('/registerCamarero', [registerCamareroController::class, 'index'])->name('registerCamarero');




Route::get('/google-auth/redirect', function () {
    return Socialite::driver('google')->redirect();
})->name('login.google');
Route::get('/google-auth/callback-url', function () {
    $user_google = Socialite::driver('google')->user();
    // dd($user_google->id);
    $user = User::updateOrCreate([
        'google_id' => $user_google->id,
    ], [
        'name' => $user_google->name,
        'email' => $user_google->email,
        'google_id' => $user_google->id,
        'habilitado'=> "1",
        'habilitado'=> "1",
    ]);
    Auth::login($user);
    return redirect('/dashboard');
});
Route::get('/dashboard', function () {
    $user = Auth::user();
    // dd($user);
    $rol = $user->id_rol; // Acceder al campo "rol" del usuario
   
    if ($rol == '3'){
        return redirect('/gestor');
    }elseif($rol == '1'){
        return redirect('/admin');
    }elseif($rol == '2'){
        return redirect('/cliente');
    }elseif($rol == '4'){
        return redirect('/camarero');
    }else{
        return redirect('/login');
    }
    // return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});
require __DIR__.'/auth.php';


Route::middleware(GestorOnly::class)->group(function () {
    
    Route::get('/gestor', function (){
        return view('gestor');
    });
    Route::post('/eventosView', [eventosController::class, 'index'])->name('eventosView');
    Route::post('/borrarEvento', [eventosController::class, 'borrar'])->name('borrarEvento');
    Route::post('/eventoNew', [eventosController::class, 'new'])->name('eventoNew');
    Route::post('/eventoUpdate', [eventosController::class, 'update'])->name('eventoUpdate');
    Route::post('/cancionesView', [eventosController::class, 'canciones'])->name('cancionesView');
    Route::post('/playlistView', [eventosController::class, 'playlist'])->name('playlistView');
    Route::post('/cancionUpdate', [eventosController::class, 'cancionUpdate'])->name('cancionUpdate');
    Route::get('/solicitudes', [eventosController::class, 'showSolicitudes'])->name('showSolicitudes');
    Route::post('/solcitudesaceptar/{id}', [eventosController::class, 'AceptarSolicitudes'])->name('AceptarSolicitudes');
    Route::post('/solcitudrechazar/{id}', [eventosController::class, 'RechazarSolicitudes'])->name('RechazarSolicitudes');
    Route::post('/editarPlaylist', [eventosController::class, 'editar'])->name('editarPlaylist');
    Route::get('/editarPlaylist', [eventosController::class, 'editar'])->name('editarPlaylist');
    Route::post('/playlistUpdate', [eventosController::class, 'playlistUpdate'])->name('playlistUpdate');
    Route::post('/personal', [eventosController::class, 'verPersonal'])->name('verPersonal');
    Route::delete('eliminarPersonal/{id}', [eventosController::class, 'eliminarPersonal'])->name('eliminarPersonal');
    Route::get('/discoteca', [eventosController::class, 'discoteca'])->name('discoteca');
    Route::post('/discotecaUpdate', [eventosController::class, 'discotecaUpdate'])->name('discotecaUpdate');
    
    
    
});


// Sergi


Route::middleware(AdminOnly::class)->group(function () {
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
        Route::get('/admin6', function () {
            return view('admin/crudcanciones');
        })->middleware(['auth', 'verified'])->name('admin.crudcanciones');
        Route::get('/admin7', function () {
            return view('admin/crudartistas');
        })->middleware(['auth', 'verified'])->name('admin.crudartistas');
        
        
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

            /* CRUD CANCIONES */
            Route::post('admin6/crudcanciones', 'showCrudCanciones')->name('crud.showCrudCanciones');
            Route::get('admin6/crudcanciones/artistas', 'obtenerArtistas')->name('crud.obtenerArtistas');
            Route::delete('admi6/crudcanciones/{id}', 'EliminarCanciones')->name('crud.EliminarCanciones');
            Route::get('admi6/crudcanciones/modadmin/{id}', 'editCanciones')->name('crud.editCanciones');
            Route::post('admi6/crudcanciones/actualizar/{id}', 'actualizarCanciones')->name('crud.actualizarCanciones');
            Route::post('admi6/crudcanciones/insercancion', 'storeCancion')->name('crud.storeCancion');

            /* CRUD ARTISTAS */
            Route::post('admin7/crudartistas', 'showCrudArtistas')->name('crud.showCrudArtistas');
            Route::delete('admin7/crudartistas/{id}', 'EliminarArtistas')->name('crud.EliminarArtistas');
            Route::get('admin7/crudartistas/modadmin/{id}', 'editArtistas')->name('crud.editArtistas');
            Route::post('admin7/crudartistas/modadmin/{id}', 'actualizarArtistas')->name('crud.actualizarArtistas');
            Route::post('admin7/crudartistas/insertartista', 'storeArtista')->name('crud.storeArtista');
       
        });
    
    
        
    
    });

});
// require __DIR__.'/auth.php';
// DAVID
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
Route::get('/perfil', [perfilController::class, 'edit'])->name('perfil');
Route::put('/profile/update', [perfilController::class, 'update'])->name('profile.update');
});


Route::middleware(ClientOnly::class)->group(function () {
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
// Route::get('/cliente/logout', [ClienteController::class, 'logout'])->name('logout');
Route::get('/cliente/mostrarCancionesEvento/{id}', [ClienteController::class, 'mostrarCancionesEvento'])->name('mostrarCancionesEvento');
Route::post('/cliente/insertarEnCarrito', [ClienteController::class, 'insertarEnCarrito'])->name('cliente.insertarEnCarrito');
Route::get('/cliente/carrito', [ClienteController::class, 'obtenerCarrito'])->name('cliente.obtenerCarrito');
Route::delete('/cliente/carrito/{id}', [ClienteController::class, 'eliminarProductoCarrito'])->name('cliente.eliminarProductoCarrito');
Route::get('/cliente/carrito/{id}', [ClienteController::class, 'eliminarProductoCarrito'])->name('cliente.eliminarProductoCarrito');

});
//  Ian


Route::middleware(CamareroOnly::class)->group(function () {
Route::get('/camarero', [CamareroController::class, 'camarero']);
Route::post('/eventos', [CamareroController::class, 'listar_eventos']) ->name('eventos');
Route::post('/playlistView2', [CamareroController::class, 'playlist'])->name('playlistView');
Route::post('/editarPlaylist2', [CamareroController::class, 'editar'])->name('editarPlaylist');

});

