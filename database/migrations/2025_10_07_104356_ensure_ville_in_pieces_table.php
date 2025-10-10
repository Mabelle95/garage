<?php
// database/migrations/2024_01_10_000001_ensure_ville_in_pieces_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pieces', function (Blueprint $table) {
            // Vérifier si la colonne existe déjà
            if (!Schema::hasColumn('pieces', 'ville')) {
                $table->string('ville')->nullable()->after('compatible_avec')->index();
            }
        });

        // Synchroniser les villes existantes
        DB::statement('
            UPDATE pieces
            SET ville = (SELECT ville FROM users WHERE users.id = pieces.user_id)
            WHERE ville IS NULL
        ');
    }

    public function down(): void
    {
        Schema::table('pieces', function (Blueprint $table) {
            $table->dropColumn('ville');
        });
    }
};
