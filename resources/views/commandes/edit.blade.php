<form action="{{ route('gestion.commandes.update-statut', $commande) }}" method="POST">
    @csrf
    @method('PUT')

    <!-- Numéro de commande -->
    <div class="mb-3">
        <label for="numero_commande" class="form-label">Numéro de commande</label>
        <input type="text" id="numero_commande" name="numero_commande"
               class="form-control" value="{{ $commande->numero_commande }}" readonly>
    </div>

    <!-- Statut (modifiable) -->
    <div class="mb-3">
        <label for="statut" class="form-label">Statut</label>
        <select id="statut" name="statut" class="form-select">
            <option value="en_attente" {{ $commande->statut == 'en_attente' ? 'selected' : '' }}>En attente</option>
            <option value="en_cours" {{ $commande->statut == 'en_cours' ? 'selected' : '' }}>En cours</option>
            <option value="livree" {{ $commande->statut == 'livree' ? 'selected' : '' }}>Livrée</option>
            <option value="annulee" {{ $commande->statut == 'annulee' ? 'selected' : '' }}>Annulée</option>
        </select>
    </div>

    <!-- Total -->
    <div class="mb-3">
        <label for="total" class="form-label">Total</label>
        <input type="text" id="total" name="total"
               class="form-control" value="{{ $commande->total }}" readonly>
    </div>

    <!-- Adresse de liv
