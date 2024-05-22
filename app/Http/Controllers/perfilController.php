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
use App\Models\User;


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
    public function update(Request $request)
    {
       
        $user = User::find(Auth::id());

        // Validar otros campos del formulario
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        // Guardar la imagen subida si existe
        if ($request->hasFile('foto')) {
            $request->validate([
                'foto' => 'image|mimes:jpeg,png,jpg,gif|max:2048'
            ]);

            $fileName = time() . '.' . $request->foto->extension();
            $request->foto->move(public_path('img/profiles'), $fileName);
            $user->foto = $fileName;
        }

        // Guardar la imagen capturada por la cámara si existe
        if ($request->cameraImage) {
            $imageData = $request->cameraImage;
            $fileName = time() . '.png';
            list($type, $data) = explode(';', $imageData);
            list(, $data) = explode(',', $data);
            $data = base64_decode($data);
            file_put_contents(public_path('img/profiles') . '/' . $fileName, $data);
            $user->foto = $fileName;
        }

        // Actualizar otros campos del usuario
        $user->name = $request->name;
        $user->email = $request->email;

        if ($request->password) {
            $user->password = bcrypt($request->password);
        }

        $user->save();

        return redirect()->back()->with('success', 'Perfil actualizado correctamente');
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
