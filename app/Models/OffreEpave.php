<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OffreEpave extends Model
{
    protected $table = 'offres_epaves';

    protected $fillable = [
        'demande_epave_id',
        'user_id',  // Changé de casse_id à user_id
        'prix_offert',
        'message',
        'statut'
    ];

    protected $casts = [
        'prix_offert' => 'decimal:2',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function demandeEpave(): BelongsTo
    {
        return $this->belongsTo(DemandeEpave::class);
    }

    // Relation générique pour tous les utilisateurs (clients et casses)
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Garde la relation casse pour la compatibilité (alias de user)
    public function casse(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function getStatutBadgeClassAttribute()
    {
        return match($this->statut) {
            'en_attente' => 'bg-warning',
            'accepte' => 'bg-success',
            'refuse' => 'bg-danger',
            default => 'bg-secondary'
        };
    }

    public function getFormattedPrixAttribute()
    {
        return number_format($this->prix_offert, 0, ',', ' ') . ' FCFA';
    }
}
