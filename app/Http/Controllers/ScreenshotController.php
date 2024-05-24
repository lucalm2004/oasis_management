<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\ScreenshotEmail;
use App\Models\RegistroEntrada;
use Illuminate\Support\Facades\Auth;
use Dompdf\Dompdf;
use Dompdf\Options;

class ScreenshotController extends Controller
{
    public function captureScreenshot(Request $request)
    {
        // Capturar la imagen y almacenarla en una variable $capturedImageURL
        $capturedImage = $request->input('capturedImage');
        $codigo = $request->input('codigo');
        $image_parts = explode(";base64,", $capturedImage);
        $image_type_aux = explode("image/", $image_parts[0]);
        $image_type = $image_type_aux[1];
        $image_base64 = base64_decode($image_parts[1]);

        // Generar un nombre único para la imagen
        $imageName = time() . '_' . $codigo . '.' . $image_type;
        $imagePath = 'tickets/' . $imageName;

        // Guardar la imagen en el servidor
        file_put_contents(public_path($imagePath), $image_base64);
        $entrada = RegistroEntrada::findOrFail($codigo);
        $entrada->entrada = $imageName;
        $entrada->save();
        // Enviar correo con el PDF como adjunto
        try {
            $user = Auth::user();
            $email = $user->email;
            Mail::to($email)->send(new ScreenshotEmail($capturedImage, $codigo));
            
            return "Correo enviado correctamente";
        } catch (\Exception $e) {
            return "Error al enviar el correo: " . $e->getMessage();
        }
    }
    
   
}
