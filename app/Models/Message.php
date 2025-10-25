<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Message extends Model
{
    use HasFactory;

    protected $fillable = [
        'expediteur_id',
        'destinataire_id',
        'commande_id',
        'sujet',
        'contenu',
        'lu',
        'lu_at',
    ];

    protected $casts = [
        'lu' => 'boolean',
        'lu_at' => 'datetime',
    ];

    /**
     * L'expéditeur du message
     */
    public function expediteur(): BelongsTo
    {
        return $this->belongsTo(User::class, 'expediteur_id');
    }

    /**
     * Le destinataire du message
     */
    public function destinataire(): BelongsTo
    {
        return $this->belongsTo(User::class, 'destinataire_id');
    }

    /**
     * La commande associée au message (optionnel)
     */
    public function commande(): BelongsTo
    {
        return $this->belongsTo(Commande::class, 'commande_id');
    }

    /**
     * Marquer le message comme lu
     */
    public function marquerCommeLu(): void
    {
        if (!$this->lu) {
            $this->update([
                'lu' => true,
                'lu_at' => now(),
            ]);
        }
    }

    /**
     * Scope pour les messages non lus
     */
    public function scopeNonLus($query)
    {
        return $query->where('lu', false);
    }

    /**
     * Scope pour les messages d'un utilisateur
     */
    public function scopePourUtilisateur($query, $userId)
    {
        return $query->where('destinataire_id', $userId);
    }
}
