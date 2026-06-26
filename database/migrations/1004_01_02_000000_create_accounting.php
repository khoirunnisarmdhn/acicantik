<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('jurnal_umum', function (Blueprint $table) {
            $table->id('id_jurnal');
            $table->foreignId('id_coa')->constrained('coa', 'id_coa');
            $table->string('posisi_dr_cr', 10)->default('');

            $table->date('tanggal');

            $table->text('deskripsi');

            $table->text('sumber_transaksi');
            $table->text('id_transaksi');
            $table->decimal('nominal', 18, 2)->default(0);

            $table->timestamps();
        });


    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jurnal_umum');
    }
};
