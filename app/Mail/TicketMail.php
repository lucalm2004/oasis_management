<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class TicketMail extends Mailable
{
    use Queueable, SerializesModels;

    public $pdfContent;
    public $viewData;
    public $userData;

    public function __construct($pdfContent, $viewData, $userData)
    {
        $this->pdfContent = $pdfContent;
        $this->viewData = $viewData;
        $this->userData = $userData;
    }

    public function build()
    {
        return $this->subject('Your Ticket for ')
        ->view('mail.emailtickets')
        ->with([
            'viewData' => $this->viewData,
            'userData' => $this->userData,
        ])
        ->attachData($this->pdfContent, 'ticket.pdf', [
            'mime' => 'application/pdf',
        ]);
    }
}