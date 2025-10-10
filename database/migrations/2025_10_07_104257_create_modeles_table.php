<?php
// database/migrations/xxxx_create_modeles_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('modeles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('marque_id')->constrained()->onDelete('cascade');
            $table->string('nom');
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->unique(['marque_id', 'nom']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('modeles');
    }
};
