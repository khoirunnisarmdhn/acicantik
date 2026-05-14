<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LraController extends Controller
{
    public function index()
    {
        // Ambil data LRA beserta nama kategorinya
        $lras = DB::table('lra')
            ->leftJoin('kategori_kas', 'lra.id_kategori', '=', 'kategori_kas.id_kategori')
            ->select('lra.*', 'kategori_kas.nama_kategori')
            ->orderBy('lra.id_lra', 'asc')
            ->get();

        // Ambil list kategori kas KELUAR dan PROYEK untuk dropdown
        $listKategori = DB::table('kategori_kas')
            ->where('arus', 'keluar')
            ->where('jenis', 'proyek')
            ->orderBy('nama_kategori', 'asc')
            ->get();

        $totalPersentase = $lras->sum('persentase');

        return view('lra.index', compact('lras', 'totalPersentase', 'listKategori'));
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'keterangan' => 'required|string|max:255',
                'persentase' => 'required|numeric|min:0.01|max:100',
                'id_kategori' => 'required|exists:kategori_kas,id_kategori',
            ]);

            $totalSaatIni = DB::table('lra')->sum('persentase');

            // Validasi agar total tidak lebih dari 100%
            if (($totalSaatIni + $request->persentase) > 100) {
                $sisa = 100 - $totalSaatIni;
                return back()->withInput()->with('error', 'Gagal! Total alokasi anggaran melebihi 100%. Sisa yang tersedia: ' . $sisa . '%');
            }

            DB::table('lra')->insert([
                'keterangan' => $request->keterangan,
                'persentase' => $request->persentase,
                'id_kategori' => $request->id_kategori,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            return back()->with('success', 'Master Item LRA berhasil ditambahkan!');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menyimpan: ' . $e->getMessage());
        }
    }

    // Tambahkan method ini di LraController
    public function update(Request $request, $id)
    {
        try {
            $request->validate([
                'keterangan' => 'required|string|max:255',
                'persentase' => 'required|numeric|min:0.01|max:100',
                'id_kategori' => 'required|exists:kategori_kas,id_kategori',
            ]);

            // Hitung total persentase selain data yang sedang diedit
            $totalLainnya = DB::table('lra')
                ->where('id_lra', '!=', $id)
                ->sum('persentase');

            if (($totalLainnya + $request->persentase) > 100) {
                $sisa = 100 - $totalLainnya;
                return back()->with('error', 'Gagal Update! Total melebihi 100%. Sisa kuota: ' . $sisa . '%');
            }

            DB::table('lra')->where('id_lra', $id)->update([
                'keterangan' => $request->keterangan,
                'persentase' => $request->persentase,
                'id_kategori' => $request->id_kategori,
                'updated_at' => now(),
            ]);

            return back()->with('success', 'Data LRA berhasil diperbarui!');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal memperbarui: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            // Cari data berdasarkan ID
            $lra = DB::table('lra')->where('id_lra', $id)->first();

            if (!$lra) {
                return redirect()->back()->with('error', 'Data tidak ditemukan atau sudah dihapus.');
            }

            // Proses hapus
            DB::table('lra')->where('id_lra', $id)->delete();

            // Mengembalikan pesan sukses ke SweetAlert2 di Blade
            return redirect()->back()->with('success', 'Item Struktur LRA berhasil dihapus!');

        } catch (\Exception $e) {
            // Jika ada error (misal: data sedang digunakan di tabel lain/foreign key constraint)
            // SweetAlert2 akan menangkap session error ini
            return redirect()->back()->with('error', 'Gagal menghapus data: ' . $e->getMessage());
        }
    }

    public function show(Request $request)
    {
        try {
            $selectedProyek = $request->get('proyek_id');
            $listProyek = DB::table('proyek')->orderBy('nama', 'asc')->get();

            // Inisialisasi default
            $dataLra = [];
            $totalAnggaranProyek = 0;

            if ($selectedProyek) {
                $proyek = DB::table('proyek')->where('id_proyek', $selectedProyek)->first();

                if ($proyek) {
                    $totalAnggaranProyek = $proyek->nilai_kontrak;

                    // Ambil Master LRA
                    $masterLra = DB::table('lra')->get();

                    foreach ($masterLra as $item) {
                        // 1. Hitung Anggaran (Persentase LRA x Nilai Kontrak)
                        $nominalAnggaran = ($item->persentase / 100) * $totalAnggaranProyek;

                        // 2. Hitung Realisasi (Sum nominal di tabel kas berdasarkan proyek & kategori)
                        $realisasi = DB::table('kas')
                            ->where('id_proyek', $selectedProyek)
                            ->where('id_kategori', $item->id_kategori)
                            ->sum('nominal');

                        $dataLra[] = (object) [
                            'keterangan' => $item->keterangan,
                            'persentase' => $item->persentase,
                            'anggaran' => $nominalAnggaran,
                            'realisasi' => $realisasi,
                            'selisih' => $nominalAnggaran - $realisasi,
                        ];
                    }
                }
            }

            return view('lra.laporan', compact('listProyek', 'selectedProyek', 'dataLra', 'totalAnggaranProyek'));

        } catch (\Exception $e) {
            // Return SweetAlert2 sesuai permintaan awal
            return redirect()->back()->with('error', 'Gagal memproses laporan: ' . $e->getMessage());
        }
    }

    public function labarugi(Request $request)
    {
        try {
            $selectedProyek = $request->get('proyek_id');
            $listProyek = DB::table('proyek')->orderBy('nama', 'asc')->get();

            $data = null;

            if ($selectedProyek) {
                $proyek = DB::table('proyek')->where('id_proyek', $selectedProyek)->first();

                if ($proyek) {
                    // 1. PENDAPATAN & TARGET
                    $nilaiKontrak = $proyek->nilai_kontrak;
                    $targetLabaPersen = $proyek->target_laba; // pastikan kolom ini ada di table proyek
                    $nominalTargetLaba = ($targetLabaPersen / 100) * $nilaiKontrak;

                    // 2. BIAYA (ANGGARAN)
                    // Anggaran Biaya = Nilai Kontrak - Target Laba
                    $anggaranBiaya = $nilaiKontrak - $nominalTargetLaba;

                    // 3. REALISASI BIAYA (Ambil dari total pengeluaran di tabel KAS)
                    $realisasiBiaya = DB::table('kas')
                        ->where('id_proyek', $selectedProyek)
                        ->where('arus', 'keluar')
                        ->sum('nominal');

                    // 4. EFISIENSI & TOTAL LABA
                    $efisiensiBiaya = $anggaranBiaya - $realisasiBiaya;
                    $totalLabaAkhir = $nominalTargetLaba + $efisiensiBiaya;

                    $data = (object) [
                        'proyek' => $proyek,
                        'nilai_kontrak' => $nilaiKontrak,
                        'target_laba_persen' => $targetLabaPersen,
                        'nominal_target_laba' => $nominalTargetLaba,
                        'anggaran_biaya' => $anggaranBiaya,
                        'realisasi_biaya' => $realisasiBiaya,
                        'efisiensi_biaya' => $efisiensiBiaya,
                        'total_laba_akhir' => $totalLabaAkhir,
                        'persentase_laba_akhir' => ($totalLabaAkhir / $nilaiKontrak) * 100
                    ];
                }
            }

            return view('lra.laba_rugi', compact('listProyek', 'selectedProyek', 'data'));

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal memproses Laba Rugi: ' . $e->getMessage());
        }
    }
}