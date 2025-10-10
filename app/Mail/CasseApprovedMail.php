<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class CasseApprovedMail extends Mailable
{
    use Queueable, SerializesModels;

    public $casse;

    public function __construct(User $casse)
    {
        $this->casse = $casse;
    }

    public function build()
    {
        return $this->subject('Votre compte casse a été approuvé')
            ->view('emails.casse-approved');
    }
}
