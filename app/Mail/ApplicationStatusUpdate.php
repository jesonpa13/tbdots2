<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ApplicationStatusUpdate extends Mailable
{
    use Queueable, SerializesModels;

    public $head_of_unit;
    public $facility;
    public $status;
    public $message;
    public $senderEmail;
    public $subject;

    public function __construct($head_of_unit, $facility, $status, $message, $senderEmail, $subject)
    {
        $this->head_of_unit = $head_of_unit;
        $this->facility = $facility;
        $this->status = $status;
        $this->message = $message;
        $this->senderEmail = $senderEmail;
        $this->subject = $subject;
    }

    public function build()
    {
        return $this
            ->from($this->senderEmail)
            ->subject($this->subject)
            ->view('emails.application_status_update'); // Make sure this view exists
    }
}