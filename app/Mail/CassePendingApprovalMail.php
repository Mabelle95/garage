<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class CassePendingApprovalMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public User $casse
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Nouvelle demande d\'approbation de casse',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.casse-pending-approval',
        );
    }
}
