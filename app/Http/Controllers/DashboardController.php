<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        try {
            // Set Waktu Sekarang (Data 2026 sesuai context)
            $now = Carbon::now();
            $bulanIni = $now->month;
            $tahunIni = $now->year;

            // 1. Statistik Proyek
            $totalProyek = DB::table('proyek')->count();
            $proyekAktif = DB::table('proyek')->where('status', 'aktif')->count();

            // 2. Hitung Saldo Kas Keseluruhan
            // Kita hitung dalam satu query agar lebih efisien
            $rekapKas = DB::table('kas')
                ->selectRaw("SUM(CASE WHEN arus = 'masuk' THEN nominal ELSE 0 END) as total_masuk")
                ->selectRaw("SUM(CASE WHEN arus = 'keluar' THEN nominal ELSE 0 END) as total_keluar")
                ->first();

            $saldoKas = ($rekapKas->total_masuk ?? 0) - ($rekapKas->total_keluar ?? 0);

            // 3. Mutasi Khusus Bulan Ini
            $kasMasukBulanIni = DB::table('kas')
                ->where('arus', 'masuk')
                ->whereMonth('tanggal', $bulanIni)
                ->whereYear('tanggal', $tahunIni)
                ->sum('nominal') ?? 0;

            $kasKeluarBulanIni = DB::table('kas')
                ->where('arus', 'keluar')
                ->whereMonth('tanggal', $bulanIni)
                ->whereYear('tanggal', $tahunIni)
                ->sum('nominal') ?? 0;

            // 4. Query Transaksi Terbaru
            // Karena sudah satu tabel, tidak perlu UNION, cukup join biasa
            $transaksiTerbaru = DB::table('kas')
                ->leftJoin('proyek', 'kas.id_proyek', '=', 'proyek.id_proyek')
                ->leftJoin('kategori_kas', 'kas.id_kategori', '=', 'kategori_kas.id_kategori')
                ->leftJoin('vendor', 'kas.id_vendor', '=', 'vendor.id_vendor')
                ->leftJoin('metode_bayar', 'kas.id_metode_bayar', '=', 'metode_bayar.id_metode_bayar')
                ->select(
                    'kas.no_form',
                    'kas.tanggal',
                    'kas.nominal',
                    'kas.keterangan',
                    'kas.arus as tipe',
                    'proyek.nama as nama_proyek',
                    'kategori_kas.nama_kategori',
                    'vendor.nama as nama_vendor',
                    'metode_bayar.nama_metode_bayar'
                )
                ->orderBy('kas.tanggal', 'desc')
                ->orderBy('kas.created_at', 'desc')
                ->limit(5)
                ->get();

            return view('dashboard', compact(
                'totalProyek',
                'proyekAktif',
                'saldoKas',
                'kasMasukBulanIni',
                'kasKeluarBulanIni',
                'transaksiTerbaru'
            ));

        } catch (\Exception $e) {
            // Mengikuti instruksi Saved Info: return sweetalert2 jika error
            return redirect()->back()->with('error', "
                <script>
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: 'Terjadi kesalahan saat memuat dashboard: " . addslashes($e->getMessage()) . "',
                    });
                </script>
            ");
        }
    }
}