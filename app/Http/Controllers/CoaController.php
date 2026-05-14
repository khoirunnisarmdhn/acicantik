<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CoaController extends Controller
{
    public function index()
    {
        // Kita join ke tabel sendiri buat dapet nama parent-nya
        $coas = DB::table('coa as c')
            ->leftJoin('coa as p', 'c.parent_id', '=', 'p.id_coa')
            ->select('c.*', 'p.nama_akun as nama_parent')
            ->orderBy('c.kode_akun')
            ->get();
        return view('coa.index', compact('coas'));
    }

    public function create()
    {
        // Ambil akun level 1 & 2 saja untuk jadi calon Parent
        $parents = DB::table('coa')->where('level', '<', 3)->orderBy('kode_akun')->get();
        return view('coa.create', compact('parents'));
    }

    public function store(Request $request)
    {
        // 1. Validasi di sisi Server
        $request->validate([
            'kode_akun' => 'required|unique:coa,kode_akun|max:20', // Cek unik di tabel coa kolom kode_akun
            'nama_akun' => 'required|max:150',
            'level' => 'required|integer',
            'urutan' => 'required|integer|min:1',
        ], [
            // Pesan Error Kustom
            'kode_akun.unique' => 'Gagal! Kode Akun "' . $request->kode_akun . '" sudah terdaftar di sistem.',
            'kode_akun.required' => 'Kode akun wajib diisi.',
        ]);

        try {
            DB::table('coa')->insert([
                'kode_akun' => $request->kode_akun,
                'nama_akun' => $request->nama_akun,
                'level' => $request->level,
                'parent_id' => $request->parent_id,
                'urutan' => $request->urutan,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            return redirect()->route('coa.index')->with('success', 'Akun COA baru berhasil ditambahkan!');
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function edit($id)
    {
        $coa = DB::table('coa')->where('id_coa', $id)->first();
        // Ambil calon parent (Level 1 & 2)
        $parents = DB::table('coa')->where('level', '<', 3)->where('id_coa', '!=', $id)->get();

        return view('coa.edit', compact('coa', 'parents'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            // Ignore ID saat ini supaya kode_akun tidak dianggap duplikat oleh dirinya sendiri
            'kode_akun' => 'required|max:20|unique:coa,kode_akun,' . $id . ',id_coa',
            'nama_akun' => 'required|max:150',
            'level' => 'required|integer',
            'urutan' => 'required|integer|min:1',
        ], [
            'kode_akun.unique' => 'Gagal! Kode Akun "' . $request->kode_akun . '" sudah digunakan oleh akun lain.',
        ]);

        DB::table('coa')->where('id_coa', $id)->update([
            'kode_akun' => $request->kode_akun,
            'nama_akun' => $request->nama_akun,
            'level' => $request->level,
            'parent_id' => $request->parent_id,
            'urutan' => $request->urutan,
            'updated_at' => now(),
        ]);

        return redirect()->route('coa.index')->with('success', 'Akun berhasil diperbarui!');
    }

    public function destroy($id)
    {
        try {
            // Cek apakah akun ini punya "anak" (sub-akun)
            $hasChild = DB::table('coa')->where('parent_id', $id)->exists();

            if ($hasChild) {
                return back()->with('error', 'Gagal! Akun ini masih memiliki sub-akun (anak). Hapus sub-akunnya dulu.');
            }

            // Cek apakah sudah dipakai di transaksi (Opsional, nanti kalau sudah ada tabel transaksi)
            // if (DB::table('jurnal')->where('id_coa', $id)->exists()) { ... }

            DB::table('coa')->where('id_coa', $id)->delete();
            return redirect()->route('coa.index')->with('success', 'Akun berhasil dihapus!');

        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan saat menghapus data.');
        }
    }
}
