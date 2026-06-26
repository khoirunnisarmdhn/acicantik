<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProyekController extends Controller
{
    public function index()
    {
        // Join ke tabel pemberi_proyek untuk ambil nama client
        $proyeks = DB::table('proyek')
            ->leftJoin('pemberi_proyek', 'proyek.id_pemberi', '=', 'pemberi_proyek.id_pemberi')
            ->select('proyek.*', 'pemberi_proyek.nama as nama_pemberi')
            ->get();

        return view('proyek.index', compact('proyeks'));
    }

    public function create()
    {
        $pemberis = DB::table('pemberi_proyek')->get();
        $globalLras = DB::table('lra')->whereNull('id_proyek')->get();
        return view('proyek.create', compact('pemberis', 'globalLras'));
    }

    public function store(Request $request)
    {
        // Bersihkan format titik/rupiah dari nilai_kontrak sebelum validasi
        if ($request->has('nilai_kontrak')) {
            $request->merge([
                'nilai_kontrak' => str_replace('.', '', $request->nilai_kontrak)
            ]);
        }

        $request->validate([
            'nama' => 'required|max:150',
            'id_pemberi' => 'required',
            'nilai_kontrak' => 'required|numeric',
            'jumlah_termin' => 'required|integer|in:1,3,4', // 1, 3, atau 4 termin
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
            'target_laba' => 'required|integer|min:1|max:100',
            'lra_persen' => 'required|array',
            'lra_persen.*' => 'required|numeric|min:0|max:100',
        ]);

        // Validasi penjumlahan target_laba + lra_persen = 100%
        $sum = intval($request->target_laba) + array_sum(array_map('floatval', $request->lra_persen));
        if ($sum != 100) {
            return back()->withInput()->withErrors(['lra_persen' => 'Total persentase alokasi + target laba harus tepat 100%!']);
        }

        // Gunakan Transaction supaya data konsisten
        DB::beginTransaction();

        try {
            // 1. Insert ke tabel Proyek dan ambil ID-nya
            $id_proyek = DB::table('proyek')->insertGetId([
                'nama' => $request->nama,
                'id_pemberi' => $request->id_pemberi,
                'nilai_kontrak' => $request->nilai_kontrak,
                'target_laba' => $request->target_laba,
                'jumlah_termin' => $request->jumlah_termin,
                'tanggal_mulai' => $request->tanggal_mulai,
                'tanggal_selesai' => $request->tanggal_selesai,
                'status' => 'aktif', // Default aktif saat pendaftaran
                'deskripsi' => $request->deskripsi,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // 2. Simpan alokasi anggaran khusus proyek di tabel lra
            foreach ($request->lra_persen as $id_lra_global => $persen) {
                $globalLra = DB::table('lra')->where('id_lra', $id_lra_global)->whereNull('id_proyek')->first();
                if ($globalLra) {
                    DB::table('lra')->insert([
                        'keterangan' => $globalLra->keterangan,
                        'persentase' => $persen,
                        'id_kategori' => $globalLra->id_kategori,
                        'id_proyek' => $id_proyek,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }

            // 3. Auto-generate termin
            $jumlahTermin = $request->jumlah_termin;
            $nilaiKontrak = $request->nilai_kontrak;

            if ($jumlahTermin == 1) {
                // Ambil ID tipe termin untuk Pelunasan / Akhir
                $tipeAkhir = DB::table('tipe_termin')->where('nama_termin', 'LIKE', '%Akhir%')->value('id_tipe_termin') ?? 3;
                DB::table('termin_proyek')->insert([
                    'id_proyek' => $id_proyek,
                    'id_tipe_termin' => $tipeAkhir,
                    'persentase' => 100,
                    'progress_keterangan' => 'Pelunasan Sekaligus',
                    'nominal' => $nilaiKontrak,
                    'keterangan' => 'Pembayaran Penuh (Full Payment)',
                    'due_date' => $request->tanggal_selesai,
                    'status_pembayaran' => 'Belum Dibayar',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            } else {
                // Pola default: DP=20%, sisanya dibagi rata ke termin tengah, termin akhir=10%
                $dpPersen = 20;
                $akhirPersen = 10;
                $sisaPersen = 100 - $dpPersen - $akhirPersen; // 70%
                $jumlahTerminTengah = $jumlahTermin - 2; // Dikurangi DP dan Termin Akhir

                // Ambil ID tipe termin
                $tipeDP = DB::table('tipe_termin')->where('nama_termin', 'LIKE', '%DP%')->value('id_tipe_termin') ?? 1;
                $tipeProgress = DB::table('tipe_termin')->where('nama_termin', 'LIKE', '%Progress%')->value('id_tipe_termin') ?? 2;
                $tipeAkhir = DB::table('tipe_termin')->where('nama_termin', 'LIKE', '%Akhir%')->value('id_tipe_termin') ?? 3;

                // Insert DP
                DB::table('termin_proyek')->insert([
                    'id_proyek' => $id_proyek,
                    'id_tipe_termin' => $tipeDP,
                    'persentase' => $dpPersen,
                    'progress_keterangan' => 'Sebelum kerja',
                    'nominal' => ($dpPersen / 100) * $nilaiKontrak,
                    'keterangan' => 'DP (Down Payment)',
                    'due_date' => $request->tanggal_mulai,
                    'status_pembayaran' => 'Belum Dibayar',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                // Insert Termin Progress (tengah)
                if ($jumlahTerminTengah > 0) {
                    $persenPerTermin = round($sisaPersen / $jumlahTerminTengah, 2);
                    $progressStep = round(75 / $jumlahTerminTengah); // Progress fisik dibagi rata sampai 75%

                    for ($i = 1; $i <= $jumlahTerminTengah; $i++) {
                        $progressPersen = min($progressStep * $i, 75);
                        DB::table('termin_proyek')->insert([
                            'id_proyek' => $id_proyek,
                            'id_tipe_termin' => $tipeProgress,
                            'persentase' => $persenPerTermin,
                            'progress_keterangan' => 'Progress ' . $progressPersen . '%',
                            'nominal' => ($persenPerTermin / 100) * $nilaiKontrak,
                            'keterangan' => 'Termin ' . $i,
                            'due_date' => $request->tanggal_selesai,
                            'status_pembayaran' => 'Belum Dibayar',
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]);
                    }
                }

                // Insert Termin Akhir
                DB::table('termin_proyek')->insert([
                    'id_proyek' => $id_proyek,
                    'id_tipe_termin' => $tipeAkhir,
                    'persentase' => $akhirPersen,
                    'progress_keterangan' => 'Finishing + Serah Terima',
                    'nominal' => ($akhirPersen / 100) * $nilaiKontrak,
                    'keterangan' => 'Termin Akhir',
                    'due_date' => $request->tanggal_selesai,
                    'status_pembayaran' => 'Belum Dibayar',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            DB::commit();
            return redirect()->route('proyek.index')->with('success', 'Proyek dan ' . $request->jumlah_termin . ' termin berhasil didaftarkan!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function edit($id)
    {
        $proyek = DB::table('proyek')->where('id_proyek', $id)->first();
        if (!$proyek) {
            return redirect()->route('proyek.index')->with('error', 'Proyek tidak ditemukan!');
        }
        $pemberis = DB::table('pemberi_proyek')->get();

        // Ambil LRA khusus proyek ini
        $projectLras = DB::table('lra')->where('id_proyek', $id)->get();

        // Fallback ke LRA global jika belum ada data LRA khusus proyek
        if ($projectLras->isEmpty()) {
            $projectLras = DB::table('lra')->whereNull('id_proyek')->get();
        }

        return view('proyek.edit', compact('proyek', 'pemberis', 'projectLras'));
    }

    public function show($id)
    {
        $proyek = DB::table('proyek')
            ->leftJoin('pemberi_proyek', 'proyek.id_pemberi', '=', 'pemberi_proyek.id_pemberi')
            ->select('proyek.*', 'pemberi_proyek.nama as nama_pemberi', 'pemberi_proyek.jenis')
            ->where('id_proyek', $id)
            ->first();

        if (!$proyek) {
            return redirect()->route('proyek.index')->with('error', 'Proyek tidak ditemukan!');
        }

        $termins = DB::table('termin_proyek')
            ->join('tipe_termin', 'termin_proyek.id_tipe_termin', '=', 'tipe_termin.id_tipe_termin')
            ->where('id_proyek', $id)
            ->select('termin_proyek.*', 'tipe_termin.nama_termin')
            ->orderBy('id_termin_proyek')
            ->get();

        $kasMasuk = DB::table('kas')
            ->leftJoin('kategori_kas', 'kas.id_kategori', '=', 'kategori_kas.id_kategori')
            ->where('kas.id_proyek', $id)
            ->where('kas.arus', 'masuk')
            ->select('kas.*', 'kategori_kas.nama_kategori')
            ->orderBy('kas.tanggal')
            ->get();

        $kasKeluar = DB::table('kas')
            ->leftJoin('kategori_kas', 'kas.id_kategori', '=', 'kategori_kas.id_kategori')
            ->leftJoin('vendor', 'kas.id_vendor', '=', 'vendor.id_vendor')
            ->where('kas.id_proyek', $id)
            ->where('kas.arus', 'keluar')
            ->select('kas.*', 'kategori_kas.nama_kategori', 'vendor.nama as nama_vendor')
            ->orderBy('kas.tanggal')
            ->get();

        $totalMasuk = $kasMasuk->sum('nominal');
        $totalKeluar = $kasKeluar->sum('nominal');

        return view('proyek.show', compact('proyek', 'termins', 'kasMasuk', 'kasKeluar', 'totalMasuk', 'totalKeluar'));
    }

    public function update(Request $request, $id)
    {
        try {
            // 1. Validasi (Hapus nilai_kontrak & jumlah_termin dari required karena di-lock di view)
            $request->validate([
                'nama' => 'required|max:150',
                'id_pemberi' => 'required',
                'tanggal_mulai' => 'required|date',
                'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai', // Tambah pengaman tanggal
                'status' => 'required',
                'target_laba' => 'required|integer|min:1|max:100',
                'lra_persen' => 'required|array',
                'lra_persen.*' => 'required|numeric|min:0|max:100',
            ]);

            // Validasi penjumlahan target_laba + lra_persen = 100%
            $sum = intval($request->target_laba) + array_sum(array_map('floatval', $request->lra_persen));
            if ($sum != 100) {
                return back()->withInput()->withErrors(['lra_persen' => 'Total persentase alokasi + target laba harus tepat 100%!']);
            }

            DB::beginTransaction();

            // 2. Eksekusi Update
            // Data nilai_kontrak dan jumlah_termin tidak ikut di-update agar tetap konsisten dengan kontrak awal
            DB::table('proyek')->where('id_proyek', $id)->update([
                'nama' => $request->nama,
                'id_pemberi' => $request->id_pemberi,
                'target_laba' => $request->target_laba,
                'tanggal_mulai' => $request->tanggal_mulai,
                'tanggal_selesai' => $request->tanggal_selesai,
                'status' => $request->status,
                'deskripsi' => $request->deskripsi,
                'updated_at' => now(),
            ]);

            // 3. Update / Insert LRA khusus proyek
            $hasLra = DB::table('lra')->where('id_proyek', $id)->exists();

            if ($hasLra) {
                // Jika sudah ada, update masing-masing LRA proyek
                foreach ($request->lra_persen as $id_lra => $persen) {
                    DB::table('lra')
                        ->where('id_lra', $id_lra)
                        ->where('id_proyek', $id)
                        ->update([
                            'persentase' => $persen,
                            'updated_at' => now(),
                        ]);
                }
            } else {
                // Jika belum ada (fallback dari global), buat baru
                foreach ($request->lra_persen as $id_lra_global => $persen) {
                    $globalLra = DB::table('lra')->where('id_lra', $id_lra_global)->whereNull('id_proyek')->first();
                    if ($globalLra) {
                        DB::table('lra')->insert([
                            'keterangan' => $globalLra->keterangan,
                            'persentase' => $persen,
                            'id_kategori' => $globalLra->id_kategori,
                            'id_proyek' => $id,
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]);
                    }
                }
            }

            DB::commit();
            return redirect()->route('proyek.index')->with('success', 'Data proyek berhasil diperbarui!');

        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollBack();
            // Jika validasi gagal (misal tanggal tidak sesuai)
            return back()->withErrors($e->errors())->withInput();

        } catch (\Exception $e) {
            DB::rollBack();
            // Jika ada error database atau sistem, munculkan SweetAlert2 dengan pesan aslinya
            return back()->with('error', 'Gagal update: ' . $e->getMessage())->withInput();
        }
    }

    public function destroy($id)
    {
        DB::table('proyek')->where('id_proyek', $id)->delete();
        return redirect()->route('proyek.index')->with('success', 'Data proyek berhasil dihapus!');
    }

    public function printRab($id)
    {
        try {
            $proyek = DB::table('proyek')
                ->leftJoin('pemberi_proyek', 'proyek.id_pemberi', '=', 'pemberi_proyek.id_pemberi')
                ->where('proyek.id_proyek', $id)
                ->select('proyek.*', 'pemberi_proyek.nama as nama_pemberi', 'pemberi_proyek.alamat as alamat_pemberi', 'pemberi_proyek.penanggung_jawab as pj_pemberi')
                ->first();

            if (!$proyek) {
                return redirect()->back()->with('error', 'Proyek tidak ditemukan.');
            }

            // Get LRA structure
            $projectLras = DB::table('lra')->where('id_proyek', $id)->get();
            if ($projectLras->isEmpty()) {
                $projectLras = DB::table('lra')->whereNull('id_proyek')->get();
            }

            $nilaiKontrak = $proyek->nilai_kontrak;
            $targetLabaPersen = $proyek->target_laba;
            $nominalTargetLaba = ($targetLabaPersen / 100) * $nilaiKontrak;

            $items = [];
            foreach ($projectLras as $item) {
                $nominalItem = ($item->persentase / 100) * $nilaiKontrak;
                $items[] = (object)[
                    'keterangan' => $item->keterangan,
                    'persentase' => $item->persentase,
                    'nominal' => $nominalItem
                ];
            }

            // Add Markup row
            $items[] = (object)[
                'keterangan' => 'Jasa Management & Profit Margin (Markup)',
                'persentase' => $targetLabaPersen,
                'nominal' => $nominalTargetLaba
            ];

            return view('proyek.rab', compact('proyek', 'items'));

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal memproses cetak RAB: ' . $e->getMessage());
        }
    }
}