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
            ['id_coa'=>11,'kode_akun'=>'11','nama_akun'=>'Kas','level'=>2,'parent_id'=>1,'urutan'=>1],
            ['id_coa'=>12,'kode_akun'=>'1101','nama_akun'=>'Kas Besar','level'=>3,'parent_id'=>11,'urutan'=>1],
            ['id_coa'=>13,'kode_akun'=>'1102','nama_akun'=>'Kas Bank','level'=>3,'parent_id'=>11,'urutan'=>2],
            ['id_coa'=>14,'kode_akun'=>'1103','nama_akun'=>'Kas Kecil','level'=>3,'parent_id'=>11,'urutan'=>3],

            ['id_coa'=>15,'kode_akun'=>'12','nama_akun'=>'Piutang Usaha','level'=>2,'parent_id'=>1,'urutan'=>2],
            ['id_coa'=>16,'kode_akun'=>'1201','nama_akun'=>'Piutang Usaha','level'=>3,'parent_id'=>15,'urutan'=>1],

            ['id_coa'=>17,'kode_akun'=>'13','nama_akun'=>'Perlengkapan','level'=>2,'parent_id'=>1,'urutan'=>3],
            ['id_coa'=>18,'kode_akun'=>'1301','nama_akun'=>'Perlengkapan','level'=>3,'parent_id'=>17,'urutan'=>1],

            ['id_coa'=>19,'kode_akun'=>'14','nama_akun'=>'Peralatan','level'=>2,'parent_id'=>1,'urutan'=>4],
            ['id_coa'=>20,'kode_akun'=>'1401','nama_akun'=>'Peralatan','level'=>3,'parent_id'=>19,'urutan'=>1],
            ['id_coa'=>21,'kode_akun'=>'1402','nama_akun'=>'Akumulasi Penyusutan Peralatan','level'=>3,'parent_id'=>19,'urutan'=>2],

            // ===== KEWAJIBAN =====
            ['id_coa'=>29,'kode_akun'=>'21','nama_akun'=>'Utang Usaha','level'=>2,'parent_id'=>2,'urutan'=>1],
            ['id_coa'=>30,'kode_akun'=>'2101','nama_akun'=>'Utang Usaha','level'=>3,'parent_id'=>29,'urutan'=>1],
            ['id_coa'=>31,'kode_akun'=>'22','nama_akun'=>'Utang Bank','level'=>2,'parent_id'=>2,'urutan'=>2],
            ['id_coa'=>32,'kode_akun'=>'2201','nama_akun'=>'Utang Bank','level'=>3,'parent_id'=>31,'urutan'=>1],

            // ===== MODAL =====
            ['id_coa'=>34,'kode_akun'=>'31','nama_akun'=>'Modal','level'=>2,'parent_id'=>3,'urutan'=>1],
            ['id_coa'=>35,'kode_akun'=>'3101','nama_akun'=>'Modal Pemilik','level'=>3,'parent_id'=>34,'urutan'=>1],
            ['id_coa'=>36,'kode_akun'=>'3102','nama_akun'=>'Prive','level'=>3,'parent_id'=>34,'urutan'=>2],

            // ===== PENDAPATAN =====
            ['id_coa'=>38,'kode_akun'=>'41','nama_akun'=>'Pendapatan Proyek','level'=>2,'parent_id'=>4,'urutan'=>1],
            ['id_coa'=>39,'kode_akun'=>'4101','nama_akun'=>'Pendapatan Jasa Konstruksi','level'=>3,'parent_id'=>38,'urutan'=>1],

            // ===== BEBAN =====
            // Beban Langsung Proyek (5 Kategori)
            ['id_coa'=>42,'kode_akun'=>'51','nama_akun'=>'Beban Proyek','level'=>2,'parent_id'=>5,'urutan'=>1],
            ['id_coa'=>43,'kode_akun'=>'5101','nama_akun'=>'Beban Material Proyek','level'=>3,'parent_id'=>42,'urutan'=>1],
            ['id_coa'=>44,'kode_akun'=>'5102','nama_akun'=>'Beban Upah Proyek','level'=>3,'parent_id'=>42,'urutan'=>2],
            ['id_coa'=>45,'kode_akun'=>'5103','nama_akun'=>'Beban Subkontraktor Proyek','level'=>3,'parent_id'=>42,'urutan'=>3],
            ['id_coa'=>46,'kode_akun'=>'5104','nama_akun'=>'Beban Akomodasi Proyek','level'=>3,'parent_id'=>42,'urutan'=>4],
            ['id_coa'=>47,'kode_akun'=>'5105','nama_akun'=>'Beban Overhead Proyek','level'=>3,'parent_id'=>42,'urutan'=>5],

            // Beban Usaha / Operasional Kantor
            ['id_coa'=>48,'kode_akun'=>'52','nama_akun'=>'Beban Usaha','level'=>2,'parent_id'=>5,'urutan'=>2],
            ['id_coa'=>49,'kode_akun'=>'5201','nama_akun'=>'Beban Gaji Kantor','level'=>3,'parent_id'=>48,'urutan'=>1],
            ['id_coa'=>50,'kode_akun'=>'5202','nama_akun'=>'Beban Sewa Kantor','level'=>3,'parent_id'=>48,'urutan'=>2],
            ['id_coa'=>51,'kode_akun'=>'5203','nama_akun'=>'Beban Listrik & Air Kantor','level'=>3,'parent_id'=>48,'urutan'=>3],
            ['id_coa'=>52,'kode_akun'=>'5204','nama_akun'=>'Beban Internet','level'=>3,'parent_id'=>48,'urutan'=>4],
            ['id_coa'=>53,'kode_akun'=>'5205','nama_akun'=>'Beban Pemasaran','level'=>3,'parent_id'=>48,'urutan'=>5],
            ['id_coa'=>54,'kode_akun'=>'5206','nama_akun'=>'Beban Administrasi Bank','level'=>3,'parent_id'=>48,'urutan'=>6],
            ['id_coa'=>55,'kode_akun'=>'5207','nama_akun'=>'Beban Penyusutan Peralatan','level'=>3,'parent_id'=>48,'urutan'=>7],
            ['id_coa'=>56,'kode_akun'=>'5208','nama_akun'=>'Beban Pajak Usaha','level'=>3,'parent_id'=>48,'urutan'=>8],
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
