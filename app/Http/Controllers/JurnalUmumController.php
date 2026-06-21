<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class JurnalUmumController extends Controller
{
    public function index(Request $request)
    {
        try {
            $bulan = $request->get('bulan', '');
            $tahun = $request->get('tahun', date('Y'));
            $proyekId = $request->get('proyek_id', '');

            $listProyek = DB::table('proyek')->orderBy('nama')->get();

            $query = DB::table('jurnal_umum')
                ->join('coa', 'jurnal_umum.id_coa', '=', 'coa.id_coa')
                ->leftJoin('coa as parent_coa', 'coa.parent_id', '=', 'parent_coa.id_coa')
                ->leftJoin('kas', 'jurnal_umum.id_transaksi', '=', 'kas.id_kas')
                ->select(
                    'jurnal_umum.*',
                    'coa.kode_akun',
                    'coa.nama_akun',
                    'parent_coa.nama_akun as nama_parent',
                    'kas.no_form as no_ref'
                )
                ->whereYear('jurnal_umum.tanggal', $tahun);

            // Filter bulan (opsional)
            if ($bulan) {
                $query->whereMonth('jurnal_umum.tanggal', $bulan);
            }

            // Filter proyek (opsional) — join ke kas untuk ambil id_proyek
            if ($proyekId) {
                $query->where('kas.id_proyek', $proyekId);
            }

            $jurnals = $query->orderBy('jurnal_umum.tanggal', 'asc')
                ->orderBy('jurnal_umum.id_jurnal', 'asc')
                ->get();

            if ($jurnals->isEmpty() && $request->hasAny(['bulan', 'tahun', 'proyek_id'])) {
                return redirect()->route('jurnal.index')
                    ->with('error', 'Data jurnal tidak ditemukan untuk filter yang dipilih.');
            }

            return view('jurnal.index', compact('jurnals', 'bulan', 'tahun', 'proyekId', 'listProyek'));

        } catch (\Exception $e) {
            return redirect()->route('jurnal.index')
                ->with('error', 'Terjadi kesalahan sistem: ' . $e->getMessage());
        }
    }
}