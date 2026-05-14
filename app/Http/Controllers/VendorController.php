<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class VendorController extends Controller
{
    public function index()
    {
        $vendors = DB::table('vendor')->get();
        return view('vendor.index', compact('vendors'));
    }

    public function create()
    {
        return view('vendor.create');
    }

    public function store(Request $request)
    {
        try {
            // 1. Validasi: Hapus 'numeric' dan ganti 'max:255' pada no_telp
            $request->validate([
                'nama' => 'required|max:150',
                'penanggung_jawab' => 'required|max:255',
                'alamat' => 'required|max:255',
                'no_telp' => 'required|max:20', // Pakai max karakter, bukan max nilai angka
                'email' => 'required|email|max:255|unique:vendor,email', // Tambah unique biar ga double
            ]);

            // 2. Eksekusi Insert
            DB::table('vendor')->insert([
                'nama' => $request->nama,
                'alamat' => $request->alamat,
                'penanggung_jawab' => $request->penanggung_jawab,
                'no_telp' => $request->no_telp,
                'email' => $request->email,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            return redirect()->route('vendor.index')->with('success', 'Vendor berhasil ditambahkan!');

        } catch (\Illuminate\Validation\ValidationException $e) {
            // Balikin ke form dengan pesan validasi yang detail
            return back()->withErrors($e->errors())->withInput();

        } catch (\Exception $e) {
            // Munculkan SweetAlert2 dengan error sistem (misal: database error)
            return back()->with('error', 'Gagal simpan vendor: ' . $e->getMessage())->withInput();
        }
    }

    public function edit($id)
    {
        $vendor = DB::table('vendor')->where('id_vendor', $id)->first();
        return view('vendor.edit', compact('vendor'));
    }

    public function update(Request $request, $id)
    {
        try {
            $request->validate([
                'nama' => 'required|max:150',
                'penanggung_jawab' => 'required|max:255',
                'alamat' => 'required|max:255',
                'no_telp' => 'required|max:20',
                'email' => 'required|email|max:255',
            ]);

            DB::table('vendor')->where('id_vendor', $id)->update([
                'nama' => $request->nama,
                'alamat' => $request->alamat,
                'penanggung_jawab' => $request->penanggung_jawab,
                'no_telp' => $request->no_telp,
                'email' => $request->email,
                'updated_at' => now(),
            ]);

            return redirect()->route('vendor.index')->with('success', 'Data vendor berhasil diperbarui!');

        } catch (\Illuminate\Validation\ValidationException $e) {
            // Balikin ke view dengan error validasi
            return back()->withErrors($e->errors())->withInput();

        } catch (\Exception $e) {
            // Jika ada error database, lempar ke SweetAlert2
            return back()->with('error', 'Gagal update vendor: ' . $e->getMessage())->withInput();
        }
    }
    public function destroy($id)
    {
        DB::table('vendor')->where('id_vendor', $id)->delete();
        return redirect()->route('vendor.index')->with('success', 'Vendor berhasil dihapus!');
    }
}