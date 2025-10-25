<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('demandes_epaves', function (Blueprint $table) {
            $table->enum('type', ['vehicule', 'epave'])
                ->default('epave')
                ->after('user_id')
                ->comment('Type de vente: véhicule ou épave');
        });
    }

    public function down(): void
    {
        Schema::table('demandes_epaves', function (Blueprint $table) {
            $table->dropColumn('type');
        });
    }
};
