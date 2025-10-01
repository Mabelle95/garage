<?php

namespace App\Models;

    use Illuminate\Database\Eloquent\Model;
    use Illuminate\Database\Eloquent\Relations\HasMany;
    use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Vehicle extends Model
{
    protected $fillable = [
        'casse_id',
        'marque',
        'modele',
        'annee',
        'numero_chassis',
        'numero_plaque',
        'couleur',
        'carburant',
        'transmission',
        'kilometrage',
        'etat',
        'date_arrivee',
        'prix_epave',
        'vendu',
        'photo_principale',
        'photos_additionnelles',
        'description',
        'data_scan'
    ];

    protected $casts = [
        'photos_additionnelles' => 'array',
        'data_scan' => 'array',
        'vendu' => 'boolean',
        'date_arrivee' => 'date'
    ];

    public function casse(): BelongsTo
    {
        return $this->belongsTo(User::class, 'casse_id');
    }

    public function pieces(): HasMany
    {
        return $this->hasMany(Piece::class);
    }
}
