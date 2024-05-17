<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class abandonaroasis extends Mailable
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
    public function build()
    {
        return $this->subject('Hasta pronto')->view('mail/oassisabandonar');
    }
}
