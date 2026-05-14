<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class JurnalUmumController extends Controller
{
    public function index(Request $request)
    {
        try {
            // Ambil filter, default ke bulan dan tahun sekarang
            $bulan = $request->get('bulan', date('m'));
            $tahun = $request->get('tahun', date('Y'));

            $jurnals = DB::table('jurnal_umum')
                ->join('coa', 'jurnal_umum.id_coa', '=', 'coa.id_coa')
                ->leftJoin('kas', 'jurnal_umum.id_transaksi', '=', 'kas.id_kas')
                ->select(
                    'jurnal_umum.*',
                    'coa.kode_akun',
                    'coa.nama_akun',
                    'kas.no_form as no_ref'
                )
                ->whereMonth('jurnal_umum.tanggal', $bulan)
                ->whereYear('jurnal_umum.tanggal', $tahun)
                ->orderBy('jurnal_umum.tanggal', 'asc')
                ->orderBy('jurnal_umum.id_jurnal', 'asc')
                ->get();

            // Cek jika user melakukan filter tapi datanya kosong
            if ($jurnals->isEmpty() && $request->has('bulan')) {
                return redirect()->route('jurnal.index')
                    ->with('error', 'Data jurnal periode ' . date('F', mktime(0, 0, 0, $bulan, 1)) . ' ' . $tahun . ' tidak ditemukan.');
            }

            return view('jurnal.index', compact('jurnals', 'bulan', 'tahun'));

        } catch (\Exception $e) {
            // Balikkan ke halaman sebelumnya dengan pesan error sistem untuk SweetAlert2
            return redirect()->route('jurnal.index')
                ->with('error', 'Terjadi kesalahan sistem: ' . $e->getMessage());
        }
    }
}