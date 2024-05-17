<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class EliminarPersonal extends Mailable
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
    public function build()
    {
        return $this->subject('Cuenta eliminada')->view('mail/eliminarpersonal');
    }
}
