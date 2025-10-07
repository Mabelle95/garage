<?php
// database/migrations/2024_01_01_000000_create_complete_casse_system.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Table des véhicules
        Schema::create('vehicles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('casse_id')->constrained('users')->onDelete('cascade');
            $table->string('marque',100)->nullable();
            $table->string('modele',100)->nullable();
            $table->year('annee');
            $table->string('numero_chassis')->unique();
            $table->string('numero_plaque')->unique();
            $table->string('couleur');
            $table->enum('carburant', ['essence', 'diesel', 'hybride', 'electrique']);
            $table->enum('transmission', ['manuelle', 'automatique']);
            $table->integer('kilometrage');
            $table->enum('etat', ['bon', 'moyen', 'mauvais', 'epave']);
            $table->date('date_arrivee');
            $table->decimal('prix_epave', 10, 2);
            $table->boolean('vendu')->default(false);
            $table->string('photo_principale')->nullable();
            $table->json('photos_additionnelles')->nullable();
            $table->text('description')->nullable();
            $table->json('data_scan')->nullable();
            $table->timestamps();

            $table->index(['marque', 'modele', 'annee']);
            $table->index('vendu');
        });

        // Table des pièces détachées
        Schema::create('pieces', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vehicle_id')->constrained()->onDelete('cascade');
            $table->string('nom');
            $table->text('description');
            $table->decimal('prix', 10, 2);
            $table->integer('quantite');
            $table->enum('etat', ['neuf', 'tres_bon', 'bon', 'moyen', 'usage']);
            $table->json('photos')->nullable();
            $table->string('reference_constructeur')->nullable();
            $table->json('compatible_avec')->nullable();
            $table->boolean('disponible')->default(true);
            $table->timestamps();

            $table->index(['nom', 'disponible']);
            $table->index('quantite');
        });

        // Table des paniers
        Schema::create('paniers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->timestamps();
        });

        // Table des items du panier
        Schema::create('panier_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('panier_id')->constrained()->onDelete('cascade');
            $table->foreignId('piece_id')->constrained()->onDelete('cascade');
            $table->integer('quantite');
            $table->timestamps();

            $table->unique(['panier_id', 'piece_id']);
        });

        // Table des commandes
        Schema::create('commandes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('numero_commande')->unique();
            $table->enum('statut', ['en_attente', 'confirmee', 'en_preparation', 'expedie', 'livree', 'annulee']);
            $table->decimal('total', 10, 2);
            $table->text('adresse_livraison');
            $table->string('telephone_livraison');
            $table->enum('mode_paiement', ['carte_bancaire', 'paypal', 'virement', 'especes']);
            $table->enum('statut_paiement', ['en_attente', 'paye', 'rembourse']);
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index('statut');
            $table->index('statut_paiement');
        });

        // Table des items de commande
        Schema::create('commande_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('commande_id')->constrained()->onDelete('cascade');
            $table->foreignId('piece_id')->constrained()->onDelete('cascade');
            $table->integer('quantite');
            $table->decimal('prix_unitaire', 10, 2);
            $table->timestamps();
        });

        // Table des notifications
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('commande_id')->nullable()->constrained()->onDelete('cascade');
            $table->enum('type', ['commande', 'paiement', 'livraison', 'stock', 'general']);
            $table->string('titre');
            $table->text('message');
            $table->boolean('lu')->default(false);
            $table->json('data')->nullable();
            $table->timestamps();

            $table->index(['user_id', 'lu']);
            $table->index('type');
        });

        // Table des demandes de vente d'épaves
        Schema::create('demandes_epaves', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('marque');
            $table->string('modele');
            $table->year('annee');
            $table->string('numero_chassis');
            $table->string('numero_plaque');
            $table->string('couleur');
            $table->enum('carburant', ['essence', 'diesel', 'hybride', 'electrique']);
            $table->integer('kilometrage');
            $table->enum('etat', ['bon', 'moyen', 'mauvais', 'epave']);
            $table->decimal('prix_souhaite', 10, 2)->nullable();
            $table->text('description');
            $table->json('photos')->nullable();
            $table->string('telephone_contact');
            $table->text('adresse');
            $table->enum('statut', ['en_attente', 'interesse', 'accepte', 'refuse']);
            $table->text('commentaire_casse')->nullable();
            $table->timestamps();

            $table->index('statut');
        });

        // Table des offres sur épaves
        Schema::create('offres_epaves', function (Blueprint $table) {
            $table->id();
            $table->foreignId('demande_epave_id')
      ->constrained('demandes_epaves')
      ->onDelete('cascade');
            $table->foreignId('casse_id')->constrained('users')->onDelete('cascade');
            $table->decimal('prix_offert', 10, 2);
            $table->text('message')->nullable();
            $table->enum('statut', ['en_attente', 'accepte', 'refuse']);
            $table->timestamps();
        });

        // Ajouter des colonnes au tableau users pour les profils
        Schema::table('users', function (Blueprint $table) {


            // Champs spécifiques aux casses automobiles
            $table->string('nom_entreprise')->nullable();
            $table->string('logo')->nullable();
            $table->json('horaires')->nullable();
            $table->boolean('actif')->default(true);
            $table->index(['latitude', 'longitude']);
            $table->index('ville');
        });

        // Table des évaluations et avis
        Schema::create('avis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('casse_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('commande_id')->nullable()->constrained()->onDelete('cascade');
            $table->integer('note')->min(1)->max(5);
            $table->text('commentaire')->nullable();
            $table->timestamps();

            $table->unique(['user_id', 'commande_id']);
            $table->index('note');
        });

        // Table des favoris
        Schema::create('favoris', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->morphs('favori'); // Pour pouvoir ajouter véhicules, pièces, casses en favoris
            $table->timestamps();

            $table->unique(['user_id', 'favori_id', 'favori_type']);
        });

        // Table des messages entre utilisateurs
        Schema::create('messages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('expediteur_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('destinataire_id')->constrained('users')->onDelete('cascade');
            $table->string('sujet');
            $table->text('message');
            $table->boolean('lu')->default(false);
            $table->timestamps();

            $table->index(['destinataire_id', 'lu']);
        });

        // Table des historiques de prix
        Schema::create('historique_prix', function (Blueprint $table) {
            $table->id();
            $table->foreignId('piece_id')->constrained()->onDelete('cascade');
            $table->decimal('ancien_prix', 10, 2);
            $table->decimal('nouveau_prix', 10, 2);
            $table->timestamp('date_changement');
            $table->timestamps();
        });

        // Table de log des activités
        Schema::create('activites', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('type');
            $table->string('description');
            $table->json('donnees')->nullable();
            $table->timestamps();

            $table->index(['user_id', 'type']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('activites');
        Schema::dropIfExists('historique_prix');
        Schema::dropIfExists('messages');
        Schema::dropIfExists('favoris');
        Schema::dropIfExists('avis');
        Schema::dropIfExists('offres_epaves');
        Schema::dropIfExists('demandes_epaves');
        Schema::dropIfExists('notifications');
        Schema::dropIfExists('commande_items');
        Schema::dropIfExists('commandes');
        Schema::dropIfExists('panier_items');
        Schema::dropIfExists('paniers');
        Schema::dropIfExists('pieces');
        Schema::dropIfExists('vehicles');

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'telephone', 'adresse', 'ville', 'code_postal',
                'latitude', 'longitude', 'nom_entreprise', 'siret',
                'description', 'logo', 'horaires', 'actif'
            ]);
        });
    }
};
