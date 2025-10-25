<?php

// app/Services/NotificationService.php
namespace App\Services;

use App\Models\Notification;
use App\Models\User;
use App\Models\Commande;
use App\Models\DemandeEpave;
use App\Models\OffreEpave;

class NotificationService
{
    public function nouvelleCommande(User $casse, Commande $commande)
    {
        return Notification::create([
            'user_id' => $casse->id,
            'commande_id' => $commande->id,
            'type' => 'commande',
            'titre' => 'Nouvelle commande reçue',
            'message' => "Vous avez reçu une nouvelle commande {$commande->numero_commande} de {$commande->user->name}.",
            'data' => [
                'commande_id' => $commande->id,
                'numero_commande' => $commande->numero_commande,
                'total' => $commande->total
            ]
        ]);
    }

    public function commandeCreee(User $client, Commande $commande)
    {
        return Notification::create([
            'user_id' => $client->id,
            'commande_id' => $commande->id,
            'type' => 'commande',
            'titre' => 'Commande créée',
            'message' => "Votre commande {$commande->numero_commande} a été créée avec succès.",
            'data' => [
                'commande_id' => $commande->id,
                'numero_commande' => $commande->numero_commande,
                'total' => $commande->total
            ]
        ]);
    }

    public function statutCommandeChange(User $client, Commande $commande, $ancienStatut, $nouveauStatut)
    {
        $messages = [
            'confirmee' => 'Votre commande a été confirmée et est en cours de traitement.',
            'en_preparation' => 'Votre commande est en cours de préparation.',
            'expedie' => 'Votre commande a été expédiée.',
            'livree' => 'Votre commande a été livrée.',
            'annulee' => 'Votre commande a été annulée.'
        ];

        return Notification::create([
            'user_id' => $client->id,
            'commande_id' => $commande->id,
            'type' => 'commande',
            'titre' => 'Statut de commande mis à jour',
            'message' => $messages[$nouveauStatut] ?? "Le statut de votre commande {$commande->numero_commande} a été mis à jour.",
            'data' => [
                'commande_id' => $commande->id,
                'ancien_statut' => $ancienStatut,
                'nouveau_statut' => $nouveauStatut
            ]
        ]);
    }

    public function commandeAnnulee(User $casse, Commande $commande)
    {
        return Notification::create([
            'user_id' => $casse->id,
            'commande_id' => $commande->id,
            'type' => 'commande',
            'titre' => 'Commande annulée',
            'message' => "La commande {$commande->numero_commande} a été annulée par le client.",
            'data' => [
                'commande_id' => $commande->id,
                'numero_commande' => $commande->numero_commande
            ]
        ]);
    }

    public function stockFaible(User $casse, $piece)
    {
        return Notification::create([
            'user_id' => $casse->id,
            'type' => 'stock',
            'titre' => 'Stock faible',
            'message' => "Le stock de la pièce '{$piece->nom}' est faible (quantité: {$piece->quantite}).",
            'data' => [
                'piece_id' => $piece->id,
                'quantite' => $piece->quantite
            ]
        ]);
    }

    public function nouvelleDemande(User $utilisateur, DemandeEpave $demande)
    {
        return Notification::create([
            'user_id' => $utilisateur->id,
            'type' => 'general',
            'titre' => 'Nouvelle demande d\'épave',
            'message' => "Une nouvelle demande de vente d'épave ({$demande->marque} {$demande->modele}) a été publiée.",
            'data' => [
                'demande_id' => $demande->id,
                'marque' => $demande->marque,
                'modele' => $demande->modele
            ]
        ]);
    }

    public function nouvelleOffre(User $proprietaire, OffreEpave $offre)
    {
        // Charger la relation si elle n'est pas déjà chargée
        $offre->load('demandeEpave', 'user');

        return Notification::create([
            'user_id' => $proprietaire->id,
            'type' => 'general',
            'titre' => 'Nouvelle offre reçue',
            'message' => "Vous avez reçu une offre de " . number_format($offre->prix_offert, 0, ',', ' ') . " FCFA pour votre {$offre->demandeEpave->marque} {$offre->demandeEpave->modele}.",
            'data' => [
                'offre_id' => $offre->id,
                'demande_id' => $offre->demande_epave_id,
                'prix_offert' => $offre->prix_offert,
                'acheteur_nom' => $offre->user->name
            ]
        ]);
    }

    public function offreAcceptee(User $acheteur, OffreEpave $offre)
    {
        // Charger la relation si elle n'est pas déjà chargée
        $offre->load('demandeEpave');

        return Notification::create([
            'user_id' => $acheteur->id,
            'type' => 'general',
            'titre' => 'Offre acceptée',
            'message' => "Votre offre de " . number_format($offre->prix_offert, 0, ',', ' ') . " FCFA pour le {$offre->demandeEpave->marque} {$offre->demandeEpave->modele} a été acceptée.",
            'data' => [
                'offre_id' => $offre->id,
                'demande_id' => $offre->demande_epave_id,
                'prix_offert' => $offre->prix_offert,
                'telephone_contact' => $offre->demandeEpave->telephone_contact,
                'adresse' => $offre->demandeEpave->adresse
            ]
        ]);
    }

    /**
     * NOUVELLE MÉTHODE : Notifier qu'une offre a été refusée
     */
    public function offreRefusee(User $acheteur, OffreEpave $offre)
    {
        // Charger la relation si elle n'est pas déjà chargée
        $offre->load('demandeEpave');

        return Notification::create([
            'user_id' => $acheteur->id,
            'type' => 'general',
            'titre' => 'Offre refusée',
            'message' => "Votre offre de " . number_format($offre->prix_offert, 0, ',', ' ') . " FCFA pour le {$offre->demandeEpave->marque} {$offre->demandeEpave->modele} a été refusée par le vendeur.",
            'data' => [
                'offre_id' => $offre->id,
                'demande_id' => $offre->demande_epave_id,
                'prix_offert' => $offre->prix_offert,
                'marque' => $offre->demandeEpave->marque,
                'modele' => $offre->demandeEpave->modele,
                'statut' => 'refuse'
            ]
        ]);
    }

    public function marquerCommeLu(User $user, $notificationId = null)
    {
        if ($notificationId) {
            return $user->notifications()->where('id', $notificationId)->update(['lu' => true]);
        }

        return $user->notifications()->update(['lu' => true]);
    }
}
