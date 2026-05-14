<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TerminProyekController extends Controller
{
    public function index()
    {
        $termins = DB::table('termin_proyek')
            ->join('proyek', 'termin_proyek.id_proyek', '=', 'proyek.id_proyek')
            ->join('tipe_termin', 'termin_proyek.id_tipe_termin', '=', 'tipe_termin.id_tipe_termin')
            ->select('termin_proyek.*', 'proyek.nama as nama_proyek', 'tipe_termin.nama_termin')
            ->orderBy('proyek.nama', 'asc')
            ->orderBy('termin_proyek.id_termin_proyek', 'asc')
            ->get();

        return view('termin.index', compact('termins'));
    }
    /**
     * Menampilkan form edit
     */
    public function edit($id)
    {
        $termin = DB::table('termin_proyek')
            ->join('proyek', 'termin_proyek.id_proyek', '=', 'proyek.id_proyek')
            ->select('termin_proyek.*', 'proyek.nama as nama_proyek', 'proyek.nilai_kontrak')
            ->where('id_termin_proyek', $id)
            ->first();

        if (!$termin) {
            return redirect()->route('termin.index')->with('error', 'Data termin tidak ditemukan!');
        }

        $tipe_termin = DB::table('tipe_termin')->get();

        // Hitung total persentase termin lain di proyek ini (untuk validasi)
        $totalPersenLain = DB::table('termin_proyek')
            ->where('id_proyek', $termin->id_proyek)
            ->where('id_termin_proyek', '!=', $id)
            ->sum('persentase');

        return view('termin.edit', compact('termin', 'tipe_termin', 'totalPersenLain'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'id_tipe_termin' => 'required',
            'persentase' => 'required|numeric|min:1|max:100',
            'due_date' => 'required|date',
        ]);

        try {
            $termin = DB::table('termin_proyek')
                ->join('proyek', 'termin_proyek.id_proyek', '=', 'proyek.id_proyek')
                ->select('termin_proyek.*', 'proyek.nilai_kontrak')
                ->where('id_termin_proyek', $id)
                ->first();

            // Validasi total persentase tidak melebihi 100%
            $totalPersenLain = DB::table('termin_proyek')
                ->where('id_proyek', $termin->id_proyek)
                ->where('id_termin_proyek', '!=', $id)
                ->sum('persentase');

            if (($totalPersenLain + $request->persentase) > 100) {
                $sisa = 100 - $totalPersenLain;
                return back()->with('error', "Total persentase melebihi 100%! Sisa yang tersedia: {$sisa}%");
            }

            // Hitung nominal otomatis dari persentase × nilai kontrak
            $nominalOtomatis = ($request->persentase / 100) * $termin->nilai_kontrak;

            DB::table('termin_proyek')->where('id_termin_proyek', $id)->update([
                'id_tipe_termin' => $request->id_tipe_termin,
                'persentase' => $request->persentase,
                'progress_keterangan' => $request->progress_keterangan,
                'nominal' => $nominalOtomatis,
                'due_date' => $request->due_date,
                'keterangan' => $request->keterangan,
                'updated_at' => now(),
            ]);

            return redirect()->route('termin.index')->with('success', 'Data termin berhasil diperbarui! Nominal: Rp ' . number_format($nominalOtomatis, 0, ',', '.'));

        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan sistem: ' . $e->getMessage());
        }
    }
}
