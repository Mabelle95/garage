<?php
// app/Models/VenteEpave.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VenteEpave extends Model
{
    use HasFactory;

    protected $fillable = [
        'client_id',
        'marque',
        'modele',
        'annee',
        'immatriculation',
        'description',
        'etat',
        'photos',
        'prix_souhaite',
        'statut',
        'notes_evaluation',
        'prix_propose'
    ];

    protected $casts = [
        'photos' => 'array',
        'prix_souhaite' => 'decimal:2',
        'prix_propose' => 'decimal:2'
    ];

    public function client()
    {
        return $this->belongsTo(User::class, 'client_id');
    }
}
