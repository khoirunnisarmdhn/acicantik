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
        // Schema::create('rekening_bank', function (Blueprint $table) {
        //     $table->id('id_rekening_bank');
        //     $table->string('nama_bank', 100);
        //     $table->string('no_rekening', 50);
        //     $table->string('nama_pemilik', 100);
        //     $table->timestamps();
        // });

        // DB::table('rekening_bank')->insert([
        //     ['nama_bank' => 'BCA', 'no_rekening' => '123-456-789', 'nama_pemilik' => 'CV Zahfran Mulai Abadi'],
        //     ['nama_bank' => 'Mandiri', 'no_rekening' => '444-555-666', 'nama_pemilik' => 'CV Zahfran Mulai Abadi'],
        //     ['nama_bank' => 'BNI', 'no_rekening' => '555-123-777', 'nama_pemilik' => 'CV Zahfran Mulai Abadi'],
        // ]);

        // Schema::create('kas', function (Blueprint $table) {
        //     $table->id('id_kas');
        //     $table->string('nama_kas', 100);
        //     $table->string('jenis', 50); // tunai, bank, proyek
        //     $table->string('status', 100); // aktif, non-aktif
        //     $table->text('deskripsi')->nullable();
        //     $table->timestamps();
        // });

        // DB::table('kas')->insert([
        //     ['nama_kas' => 'Kas Besar', 'jenis' => 'Tunai', 'status' => 'Aktif', 'deskripsi' => 'Kas utama perusahaan'],
        //     ['nama_kas' => 'Kas Kecil', 'jenis' => 'Tunai', 'status' => 'Aktif', 'deskripsi' => 'Petty cash operasional'],
        //     ['nama_kas' => 'Rekening BNI 001', 'jenis' => 'Bank', 'status' => 'Aktif', 'deskripsi' => 'Rekening bank perusahaan'],
        //     ['nama_kas' => 'Kas Proyek A', 'jenis' => 'Kas Proyek', 'status' => 'Aktif', 'deskripsi' => 'Kas lapangan Proyek A'],
        // ]);

        // Schema::create('rekening_kas', function (Blueprint $table) {
        //     $table->id('id_rekening_kas');

        //     $table->foreignId('id_kas')->constrained('kas', 'id_kas')->cascadeOnDelete();
        //     $table->foreignId('id_rekening_bank')->nullable()->constrained('rekening_bank', 'id_rekening_bank')->cascadeOnDelete();
        //     $table->text('keterangan')->nullable();
        //     $table->timestamps();
        // });

        // DB::table('rekening_kas')->insert([
        //     ['id_kas' => 1, 'id_rekening_bank' => 2, 'keterangan' => 'Kas Besar terhubung ke rekening Mandiri'],
        //     ['id_kas' => 2, 'id_rekening_bank' => 1, 'keterangan' => 'Kas Kecil terhubung ke rekening BCA'],
        //     ['id_kas' => 3, 'id_rekening_bank' => 3, 'keterangan' => 'Rekening BNI 001 terhubung ke rekening BNI'],
        //     ['id_kas' => 4, 'id_rekening_bank' => 3, 'keterangan' => 'Kas Proyek A terhubung ke rekening BNI'],
        // ]);

        // termin proyek
        Schema::create('termin_proyek', function (Blueprint $table) {
            $table->id('id_termin_proyek');

            $table->foreignId('id_proyek')->constrained('proyek', 'id_proyek')->cascadeOnDelete();
            $table->foreignId('id_tipe_termin')->constrained('tipe_termin', 'id_tipe_termin')->cascadeOnDelete();

            $table->decimal('nominal', 18, 2)->default(0);
            $table->text('keterangan')->nullable();
            $table->date('due_date')->nullable();
            $table->text('status_pembayaran')->default('Belum Dibayar');
            $table->timestamps();
        });

        DB::table('termin_proyek')->insert([
            // Proyek 1 (1 Termin)
            ['id_proyek' => 1, 'id_tipe_termin' => 3, 'nominal' => 1000000, 'keterangan' => 'Pelunasan Proyek Gedung A', 'due_date' => '2026-01-01', 'status_pembayaran' => 'Lunas'],

            // Proyek 2 (2 Termin)
            ['id_proyek' => 2, 'id_tipe_termin' => 1, 'nominal' => 1000000, 'keterangan' => 'DP Renovasi Kantor B', 'due_date' => '2026-02-01', 'status_pembayaran' => 'Lunas'],
            ['id_proyek' => 2, 'id_tipe_termin' => 3, 'nominal' => 1000000, 'keterangan' => 'Pelunasan Renovasi Kantor B', 'due_date' => '2026-02-02', 'status_pembayaran' => 'Belum Dibayar'],

            // Proyek 3 (3 Termin - Contoh kosongan)
            ['id_proyek' => 3, 'id_tipe_termin' => 1, 'nominal' => 0, 'keterangan' => 'Termin 1 (Belum Diatur)', 'due_date' => '2026-03-01', 'status_pembayaran' => 'Belum Dibayar'],
            ['id_proyek' => 3, 'id_tipe_termin' => 2, 'nominal' => 0, 'keterangan' => 'Termin 2 (Belum Diatur)', 'due_date' => '2026-03-02', 'status_pembayaran' => 'Belum Dibayar'],
            ['id_proyek' => 3, 'id_tipe_termin' => 3, 'nominal' => 0, 'keterangan' => 'Termin 3 (Belum Diatur)', 'due_date' => '2026-03-03', 'status_pembayaran' => 'Belum Dibayar'],
        ]);

        Schema::create('kas', function (Blueprint $table) {
            $table->id('id_kas');
            $table->string('no_form', 50)->unique(); // KM-... atau KK-...
            $table->date('tanggal');
            $table->enum('arus', ['masuk', 'keluar']); // Pembeda transaksi

            // Relasi ke Kategori Tunggal
            $table->foreignId('id_kategori')->constrained('kategori_kas', 'id_kategori')->cascadeOnDelete();

            // Relasi Opsional (Nullable)
            $table->foreignId('id_proyek')->nullable()->constrained('proyek', 'id_proyek')->nullOnDelete();
            $table->foreignId('id_vendor')->nullable()->constrained('vendor', 'id_vendor')->nullOnDelete();
            $table->foreignId('id_metode_bayar')->nullable()->constrained('metode_bayar', 'id_metode_bayar')->nullOnDelete();
            $table->foreignId('id_termin_proyek')->nullable()->constrained('termin_proyek', 'id_termin_proyek')->nullOnDelete();

            $table->decimal('nominal', 18, 2);
            $table->text('keterangan');
            $table->string('upload_bukti')->nullable();
            $table->timestamps();
        });

        // Seeding Data Gabungan
        DB::table('kas')->insert([
            // Transaksi Masuk
            [
                'no_form' => 'KM-20260101-001',
                'tanggal' => '2026-01-01',
                'arus' => 'masuk',
                'id_kategori' => 1, // Pembayaran Proyek - Termin
                'id_proyek' => 1,
                'id_vendor' => null,
                'id_metode_bayar' => 1,
                'id_termin_proyek' => 1,
                'nominal' => 1000000,
                'keterangan' => 'Kas masuk Proyek A',
                'upload_bukti' => 'km-001-2026.jpg',
                'created_at' => now(),
            ],
            [
                'no_form' => 'KM-20260101-002',
                'tanggal' => '2026-01-01',
                'arus' => 'masuk',
                'id_kategori' => 2, // Pembayaran Proyek - Termin
                'id_proyek' => 2,
                'id_vendor' => null,
                'id_metode_bayar' => 1,
                'id_termin_proyek' => 1,
                'nominal' => 1000000,
                'keterangan' => 'Kas masuk Proyek A',
                'upload_bukti' => 'km-001-2026.jpg',
                'created_at' => now(),
            ],
            // Transaksi Keluar
            [
                'no_form' => 'KK-20260101-001',
                'tanggal' => '2026-01-01',
                'arus' => 'keluar',
                'id_kategori' => 5, // Pembelian Material (Sesuai ID di seeding kategori)
                'id_proyek' => 1,
                'id_vendor' => 1,
                'id_metode_bayar' => 1,
                'id_termin_proyek' => null,
                'nominal' => 1000000,
                'keterangan' => 'Kas keluar Proyek A',
                'upload_bukti' => 'kk-001-2026.jpg',
                'created_at' => now(),
            ],
            [
                'no_form' => 'KK-20260101-002',
                'tanggal' => '2026-01-01',
                'arus' => 'keluar',
                'id_kategori' => 5, // Pembelian Material (Sesuai ID di seeding kategori)
                'id_proyek' => 2,
                'id_vendor' => 1,
                'id_metode_bayar' => 1,
                'id_termin_proyek' => null,
                'nominal' => 1000000,
                'keterangan' => 'Kas keluar Proyek B',
                'upload_bukti' => 'kk-001-2026.jpg',
                'created_at' => now(),
            ],
        ]);

        Schema::create('rincian', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_kas');
            $table->string('nama');
            $table->decimal('nominal', 15, 2);
            $table->timestamps();

            $table->foreign('id_kas')->references('id_kas')->on('kas')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Schema::dropIfExists('pembayaran_detail');
        Schema::dropIfExists('kas');
        Schema::dropIfExists('termin_proyek');
        Schema::dropIfExists('rincian');
    }
};
