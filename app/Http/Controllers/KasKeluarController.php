<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;

class KasKeluarController extends Controller
{
    public function show($id)
    {
        $data = DB::table('kas')
            ->leftJoin('kategori_kas', 'kas.id_kategori', '=', 'kategori_kas.id_kategori')
            ->leftJoin('proyek', 'kas.id_proyek', '=', 'proyek.id_proyek')
            ->leftJoin('vendor', 'kas.id_vendor', '=', 'vendor.id_vendor')
            ->leftJoin('metode_bayar', 'kas.id_metode_bayar', '=', 'metode_bayar.id_metode_bayar')
            ->select(
                'kas.*',
                'kas.tanggal as tanggal_keluar',
                'kategori_kas.nama_kategori',
                'proyek.nama as nama_proyek',
                'vendor.nama',
                'metode_bayar.nama_metode_bayar'
            )
            ->where('kas.id_kas', $id)
            ->where('kas.arus', 'keluar')
            ->first();

        if (!$data) {
            return redirect()->route('kas-keluar.index')->with('error', 'Data tidak ditemukan!');
        }

        $rincian = DB::table('rincian')->where('id_kas', $id)->get();

        return view('kas_keluar.show', compact('data', 'rincian'));
    }

    public function index()
    {
        $kas_keluars = DB::table('kas')
            ->leftJoin('kategori_kas', 'kas.id_kategori', '=', 'kategori_kas.id_kategori')
            ->leftJoin('proyek', 'kas.id_proyek', '=', 'proyek.id_proyek')
            ->leftJoin('vendor', 'kas.id_vendor', '=', 'vendor.id_vendor')
            ->leftJoin('metode_bayar', 'kas.id_metode_bayar', '=', 'metode_bayar.id_metode_bayar')
            ->select('kas.*', 'kategori_kas.nama_kategori', 'proyek.nama as nama_proyek', 'vendor.nama', 'metode_bayar.nama_metode_bayar')
            ->where('kas.arus', 'keluar') // Filter arus keluar
            ->orderBy('kas.tanggal', 'desc')
            ->get();

        return view('kas_keluar.index', compact('kas_keluars'));
    }

    public function create()
    {
        // Filter kategori khusus arus keluar
        $kategoriProyek = DB::table('kategori_kas')
            ->where('arus', 'keluar')
            ->where('jenis', 'proyek')
            ->get();

        $kategoriUmum = DB::table('kategori_kas')
            ->where('arus', 'keluar')
            ->where('jenis', 'non-proyek')
            ->get();

        $proyek = DB::table('proyek')->where('status', 'aktif')->get();
        $vendor = DB::table('vendor')->get(); // Kas keluar biasanya melibatkan vendor/supplier
        $metode = DB::table('metode_bayar')->get();

        // Generate No Form Kas Keluar (KK)
        $date = date('Ymd');
        $count = DB::table('kas')->where('arus', 'keluar')->whereDate('created_at', date('Y-m-d'))->count();
        $no_form = 'KK-'.$date.'-'.str_pad($count + 1, 3, '0', STR_PAD_LEFT);

        return view('kas_keluar.create', compact('kategoriProyek', 'kategoriUmum', 'proyek', 'vendor', 'metode', 'no_form'));
    }

    public function store(Request $request)
    {
        // Bersihkan format rupiah dari rincian sebelum validasi
        if ($request->has('rincian')) {
            $rincian = $request->rincian;
            foreach ($rincian as &$item) {
                $item['nominal'] = str_replace('.', '', $item['nominal'] ?? '0');
            }
            $request->merge(['rincian' => $rincian]);
        }

        $request->validate([
            'no_form' => 'required|unique:kas,no_form',
            'tanggal' => 'required|date',
            'id_kategori' => 'required|exists:kategori_kas,id_kategori',
            'id_metode_bayar' => 'required',
            'keterangan' => 'nullable|string',
            'upload_bukti' => 'nullable|image|mimes:jpg,png,jpeg|max:2048',

            // RINCIAN
            'rincian' => 'required|array|min:1',
            'rincian.*.nama' => 'required|string|max:255',
            'rincian.*.nominal' => 'required|numeric|min:1',
        ]);

        $totalNominal = 0;

        foreach ($request->rincian as $item) {

            $nominal = (int) str_replace('.', '', $item['nominal']);

            $totalNominal += $nominal;
        }

        DB::beginTransaction();
        try {
            $kategori = DB::table('kategori_kas')->where('id_kategori', $request->id_kategori)->first();

            if (! $kategori || ! $kategori->id_coa_debit || ! $kategori->id_coa_kredit) {
                throw new \Exception('Mapping Akun (COA) belum diset pada kategori ini!');
            }

            $fileName = null;
            if ($request->hasFile('upload_bukti')) {
                $fileName = 'KK_'.time().'.'.$request->upload_bukti->extension();
                $request->upload_bukti->move(public_path('uploads/kas'), $fileName);
            }

            $id_kas = DB::table('kas')->insertGetId([
                'no_form' => $request->no_form,
                'tanggal' => $request->tanggal,
                'arus' => 'keluar', // ARUS KELUAR
                'id_kategori' => $request->id_kategori,
                'id_proyek' => $request->id_proyek ?: null,
                'id_vendor' => $request->id_vendor ?: null,
                'id_metode_bayar' => $request->id_metode_bayar,
                'nominal' => $totalNominal,
                'keterangan' => $request->keterangan ?? '-',
                'upload_bukti' => $fileName,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            foreach ($request->rincian as $item) {
                $nominal = (int) str_replace('.', '', $item['nominal']);

                DB::table('rincian')->insert([
                    'id_kas' => $id_kas,
                    'nama' => $item['nama'],
                    'nominal' => $nominal,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            // Jurnal Otomatis Kas Keluar
            $commonJurnal = [
                'tanggal' => $request->tanggal,
                'deskripsi' => "[$request->no_form] $request->keterangan",
                'sumber_transaksi' => 'Kas Keluar',
                'id_transaksi' => $id_kas,
                'nominal' => $totalNominal,
                'created_at' => now(),
            ];

            // Baris DEBIT (Biasanya Beban atau Aset bertambah)
            DB::table('jurnal_umum')->insert(array_merge($commonJurnal, [
                'id_coa' => $kategori->id_coa_debit,
                'posisi_dr_cr' => 'dr',
            ]));

            // Baris KREDIT (Kas/Bank berkurang)
            DB::table('jurnal_umum')->insert(array_merge($commonJurnal, [
                'id_coa' => $kategori->id_coa_kredit,
                'posisi_dr_cr' => 'cr',
            ]));

            DB::commit();

            // === CEK WARNING ANGGARAN LRA ===
            $warningMessage = null;
            if ($request->id_proyek) {
                // Cek apakah kategori ini ada di master LRA
                $lraItem = DB::table('lra')->where('id_kategori', $request->id_kategori)->first();

                if ($lraItem) {
                    // Ambil nilai kontrak proyek
                    $proyek = DB::table('proyek')->where('id_proyek', $request->id_proyek)->first();

                    if ($proyek) {
                        // Hitung anggaran untuk kategori ini
                        $anggaranKategori = ($lraItem->persentase / 100) * $proyek->nilai_kontrak;

                        // Hitung total realisasi pengeluaran kategori ini di proyek
                        $totalRealisasi = DB::table('kas')
                            ->where('id_proyek', $request->id_proyek)
                            ->where('id_kategori', $request->id_kategori)
                            ->where('arus', 'keluar')
                            ->sum('nominal');

                        if ($totalRealisasi > $anggaranKategori) {
                            $selisih = $totalRealisasi - $anggaranKategori;
                            $warningMessage = 'PERINGATAN: Pengeluaran kategori "' . $kategori->nama_kategori . '" sudah MELEBIHI anggaran LRA sebesar Rp ' . number_format($selisih, 0, ',', '.') . '! (Anggaran: Rp ' . number_format($anggaranKategori, 0, ',', '.') . ' | Realisasi: Rp ' . number_format($totalRealisasi, 0, ',', '.') . ')';
                        }
                    }
                }
            }

            if ($warningMessage) {
                return redirect()->route('kas-keluar.index')->with('warning', $warningMessage);
            }

            return redirect()->route('kas-keluar.index')->with('success', 'Transaksi Kas Keluar berhasil disimpan.');
        } catch (\Exception $e) {

            DB::rollBack();

            Log::error('ERROR KAS KELUAR', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString(),
                'request' => $request->all(),
            ]);

            return back()
                ->withInput()
                ->with('error', 'Gagal Simpan: '.$e->getMessage());
        }
    }

    public function edit($id)
    {
        $kas = DB::table('kas')
            ->where('id_kas', $id)
            ->first();

        if (! $kas) {
            return redirect()
                ->route('kas-keluar.index')
                ->with('error', 'Data tidak ditemukan!');
        }

        // rincian khusus kas ini
        $rincian = DB::table('rincian')
            ->where('id_kas', $id)
            ->get();

        // kategori
        $kategori = DB::table('kategori_kas')
            ->where('arus', 'keluar')
            ->get();

        // master data
        $proyek = DB::table('proyek')
            ->where('status', 'aktif')
            ->get();

        $vendor = DB::table('vendor')->get();

        $metode = DB::table('metode_bayar')->get();

        return view(
            'kas_keluar.edit',
            compact(
                'kas',
                'rincian',
                'kategori',
                'proyek',
                'vendor',
                'metode'
            )
        );
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'no_form' => 'required|unique:kas,no_form,'.$id.',id_kas',
            'tanggal' => 'required|date',
            'id_kategori' => 'required|exists:kategori_kas,id_kategori',
            'id_metode_bayar' => 'required',
            'keterangan' => 'required',

            // upload jadi optional saat edit
            'upload_bukti' => 'nullable|image|mimes:jpg,png,jpeg|max:2048',

            'rincian' => 'required|array|min:1',
            'rincian.*.nama' => 'required|string|max:255',
            'rincian.*.nominal' => 'required',
        ]);

        $totalNominal = 0;

        foreach ($request->rincian as $item) {

            $nominal = (int) str_replace('.', '', $item['nominal']);

            $totalNominal += $nominal;
        }

        DB::beginTransaction();
        try {
            $kas = DB::table('kas')->where('id_kas', $id)->first();
            $kategori = DB::table('kategori_kas')->where('id_kategori', $request->id_kategori)->first();

            if (! $kategori->id_coa_debit || ! $kategori->id_coa_kredit) {
                throw new \Exception('Mapping Akun (COA) belum diset pada kategori ini!');
            }

            $fileName = $kas->upload_bukti;
            if ($request->hasFile('upload_bukti')) {
                // Hapus file lama jika ada
                if ($fileName && File::exists(public_path('uploads/kas/'.$fileName))) {
                    File::delete(public_path('uploads/kas/'.$fileName));
                }
                $fileName = 'KK_'.time().'.'.$request->upload_bukti->extension();
                $request->upload_bukti->move(public_path('uploads/kas'), $fileName);
            }
            // 1. Update Tabel Kas
            DB::table('kas')->where('id_kas', $id)->update([
                'tanggal' => $request->tanggal,
                'id_kategori' => $request->id_kategori,
                'id_proyek' => $request->id_proyek ?: null,
                'id_vendor' => $request->id_vendor ?: null,
                'id_metode_bayar' => $request->id_metode_bayar,
                'nominal' => $totalNominal,
                'keterangan' => $request->keterangan,
                'upload_bukti' => $fileName,
                'updated_at' => now(),
            ]);

            // 2. Refresh Jurnal (Hapus yang lama, insert yang baru)
            DB::table('jurnal_umum')->where('sumber_transaksi', 'Kas Keluar')->where('id_transaksi', $id)->delete();

            DB::table('rincian')->where('id_kas', $id)->delete();

            foreach ($request->rincian as $item) {
                $nominal = (int) str_replace('.', '', $item['nominal']);

                DB::table('rincian')->insert([
                    'id_kas' => $id,
                    'nama' => $item['nama'],
                    'nominal' => $nominal,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            $commonJurnal = [
                'tanggal' => $request->tanggal,
                'deskripsi' => "[$kas->no_form] $request->keterangan",
                'sumber_transaksi' => 'Kas Keluar',
                'id_transaksi' => $id,
                'nominal' => $totalNominal,
                'created_at' => now(),
            ];

            DB::table('jurnal_umum')->insert(array_merge($commonJurnal, ['id_coa' => $kategori->id_coa_debit, 'posisi_dr_cr' => 'dr']));
            DB::table('jurnal_umum')->insert(array_merge($commonJurnal, ['id_coa' => $kategori->id_coa_kredit, 'posisi_dr_cr' => 'cr']));

            DB::commit();

            return redirect()->route('kas-keluar.index')->with('success', 'Transaksi Kas Keluar berhasil diperbarui.');

        } catch (\Exception $e) {
            DB::rollBack();

            return back()->with('error', 'Gagal Update: '.$e->getMessage());
        }
    }

    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            $kas = DB::table('kas')->where('id_kas', $id)->first();
            if (! $kas) {
                throw new \Exception('Data tidak ditemukan!');
            }

            if ($kas->upload_bukti) {
                $filePath = public_path('uploads/kas/'.$kas->upload_bukti);
                if (File::exists($filePath)) {
                    File::delete($filePath);
                }
            }

            DB::table('jurnal_umum')->where('sumber_transaksi', 'Kas Keluar')->where('id_transaksi', $id)->delete();
            DB::table('kas')->where('id_kas', $id)->delete();

            DB::commit();

            return redirect()->route('kas-keluar.index')->with('success', 'Data Kas Keluar dihapus!');
        } catch (\Exception $e) {
            DB::rollBack();

            return back()->with('error', 'Gagal Hapus: '.$e->getMessage());
        }
    }
}
