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

// app/Http/Requests/UpdateVehicleRequest.php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateVehicleRequest extends FormRequest
{
    public function authorize(): bool
    {
        $vehicle = $this->route('vehicle');
        return auth()->user()->isCasse() && auth()->id() === $vehicle->casse_id;
    }

    public function rules(): array
    {
        $vehicleId = $this->route('vehicle')->id;

        return [
            'marque' => ['required', 'string', 'max:255'],
            'modele' => ['required', 'string', 'max:255'],
            'annee' => ['required', 'integer', 'min:1900', 'max:' . (date('Y') + 1)],
            'numero_chassis' => ['required', 'string', 'max:17', Rule::unique('vehicles')->ignore($vehicleId)],
            'numero_plaque' => ['required', 'string', 'max:20', Rule::unique('vehicles')->ignore($vehicleId)],
            'couleur' => ['required', 'string', 'max:100'],
            'carburant' => ['required', Rule::in(['essence', 'diesel', 'hybride', 'electrique'])],
            'transmission' => ['required', Rule::in(['manuelle', 'automatique'])],
            'kilometrage' => ['required', 'integer', 'min:0'],
            'etat' => ['required', Rule::in(['bon', 'moyen', 'mauvais', 'epave'])],
            'prix_epave' => ['required', 'numeric', 'min:0'],
            'vendu' => ['boolean'],
            'photo_principale' => ['nullable', 'image', 'mimes:jpeg,png,jpg', 'max:2048'],
            'photos_additionnelles' => ['nullable', 'array', 'max:5'],
            'photos_additionnelles.*' => ['image', 'mimes:jpeg,png,jpg', 'max:2048'],
            'description' => ['nullable', 'string', 'max:1000'],
        ];
    }

    public function messages(): array
    {
        return [
            'numero_chassis.unique' => 'Ce numéro de châssis est déjà utilisé par un autre véhicule.',
            'numero_plaque.unique' => 'Ce numéro de plaque est déjà utilisé par un autre véhicule.',
        ];
    }
}

// app/Http/Requests/StorePieceRequest.php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Models\Vehicle;

class StorePieceRequest extends FormRequest
{
    public function authorize(): bool
    {
        if (!auth()->user()->isCasse()) {
            return false;
        }

        // Vérifier que le véhicule appartient à la casse connectée
        $vehicle = Vehicle::find($this->vehicle_id);
        return $vehicle && $vehicle->casse_id === auth()->id();
    }

    public function rules(): array
    {
        return [
            'vehicle_id' => ['required', 'exists:vehicles,id'],
            'nom' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string', 'max:1000'],
            'prix' => ['required', 'numeric', 'min:0.01'],
            'quantite' => ['required', 'integer', 'min:1'],
            'etat' => ['required', Rule::in(['neuf', 'tres_bon', 'bon', 'moyen', 'usage'])],
            'photos' => ['nullable', 'array', 'max:5'],
            'photos.*' => ['image', 'mimes:jpeg,png,jpg', 'max:2048'],
            'reference_constructeur' => ['nullable', 'string', 'max:100'],
            'compatible_avec' => ['nullable', 'array'],
            'compatible_avec.*' => ['string', 'max:255'],
        ];
    }

    public function messages(): array
    {
        return [
            'vehicle_id.required' => 'Vous devez sélectionner un véhicule.',
            'vehicle_id.exists' => 'Le véhicule sélectionné n\'existe pas.',
            'nom.required' => 'Le nom de la pièce est obligatoire.',
            'description.required' => 'La description est obligatoire.',
            'prix.required' => 'Le prix est obligatoire.',
            'prix.min' => 'Le prix doit être supérieur à 0.',
            'quantite.required' => 'La quantité est obligatoire.',
            'quantite.min' => 'La quantité doit être d\'au moins 1.',
            'etat.in' => 'L\'état sélectionné est invalide.',
            'photos.max' => 'Vous ne pouvez pas ajouter plus de 5 photos.',
            'photos.*.image' => 'Chaque fichier doit être une image.',
            'photos.*.max' => 'Chaque image ne peut pas dépasser 2MB.',
        ];
    }
}

// app/Http/Requests/UpdatePieceRequest.php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdatePieceRequest extends FormRequest
{
    public function authorize(): bool
    {
        $piece = $this->route('piece');
        return auth()->user()->isCasse() && auth()->id() === $piece->vehicle->casse_id;
    }

    public function rules(): array
    {
        return [
            'nom' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string', 'max:1000'],
            'prix' => ['required', 'numeric', 'min:0.01'],
            'quantite' => ['required', 'integer', 'min:0'],
            'etat' => ['required', Rule::in(['neuf', 'tres_bon', 'bon', 'moyen', 'usage'])],
            'photos' => ['nullable', 'array', 'max:5'],
            'photos.*' => ['image', 'mimes:jpeg,png,jpg', 'max:2048'],
            'reference_constructeur' => ['nullable', 'string', 'max:100'],
            'compatible_avec' => ['nullable', 'array'],
            'compatible_avec.*' => ['string', 'max:255'],
            'disponible' => ['boolean'],
        ];
    }
}

// app/Http/Requests/StoreCommandeRequest.php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreCommandeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->user()->isClient();
    }

    public function rules(): array
    {
        return [
            'nom_complet' => ['required', 'string', 'max:255'],
            'adresse_livraison' => ['required', 'string', 'max:500'],
            'telephone_livraison' => ['required', 'string', 'max:20'],
            'ville' => ['required', 'string', 'max:100'],
            'code_postal' => ['nullable', 'string', 'max:10'],
            'mode_paiement' => ['required', Rule::in(['carte_bancaire', 'paypal', 'virement', 'especes'])],
            'notes' => ['nullable', 'string', 'max:500'],
        ];
    }

    public function messages(): array
    {
        return [
            'nom_complet.required' => 'Le nom complet est obligatoire.',
            'adresse_livraison.required' => 'L\'adresse de livraison est obligatoire.',
            'telephone_livraison.required' => 'Le numéro de téléphone est obligatoire.',
            'ville.required' => 'La ville est obligatoire.',
            'mode_paiement.required' => 'Vous devez sélectionner un mode de paiement.',
            'mode_paiement.in' => 'Le mode de paiement sélectionné est invalide.',
        ];
    }
}

// app/Http/Requests/UpdateCommandeStatutRequest.php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateCommandeStatutRequest extends FormRequest
{
    public function authorize(): bool
    {
        $commande = $this->route('commande');

        // Seules les casses qui ont des pièces dans cette commande peuvent modifier le statut
        return auth()->user()->isCasse() &&
            $commande->items()->whereHas('piece.vehicle', function($query) {
                $query->where('casse_id', auth()->id());
            })->exists();
    }

    public function rules(): array
    {
        return [
            'statut' => ['required', Rule::in(['en_attente', 'confirmee', 'en_preparation', 'expedie', 'livree', 'annulee'])],
            'commentaire' => ['nullable', 'string', 'max:500'],
        ];
    }

    public function messages(): array
    {
        return [
            'statut.required' => 'Le statut est obligatoire.',
            'statut.in' => 'Le statut sélectionné est invalide.',
        ];
    }
}

// app/Http/Requests/AddToPanierRequest.php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Piece;

class AddToPanierRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->user()->isClient();
    }

    public function rules(): array
    {
        $piece = $this->route('piece');

        return [
            'quantite' => ['required', 'integer', 'min:1', 'max:' . $piece->quantite],
        ];
    }

    public function messages(): array
    {
        $piece = $this->route('piece');

        return [
            'quantite.required' => 'La quantité est obligatoire.',
            'quantite.min' => 'La quantité doit être d\'au moins 1.',
            'quantite.max' => "Stock insuffisant. Maximum disponible: {$piece->quantite}",
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $piece = $this->route('piece');

            if (!$piece->disponible) {
                $validator->errors()->add('piece', 'Cette pièce n\'est plus disponible.');
            }
        });
    }
}

// app/Http/Requests/UpdatePanierItemRequest.php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePanierItemRequest extends FormRequest
{
    public function authorize(): bool
    {
        $item = $this->route('item');
        return auth()->id() === $item->panier->user_id;
    }

    public function rules(): array
    {
        $item = $this->route('item');

        return [
            'quantite' => ['required', 'integer', 'min:1', 'max:' . $item->piece->quantite],
        ];
    }

    public function messages(): array
    {
        $item = $this->route('item');

        return [
            'quantite.max' => "Stock insuffisant. Maximum disponible: {$item->piece->quantite}",
        ];
    }
}

// app/Http/Requests/StoreDemandeEpaveRequest.php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreDemandeEpaveRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->user()->isClient();
    }

    public function rules(): array
    {
        return [
            'marque' => ['required', 'string', 'max:255'],
            'modele' => ['required', 'string', 'max:255'],
            'annee' => ['required', 'integer', 'min:1900', 'max:' . date('Y')],
            'numero_chassis' => ['required', 'string', 'max:17'],
            'numero_plaque' => ['required', 'string', 'max:20'],
            'couleur' => ['required', 'string', 'max:100'],
            'carburant' => ['required', Rule::in(['essence', 'diesel', 'hybride', 'electrique'])],
            'kilometrage' => ['required', 'integer', 'min:0'],
            'etat' => ['required', Rule::in(['bon', 'moyen', 'mauvais', 'epave'])],
            'prix_souhaite' => ['nullable', 'numeric', 'min:0'],
            'description' => ['required', 'string', 'max:1000'],
            'photos' => ['required', 'array', 'min:3', 'max:8'],
            'photos.*' => ['image', 'mimes:jpeg,png,jpg', 'max:2048'],
            'telephone_contact' => ['required', 'string', 'max:20'],
            'adresse' => ['required', 'string', 'max:500'],
        ];
    }

    public function messages(): array
    {
        return [
            'photos.required' => 'Vous devez ajouter au moins une photo.',
            'photos.min' => 'Vous devez ajouter au moins 3 photos.',
            'photos.max' => 'Vous ne pouvez pas ajouter plus de 8 photos.',
            'photos.*.image' => 'Chaque fichier doit être une image.',
            'photos.*.max' => 'Chaque image ne peut pas dépasser 2MB.',
            'description.required' => 'Une description détaillée est obligatoire.',
            'telephone_contact.required' => 'Votre numéro de téléphone est obligatoire.',
            'adresse.required' => 'L\'adresse où se trouve le véhicule est obligatoire.',
        ];
    }
}

// app/Http/Requests/UpdateDemandeEpaveRequest.php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateDemandeEpaveRequest extends FormRequest
{
    public function authorize(): bool
    {
        $demande = $this->route('demandeEpave');
        return auth()->id() === $demande->user_id && $demande->statut === 'en_attente';
    }

    public function rules(): array
    {
        return [
            'marque' => ['required', 'string', 'max:255'],
            'modele' => ['required', 'string', 'max:255'],
            'annee' => ['required', 'integer', 'min:1900', 'max:' . date('Y')],
            'couleur' => ['required', 'string', 'max:100'],
            'carburant' => ['required', Rule::in(['essence', 'diesel', 'hybride', 'electrique'])],
            'kilometrage' => ['required', 'integer', 'min:0'],
            'etat' => ['required', Rule::in(['bon', 'moyen', 'mauvais', 'epave'])],
            'prix_souhaite' => ['nullable', 'numeric', 'min:0'],
            'description' => ['required', 'string', 'max:1000'],
            'photos' => ['nullable', 'array', 'max:8'],
            'photos.*' => ['image', 'mimes:jpeg,png,jpg', 'max:2048'],
            'telephone_contact' => ['required', 'string', 'max:20'],
            'adresse' => ['required', 'string', 'max:500'],
        ];
    }
}

// app/Http/Requests/StoreOffreEpaveRequest.php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreOffreEpaveRequest extends FormRequest
{
    public function authorize(): bool
    {
        $demande = $this->route('demandeEpave');

        return auth()->user()->isCasse() &&
            $demande->statut === 'en_attente' &&
            !$demande->offres()->where('casse_id', auth()->id())->exists();
    }

    public function rules(): array
    {
        return [
            'prix_offert' => ['required', 'numeric', 'min:1'],
            'message' => ['nullable', 'string', 'max:500'],
        ];
    }

    public function messages(): array
    {
        return [
            'prix_offert.required' => 'Le prix offert est obligatoire.',
            'prix_offert.min' => 'Le prix offert doit être d\'au moins 1FCFA.',
        ];
    }
}

// app/Http/Requests/UpdateProfileRequest.php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateProfileRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $user = auth()->user();

        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', Rule::unique('users')->ignore($user->id)],
            'telephone' => ['nullable', 'string', 'max:20'],
            'adresse' => ['nullable', 'string', 'max:500'],
            'ville' => ['nullable', 'string', 'max:100'],
            'code_postal' => ['nullable', 'string', 'max:10'],
        ];

        if ($user->isCasse()) {
            $rules = array_merge($rules, [
                'nom_entreprise' => ['required', 'string', 'max:255'],
                'siret' => ['nullable', 'string', 'max:20'],
                'description' => ['nullable', 'string', 'max:1000'],
                'logo' => ['nullable', 'image', 'mimes:jpeg,png,jpg', 'max:2048'],
                'horaires' => ['nullable', 'array'],
                'horaires.lundi' => ['nullable', 'string', 'max:50'],
                'horaires.mardi' => ['nullable', 'string', 'max:50'],
                'horaires.mercredi' => ['nullable', 'string', 'max:50'],
                'horaires.jeudi' => ['nullable', 'string', 'max:50'],
                'horaires.vendredi' => ['nullable', 'string', 'max:50'],
                'horaires.samedi' => ['nullable', 'string', 'max:50'],
                'horaires.dimanche' => ['nullable', 'string', 'max:50'],
                'latitude' => ['nullable', 'numeric', 'between:-90,90'],
                'longitude' => ['nullable', 'numeric', 'between:-180,180'],
            ]);
        }

        return $rules;
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Le nom est obligatoire.',
            'email.required' => 'L\'email est obligatoire.',
            'email.unique' => 'Cet email est déjà utilisé.',
            'nom_entreprise.required' => 'Le nom de l\'entreprise est obligatoire pour les casses.',
            'logo.image' => 'Le logo doit être une image.',
            'logo.max' => 'Le logo ne peut pas dépasser 2MB.',
            'latitude.between' => 'La latitude doit être comprise entre -90 et 90.',
            'longitude.between' => 'La longitude doit être comprise entre -180 et 180.',
            'radius.min' => 'Le rayon de recherche doit être d\'au moins 1 km.',
            'radius.max' => 'Le rayon de recherche ne peut pas dépasser 200 km.',
            'per_page.min' => 'Minimum 6 résultats par page.',
            'per_page.max' => 'Maximum 50 résultats par page.',
        ];
    }

    protected function prepareForValidation(): void
    {
        // Valeurs par défaut
        $this->merge([
            'type' => $this->type ?? 'all',
            'radius' => $this->radius ?? 50,
            'per_page' => $this->per_page ?? 12,
            'sort' => $this->sort ?? 'date_desc'
        ]);

        // Validation du prix
        if ($this->prix_min && $this->prix_max && $this->prix_min > $this->prix_max) {
            $this->merge([
                'prix_min' => $this->prix_max,
                'prix_max' => $this->prix_min
            ]);
        }
    }
}

// app/Http/Requests/FilterVehiclesRequest.php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class FilterVehiclesRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'search' => ['nullable', 'string', 'max:255'],
            'marque' => ['nullable', 'string', 'max:100'],
            'modele' => ['nullable', 'string', 'max:100'],
            'annee_min' => ['nullable', 'integer', 'min:1900'],
            'annee_max' => ['nullable', 'integer', 'max:' . (date('Y') + 1)],
            'prix_min' => ['nullable', 'numeric', 'min:0'],
            'prix_max' => ['nullable', 'numeric', 'min:0'],
            'etat' => ['nullable', Rule::in(['bon', 'moyen', 'mauvais', 'epave'])],
            'carburant' => ['nullable', Rule::in(['essence', 'diesel', 'hybride', 'electrique'])],
            'transmission' => ['nullable', Rule::in(['manuelle', 'automatique'])],
            'vendu' => ['nullable', 'boolean'],
            'latitude' => ['nullable', 'numeric', 'between:-90,90'],
            'longitude' => ['nullable', 'numeric', 'between:-180,180'],
            'radius' => ['nullable', 'integer', 'min:1', 'max:200'],
            'sort' => ['nullable', Rule::in(['price_asc', 'price_desc', 'date_asc', 'date_desc', 'year_asc', 'year_desc'])],
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'sort' => $this->sort ?? 'date_desc',
            'radius' => $this->radius ?? 50,
            'vendu' => $this->vendu ?? false
        ]);
    }
}

// app/Http/Requests/FilterPiecesRequest.php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class FilterPiecesRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'search' => ['nullable', 'string', 'max:255'],
            'marque' => ['nullable', 'string', 'max:100'],
            'modele' => ['nullable', 'string', 'max:100'],
            'annee' => ['nullable', 'integer', 'min:1900', 'max:' . (date('Y') + 1)],
            'prix_min' => ['nullable', 'numeric', 'min:0'],
            'prix_max' => ['nullable', 'numeric', 'min:0'],
            'etat' => ['nullable', Rule::in(['neuf', 'tres_bon', 'bon', 'moyen', 'usage'])],
            'disponible' => ['nullable', 'boolean'],
            'casse_id' => ['nullable', 'exists:users,id'],
            'latitude' => ['nullable', 'numeric', 'between:-90,90'],
            'longitude' => ['nullable', 'numeric', 'between:-180,180'],
            'radius' => ['nullable', 'integer', 'min:1', 'max:200'],
            'sort' => ['nullable', Rule::in(['price_asc', 'price_desc', 'date_asc', 'date_desc', 'name_asc', 'name_desc'])],
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'sort' => $this->sort ?? 'date_desc',
            'radius' => $this->radius ?? 50,
            'disponible' => $this->disponible ?? true
        ]);
    }
}

// app/Http/Requests/FilterCommandesRequest.php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class FilterCommandesRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'statut' => ['nullable', Rule::in(['en_attente', 'confirmee', 'en_preparation', 'expedie', 'livree', 'annulee'])],
            'statut_paiement' => ['nullable', Rule::in(['en_attente', 'paye', 'rembourse'])],
            'mode_paiement' => ['nullable', Rule::in(['carte_bancaire', 'paypal', 'virement', 'especes'])],
            'date_debut' => ['nullable', 'date'],
            'date_fin' => ['nullable', 'date', 'after_or_equal:date_debut'],
            'montant_min' => ['nullable', 'numeric', 'min:0'],
            'montant_max' => ['nullable', 'numeric', 'min:0'],
            'numero_commande' => ['nullable', 'string', 'max:50'],
            'sort' => ['nullable', Rule::in(['date_asc', 'date_desc', 'total_asc', 'total_desc'])],
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'sort' => $this->sort ?? 'date_desc'
        ]);
    }
}

// app/Http/Requests/FilterDemandesEpavesRequest.php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class FilterDemandesEpavesRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'search' => ['nullable', 'string', 'max:255'],
            'marque' => ['nullable', 'string', 'max:100'],
            'modele' => ['nullable', 'string', 'max:100'],
            'etat' => ['nullable', Rule::in(['bon', 'moyen', 'mauvais', 'epave'])],
            'statut' => ['nullable', Rule::in(['en_attente', 'interesse', 'accepte', 'refuse'])],
            'prix_min' => ['nullable', 'numeric', 'min:0'],
            'prix_max' => ['nullable', 'numeric', 'min:0'],
            'annee_min' => ['nullable', 'integer', 'min:1900'],
            'annee_max' => ['nullable', 'integer', 'max:' . date('Y')],
            'carburant' => ['nullable', Rule::in(['essence', 'diesel', 'hybride', 'electrique'])],
            'tri' => ['nullable', Rule::in(['recent', 'ancien', 'prix_asc', 'prix_desc'])],
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'tri' => $this->tri ?? 'recent',
            'statut' => $this->statut ?? 'en_attente'
        ]);
    }
}

// app/Http/Requests/StoreMessageRequest.php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreMessageRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'destinataire_id' => ['required', 'exists:users,id', 'different:' . auth()->id()],
            'sujet' => ['required', 'string', 'max:255'],
            'message' => ['required', 'string', 'max:2000'],
        ];
    }

    public function messages(): array
    {
        return [
            'destinataire_id.required' => 'Vous devez sélectionner un destinataire.',
            'destinataire_id.exists' => 'Le destinataire sélectionné n\'existe pas.',
            'destinataire_id.different' => 'Vous ne pouvez pas vous envoyer un message à vous-même.',
            'sujet.required' => 'Le sujet est obligatoire.',
            'message.required' => 'Le message est obligatoire.',
            'message.max' => 'Le message ne peut pas dépasser 2000 caractères.',
        ];
    }
}

// app/Http/Requests/StoreAvisRequest.php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreAvisRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->user()->isClient();
    }

    public function rules(): array
    {
        return [
            'casse_id' => ['required', 'exists:users,id'],
            'commande_id' => ['nullable', 'exists:commandes,id'],
            'note' => ['required', 'integer', 'min:1', 'max:5'],
            'commentaire' => ['nullable', 'string', 'max:500'],
        ];
    }

    public function messages(): array
    {
        return [
            'casse_id.required' => 'Vous devez sélectionner une casse.',
            'casse_id.exists' => 'La casse sélectionnée n\'existe pas.',
            'note.required' => 'La note est obligatoire.',
            'note.min' => 'La note minimale est 1.',
            'note.max' => 'La note maximale est 5.',
            'commentaire.max' => 'Le commentaire ne peut pas dépasser 500 caractères.',
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            // Vérifier que l'utilisateur n'a pas déjà laissé un avis pour cette commande
            if ($this->commande_id) {
                $existingAvis = auth()->user()->avisDonnes()
                    ->where('commande_id', $this->commande_id)
                    ->exists();

                if ($existingAvis) {
                    $validator->errors()->add('commande_id', 'Vous avez déjà laissé un avis pour cette commande.');
                }
            }

            // Vérifier que la casse est bien une casse
            $casse = \App\Models\User::find($this->casse_id);
            if ($casse && !$casse->isCasse()) {
                $validator->errors()->add('casse_id', 'L\'utilisateur sélectionné n\'est pas une casse automobile.');
            }
        });
    }
}

// app/Http/Requests/StorePromotionRequest.php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StorePromotionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->user()->isCasse() || auth()->user()->isAdmin();
    }

    public function rules(): array
    {
        return [
            'code' => ['required', 'string', 'max:20', 'unique:promotions,code'],
            'nom' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string', 'max:500'],
            'type' => ['required', Rule::in(['pourcentage', 'montant_fixe'])],
            'valeur' => ['required', 'numeric', 'min:0.01'],
            'montant_minimum' => ['nullable', 'numeric', 'min:0'],
            'date_debut' => ['required', 'date', 'after_or_equal:today'],
            'date_fin' => ['required', 'date', 'after:date_debut'],
            'limite_utilisation' => ['required', 'integer', 'min:1'],
        ];
    }

    public function messages(): array
    {
        return [
            'code.required' => 'Le code promotionnel est obligatoire.',
            'code.unique' => 'Ce code promotionnel existe déjà.',
            'nom.required' => 'Le nom de la promotion est obligatoire.',
            'description.required' => 'La description est obligatoire.',
            'type.required' => 'Le type de promotion est obligatoire.',
            'type.in' => 'Le type de promotion sélectionné est invalide.',
            'valeur.required' => 'La valeur de la promotion est obligatoire.',
            'valeur.min' => 'La valeur doit être supérieure à 0.',
            'date_debut.after_or_equal' => 'La date de début ne peut pas être dans le passé.',
            'date_fin.after' => 'La date de fin doit être après la date de début.',
            'limite_utilisation.min' => 'La limite d\'utilisation doit être d\'au moins 1.',
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            if ($this->type === 'pourcentage' && $this->valeur > 100) {
                $validator->errors()->add('valeur', 'Le pourcentage ne peut pas dépasser 100%.');
            }
        });
    }
}

// app/Http/Requests/ApplyPromotionRequest.php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Promotion;

class ApplyPromotionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->user()->isClient();
    }

    public function rules(): array
    {
        return [
            'code' => ['required', 'string', 'exists:promotions,code'],
        ];
    }

    public function messages(): array
    {
        return [
            'code.required' => 'Le code promotionnel est obligatoire.',
            'code.exists' => 'Ce code promotionnel n\'existe pas.',
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $promotion = Promotion::where('code', $this->code)->first();

            if ($promotion && !$promotion->isValide()) {
                if ($promotion->date_debut > now()) {
                    $validator->errors()->add('code', 'Ce code promotionnel n\'est pas encore actif.');
                } elseif ($promotion->date_fin < now()) {
                    $validator->errors()->add('code', 'Ce code promotionnel a expiré.');
                } elseif ($promotion->utilise >= $promotion->limite_utilisation) {
                    $validator->errors()->add('code', 'Ce code promotionnel a atteint sa limite d\'utilisation.');
                } elseif (!$promotion->active) {
                    $validator->errors()->add('code', 'Ce code promotionnel n\'est plus actif.');
                }
            }
        });
    }
}

// app/Http/Requests/UpdateStockRequest.php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateStockRequest extends FormRequest
{
    public function authorize(): bool
    {
        $piece = $this->route('piece');
        return auth()->user()->isCasse() && auth()->id() === $piece->vehicle->casse_id;
    }

    public function rules(): array
    {
        return [
            'quantite' => ['required', 'integer', 'min:0'],
            'raison' => ['nullable', 'string', 'max:255'],
        ];
    }

    public function messages(): array
    {
        return [
            'quantite.required' => 'La quantité est obligatoire.',
            'quantite.min' => 'La quantité ne peut pas être négative.',
        ];
    }
}

// app/Http/Requests/ContactRequest.php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ContactRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nom' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'sujet' => ['required', 'string', 'max:255'],
            'message' => ['required', 'string', 'max:2000'],
            'telephone' => ['nullable', 'string', 'max:20'],
        ];
    }

    public function messages(): array
    {
        return [
            'nom.required' => 'Votre nom est obligatoire.',
            'email.required' => 'Votre email est obligatoire.',
            'email.email' => 'Veuillez entrer une adresse email valide.',
            'sujet.required' => 'Le sujet est obligatoire.',
            'message.required' => 'Le message est obligatoire.',
            'message.max' => 'Le message ne peut pas dépasser 2000 caractères.',
        ];
    }
}

// app/Http/Requests/ReportContentRequest.php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ReportContentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'type' => ['required', Rule::in(['vehicle', 'piece', 'demande_epave', 'user', 'avis'])],
            'item_id' => ['required', 'integer'],
            'raison' => ['required', Rule::in(['contenu_inapproprie', 'fausse_information', 'prix_suspect', 'spam', 'autre'])],
            'description' => ['required', 'string', 'max:500'],
        ];
    }

    public function messages(): array
    {
        return [
            'type.required' => 'Le type de contenu est obligatoire.',
            'type.in' => 'Le type de contenu sélectionné est invalide.',
            'item_id.required' => 'L\'identifiant de l\'élément est obligatoire.',
            'raison.required' => 'La raison du signalement est obligatoire.',
            'raison.in' => 'La raison sélectionnée est invalide.',
            'description.required' => 'Une description est obligatoire.',
            'description.max' => 'La description ne peut pas dépasser 500 caractères.',
        ];
    }
}

// app/Http/Requests/BulkActionRequest.php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class BulkActionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->user()->isCasse() || auth()->user()->isAdmin();
    }

    public function rules(): array
    {
        return [
            'action' => ['required', Rule::in(['delete', 'activate', 'deactivate', 'update_stock', 'update_price'])],
            'ids' => ['required', 'array', 'min:1'],
            'ids.*' => ['integer', 'min:1'],
            'value' => ['nullable', 'numeric'], // Pour les actions update_stock, update_price
            'percentage' => ['nullable', 'numeric', 'min:-100'], // Pour update_price en pourcentage
        ];
    }

    public function messages(): array
    {
        return [
            'action.required' => 'L\'action est obligatoire.',
            'action.in' => 'L\'action sélectionnée est invalide.',
            'ids.required' => 'Vous devez sélectionner au moins un élément.',
            'ids.min' => 'Vous devez sélectionner au moins un élément.',
            'ids.*.integer' => 'Les identifiants doivent être des nombres entiers.',
            'percentage.min' => 'Le pourcentage ne peut pas être inférieur à -100%.',
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $action = $this->action;

            if (in_array($action, ['update_stock', 'update_price']) && !$this->has('value') && !$this->has('percentage')) {
                $validator->errors()->add('value', 'Une valeur est requise pour cette action.');
            }
        });
    }
}

// app/Http/Requests/ExportRequest.php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ExportRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->user()->isCasse() || auth()->user()->isAdmin();
    }

    public function rules(): array
    {
        return [
            'type' => ['required', Rule::in(['vehicles', 'pieces', 'commandes', 'clients'])],
            'format' => ['required', Rule::in(['csv', 'excel', 'pdf'])],
            'date_debut' => ['nullable', 'date'],
            'date_fin' => ['nullable', 'date', 'after_or_equal:date_debut'],
            'filters' => ['nullable', 'array'],
        ];
    }

    public function messages(): array
    {
        return [
            'type.required' => 'Le type d\'export est obligatoire.',
            'type.in' => 'Le type d\'export sélectionné est invalide.',
            'format.required' => 'Le format d\'export est obligatoire.',
            'format.in' => 'Le format sélectionné est invalide.',
            'date_fin.after_or_equal' => 'La date de fin doit être après la date de début.',
        ];
    }
}

/*
==============================================
UTILISATION DES REQUEST DANS LES CONTRÔLEURS
==============================================

Les Request créés s'utilisent simplement en remplaçant "Request $request" par le Request spécifique :

// Avant
public function store(Request $request)
{
    $validated = $request->validate([...]);
}

// Après
public function store(StoreVehicleRequest $request)
{
    $validated = $request->validated();
}

==============================================
AVANTAGES DES FORM REQUESTS
==============================================

1. **Validation centralisée** - Toutes les règles au même endroit
2. **Autorisation intégrée** - Vérification des permissions
3. **Messages personnalisés** - Erreurs en français
4. **Code plus propre** - Contrôleurs plus lisibles
5. **Réutilisabilité** - Mêmes règles partout
6. **Testabilité** - Tests unitaires faciles

==============================================
COMMANDES ARTISAN UTILES
==============================================

# Créer un nouveau FormRequest
php artisan make:request StoreProductRequest

# Lister toutes les routes avec validation
php artisan route:list --columns=method,uri,name,action

# Tester les validations
php artisan tinker
>>> app(\App\Http\Requests\StoreVehicleRequest::class)->rules()

==============================================
*/ longitude doit être comprise entre -180 et 180.',
        ];
    }
}

// app/Http/Requests/UpdatePasswordRequest.php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Hash;

class UpdatePasswordRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'current_password' => ['required', 'string'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ];
    }

    public function messages(): array
    {
        return [
            'current_password.required' => 'Le mot de passe actuel est obligatoire.',
            'password.required' => 'Le nouveau mot de passe est obligatoire.',
            'password.min' => 'Le mot de passe doit contenir au moins 8 caractères.',
            'password.confirmed' => 'La confirmation du mot de passe ne correspond pas.',
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            if (!Hash::check($this->current_password, auth()->user()->password)) {
                $validator->errors()->add('current_password', 'Le mot de passe actuel est incorrect.');
            }
        });
    }
}

// app/Http/Requests/SearchRequest.php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SearchRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'q' => ['nullable', 'string', 'max:255'],
            'type' => ['nullable', Rule::in(['all', 'pieces', 'vehicles', 'casses'])],
            'marque' => ['nullable', 'string', 'max:100'],
            'modele' => ['nullable', 'string', 'max:100'],
            'annee' => ['nullable', 'integer', 'min:1900', 'max:' . (date('Y') + 1)],
            'prix_min' => ['nullable', 'numeric', 'min:0'],
            'prix_max' => ['nullable', 'numeric', 'min:0'],
            'etat' => ['nullable', Rule::in(['bon', 'moyen', 'mauvais', 'epave'])],
            'latitude' => ['nullable', 'numeric', 'between:-90,90'],
            'longitude' => ['nullable', 'numeric', 'between:-180,180'],
            'radius' => ['nullable', 'integer', 'min:1', 'max:200'],
            'sort' => ['nullable', Rule::in(['price_asc', 'price_desc', 'date_asc', 'date_desc', 'distance'])],
            'per_page' => ['nullable', 'integer', 'min:6', 'max:50'],
        ];
    }

    public function messages(): array
    {
        return [
            'q.max' => 'La recherche ne peut pas dépasser 255 caractères.',
            'type.in' => 'Le type de recherche sélectionné est invalide.',
            'prix_min.min' => 'Le prix minimum doit être positif.',
            'prix_max.min' => 'Le prix maximum doit être positif.',
            'latitude.between' => 'La latitude doit être comprise entre -90 et 90.',
            'longitude.between' => 'La
