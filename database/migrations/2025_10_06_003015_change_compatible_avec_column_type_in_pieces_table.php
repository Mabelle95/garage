    <?php

    use Illuminate\Database\Migrations\Migration;
    use Illuminate\Database\Schema\Blueprint;
    use Illuminate\Support\Facades\Schema;

    return new class extends Migration
    {
        public function up(): void
        {
            Schema::table('pieces', function (Blueprint $table) {
                $table->text('compatible_avec')->change();
            });
        }

        public function down(): void
        {
            Schema::table('pieces', function (Blueprint $table) {
                $table->json('compatible_avec')->change();
            });
        }
    };

