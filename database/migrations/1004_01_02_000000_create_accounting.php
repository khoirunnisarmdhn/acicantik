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

        DB::table('jurnal_umum')->insert([
            ['id_coa' => 12, 'posisi_dr_cr' => 'dr', 'tanggal' => date('Y-m-d'), 'deskripsi' => 'Penerimaan termin proyek', 'sumber_transaksi' => 'Kas Masuk', 'id_transaksi' => '1', 'nominal' => 1000000],
            ['id_coa' => 39, 'posisi_dr_cr' => 'cr', 'tanggal' => date('Y-m-d'), 'deskripsi' => 'Penerimaan termin proyek', 'sumber_transaksi' => 'Kas Masuk', 'id_transaksi' => '1', 'nominal' => 1000000],
            ['id_coa' => 12, 'posisi_dr_cr' => 'dr', 'tanggal' => date('Y-m-d'), 'deskripsi' => 'Penerimaan termin proyek', 'sumber_transaksi' => 'Kas Masuk', 'id_transaksi' => '2', 'nominal' => 2000000],
            ['id_coa' => 40, 'posisi_dr_cr' => 'cr', 'tanggal' => date('Y-m-d'), 'deskripsi' => 'Penerimaan termin proyek', 'sumber_transaksi' => 'Kas Masuk', 'id_transaksi' => '2', 'nominal' => 2000000],
            ['id_coa' => 43, 'posisi_dr_cr' => 'dr', 'tanggal' => date('Y-m-d'), 'deskripsi' => 'Pembayaran material proyek', 'sumber_transaksi' => 'Kas Keluar', 'id_transaksi' => '1', 'nominal' => 1000000],
            ['id_coa' => 12, 'posisi_dr_cr' => 'cr', 'tanggal' => date('Y-m-d'), 'deskripsi' => 'Pembayaran material proyek', 'sumber_transaksi' => 'Kas Keluar', 'id_transaksi' => '1', 'nominal' => 1000000],
            ['id_coa' => 43, 'posisi_dr_cr' => 'dr', 'tanggal' => date('Y-m-d'), 'deskripsi' => 'Pembayaran material proyek', 'sumber_transaksi' => 'Kas Keluar', 'id_transaksi' => '2', 'nominal' => 2000000],
            ['id_coa' => 12, 'posisi_dr_cr' => 'cr', 'tanggal' => date('Y-m-d'), 'deskripsi' => 'Pembayaran material proyek', 'sumber_transaksi' => 'Kas Keluar', 'id_transaksi' => '2', 'nominal' => 2000000],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jurnal_umum');
    }
};
