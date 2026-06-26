<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ExampleProjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Seed Proyek
        DB::table('proyek')->insert([
            'id_proyek' => 1,
            'nama' => 'Pembangunan Jembatan Cisadane',
            'id_pemberi' => 3, // Bapak Hadi Sutrisno
            'nilai_kontrak' => 120000000.00,
            'target_laba' => 15,
            'jumlah_termin' => 1,
            'tanggal_mulai' => '2026-01-01',
            'tanggal_selesai' => '2026-06-01',
            'status' => 'aktif',
            'deskripsi' => null,
            'created_at' => '2026-06-26 15:00:16',
            'updated_at' => '2026-06-26 15:00:16',
        ]);

        // 2. Seed Struktur LRA khusus Proyek
        DB::table('struktur_lra')->insert([
            ['id_lra' => 6, 'keterangan' => 'Biaya Material', 'persentase' => 16, 'id_kategori' => 5, 'id_proyek' => 1, 'created_at' => '2026-06-26 15:00:16', 'updated_at' => '2026-06-26 15:00:16'],
            ['id_lra' => 7, 'keterangan' => 'Biaya Upah', 'persentase' => 22, 'id_kategori' => 6, 'id_proyek' => 1, 'created_at' => '2026-06-26 15:00:16', 'updated_at' => '2026-06-26 15:00:16'],
            ['id_lra' => 8, 'keterangan' => 'Biaya Subkontraktor', 'persentase' => 32, 'id_kategori' => 7, 'id_proyek' => 1, 'created_at' => '2026-06-26 15:00:16', 'updated_at' => '2026-06-26 15:00:16'],
            ['id_lra' => 9, 'keterangan' => 'Biaya Akomodasi', 'persentase' => 10, 'id_kategori' => 8, 'id_proyek' => 1, 'created_at' => '2026-06-26 15:00:16', 'updated_at' => '2026-06-26 15:00:16'],
            ['id_lra' => 10, 'keterangan' => 'Biaya Overhead', 'persentase' => 5, 'id_kategori' => 9, 'id_proyek' => 1, 'created_at' => '2026-06-26 15:00:16', 'updated_at' => '2026-06-26 15:00:16'],
        ]);

        // 3. Seed Termin Proyek
        DB::table('termin_proyek')->insert([
            'id_termin_proyek' => 1,
            'id_proyek' => 1,
            'id_tipe_termin' => 3, // Full Payment
            'persentase' => 100.00,
            'progress_keterangan' => 'Pelunasan Sekaligus',
            'nominal' => 120000000.00,
            'keterangan' => 'Pembayaran Penuh (Full Payment)',
            'due_date' => '2026-06-01',
            'status_pembayaran' => 'Lunas',
            'created_at' => '2026-06-26 15:00:16',
            'updated_at' => '2026-06-26 15:00:43',
        ]);

        // 4. Seed Kas (KM & KK)
        DB::table('kas')->insert([
            [
                'id_kas' => 1,
                'no_form' => 'KM-20260626-001',
                'tanggal' => '2026-06-26',
                'arus' => 'masuk',
                'id_kategori' => 2, // Full Payment
                'id_proyek' => 1,
                'id_vendor' => null,
                'id_metode_bayar' => 2, // Bank
                'id_termin_proyek' => 1,
                'nominal' => 120000000.00,
                'keterangan' => '-',
                'upload_bukti' => 'KM_1782486043.png',
                'created_at' => '2026-06-26 15:00:43',
                'updated_at' => '2026-06-26 15:00:43',
            ],
            [
                'id_kas' => 2,
                'no_form' => 'KK-20260626-001',
                'tanggal' => '2026-05-05',
                'arus' => 'keluar',
                'id_kategori' => 5, // Biaya Material
                'id_proyek' => 1,
                'id_vendor' => 4, // PT Beton Prima
                'id_metode_bayar' => 2, // Bank
                'id_termin_proyek' => null,
                'nominal' => 16000000.00,
                'keterangan' => '-',
                'upload_bukti' => 'KK_1782486118.png',
                'created_at' => '2026-06-26 15:01:58',
                'updated_at' => '2026-06-26 15:02:24',
            ],
            [
                'id_kas' => 3,
                'no_form' => 'KK-20260626-002',
                'tanggal' => '2026-05-06',
                'arus' => 'keluar',
                'id_kategori' => 6, // Biaya Upah
                'id_proyek' => 1,
                'id_vendor' => null,
                'id_metode_bayar' => 2, // Bank
                'id_termin_proyek' => null,
                'nominal' => 4000000.00,
                'keterangan' => '-',
                'upload_bukti' => 'KK_1782486185.png',
                'created_at' => '2026-06-26 15:03:05',
                'updated_at' => '2026-06-26 15:03:05',
            ],
            [
                'id_kas' => 4,
                'no_form' => 'KK-20260626-003',
                'tanggal' => '2026-03-03',
                'arus' => 'keluar',
                'id_kategori' => 7, // Biaya Subkontraktor
                'id_proyek' => 1,
                'id_vendor' => null,
                'id_metode_bayar' => 1, // Cash
                'id_termin_proyek' => null,
                'nominal' => 8000000.00,
                'keterangan' => '-',
                'upload_bukti' => 'KK_1782486226.png',
                'created_at' => '2026-06-26 15:03:46',
                'updated_at' => '2026-06-26 15:03:46',
            ],
            [
                'id_kas' => 5,
                'no_form' => 'KK-20260626-004',
                'tanggal' => '2026-03-06',
                'arus' => 'keluar',
                'id_kategori' => 8, // Biaya Akomodasi
                'id_proyek' => 1,
                'id_vendor' => null,
                'id_metode_bayar' => 1, // Cash
                'id_termin_proyek' => null,
                'nominal' => 320000.00,
                'keterangan' => '-',
                'upload_bukti' => 'KK_1782486278.png',
                'created_at' => '2026-06-26 15:04:38',
                'updated_at' => '2026-06-26 15:04:38',
            ],
            [
                'id_kas' => 6,
                'no_form' => 'KK-20260626-005',
                'tanggal' => '2026-05-05',
                'arus' => 'keluar',
                'id_kategori' => 9, // Biaya Overhead
                'id_proyek' => 1,
                'id_vendor' => null,
                'id_metode_bayar' => 1, // Cash
                'id_termin_proyek' => null,
                'nominal' => 5000000.00,
                'keterangan' => '-',
                'upload_bukti' => 'KK_1782486309.png',
                'created_at' => '2026-06-26 15:05:09',
                'updated_at' => '2026-06-26 15:05:09',
            ]
        ]);

        // 5. Seed Jurnal Umum
        DB::table('jurnal_umum')->insert([
            ['id_jurnal' => 1, 'id_coa' => 13, 'posisi_dr_cr' => 'dr', 'tanggal' => '2026-06-26', 'deskripsi' => '[KM-20260626-001] ', 'sumber_transaksi' => 'Kas Masuk', 'id_transaksi' => '1', 'nominal' => 120000000.00, 'created_at' => '2026-06-26 15:00:43'],
            ['id_jurnal' => 2, 'id_coa' => 39, 'posisi_dr_cr' => 'cr', 'tanggal' => '2026-06-26', 'deskripsi' => '[KM-20260626-001] ', 'sumber_transaksi' => 'Kas Masuk', 'id_transaksi' => '1', 'nominal' => 120000000.00, 'created_at' => '2026-06-26 15:00:43'],
            
            ['id_jurnal' => 5, 'id_coa' => 43, 'posisi_dr_cr' => 'dr', 'tanggal' => '2026-05-05', 'deskripsi' => '[KK-20260626-001] -', 'sumber_transaksi' => 'Kas Keluar', 'id_transaksi' => '2', 'nominal' => 16000000.00, 'created_at' => '2026-06-26 15:02:24'],
            ['id_jurnal' => 6, 'id_coa' => 13, 'posisi_dr_cr' => 'cr', 'tanggal' => '2026-05-05', 'deskripsi' => '[KK-20260626-001] -', 'sumber_transaksi' => 'Kas Keluar', 'id_transaksi' => '2', 'nominal' => 16000000.00, 'created_at' => '2026-06-26 15:02:24'],
            
            ['id_jurnal' => 7, 'id_coa' => 44, 'posisi_dr_cr' => 'dr', 'tanggal' => '2026-05-06', 'deskripsi' => '[KK-20260626-002] ', 'sumber_transaksi' => 'Kas Keluar', 'id_transaksi' => '3', 'nominal' => 4000000.00, 'created_at' => '2026-06-26 15:03:05'],
            ['id_jurnal' => 8, 'id_coa' => 13, 'posisi_dr_cr' => 'cr', 'tanggal' => '2026-05-06', 'deskripsi' => '[KK-20260626-002] ', 'sumber_transaksi' => 'Kas Keluar', 'id_transaksi' => '3', 'nominal' => 4000000.00, 'created_at' => '2026-06-26 15:03:05'],
            
            ['id_jurnal' => 9, 'id_coa' => 45, 'posisi_dr_cr' => 'dr', 'tanggal' => '2026-03-03', 'deskripsi' => '[KK-20260626-003] ', 'sumber_transaksi' => 'Kas Keluar', 'id_transaksi' => '4', 'nominal' => 8000000.00, 'created_at' => '2026-06-26 15:03:46'],
            ['id_jurnal' => 10, 'id_coa' => 13, 'posisi_dr_cr' => 'cr', 'tanggal' => '2026-03-03', 'deskripsi' => '[KK-20260626-003] ', 'sumber_transaksi' => 'Kas Keluar', 'id_transaksi' => '4', 'nominal' => 8000000.00, 'created_at' => '2026-06-26 15:03:46'],
            
            ['id_jurnal' => 11, 'id_coa' => 46, 'posisi_dr_cr' => 'dr', 'tanggal' => '2026-03-06', 'deskripsi' => '[KK-20260626-004] ', 'sumber_transaksi' => 'Kas Keluar', 'id_transaksi' => '5', 'nominal' => 320000.00, 'created_at' => '2026-06-26 15:04:38'],
            ['id_jurnal' => 12, 'id_coa' => 13, 'posisi_dr_cr' => 'cr', 'tanggal' => '2026-03-06', 'deskripsi' => '[KK-20260626-004] ', 'sumber_transaksi' => 'Kas Keluar', 'id_transaksi' => '5', 'nominal' => 320000.00, 'created_at' => '2026-06-26 15:04:38'],
            
            ['id_jurnal' => 13, 'id_coa' => 47, 'posisi_dr_cr' => 'dr', 'tanggal' => '2026-05-05', 'deskripsi' => '[KK-20260626-005] ', 'sumber_transaksi' => 'Kas Keluar', 'id_transaksi' => '6', 'nominal' => 5000000.00, 'created_at' => '2026-06-26 15:05:09'],
            ['id_jurnal' => 14, 'id_coa' => 13, 'posisi_dr_cr' => 'cr', 'tanggal' => '2026-05-05', 'deskripsi' => '[KK-20260626-005] ', 'sumber_transaksi' => 'Kas Keluar', 'id_transaksi' => '6', 'nominal' => 5000000.00, 'created_at' => '2026-06-26 15:05:09'],
        ]);
    }
}
