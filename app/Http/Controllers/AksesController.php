<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AksesController extends Controller
{
    public function index()
    {
        // Ambil data akses menggunakan Query Builder sesuai gaya COA
        $akses = DB::table('akses')->orderBy('nama_akses')->get();
        return view('akses.index', compact('akses'));
    }

    public function create()
    {
        // Daftar menu sesuai use case SIM Keuangan Konstruksi
        $menus = [
            'dashboard',
            'user',          // Mengelola User & Role
            'klien',         // Mengelola Klien (Pemberi Proyek)
            'vendor',        // Mengelola Vendor
            'kas_masuk',     // Mengelola Transaksi Kas Masuk
            'kas_keluar',    // Mengelola Transaksi Kas Keluar
            'termin',        // Update Termin Proyek
            'coa',           // Mengelola COA
            'lra',           // Mengelola LRA
            'jurnal',        // Melihat Jurnal Umum
            'laporan_realisasi', // Melihat Laporan Realisasi Anggaran
            'laba_rugi',     // Melihat Laba Rugi Proyek
        ];
        return view('akses.create', compact('menus'));
    }

    public function store(Request $request)
    {
        // 1. Validasi
        $request->validate([
            'nama_akses' => 'required|unique:akses,nama_akses',
            'fitur_slug' => 'required|array',
        ]);

        try {
            // 2. Pastikan baris insert ini ada dan datanya benar
            // Gunakan DB::table('nama_tabel_lo')
            $simpan = DB::table('akses')->insert([
                'nama_akses' => strtoupper($request->nama_akses),
                'fitur_slug' => implode(',', $request->fitur_slug), // Gabung array jadi string
            ]);

            if ($simpan) {
                return redirect()->route('akses.index')->with('success', 'Data berhasil disimpan ke database!');
            } else {
                return back()->withInput()->with('error', 'Gagal menyimpan data, coba ulangi lagi.');
            }

        } catch (\Exception $e) {
            // Balikin SweetAlert Error biar ketahuan salahnya di mana
            return back()->withInput()->with('error', 'Database Error: ' . $e->getMessage());
        }
    }

    public function edit($id)
    {
        $akses = DB::table('akses')->where('id_akses', $id)->first();
        $menus = [
            'dashboard',
            'user',
            'klien',
            'vendor',
            'kas_masuk',
            'kas_keluar',
            'termin',
            'coa',
            'lra',
            'jurnal',
            'laporan_realisasi',
            'laba_rugi',
        ];

        if (!$akses) {
            return redirect()->route('akses.index')->with('error', 'Data tidak ditemukan!');
        }

        return view('akses.edit', compact('akses', 'menus'));
    }

    public function update(Request $request, $id)
    {
        // 1. Validasi (Abaikan unique untuk ID yang sedang diedit)
        $request->validate([
            'nama_akses' => 'required|max:50|unique:akses,nama_akses,' . $id . ',id_akses',
            'fitur_slug' => 'required|array',
        ], [
            'nama_akses.required' => 'Nama akses wajib diisi.',
            'nama_akses.unique' => 'Nama akses sudah digunakan oleh role lain.',
            'fitur_slug.required' => 'Pilih minimal satu izin menu.',
        ]);

        try {
            // 2. Eksekusi Update menggunakan Query Builder
            $affected = DB::table('akses')
                ->where('id_akses', $id) // Pastikan ID ini sesuai dengan Primary Key di DB
                ->update([
                    'nama_akses' => strtoupper($request->nama_akses),
                    'fitur_slug' => implode(',', $request->fitur_slug),
                ]);

            // 3. Return dengan SweetAlert (Success/Info)
            if ($affected >= 0) {
                // affected bisa 0 kalau user klik simpan tapi tidak ada data yang diubah sama sekali
                return redirect()->route('akses.index')->with('success', 'Data akses berhasil diperbarui!');
            }

            return back()->with('error', 'Gagal memperbarui data, ID tidak ditemukan.');

        } catch (\Exception $e) {
            // Ini yang akan ditangkap SweetAlert2 error di view
            return back()->withInput()->with('error', 'Terjadi kesalahan database: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            // Cek apakah akses ini masih dipakai user (via tabel pivot user_akses)
            $isUsed = DB::table('user_akses')->where('id_akses', $id)->exists();

            if ($isUsed) {
                return back()->with('error', 'Gagal! Akses ini masih digunakan oleh beberapa user. Ubah akses user terkait terlebih dahulu.');
            }

            DB::table('akses')->where('id_akses', $id)->delete();
            return redirect()->route('akses.index')->with('success', 'Hak Akses berhasil dihapus!');

        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan saat menghapus data.');
        }
    }
}