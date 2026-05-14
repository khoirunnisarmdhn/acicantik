<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('termin_proyek', function (Blueprint $table) {
            $table->decimal('persentase', 5, 2)->default(0)->after('id_tipe_termin');
            $table->string('progress_keterangan', 100)->nullable()->after('persentase');
        });
    }

    public function down(): void
    {
        Schema::table('termin_proyek', function (Blueprint $table) {
            $table->dropColumn(['persentase', 'progress_keterangan']);
        });
    }
};
