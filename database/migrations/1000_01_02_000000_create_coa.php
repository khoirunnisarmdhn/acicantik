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

        Schema::create('coa', function (Blueprint $table) {
            $table->id('id_coa');
            $table->string('kode_akun', 20)->unique();
            $table->string('nama_akun', 150);
            $table->integer('level'); // 1, 2, 3
            // Self-referencing untuk Parent-Child
            $table->foreignId('parent_id')->nullable()->constrained('coa', 'id_coa')->cascadeOnDelete();
            $table->integer('urutan');
            // $table->foreignId('id_status')->constrained('status', 'id_status');
            // $table->string('header_report', 50)->nullable(); // Posisi: Neraca/Laba Rugi
            // $table->string('posisi_normal', 10); // Debit/Kredit
            $table->timestamps();
        });
        DB::table('coa')->insert([
            // ===== LEVEL 1 =====
            ['id_coa'=>1,'kode_akun'=>'1','nama_akun'=>'AKTIVA','level'=>1,'parent_id'=>null,'urutan'=>1],
            ['id_coa'=>2,'kode_akun'=>'2','nama_akun'=>'KEWAJIBAN','level'=>1,'parent_id'=>null,'urutan'=>2],
            ['id_coa'=>3,'kode_akun'=>'3','nama_akun'=>'MODAL','level'=>1,'parent_id'=>null,'urutan'=>3],
            ['id_coa'=>4,'kode_akun'=>'4','nama_akun'=>'PENDAPATAN','level'=>1,'parent_id'=>null,'urutan'=>4],
            ['id_coa'=>5,'kode_akun'=>'5','nama_akun'=>'BEBAN','level'=>1,'parent_id'=>null,'urutan'=>5],

            // ===== AKTIVA =====
            ['id_coa'=>11,'kode_akun'=>'11','nama_akun'=>'Kas dan Setara Kas','level'=>2,'parent_id'=>1,'urutan'=>1],
            ['id_coa'=>12,'kode_akun'=>'1101','nama_akun'=>'Kas Besar','level'=>3,'parent_id'=>11,'urutan'=>1],
            ['id_coa'=>13,'kode_akun'=>'1102','nama_akun'=>'Bank Kecil','level'=>3,'parent_id'=>11,'urutan'=>2],
            // ['id_coa'=>13,'kode_akun'=>'1102','nama_akun'=>'Bank BCA','level'=>3,'parent_id'=>11,'urutan'=>2],
            // ['id_coa'=>14,'kode_akun'=>'1103','nama_akun'=>'Bank BRI','level'=>3,'parent_id'=>11,'urutan'=>3],

            ['id_coa'=>15,'kode_akun'=>'12','nama_akun'=>'Piutang Usaha','level'=>2,'parent_id'=>1,'urutan'=>2],
            ['id_coa'=>16,'kode_akun'=>'1201','nama_akun'=>'Piutang Proyek Pemerintah','level'=>3,'parent_id'=>15,'urutan'=>1],
            ['id_coa'=>17,'kode_akun'=>'1202','nama_akun'=>'Piutang Proyek Swasta','level'=>3,'parent_id'=>15,'urutan'=>2],
            ['id_coa'=>18,'kode_akun'=>'1203','nama_akun'=>'Piutang Proyek Perorangan','level'=>3,'parent_id'=>15,'urutan'=>3],

            ['id_coa'=>19,'kode_akun'=>'13','nama_akun'=>'Persediaan','level'=>2,'parent_id'=>1,'urutan'=>3],
            ['id_coa'=>20,'kode_akun'=>'1301','nama_akun'=>'Persediaan Material','level'=>3,'parent_id'=>19,'urutan'=>1],
            ['id_coa'=>21,'kode_akun'=>'1302','nama_akun'=>'Persediaan Barang Dagang','level'=>3,'parent_id'=>19,'urutan'=>2],

            ['id_coa'=>22,'kode_akun'=>'14','nama_akun'=>'Uang Muka','level'=>2,'parent_id'=>1,'urutan'=>4],
            ['id_coa'=>23,'kode_akun'=>'1401','nama_akun'=>'Uang Muka Proyek','level'=>3,'parent_id'=>22,'urutan'=>1],
            ['id_coa'=>24,'kode_akun'=>'1402','nama_akun'=>'Uang Muka Pembelian','level'=>3,'parent_id'=>22,'urutan'=>2],

            ['id_coa'=>25,'kode_akun'=>'15','nama_akun'=>'Aktiva Tetap','level'=>2,'parent_id'=>1,'urutan'=>5],
            ['id_coa'=>26,'kode_akun'=>'1501','nama_akun'=>'Peralatan Proyek','level'=>3,'parent_id'=>25,'urutan'=>1],
            ['id_coa'=>27,'kode_akun'=>'1502','nama_akun'=>'Kendaraan Operasional','level'=>3,'parent_id'=>25,'urutan'=>2],
            ['id_coa'=>28,'kode_akun'=>'1503','nama_akun'=>'Akumulasi Penyusutan','level'=>3,'parent_id'=>25,'urutan'=>3],

            // ===== KEWAJIBAN =====
            ['id_coa'=>29,'kode_akun'=>'21','nama_akun'=>'Utang Usaha','level'=>2,'parent_id'=>2,'urutan'=>1],
            ['id_coa'=>30,'kode_akun'=>'2101','nama_akun'=>'Utang Vendor Material','level'=>3,'parent_id'=>29,'urutan'=>1],
            ['id_coa'=>31,'kode_akun'=>'2102','nama_akun'=>'Utang Subkontraktor','level'=>3,'parent_id'=>29,'urutan'=>2],

            ['id_coa'=>32,'kode_akun'=>'22','nama_akun'=>'Utang Bank','level'=>2,'parent_id'=>2,'urutan'=>2],
            ['id_coa'=>33,'kode_akun'=>'2201','nama_akun'=>'Pinjaman Bank','level'=>3,'parent_id'=>32,'urutan'=>1],

            // ===== MODAL =====
            ['id_coa'=>34,'kode_akun'=>'31','nama_akun'=>'Modal','level'=>2,'parent_id'=>3,'urutan'=>1],
            ['id_coa'=>35,'kode_akun'=>'3101','nama_akun'=>'Modal Pemilik','level'=>3,'parent_id'=>34,'urutan'=>1],
            ['id_coa'=>36,'kode_akun'=>'3102','nama_akun'=>'Tambahan Modal','level'=>3,'parent_id'=>34,'urutan'=>2],
            ['id_coa'=>37,'kode_akun'=>'3103','nama_akun'=>'Prive','level'=>3,'parent_id'=>34,'urutan'=>3],

            // ===== PENDAPATAN =====
            ['id_coa'=>38,'kode_akun'=>'41','nama_akun'=>'Pendapatan Konstruksi','level'=>2,'parent_id'=>4,'urutan'=>1],
            ['id_coa'=>39,'kode_akun'=>'4101','nama_akun'=>'Termin Proyek Pemerintah','level'=>3,'parent_id'=>38,'urutan'=>1],
            ['id_coa'=>40,'kode_akun'=>'4102','nama_akun'=>'Termin Proyek Swasta','level'=>3,'parent_id'=>38,'urutan'=>2],
            ['id_coa'=>41,'kode_akun'=>'4103','nama_akun'=>'Termin Proyek Perorangan','level'=>3,'parent_id'=>38,'urutan'=>3],

            // ===== BEBAN =====
            ['id_coa'=>42,'kode_akun'=>'51','nama_akun'=>'Beban Material','level'=>2,'parent_id'=>5,'urutan'=>1],
            ['id_coa'=>43,'kode_akun'=>'5101','nama_akun'=>'Pembelian Material Proyek','level'=>3,'parent_id'=>42,'urutan'=>1],

            ['id_coa'=>44,'kode_akun'=>'52','nama_akun'=>'Beban Tenaga Kerja','level'=>2,'parent_id'=>5,'urutan'=>2],
            ['id_coa'=>45,'kode_akun'=>'5201','nama_akun'=>'Gaji Tenaga Kerja Proyek','level'=>3,'parent_id'=>44,'urutan'=>1],

            ['id_coa'=>46,'kode_akun'=>'53','nama_akun'=>'Beban Operasional Proyek','level'=>2,'parent_id'=>5,'urutan'=>3],
            ['id_coa'=>47,'kode_akun'=>'5301','nama_akun'=>'Transport Proyek','level'=>3,'parent_id'=>46,'urutan'=>1],
            ['id_coa'=>48,'kode_akun'=>'5302','nama_akun'=>'Sewa Alat Proyek','level'=>3,'parent_id'=>46,'urutan'=>2],

            ['id_coa'=>49,'kode_akun'=>'54','nama_akun'=>'Beban Administrasi','level'=>2,'parent_id'=>5,'urutan'=>4],
            ['id_coa'=>50,'kode_akun'=>'5401','nama_akun'=>'Gaji Staf Kantor','level'=>3,'parent_id'=>49,'urutan'=>1],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('coa');
    }
};
