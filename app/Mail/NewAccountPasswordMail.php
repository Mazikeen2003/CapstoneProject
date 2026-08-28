<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NewAccountPasswordMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public string $name,
        public string $username,
        public string $setupUrl,
    ) {}

    public function build()
    {
        return $this->subject('Set Up Your ProjectTracker Account')
            ->view('emails.new-account-password');
    }
}
