<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('offres_epaves', function (Blueprint $table) {
            // Renommer casse_id en user_id
            $table->renameColumn('casse_id', 'user_id');
        });
    }

    public function down(): void
    {
        Schema::table('offres_epaves', function (Blueprint $table) {
            $table->renameColumn('user_id', 'casse_id');
        });
    }
};
