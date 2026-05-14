<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {

        Schema::create('kategori_kas', function (Blueprint $table) {
            $table->id('id_kategori'); // Satu PK untuk semua
            $table->string('nama_kategori', 100);
            $table->enum('arus', ['masuk', 'keluar']); // Pembeda arus kas
            $table->enum('jenis', ['proyek', 'non-proyek']); // Pembeda klasifikasi
            $table->text('deskripsi')->nullable();

            // Mapping COA
            $table->foreignId('id_coa_debit')->nullable()->constrained('coa', 'id_coa');
            $table->foreignId('id_coa_kredit')->nullable()->constrained('coa', 'id_coa');

            $table->timestamps();
        });

        // 2. Insert Data Seeding (Gabungan Masuk & Keluar)
        DB::table('kategori_kas')->insert([
            // --- KATEGORI KAS MASUK ---
            [
                'nama_kategori' => 'Pembayaran Proyek - Termin',
                'arus' => 'masuk',
                'jenis' => 'proyek',
                'id_coa_debit' => 12,
                'id_coa_kredit' => 39,
                'deskripsi' => 'Pembayaran berdasarkan progres pekerjaan',
                'created_at' => now(),
            ],
            [
                'nama_kategori' => 'Pembayaran Proyek - Uang Muka',
                'arus' => 'masuk',
                'jenis' => 'proyek',
                'id_coa_debit' => 12,
                'id_coa_kredit' => 23,
                'deskripsi' => 'Pembayaran awal proyek',
                'created_at' => now(),
            ],
            [
                'nama_kategori' => 'Penambahan Modal',
                'arus' => 'masuk',
                'jenis' => 'non-proyek',
                'id_coa_debit' => 12,
                'id_coa_kredit' => 36,
                'deskripsi' => 'Setoran modal dari pemilik',
                'created_at' => now(),
            ],
            [
                'nama_kategori' => 'Piutang Bank',
                'arus' => 'masuk',
                'jenis' => 'non-proyek',
                'id_coa_debit' => 12,
                'id_coa_kredit' => 33,
                'deskripsi' => 'Pencairan dana dari pinjaman bank',
                'created_at' => now(),
            ],

            // --- KATEGORI KAS KELUAR ---
            [
                'nama_kategori' => 'Pembelian Material',
                'arus' => 'keluar',
                'jenis' => 'proyek',
                'id_coa_debit' => 43,
                'id_coa_kredit' => 12,
                'deskripsi' => 'Pembelian barang/bahan ke vendor',
                'created_at' => now(),
            ],
            [
                'nama_kategori' => 'Tenaga Kerja Proyek',
                'arus' => 'keluar',
                'jenis' => 'proyek',
                'id_coa_debit' => 45,
                'id_coa_kredit' => 12,
                'deskripsi' => 'Pembayaran tukang, mandor, pekerja lapangan',
                'created_at' => now(),
            ],
            [
                'nama_kategori' => 'Tenaga Kerja Kantor',
                'arus' => 'keluar',
                'jenis' => 'non-proyek',
                'id_coa_debit' => 50,
                'id_coa_kredit' => 12,
                'deskripsi' => 'Gaji administratif kantor / staf internal',
                'created_at' => now(),
            ],
            [
                'nama_kategori' => 'Operasional Proyek',
                'arus' => 'keluar',
                'jenis' => 'proyek',
                'id_coa_debit' => 47,
                'id_coa_kredit' => 12,
                'deskripsi' => 'Transportasi, ATK, listrik lapangan, dll.',
                'created_at' => now(),
            ],
        ]);

        // Tabel LRA
        Schema::create('lra', function (Blueprint $table) {
            $table->id('id_lra');
            $table->string('keterangan');
            $table->integer('persentase');
            $table->foreignId('id_kategori')->constrained('kategori_kas', 'id_kategori')->cascadeOnDelete();
            $table->timestamps();
        });

        DB::table('lra')->insert([
            ['keterangan' => 'Gaji Karyawan', 'persentase' => '60', 'id_kategori' => 8],
            ['keterangan' => 'Biaya Operasional Proyek', 'persentase' => '5', 'id_kategori' => 6],
            ['keterangan' => 'Biaya Material', 'persentase' => '15', 'id_kategori' => 5],
        ]);

        // Tabel Pemberi Proyek
        Schema::create('pemberi_proyek', function (Blueprint $table) {
            $table->id('id_pemberi');
            $table->string('jenis', 50); // Perorangan, Swasta, Pemerintah
            $table->string('nama', 150);
            $table->string('alamat', 255);
            $table->string('penanggung_jawab', 255);
            $table->string('no_telp', 20);
            $table->string('email', 100);
            $table->timestamps();
        });

        DB::table('pemberi_proyek')->insert([
            ['jenis' => 'Pemerintah', 'nama' => 'Dinas PUPR Kota Serang', 'alamat' => 'Jl. Mayor Syafei No. 12, Kota Serang', 'penanggung_jawab' => 'Syarifudin, ST', 'no_telp' => '0812-3456-7890', 'email' => 'puprserang@serangkota.go.id'],
            ['jenis' => 'Swasta', 'nama' => 'PT Maju Sejahtera Konstruksi', 'alamat' => 'Jl. Industri No. 88, Cilegon', 'penanggung_jawab' => 'Ahmad Rudi', 'no_telp' => '0813-9876-5520', 'email' => 'info@maju-sejahtera.co.id'],
            ['jenis' => 'Perorangan', 'nama' => 'Bapak Hadi Sutrisno', 'alamat' => 'Jl. Trip Jamaksari No. 5, Serang', 'penanggung_jawab' => 'Hadi Sutrisno', 'no_telp' => '0812-2244-3344', 'email' => '-'],
        ]);

        // Tabel Proyek 
        Schema::create('proyek', function (Blueprint $table) {
            $table->id('id_proyek');
            $table->string('nama', 150);
            $table->foreignId('id_pemberi')->constrained('pemberi_proyek', 'id_pemberi')->cascadeOnDelete();
            $table->decimal('nilai_kontrak', 18, 2);
            $table->integer('target_laba')->default(0);
            $table->integer('jumlah_termin');
            $table->date('tanggal_mulai');
            $table->date('tanggal_selesai');
            $table->string('status'); // aktif, non-aktif
            $table->string('deskripsi', 255)->nullable();
            $table->timestamps();
        });

        DB::table('proyek')->insert([
            ['nama' => 'Proyek 1', 'id_pemberi' => 1, 'nilai_kontrak' => 1000000, 'target_laba' => 10, 'jumlah_termin' => 1, 'tanggal_mulai' => '2026-01-01', 'tanggal_selesai' => '2026-01-31', 'status' => 'selesai', 'deskripsi' => 'Proyek 1'],
            ['nama' => 'Proyek 2', 'id_pemberi' => 2, 'nilai_kontrak' => 2000000, 'target_laba' => 15, 'jumlah_termin' => 2, 'tanggal_mulai' => '2026-01-01', 'tanggal_selesai' => '2026-02-28', 'status' => 'aktif', 'deskripsi' => 'Proyek 2'],
            ['nama' => 'Proyek 3', 'id_pemberi' => 3, 'nilai_kontrak' => 3000000, 'target_laba' => 20, 'jumlah_termin' => 3, 'tanggal_mulai' => '2026-01-01', 'tanggal_selesai' => '2026-03-31', 'status' => 'aktif', 'deskripsi' => 'Proyek 3'],
        ]);

        // Tabel Vendor
        Schema::create('vendor', function (Blueprint $table) {
            $table->id('id_vendor');
            $table->string('nama', 150);
            $table->string('alamat', 255);
            $table->string('penanggung_jawab', 255);
            $table->string('no_telp', 20);
            $table->string('email', 100);
            $table->timestamps();
        });

        DB::table('vendor')->insert([
            ['nama' => 'CV Makmur Jaya', 'alamat' => 'Jl. Raya Serang No. 12, Banten', 'penanggung_jawab' => 'Budi Santoso', 'no_telp' => '081234567890', 'email' => 'cs@makmurjaya.com'],
            ['nama' => 'UD Sumber Rezeki', 'alamat' => 'Jl. A. Yani No. 33, Serang', 'penanggung_jawab' => 'Dedi', 'no_telp' => '082233445566', 'email' => '-'],
            ['nama' => 'Toko Bangunan “Pak Udin”', 'alamat' => 'Pasar Lama Serang, Banten', 'penanggung_jawab' => 'Udin', 'no_telp' => '081278889900', 'email' => '-'],
            ['nama' => 'PT Beton Prima', 'alamat' => 'Kawasan Industri Cikande', 'penanggung_jawab' => 'Rita', 'no_telp' => '081299223344', 'email' => 'sales@betonprima.co.id'],
        ]);

        Schema::create('metode_bayar', function (Blueprint $table) {
            $table->id('id_metode_bayar');
            $table->string('nama_metode_bayar', 50);
            $table->string('deskripsi', 255);
            $table->timestamps();
        });

        DB::table('metode_bayar')->insert([
            ['nama_metode_bayar' => 'Cash', 'deskripsi' => 'Cash'],
            ['nama_metode_bayar' => 'Bank', 'deskripsi' => 'Bank'],
        ]);

        Schema::create('tipe_termin', function (Blueprint $table) {
            $table->id('id_tipe_termin');
            $table->string('nama_termin', 100); // uang muka, termin, full payment
            $table->text('deskripsi')->nullable();
            $table->timestamps();
        });

        DB::table('tipe_termin')->insert([
            ['nama_termin' => 'Uang Muka', 'deskripsi' => 'Pembayaran awal sebelum pekerjaan'],
            ['nama_termin' => 'Termin', 'deskripsi' => 'Pembayaran progres berdasarkan persentase'],
            ['nama_termin' => 'Full Payment', 'deskripsi' => 'Pelunasan nilai kontrak 100%'],
        ]);

        Schema::create('realisasi_anggaran', function (Blueprint $table) {
            $table->id('id_realisasi_anggaran');
            $table->string('nama_realisasi', 100);
            $table->integer('presentase');
            $table->text('deskripsi')->nullable();
            $table->timestamps();
        });

        DB::table('realisasi_anggaran')->insert([
            ['nama_realisasi' => 'Uang Muka', 'presentase' => 10, 'deskripsi' => 'Pembayaran awal sebelum pekerjaan'],
            ['nama_realisasi' => '10%', 'presentase' => 10, 'deskripsi' => 'Pembayaran progres berdasarkan persentase'],
            ['nama_realisasi' => '20%', 'presentase' => 20, 'deskripsi' => 'Pembayaran progres berdasarkan persentase'],
            ['nama_realisasi' => '30%', 'presentase' => 30, 'deskripsi' => 'Pembayaran progres berdasarkan persentase'],
            ['nama_realisasi' => '40%', 'presentase' => 40, 'deskripsi' => 'Pembayaran progres berdasarkan persentase'],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lra');
        Schema::dropIfExists('proyek');
        Schema::dropIfExists('pemberi_proyek');
        Schema::dropIfExists('vendor');
        Schema::dropIfExists('metode_bayar');
        Schema::dropIfExists('kategori_kas');
        Schema::dropIfExists('tipe_termin');
        Schema::dropIfExists('realisasi_anggaran');
    }
};
