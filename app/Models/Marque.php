<?php
// app/Models/Marque.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Marque extends Model
{
    protected $fillable = ['nom', 'is_active'];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function modeles(): HasMany
    {
        return $this->hasMany(Modele::class);
    }

    public function pieces(): HasMany
    {
        return $this->hasMany(Piece::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
