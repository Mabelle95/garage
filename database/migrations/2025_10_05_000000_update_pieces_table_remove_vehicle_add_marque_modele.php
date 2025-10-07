<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pieces', function (Blueprint $table) {
            // 🔹 Supprimer la clé étrangère si elle existe
            if (Schema::hasColumn('pieces', 'vehicle_id')) {
                $table->dropForeign(['vehicle_id']);
                $table->dropColumn('vehicle_id');
            }

            // 🔹 Ajouter les nouveaux champs
            if (!Schema::hasColumn('pieces', 'marque_piece')) {
                $table->string('marque_piece')->nullable()->after('id');
            }

            if (!Schema::hasColumn('pieces', 'modele_piece')) {
                $table->string('modele_piece')->nullable()->after('marque_piece');
            }
        });
    }

    public function down(): void
    {
        Schema::table('pieces', function (Blueprint $table) {
            // 🔹 En cas de rollback
            if (Schema::hasColumn('pieces', 'marque_piece')) {
                $table->dropColumn('marque_piece');
            }

            if (Schema::hasColumn('pieces', 'modele_piece')) {
                $table->dropColumn('modele_piece');
            }

            if (!Schema::hasColumn('pieces', 'vehicle_id')) {
                $table->foreignId('vehicle_id')
                    ->nullable()
                    ->constrained('vehicles')
                    ->nullOnDelete();
            }
        });
    }
};
