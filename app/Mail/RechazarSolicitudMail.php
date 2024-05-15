<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class RechazarSolicitudMail extends Mailable
{
    use Queueable, SerializesModels;

    public $user_correo;
    public $discoteca_correo;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($user_correo, $discoteca_correo)
    {
        
        $this->user_correo = $user_correo;
        $this->discoteca_correo = $discoteca_correo;
    }
    /**
     * Build the message.
     *
     * @return $this
     */
    public function build(){

        $rol = $this->user_correo->id_rol;

        if ($rol == 3) {
            $estado = "Solicitud discoteca rechazada";
        } else if ($rol == 4) {
            $estado = "Solicitud de empleo rechazada";
        }
        
        return $this->subject($estado)->view('mail.rechazarsolicitud');
    }

}
