<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

/**
 * MIGRATION POUR AJOUTER 'discussion' AU TYPE ENUM DE LA TABLE NOTIFICATIONS
 *
 * INSTALLATION:
 * 1. Copiez ce fichier dans : database/migrations/
 * 2. Renommez-le avec la date actuelle : YYYY_MM_DD_HHMMSS_add_discussion_to_notifications_type.php
 *    Exemple: 2025_10_25_140000_add_discussion_to_notifications_type.php
 * 3. Exécutez : php artisan migrate
 */

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Pour MySQL, on doit utiliser une requête SQL brute pour modifier un ENUM
        DB::statement("ALTER TABLE notifications MODIFY COLUMN type ENUM('commande', 'paiement', 'livraison', 'stock', 'general', 'discussion') NOT NULL");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Restaurer l'ancien ENUM (sans 'discussion')
        DB::statement("ALTER TABLE notifications MODIFY COLUMN type ENUM('commande', 'paiement', 'livraison', 'stock', 'general') NOT NULL");
    }
};
