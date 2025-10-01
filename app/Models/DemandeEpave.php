<?php

// app/Models/DemandeEpave.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class DemandeEpave extends Model
{
    protected $table = 'demandes_epaves'; // <-- IMPORTANT

    protected $fillable = [
        'user_id',
        'marque',
        'modele',
        'annee',
        'numero_chassis',
        'numero_plaque',
        'couleur',
        'carburant',
        'kilometrage',
        'etat',
        'prix_souhaite',
        'description',
        'photos',
        'telephone_contact',
        'adresse',
        'statut',
        'commentaire_casse'
    ];

    protected $casts = [
        'photos' => 'array'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function offres(): HasMany
    {
        return $this->hasMany(OffreEpave::class);
    }

    public function getPhotosUrlsAttribute()
    {
        if (!$this->photos) return [];

        return collect($this->photos)->map(function($photo) {
            return asset('storage/' . $photo);
        })->toArray();
    }

    public function getStatutBadgeClassAttribute()
    {
        return match($this->statut) {
            'en_attente' => 'bg-warning',
            'interesse' => 'bg-info',
            'accepte' => 'bg-success',
            'refuse' => 'bg-danger',
            default => 'bg-secondary'
        };
    }

    public function getMeilleureOffre()
    {
        return $this->offres()->orderBy('prix_offert', 'desc')->first();
    }

    public function hasOffreFrom($casseId)
    {
        return $this->offres()->where('user_id', $casseId)->exists();
    }
}

