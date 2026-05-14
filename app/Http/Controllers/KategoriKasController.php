<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class KategoriKasController extends Controller
{
    /**
     * Tampilkan semua kategori kas
     */
    public function index()
    {
        // Ambil semua data dari satu tabel kategori_kas
        $kategori = DB::table('kategori_kas')
            ->leftJoin('coa as coa_debit', 'kategori_kas.id_coa_debit', '=', 'coa_debit.id_coa')
            ->leftJoin('coa as coa_kredit', 'kategori_kas.id_coa_kredit', '=', 'coa_kredit.id_coa')
            ->select(
                'kategori_kas.*',
                'coa_debit.nama_akun as nama_debit',
                'coa_debit.kode_akun as kode_debit',
                'coa_kredit.nama_akun as nama_kredit',
                'coa_kredit.kode_akun as kode_kredit'
            )
            ->get();

        return view('kategori.index', compact('kategori'));
    }

    /**
     * Form tambah kategori
     */
    public function create()
    {
        // Kita ambil data COA untuk pilihan Mapping Akun di form
        $coa = DB::table('coa')->orderBy('kode_akun', 'asc')->get();
        return view('kategori.create', compact('coa'));
    }

    public function store(Request $request)
    {
        // Validasi input
        $request->validate([
            'nama_kategori' => 'required|string|max:255',
            'arus' => 'required|in:masuk,keluar',
            'jenis' => 'required|in:proyek,non-proyek',
            'id_coa_debit' => 'nullable|exists:coa,id_coa',
            'id_coa_kredit' => 'nullable|exists:coa,id_coa',
        ]);

        try {
            DB::table('kategori_kas')->insert([
                'nama_kategori' => $request->nama_kategori,
                'arus' => $request->arus,
                'jenis' => $request->jenis,
                'id_coa_debit' => $request->id_coa_debit,
                'id_coa_kredit' => $request->id_coa_kredit,
                'deskripsi' => $request->deskripsi,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            return redirect()->route('kategori.index')->with('success', 'Kategori baru berhasil ditambahkan!');
        } catch (\Exception $e) {
            // Balikin error ke SweetAlert2 di view
            return back()->withInput()->with('error', 'Gagal menyimpan: ' . $e->getMessage());
        }
    }

    /**
     * Form edit kategori
     */
    public function edit($id)
    {
        // Cari data di satu tabel tunggal
        $data = DB::table('kategori_kas')->where('id_kategori', $id)->first();

        if (!$data) {
            return redirect()->route('kategori.index')->with('error', 'Data kategori tidak ditemukan!');
        }

        $coa = DB::table('coa')->orderBy('kode_akun', 'asc')->get();

        // Kita definisikan primary key-nya secara manual untuk view lo
        $pk = 'id_kategori';

        // Variabel $jenis diambil langsung dari database record-nya
        $jenis = $data->arus;

        return view('kategori.edit', compact('data', 'coa', 'pk', 'jenis'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_kategori' => 'required|string|max:255',
            'arus' => 'required|in:masuk,keluar',
            'jenis' => 'required|in:proyek,non-proyek',
            'id_coa_debit' => 'required',
            'id_coa_kredit' => 'required',
        ]);

        try {
            DB::table('kategori_kas')->where('id_kategori', $id)->update([
                'nama_kategori' => $request->nama_kategori,
                'arus' => $request->arus,
                'jenis' => $request->jenis,
                'id_coa_debit' => $request->id_coa_debit,
                'id_coa_kredit' => $request->id_coa_kredit,
                'deskripsi' => $request->deskripsi,
                'updated_at' => now(),
            ]);

            return redirect()->route('kategori.index')->with('success', 'Kategori berhasil diperbarui!');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal memperbarui data: ' . $e->getMessage());
        }
    }

    /**
     * Hapus kategori
     */
    public function destroy($id)
    {
        try {
            // Proteksi jika sudah ada transaksi
            $isUsed = DB::table('kas')->where('id_kategori', $id)->exists();
            if ($isUsed) {
                return back()->with('error', 'Kategori ini tidak bisa dihapus karena sudah memiliki data transaksi!');
            }

            DB::table('kategori_kas')->where('id_kategori', $id)->delete();
            return redirect()->route('kategori.index')->with('success', 'Kategori berhasil dihapus!');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menghapus data: ' . $e->getMessage());
        }
    }
}