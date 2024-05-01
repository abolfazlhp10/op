<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class Sendmail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */

    public $subject;
    public $data;

    public function __construct($data,$subject)
    {
        $this->data = $data;
        $this->subject = $subject;
    }


    public function build()
    {
        return $this->subject($this->subject)->view('email/index');
    }
}
