<?php
// database/migrations/xxxx_update_pieces_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pieces', function (Blueprint $table) {
            // Supprimer les anciennes colonnes

            // Ajouter les nouvelles relations
            $table->foreignId('marque_id')->nullable()->after('id')->constrained()->nullOnDelete();
            $table->foreignId('modele_id')->nullable()->after('marque_id')->constrained()->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('pieces', function (Blueprint $table) {
            $table->dropForeign(['marque_id']);
            $table->dropForeign(['modele_id']);
            $table->dropColumn(['marque_id', 'modele_id']);

            $table->string('marque_piece')->after('id');
            $table->string('modele_piece')->after('marque_piece');
            $table->string('ville')->after('compatible_avec');
        });
    }
};
