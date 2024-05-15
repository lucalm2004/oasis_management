<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class EventoModificadoMail extends Mailable
{
    use Queueable, SerializesModels;

    public $camarero;
    public $discoteca_correo;
    public $evento;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($camarero, $discoteca_correo, $evento)
    {
        
        $this->camarero = $camarero;
        $this->discoteca_correo = $discoteca_correo;
        $this->evento = $evento;
    }
    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Cambios Evento')->view('mail/camareroeventoactualizado');
    }
}
