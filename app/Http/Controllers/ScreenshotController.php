<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\ScreenshotEmail;
use Illuminate\Support\Facades\Auth;
use Dompdf\Dompdf;
use Dompdf\Options;

class ScreenshotController extends Controller
{
    public function captureScreenshot(Request $request)
    {
        // Capturar la imagen y almacenarla en una variable $capturedImageURL
        $capturedImage = $request->input('capturedImage');
                
        // Enviar correo con el PDF como adjunto
        try {
            $user = Auth::user();
            $email = $user->email;
            Mail::to($email)->send(new ScreenshotEmail($capturedImage));
            
            return "Correo enviado correctamente";
        } catch (\Exception $e) {
            return "Error al enviar el correo: " . $e->getMessage();
        }
    }
    
   
}
