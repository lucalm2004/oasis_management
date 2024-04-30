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

class ProfileController extends Controller
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
    
        // Guardar los cambios en el usuario
        $user->save();
    
        // Redirigir de vuelta a la página de edición del perfil con un mensaje de éxito
        return redirect()->route('profile.edit')->with('status', 'profile-updated');
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
}
