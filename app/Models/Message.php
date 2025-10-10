<?php

// app/Models/Message.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Message extends Model
{
    protected $fillable = [
        'expediteur_id',
        'destinataire_id',
        'sujet',
        'message',
        'lu'
    ];

    protected $casts = [
        'lu' => 'boolean'
    ];

    public function expediteur(): BelongsTo
    {
        return $this->belongsTo(User::class, 'expediteur_id');
    }

    public function destinataire(): BelongsTo
    {
        return $this->belongsTo(User::class, 'destinataire_id');
    }

    public function markAsRead()
    {
        $this->update(['lu' => true]);
    }

    public function isFromCurrentUser()
    {
        return $this->expediteur_id === auth()->id();
    }

    public function isToCurrentUser()
    {
        return $this->destinataire_id === auth()->id();
    }
}

