<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Créer la table des noms de pièces
        Schema::create('nom_pieces', function (Blueprint $table) {
            $table->id();
            $table->string('nom')->unique();
            $table->string('categorie')->nullable(); // Ex: Moteur, Carrosserie, Électrique, etc.
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // Ajouter la colonne nom_piece_id à la table pieces
        Schema::table('pieces', function (Blueprint $table) {
            $table->foreignId('nom_piece_id')->nullable()->after('id')->constrained('nom_pieces')->onDelete('set null');
        });

        // Insérer quelques noms de pièces par défaut
        DB::table('nom_pieces')->insert([
            ['nom' => 'Moteur', 'categorie' => 'Mécanique', 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['nom' => 'Boîte de vitesse', 'categorie' => 'Transmission', 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['nom' => 'Radiateur', 'categorie' => 'Refroidissement', 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['nom' => 'Alternateur', 'categorie' => 'Électrique', 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['nom' => 'Démarreur', 'categorie' => 'Électrique', 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['nom' => 'Pare-choc avant', 'categorie' => 'Carrosserie', 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['nom' => 'Pare-choc arrière', 'categorie' => 'Carrosserie', 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['nom' => 'Capot', 'categorie' => 'Carrosserie', 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['nom' => 'Aile avant droite', 'categorie' => 'Carrosserie', 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['nom' => 'Aile avant gauche', 'categorie' => 'Carrosserie', 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['nom' => 'Phare avant droit', 'categorie' => 'Éclairage', 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['nom' => 'Phare avant gauche', 'categorie' => 'Éclairage', 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['nom' => 'Feu arrière droit', 'categorie' => 'Éclairage', 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['nom' => 'Feu arrière gauche', 'categorie' => 'Éclairage', 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['nom' => 'Porte avant droite', 'categorie' => 'Carrosserie', 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['nom' => 'Porte avant gauche', 'categorie' => 'Carrosserie', 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['nom' => 'Porte arrière droite', 'categorie' => 'Carrosserie', 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['nom' => 'Porte arrière gauche', 'categorie' => 'Carrosserie', 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['nom' => 'Rétroviseur droit', 'categorie' => 'Accessoires', 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['nom' => 'Rétroviseur gauche', 'categorie' => 'Accessoires', 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['nom' => 'Pare-brise', 'categorie' => 'Vitrage', 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['nom' => 'Lunette arrière', 'categorie' => 'Vitrage', 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['nom' => 'Amortisseur avant', 'categorie' => 'Suspension', 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['nom' => 'Amortisseur arrière', 'categorie' => 'Suspension', 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['nom' => 'Disque de frein avant', 'categorie' => 'Freinage', 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['nom' => 'Disque de frein arrière', 'categorie' => 'Freinage', 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['nom' => 'Étrier de frein', 'categorie' => 'Freinage', 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['nom' => 'Compresseur climatisation', 'categorie' => 'Climatisation', 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['nom' => 'Turbo', 'categorie' => 'Mécanique', 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['nom' => 'Volant moteur', 'categorie' => 'Mécanique', 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
        ]);
    }

    public function down(): void
    {
        Schema::table('pieces', function (Blueprint $table) {
            $table->dropForeign(['nom_piece_id']);
            $table->dropColumn('nom_piece_id');
        });

        Schema::dropIfExists('nom_pieces');
    }
};
