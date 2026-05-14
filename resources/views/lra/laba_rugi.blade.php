<x-app-layout>
    @section('title', 'Laporan Laba Rugi Proyek')

    <script>
        function extractExcel() {
            let table = document.querySelector("#labarugiTable");
            let html = `
                <style>
                    table { border-collapse: collapse; width: 100%; }
                    th, td { border: 1px solid black; padding: 10px; text-align: left; font-family: Arial; }
                    .text-right { text-align: right; }
                    .font-bold { font-weight: bold; }
                </style>
                ${table.outerHTML}
            `;
            let blob = new Blob([html], { type: 'application/vnd.ms-excel' });
            let url = URL.createObjectURL(blob);
            let a = document.createElement('a');
            a.href = url;
            a.download = 'Laba_Rugi_{{ $data->proyek->nama ?? "Proyek" }}.xls';
            a.click();
        }
    </script>

    <style>
        /* Hilangkan padding default agar menempel ke toolbar */
        main { padding-top: 0 !important; }

        @media print {
            body * { visibility: hidden; background: white !important; color: black !important; }
            #printArea, #printArea * { visibility: visible; }
            #printArea { position: absolute; left: 0; top: 0; width: 100%; margin: 0; padding: 0; box-shadow: none !important; border: none !important; }
            .no-print { display: none !important; }
            
            /* Paksa border tabel muncul saat print */
            table { border-collapse: collapse !important; width: 100% !important; }
            th, td { border: 1px solid black !important; color: black !important; padding: 12px !important; }
            .bg-gray-50, .bg-gray-100 { background-color: transparent !important; }
            tfoot { border-top: 2px solid black !important; }
        }
    </style>

    <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 pt-0 pb-12">
        
        {{-- Toolbar --}}
        <div class="no-print bg-white dark:bg-gray-800 p-4 rounded-b-xl shadow-sm mb-6 flex justify-between items-center border-x border-b border-gray-100 dark:border-gray-700 sticky top-0 z-20">
            <form action="{{ route('lra.labarugi') }}" method="GET" class="flex gap-3" id="filterForm">
                <select name="proyek_id" onchange="document.getElementById('filterForm').submit()" class="rounded-lg border-gray-300 text-sm font-bold dark:bg-gray-700 dark:text-white cursor-pointer">
                    <option value="">-- Pilih Proyek --</option>
                    @foreach ($listProyek as $p)
                        <option value="{{ $p->id_proyek }}" {{ $selectedProyek == $p->id_proyek ? 'selected' : '' }}>
                            {{ $p->nama }}
                        </option>
                    @endforeach
                </select>
            </form>
            @if($data)
                <div class="flex gap-2">
                    <button onclick="extractExcel()" class="bg-emerald-600 text-white px-4 py-2 rounded-lg text-xs font-bold hover:bg-emerald-700 transition flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        EXCEL
                    </button>
                    <button onclick="window.print()" class="bg-gray-800 text-white px-4 py-2 rounded-lg text-xs font-bold hover:bg-black transition flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                        PRINT
                    </button>
                </div>
            @endif
        </div>

        @if($data)
        <div id="printArea" class="bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-2xl shadow-sm overflow-hidden">
            <div class="p-8 text-center border-b border-gray-100 dark:border-gray-800">
                <h1 class="text-2xl font-black uppercase dark:text-white tracking-tighter">Analisis Laba Rugi Proyek</h1>
                <p class="text-indigo-600 font-bold mt-1 text-lg uppercase">{{ $data->proyek->nama }}</p>
            </div>

            <div class="p-8">
                <table id="labarugiTable" class="w-full text-sm">
                    <thead>
                        <tr class="border-b-2 border-black dark:border-gray-600 bg-gray-50 dark:bg-gray-800">
                            <th class="p-4 text-left uppercase tracking-wider font-bold">Keterangan Analisis</th>
                            <th class="p-4 text-right uppercase tracking-wider font-bold w-64">Nilai (IDR)</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                        {{-- SEKSI PENDAPATAN --}}
                        <tr class="bg-gray-50/50 dark:bg-gray-800/50">
                            <td class="py-4 px-4 font-bold text-xs uppercase tracking-widest text-gray-400" colspan="2">A. Pendapatan & Target Laba</td>
                        </tr>
                        <tr>
                            <td class="py-3 px-8 italic font-medium">Nilai Kontrak Proyek</td>
                            <td class="py-3 px-4 text-right font-bold text-gray-900 dark:text-white">Rp {{ number_format($data->nilai_kontrak, 0, ',', '.') }}</td>
                        </tr>
                        <tr>
                            <td class="py-3 px-8 italic font-medium text-emerald-600">Target Laba ({{ $data->target_laba_persen }}%)</td>
                            <td class="py-3 px-4 text-right text-emerald-600 font-black italic">Rp {{ number_format($data->nominal_target_laba, 0, ',', '.') }}</td>
                        </tr>

                        {{-- SEKSI BIAYA --}}
                        <tr class="bg-gray-50/50 dark:bg-gray-800/50">
                            <td class="py-4 px-4 font-bold text-xs uppercase tracking-widest text-gray-400" colspan="2">B. Beban & Biaya Lapangan</td>
                        </tr>
                        <tr>
                            <td class="py-3 px-8 italic font-medium">Anggaran Biaya (Plafon)</td>
                            <td class="py-3 px-4 text-right font-medium">Rp {{ number_format($data->anggaran_biaya, 0, ',', '.') }}</td>
                        </tr>
                        <tr>
                            <td class="py-3 px-8 italic font-medium text-rose-600">Realisasi Pengeluaran (Actual LRA)</td>
                            <td class="py-3 px-4 text-right text-rose-600 font-bold">(Rp {{ number_format($data->realisasi_biaya, 0, ',', '.') }})</td>
                        </tr>
                        <tr class="bg-indigo-50/30 dark:bg-indigo-900/10">
                            <td class="py-3 px-8 font-bold italic text-indigo-600">Efisiensi / (Pemborosan) Biaya</td>
                            <td class="py-3 px-4 text-right font-black {{ $data->efisiensi_biaya < 0 ? 'text-rose-600' : 'text-emerald-600' }}">
                                Rp {{ number_format($data->efisiensi_biaya, 0, ',', '.') }}
                            </td>
                        </tr>

                        {{-- GRAND TOTAL --}}
                    </tbody>
                    <tfoot>
                        <tr class="bg-gray-900 text-white dark:bg-white dark:text-black">
                            <td class="p-6 text-lg font-black uppercase tracking-tighter">Estimasi Laba Akhir Proyek</td>
                            <td class="p-6 text-right text-2xl font-black tracking-tight">
                                Rp {{ number_format($data->total_laba_akhir, 0, ',', '.') }}
                            </td>
                        </tr>
                    </tfoot>
                </table>

                <div class="mt-8 p-6 bg-indigo-50 dark:bg-indigo-900/20 rounded-2xl border border-indigo-100 dark:border-indigo-800 no-print">
                    <div class="flex justify-between items-center">
                        <span class="text-sm font-bold text-indigo-900 dark:text-indigo-300 uppercase tracking-widest">Persentase Laba Akhir</span>
                        <span class="text-2xl font-black text-indigo-700 dark:text-indigo-400">{{ round($data->persentase_laba_akhir, 2) }}%</span>
                    </div>
                    <p class="text-[10px] text-indigo-400 mt-1 italic text-center uppercase tracking-widest">Original Target: {{ $data->target_laba_persen }}%</p>
                </div>

                {{-- Tanda Tangan (Hanya Muncul saat Print) --}}
                <div class="hidden print:grid grid-cols-2 gap-20 mt-16 text-center">
                    <div>
                        <p class="font-bold uppercase text-xs">Dibuat Oleh,</p>
                        <div class="mt-20 border-b border-black w-48 mx-auto"></div>
                        <p class="mt-2 text-[10px] uppercase font-bold text-gray-500 italic">Project Finance</p>
                    </div>
                    <div>
                        <p class="font-bold uppercase text-xs">Disetujui Oleh,</p>
                        <div class="mt-20 border-b border-black w-48 mx-auto"></div>
                        <p class="mt-2 text-[10px] uppercase font-bold text-gray-500 italic">Manajer Operasional</p>
                    </div>
                </div>
            </div>
        </div>
        @else
            <div class="py-20 text-center bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-dashed border-gray-300">
                <svg class="w-16 h-16 mx-auto text-gray-200 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                <p class="text-gray-400 font-bold uppercase tracking-widest text-xs">Silakan pilih proyek melalui menu di atas.</p>
            </div>
        @endif
    </div>

    @if (session('error'))
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        Swal.fire({
            icon: 'error',
            title: 'Sistem Error',
            text: "{{ session('error') }}",
            confirmButtonColor: '#4f46e5'
        });
    </script>
    @endif
</x-app-layout>