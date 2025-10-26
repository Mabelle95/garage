<?php




// app/Http/Requests/StoreVehicleRequest.php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreVehicleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->user()->isCasse();
    }

    public function rules(): array
    {
        return [
            'marque' => ['required', 'string', 'max:255'],
            'modele' => ['required', 'string', 'max:255'],
            'annee' => ['required', 'integer', 'min:1900', 'max:' . (date('Y') + 1)],
            'numero_chassis' => ['required', 'string', 'max:17', 'unique:vehicles,numero_chassis'],
            'numero_plaque' => ['required', 'string', 'max:20', 'unique:vehicles,numero_plaque'],
            'couleur' => ['required', 'string', 'max:100'],
            'carburant' => ['required', Rule::in(['essence', 'diesel', 'hybride', 'electrique'])],
            'transmission' => ['required', Rule::in(['manuelle', 'automatique'])],
            'kilometrage' => ['required', 'integer', 'min:0'],
            'etat' => ['required', Rule::in(['bon', 'moyen', 'mauvais', 'epave'])],
            'date_arrivee' => ['required', 'date', 'before_or_equal:today'],
            'prix_epave' => ['required', 'numeric', 'min:0'],
            'photo_principale' => ['nullable', 'image', 'mimes:jpeg,png,jpg', 'max:2048'],
            'photos_additionnelles' => ['nullable', 'array', 'max:5'],
            'photos_additionnelles.*' => ['image', 'mimes:jpeg,png,jpg', 'max:2048'],
            'description' => ['nullable', 'string', 'max:1000'],
            'data_scan' => ['nullable', 'json']
        ];
    }

    public function messages(): array
    {
        return [
            'marque.required' => 'La marque est obligatoire.',
            'modele.required' => 'Le modèle est obligatoire.',
            'annee.required' => 'L\'année est obligatoire.',
            'annee.min' => 'L\'année doit être supérieure à 1900.',
            'annee.max' => 'L\'année ne peut pas être dans le futur.',
            'numero_chassis.required' => 'Le numéro de châssis est obligatoire.',
            'numero_chassis.unique' => 'Ce numéro de châssis existe déjà.',
            'numero_chassis.max' => 'Le numéro de châssis ne peut pas dépasser 17 caractères.',
            'numero_plaque.required' => 'Le numéro de plaque est obligatoire.',
            'numero_plaque.unique' => 'Ce numéro de plaque existe déjà.',
            'carburant.in' => 'Le type de carburant sélectionné est invalide.',
            'transmission.in' => 'Le type de transmission sélectionné est invalide.',
            'etat.in' => 'L\'état sélectionné est invalide.',
            'date_arrivee.before_or_equal' => 'La date d\'arrivée ne peut pas être dans le futur.',
            'prix_epave.min' => 'Le prix doit être positif.',
            'photo_principale.image' => 'Le fichier doit être une image.',
            'photo_principale.max' => 'L\'image ne peut pas dépasser 2MB.',
            'photos_additionnelles.max' => 'Vous ne pouvez pas ajouter plus de 5 photos.',
            'photos_additionnelles.*.image' => 'Chaque fichier doit être une image.',
            'photos_additionnelles.*.max' => 'Chaque image ne peut pas dépasser 2MB.',
        ];
    }

    protected function prepareForValidation(): void
    {
        if ($this->has('numero_chassis')) {
            $this->merge([
                'numero_chassis' => strtoupper($this->numero_chassis)
            ]);
        }
    }
}
