<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class DiscotecaEliminadaMail extends Mailable
{
    use Queueable, SerializesModels;

    public $camarero;
    public $discoteca_correo;
    
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($camarero, $discoteca_correo,)
    {
        
        $this->camarero = $camarero;
        $this->discoteca_correo = $discoteca_correo;
        
    }
    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Discoteca eliminada')->view('mail/discotecaeliminada');
    }
}
