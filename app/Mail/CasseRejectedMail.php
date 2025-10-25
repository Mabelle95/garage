<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class CasseRejectedMail extends Mailable
{
    use Queueable, SerializesModels;

    public $casse;

    public function __construct(User $casse)
    {
        $this->casse = $casse;
    }

    public function build()
    {
        return $this->subject('Votre demande de compte casse a été rejetée')
            ->view('emails.casse-rejected');
    }
}
