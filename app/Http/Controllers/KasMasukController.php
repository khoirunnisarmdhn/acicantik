<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class KasMasukController extends Controller
{
    public function index()
    {
        // Filter 'arus' => 'masuk'
        $kas_masuks = DB::table('kas')
            ->leftJoin('kategori_kas', 'kas.id_kategori', '=', 'kategori_kas.id_kategori')
            ->leftJoin('proyek', 'kas.id_proyek', '=', 'proyek.id_proyek')
            ->leftJoin('metode_bayar', 'kas.id_metode_bayar', '=', 'metode_bayar.id_metode_bayar')
            ->select('kas.*', 'kategori_kas.nama_kategori', 'proyek.nama as nama_proyek', 'metode_bayar.nama_metode_bayar')
            ->where('kas.arus', 'masuk')
            ->orderBy('kas.tanggal', 'desc')
            ->get();

        return view('kas_masuk.index', compact('kas_masuks'));
    }

    public function create()
    {
        // Pisahkan kategori berdasarkan jenis untuk di-switch di Alpine.js
        $kategoriProyek = DB::table('kategori_kas')
            ->where('arus', 'masuk')
            ->where('jenis', 'proyek')
            ->get();

        $kategoriUmum = DB::table('kategori_kas')
            ->where('arus', 'masuk')
            ->where('jenis', 'non-proyek')
            ->get();

        $proyek = DB::table('proyek')->where('status', 'aktif')->get();
        $metode = DB::table('metode_bayar')->get();

        // Generate No Form
        $date = date('Ymd');
        $count = DB::table('kas')->where('arus', 'masuk')->whereDate('created_at', date('Y-m-d'))->count();
        $no_form = 'KM-' . $date . '-' . str_pad($count + 1, 3, '0', STR_PAD_LEFT);

        return view('kas_masuk.create', compact('kategoriProyek', 'kategoriUmum', 'proyek', 'metode', 'no_form'));
    }

    public function store(Request $request)
    {
        // Validasi diperketat agar tidak lolos data sampah
        $request->validate([
            'no_form' => 'required|unique:kas,no_form',
            'tanggal' => 'required|date',
            'id_kategori' => 'required|exists:kategori_kas,id_kategori',
            'id_metode_bayar' => 'required',
            'nominal' => 'required|numeric|min:1',
            'keterangan' => 'required',
            'upload_bukti' => 'required|image|mimes:jpg,png,jpeg|max:2048'
        ]);

        DB::beginTransaction();
        try {
            // 1. Ambil COA dari Kategori (Penting untuk Jurnal)
            $kategori = DB::table('kategori_kas')->where('id_kategori', $request->id_kategori)->first();

            if (!$kategori || !$kategori->id_coa_debit || !$kategori->id_coa_kredit) {
                throw new \Exception("Mapping Akun (COA) belum diset pada kategori ini!");
            }

            // 2. Handle File Upload
            $fileName = null;
            if ($request->hasFile('upload_bukti')) {
                $fileName = 'KM_' . time() . '.' . $request->upload_bukti->extension();
                // Pastikan folder ini ada di /public/uploads/kas
                $request->upload_bukti->move(public_path('uploads/kas'), $fileName);
            }

            // 3. Insert ke tabel 'kas' sesuai struktur kolom lo
            $id_kas = DB::table('kas')->insertGetId([
                'no_form' => $request->no_form,
                'tanggal' => $request->tanggal,
                'arus' => 'masuk', // Enum: masuk
                'id_kategori' => $request->id_kategori,
                'id_proyek' => $request->id_proyek ?: null, // Biar gak error kalau kosong
                'id_vendor' => null,
                'id_metode_bayar' => $request->id_metode_bayar,
                'id_termin_proyek' => $request->id_termin_proyek ?: null,
                'nominal' => $request->nominal,
                'keterangan' => $request->keterangan,
                'upload_bukti' => $fileName,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // 4. Update Status Termin jadi Lunas
            if ($request->id_termin_proyek) {
                DB::table('termin_proyek')
                    ->where('id_termin_proyek', $request->id_termin_proyek)
                    ->update([
                        'status_pembayaran' => 'Lunas',
                        'updated_at' => now()
                    ]);

                // --- TAMBAHAN: CEK STATUS PROYEK ---
                if ($request->id_proyek) {
                    // Hitung termin yang BELUM lunas pada proyek ini
                    $terminBelumLunas = DB::table('termin_proyek')
                        ->where('id_proyek', $request->id_proyek)
                        ->where('status_pembayaran', '!=', 'Lunas')
                        ->count();

                    // Kalau sudah lunas semua (count = 0), update status proyek jadi Selesai
                    if ($terminBelumLunas === 0) {
                        DB::table('proyek')
                            ->where('id_proyek', $request->id_proyek)
                            ->update([
                                'status' => 'Selesai', // Pastikan nama kolom 'status' sesuai di tabel proyek lo
                                'updated_at' => now()
                            ]);
                    }
                }
                // -----------------------------------
            }

            // 5. Jurnal Otomatis
            $commonJurnal = [
                'tanggal' => $request->tanggal,
                'deskripsi' => "[$request->no_form] $request->keterangan",
                'sumber_transaksi' => 'Kas Masuk',
                'id_transaksi' => $id_kas,
                'nominal' => $request->nominal,
                'created_at' => now(),
            ];

            // Debit (Biasanya Kas/Bank)
            DB::table('jurnal_umum')->insert(array_merge($commonJurnal, [
                'id_coa' => $kategori->id_coa_debit,
                'posisi_dr_cr' => 'dr'
            ]));

            // Kredit (Biasanya Pendapatan/Piutang)
            DB::table('jurnal_umum')->insert(array_merge($commonJurnal, [
                'id_coa' => $kategori->id_coa_kredit,
                'posisi_dr_cr' => 'cr'
            ]));

            DB::commit();
            return redirect()->route('kas-masuk.index')->with('success', 'Transaksi berhasil disimpan dan status termin diperbarui.');

        } catch (\Exception $e) {
            DB::rollBack();

            // SweetAlert akan menangkap 'error' session ini
            return back()->withInput()->with('error', 'Gagal Simpan: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            // 1. Ambil data kas sebelum dihapus
            $kas = DB::table('kas')->where('id_kas', $id)->first();

            if (!$kas) {
                throw new \Exception("Data transaksi tidak ditemukan!");
            }

            // 2. Handle File (Hapus bukti transfer fisik)
            if ($kas->upload_bukti) {
                $filePath = public_path('uploads/kas/' . $kas->upload_bukti);
                if (File::exists($filePath)) {
                    File::delete($filePath);
                }
            }

            // 3. ROLLBACK STATUS TERMIN (Jika ada)
            if ($kas->id_termin_proyek) {
                DB::table('termin_proyek')
                    ->where('id_termin_proyek', $kas->id_termin_proyek)
                    ->update([
                        'status_pembayaran' => 'Belum Dibayar', // Balikin statusnya
                        'updated_at' => now()
                    ]);

                // 4. ROLLBACK STATUS PROYEK (Jika tadinya 'Selesai' jadi 'aktif')
                if ($kas->id_proyek) {
                    DB::table('proyek')
                        ->where('id_proyek', $kas->id_proyek)
                        ->update([
                            'status' => 'aktif', // Proyek aktif kembali karena ada termin yang batal lunas
                            'updated_at' => now()
                        ]);
                }
            }

            // 5. Hapus Jurnal dan Kas
            DB::table('jurnal_umum')
                ->where('sumber_transaksi', 'Kas Masuk')
                ->where('id_transaksi', $id)
                ->delete();

            DB::table('kas')->where('id_kas', $id)->delete();

            DB::commit();
            return redirect()->route('kas-masuk.index')->with('success', 'Data transaksi dihapus dan status termin telah dikembalikan.');

        } catch (\Exception $e) {
            DB::rollBack();
            // Sesuai request lo, return SweetAlert2 (lewat session error)
            return back()->with('error', 'Gagal Hapus: ' . $e->getMessage());
        }
    }

    public function getTerminByProyek($id)
    {
        $termin = DB::table('termin_proyek')
            ->join('tipe_termin', 'termin_proyek.id_tipe_termin', '=', 'tipe_termin.id_tipe_termin')
            ->where('id_proyek', $id)
            ->where('status_pembayaran', '!=', 'Lunas')
            ->select('termin_proyek.*', 'tipe_termin.nama_termin')
            ->get();

        return response()->json($termin);
    }
}