<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('demandes_epaves', function (Blueprint $table) {
            $table->enum('statut', ['en_attente', 'vendu', 'annule', 'expire'])
                ->default('en_attente')
                ->change();
        });
    }

    public function down(): void
    {
        Schema::table('demandes_epaves', function (Blueprint $table) {
            $table->enum('statut', ['en_attente', 'annule', 'expire'])
                ->default('en_attente')
                ->change();
        });
    }
};
