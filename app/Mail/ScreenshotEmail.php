<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ScreenshotEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $screenshotURL;
    public $codigo;

    public function __construct($screenshotURL, $codigo)
    {
        $this->screenshotURL = $screenshotURL;
        $this->codigo = $codigo;
    }

    public function build()
    {
        return $this->subject('Your Tickets')->view('mail.screenshot')
                    ->attach($this->screenshotURL, [
                        'as' => 'ticket_' . $this->codigo . '_.png',
                        'mime' => 'image/png'
                    ]);
    }
}