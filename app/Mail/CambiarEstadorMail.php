<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class CambiarEstadorMail extends Mailable
{
    use Queueable, SerializesModels;

    public $user_correo;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($user_correo)
    {
        
        $this->user_correo = $user_correo;
    }
    /**
     * Build the message.
     *
     * @return $this
     */
    public function build(){

        $habilitado = $this->user_correo->habilitado;

        if ($habilitado == 1) {
            $estado = "Habilitada";
        } else {
            $estado = "Deshabilitada";
        }
        
        return $this->subject("Cuenta $estado")->view('mail.cambioestado');
    }

}
