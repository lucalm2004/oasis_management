<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ScreenshotEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $screenshotURL;

    public function __construct($screenshotURL)
    {
        $this->screenshotURL = $screenshotURL;
    }

    public function build()
    {
        return $this->view('mail.screenshot')
                    ->attach($this->screenshotURL, [
                        'as' => 'screenshot.png',
                        'mime' => 'image/png'
                    ]);
    }
}