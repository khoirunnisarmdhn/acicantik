<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PemberiProyekController extends Controller
{
    public function index()
    {
        $pemberis = DB::table('pemberi_proyek')->get();
        return view('pemberi.index', compact('pemberis'));
    }

    public function create()
    {
        return view('pemberi.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'jenis' => 'required',
            'nama' => 'required|string|max:255',
            'penanggung_jawab' => 'required|string|max:255',
            'alamat' => 'required|string',
            'no_telp' => 'required|numeric',
            'email' => 'required|email',
        ]);

        try {
            DB::table('pemberi_proyek')->insert([
                'jenis' => $request->jenis,
                'nama' => $request->nama,
                'alamat' => $request->alamat,
                'penanggung_jawab' => $request->penanggung_jawab,
                'no_telp' => $request->no_telp,
                'email' => $request->email,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Kirim sinyal sukses
            return redirect()->route('pemberi.index')->with('success', 'Data Pemberi Proyek berhasil disimpan!');

        } catch (\Exception $e) {
            // Kirim sinyal error jika database bermasalah
            return back()->withInput()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function edit($id)
    {
        $pemberi = DB::table('pemberi_proyek')->where('id_pemberi', $id)->first();
        return view('pemberi.edit', compact('pemberi'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'jenis' => 'required',
            'nama' => 'required|string|max:255',
            'penanggung_jawab' => 'required|string|max:255',
            'alamat' => 'required|string',
            'no_telp' => 'required|numeric',
            'email' => 'required|email',
        ]);

        try {
            DB::table('pemberi_proyek')->where('id_pemberi', $id)->update([
                'jenis' => $request->jenis,
                'nama' => $request->nama,
                'alamat' => $request->alamat,
                'penanggung_jawab' => $request->penanggung_jawab,
                'no_telp' => $request->no_telp,
                'email' => $request->email,
                'updated_at' => now(),
            ]);

            return redirect()->route('pemberi.index')->with('success', 'Data Pemberi Proyek berhasil diperbarui!');

        } catch (\Illuminate\Database\QueryException $e) {
            // Menangkap error spesifik database (misal: duplikat entry atau constraint)
            return back()->withInput()->with('error', 'Gagal update ke Database: ' . $e->getMessage());
        } catch (\Exception $e) {
            // Menangkap error umum lainnya
            return back()->withInput()->with('error', 'Terjadi kesalahan sistem: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        DB::table('pemberi_proyek')->where('id_pemberi', $id)->delete();
        return redirect()->route('pemberi.index')->with('success', 'Data berhasil dihapus!');
    }
}