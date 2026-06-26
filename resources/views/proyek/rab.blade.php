<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RAB Penawaran - {{ $proyek->nama }}</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            color: #333;
            line-height: 1.5;
            margin: 0;
            padding: 40px;
            background-color: #fff;
        }

        .header-container {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            border-bottom: 3px double #333;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }

        .company-details h1 {
            font-size: 24px;
            font-weight: 900;
            margin: 0;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .company-details p {
            font-size: 11px;
            color: #666;
            margin: 2px 0 0 0;
            text-transform: uppercase;
            font-weight: bold;
            letter-spacing: 1px;
        }

        .document-title {
            text-align: right;
        }

        .document-title h2 {
            font-size: 18px;
            font-weight: 800;
            margin: 0;
            text-transform: uppercase;
            color: #4f46e5;
        }

        .document-title p {
            font-size: 11px;
            margin: 5px 0 0 0;
            font-family: monospace;
            font-weight: bold;
        }

        .info-grid {
            display: grid;
            grid-template-cols: 1fr 1fr;
            gap: 40px;
            margin-bottom: 30px;
            font-size: 13px;
        }

        .info-box h3 {
            font-size: 11px;
            font-weight: bold;
            text-transform: uppercase;
            color: #888;
            margin: 0 0 8px 0;
            letter-spacing: 1px;
            border-bottom: 1px solid #eee;
            padding-bottom: 5px;
        }

        .info-box table {
            width: 100%;
            border-collapse: collapse;
        }

        .info-box table td {
            padding: 4px 0;
            vertical-align: top;
        }

        .info-box table td.label {
            width: 130px;
            font-weight: bold;
            color: #555;
        }

        .info-box table td.colon {
            width: 15px;
            color: #555;
        }

        .table-title {
            font-size: 13px;
            font-weight: bold;
            margin-bottom: 10px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 40px;
            font-size: 13px;
        }

        .items-table th {
            background-color: #f8fafc;
            border-top: 1px solid #333;
            border-bottom: 2px solid #333;
            padding: 12px 10px;
            font-weight: bold;
            text-transform: uppercase;
            font-size: 11px;
            letter-spacing: 0.5px;
        }

        .items-table td {
            padding: 12px 10px;
            border-bottom: 1px solid #e2e8f0;
        }

        .items-table tr.total-row td {
            border-top: 2px solid #333;
            border-bottom: 3px double #333;
            font-weight: bold;
            background-color: #f8fafc;
        }

        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .font-bold { font-weight: bold; }

        .signatures {
            display: grid;
            grid-template-cols: 1fr 1fr;
            gap: 80px;
            margin-top: 60px;
            font-size: 13px;
            text-align: center;
        }

        .signature-box {
            display: inline-block;
        }

        .signature-line {
            margin-top: 70px;
            border-bottom: 1px solid #333;
            width: 220px;
            margin-left: auto;
            margin-right: auto;
        }

        .signature-role {
            font-size: 11px;
            color: #666;
            margin-top: 5px;
            font-weight: bold;
            text-transform: uppercase;
        }

        .toolbar {
            background-color: #f1f5f9;
            border: 1px solid #e2e8f0;
            padding: 15px;
            border-radius: 12px;
            margin-bottom: 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .toolbar p {
            margin: 0;
            font-size: 12px;
            font-weight: bold;
            color: #475569;
        }

        .btn {
            background-color: #10b981;
            color: white;
            padding: 8px 16px;
            border: none;
            border-radius: 8px;
            font-weight: bold;
            font-size: 12px;
            cursor: pointer;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: all 0.2s;
            box-shadow: 0 4px 6px -1px rgba(16, 185, 129, 0.2);
        }

        .btn:hover {
            background-color: #059669;
        }

        .btn-print {
            background-color: #1e293b;
            box-shadow: 0 4px 6px -1px rgba(30, 41, 59, 0.2);
        }

        .btn-print:hover {
            background-color: #0f172a;
        }

        @media print {
            body {
                padding: 0;
            }
            .toolbar {
                display: none !important;
            }
        }
    </style>
</head>
<body>

    <!-- Toolbar no-print -->
    <div class="toolbar">
        <p>Halaman Pratinjau Dokumen Penawaran Klien (RAB Penawaran)</p>
        <div style="display: flex; gap: 10px;">
            <button onclick="window.print()" class="btn btn-print">
                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                CETAK DOKUMEN (PDF)
            </button>
            <button onclick="window.close()" class="btn" style="background-color:#ef4444; box-shadow:none;">TUTUP</button>
        </div>
    </div>

    <!-- Header Dokumen -->
    <div class="header-container">
        <div class="company-details">
            <h1>CV Zahfran Mulia Abadi</h1>
            <p>Manajemen Keuangan & Estimasi Proyek</p>
        </div>
        <div class="document-title">
            <h2>Rincian Anggaran Penawaran</h2>
            <p>REF: RAB-PRY-{{ str_pad($proyek->id_proyek, 4, '0', STR_PAD_LEFT) }}</p>
        </div>
    </div>

    <!-- Informasi Proyek & Klien -->
    <div class="info-grid">
        <div class="info-box">
            <h3>Pemberi Tugas (Klien)</h3>
            <table>
                <tr>
                    <td class="label">Nama Instansi/Klien</td>
                    <td class="colon">:</td>
                    <td>{{ $proyek->nama_pemberi }}</td>
                </tr>
                <tr>
                    <td class="label">Alamat Instansi</td>
                    <td class="colon">:</td>
                    <td>{{ $proyek->alamat_pemberi ?? '-' }}</td>
                </tr>
                <tr>
                    <td class="label">Penanggung Jawab</td>
                    <td class="colon">:</td>
                    <td>{{ $proyek->pj_pemberi ?? '-' }}</td>
                </tr>
            </table>
        </div>
        <div class="info-box">
            <h3>Detail Kesepakatan Proyek</h3>
            <table>
                <tr>
                    <td class="label">Nama Pekerjaan</td>
                    <td class="colon">:</td>
                    <td class="font-bold">{{ $proyek->nama }}</td>
                </tr>
                <tr>
                    <td class="label">Masa Pelaksanaan</td>
                    <td class="colon">:</td>
                    <td>
                        {{ \Carbon\Carbon::parse($proyek->tanggal_mulai)->format('d F Y') }}
                        s.d
                        {{ \Carbon\Carbon::parse($proyek->tanggal_selesai)->format('d F Y') }}
                    </td>
                </tr>
                <tr>
                    <td class="label">Jenis Penawaran</td>
                    <td class="colon">:</td>
                    <td>Paket Kontrak Konstruksi (Lump Sum)</td>
                </tr>
            </table>
        </div>
    </div>

    <!-- Tabel Rincian Anggaran -->
    <div class="table-title">Daftar Rencana Anggaran Biaya Kontrak</div>
    <table class="items-table">
        <thead>
            <tr>
                <th class="text-center" style="width: 50px;">No</th>
                <th class="text-left">Uraian Kategori Pekerjaan / Jasa</th>
                <th class="text-center" style="width: 120px;">Bobot (%)</th>
                <th class="text-right" style="width: 250px;">Jumlah Harga (Rp)</th>
            </tr>
        </thead>
        <tbody>
            @foreach($items as $index => $item)
            <tr>
                <td class="text-center">{{ $index + 1 }}</td>
                <td class="{{ $index === count($items) - 1 ? 'font-bold' : '' }}">{{ $item->keterangan }}</td>
                <td class="text-center">{{ number_format($item->persentase, 0) }}%</td>
                <td class="text-right {{ $index === count($items) - 1 ? 'font-bold' : '' }}">
                    Rp {{ number_format($item->nominal, 0, ',', '.') }}
                </td>
            </tr>
            @endforeach
            <tr class="total-row">
                <td colspan="2" class="text-right">TOTAL NILAI KONTRAK PENAWARAN (TERMASUK MARKUP)</td>
                <td class="text-center">100%</td>
                <td class="text-right">Rp {{ number_format($proyek->nilai_kontrak, 0, ',', '.') }}</td>
            </tr>
        </tbody>
    </table>

    <!-- Tanda Tangan Penandatangan -->
    <div class="signatures">
        <div class="signature-box">
            <p>Disetujui Oleh (Klien),</p>
            <p class="font-bold" style="margin-top:5px;">{{ $proyek->nama_pemberi }}</p>
            <div class="signature-line"></div>
            <p class="signature-role">{{ $proyek->pj_pemberi ?? 'Penanggung Jawab' }}</p>
        </div>
        <div class="signature-box">
            <p>Diajukan Oleh (Kontraktor),</p>
            <p class="font-bold" style="margin-top:5px;">CV Zahfran Mulia Abadi</p>
            <div class="signature-line"></div>
            <p class="signature-role">Project Estimator & Finance</p>
        </div>
    </div>

</body>
</html>
