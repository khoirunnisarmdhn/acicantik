<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('struktur_lra', function (Blueprint $table) {
            $table->foreignId('id_proyek')->nullable()->constrained('proyek', 'id_proyek')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('struktur_lra', function (Blueprint $table) {
            $table->dropForeign(['id_proyek']);
            $table->dropColumn('id_proyek');
        });
    }
};
