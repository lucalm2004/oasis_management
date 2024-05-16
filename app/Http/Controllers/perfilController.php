<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;
use App\Models\Discoteca;
use Illuminate\Support\Facades\Redirect;

class perfilController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request)
    {
        $discotecas = Discoteca::all();  // Obtener las discotecas desde el modelo Discoteca
        $user = Auth::user();            // Obtener el usuario autenticado
    
        return view('perfil', compact('discotecas', 'user'));
    }
    
    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $user = $request->user();
    
        // Actualizar los campos del usuario con los datos validados del formulario
        $user->fill($request->validated());
    
        // Verificar si se proporcionó una nueva contraseña
        if ($request->filled('password')) {
            $user->password = Hash::make($request->input('password'));
        }
        if ($request->hasFile('foto')) {
            // Obtener la imagen cargada
            $image = $request->file('foto');
            // Generar un nombre único para la imagen
            $imageName = time() . '_' . $image->getClientOriginalName();
            // Mover la imagen a la carpeta deseada (por ejemplo, public/img/profiles/)
            $image->move(public_path('img/profiles'), $imageName);
            // Actualizar el campo 'foto' del usuario en la base de datos
            $user->foto = $imageName;

        }
        
    
        // Guardar los cambios en el usuario
        $user->save();
    
        // Redirigir al usuario a la página de perfil con un mensaje de éxito
        return redirect('/perfil')->with('status', 'profile-updated');
    }
    

    /**
     * Show the user's profile with role.
     */
    public function perfil(): View
    {
        $user = Auth::user(); // Obtener el usuario autenticado
        $discotecas = Discoteca::all(); // Obtener todas las discotecas desde el modelo Discoteca

        return view('perfil', compact('discotecas', 'user'));
    }
    public function bonificacion()
    {
        $user = Auth::user();
    
        // Verificar si hay un usuario autenticado
        $nombreUsuario = $user ? $user->name : null;
    
        // Pasar la variable 'nombreUsuario' a la vista
        return view('cliente.bonificacion', compact('nombreUsuario'));
    }
    
}
