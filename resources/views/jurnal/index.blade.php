<x-app-layout>
    @section('title', 'Jurnal Umum')

    <script>
        function extractExcel() {
            let table = document.querySelector("#jurnalTable");
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
            a.download = 'Jurnal_Umum_{{ $bulan }}_{{ $tahun }}.xls';
            a.click();
        }
    </script>

    <style>
        main { padding-top: 0 !important; }
        
        @media print {
            body * { visibility: hidden; background: white !important; color: black !important; }
            #printArea, #printArea * { visibility: visible; }
            #printArea { position: absolute; left: 0; top: 0; width: 100%; margin: 0; padding: 0; box-shadow: none !important; border: none !important; }
            .no-print { display: none !important; }
            
            table { border-collapse: collapse !important; width: 100% !important; margin-top: 20px; }
            th, td { border: 1px solid black !important; padding: 8px !important; color: black !important; }
            .bg-gray-100, .bg-gray-50 { background-color: #f3f4f6 !important; -webkit-print-color-adjust: exact; }
            tfoot { font-weight: bold; border-top: 2px solid black !important; }
        }
    </style>

    <div class="pt-0 pb-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            {{-- Toolbar --}}
            <div class="no-print bg-white dark:bg-gray-800 p-4 rounded-b-xl shadow-sm border-x border-b border-gray-100 dark:border-gray-700 mb-6 flex flex-wrap items-end justify-between gap-4 sticky top-0 z-20">
                <form action="{{ route('jurnal.index') }}" method="GET" class="flex items-end gap-3" id="filterForm">
                    <div>
                        <label class="block text-[10px] font-bold text-indigo-500 uppercase mb-1 ml-1 tracking-widest">Bulan Periode</label>
                        <select name="bulan" onchange="document.getElementById('filterForm').submit()" 
                            class="rounded-lg border-gray-200 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 text-sm focus:ring-indigo-500 w-48 font-bold cursor-pointer">
                            @foreach (range(1, 12) as $m)
                                <option value="{{ sprintf('%02d', $m) }}" {{ $bulan == sprintf('%02d', $m) ? 'selected' : '' }}>
                                    {{ date('F', mktime(0, 0, 0, $m, 1)) }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold text-indigo-500 uppercase mb-1 ml-1 tracking-widest">Tahun</label>
                        <select name="tahun" onchange="document.getElementById('filterForm').submit()" 
                            class="rounded-lg border-gray-200 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 text-sm focus:ring-indigo-500 w-32 font-bold cursor-pointer">
                            @foreach (range(date('Y') - 5, date('Y') + 1) as $y)
                                <option value="{{ $y }}" {{ $tahun == $y ? 'selected' : '' }}>{{ $y }}</option>
                            @endforeach
                        </select>
                    </div>
                </form>

                <div class="flex gap-2">
                    <button onclick="extractExcel()" class="bg-emerald-600 text-white px-4 py-2 rounded-lg text-xs font-bold hover:bg-emerald-700 transition flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        EXCEL
                    </button>
                    <button onclick="window.print()" class="bg-gray-800 text-white px-4 py-2 rounded-lg text-xs font-bold hover:bg-black transition flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                        PRINT PDF
                    </button>
                </div>
            </div>

            {{-- Area Laporan --}}
            <div id="printArea" class="bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-700 shadow-sm rounded-2xl overflow-hidden">
                
                <div class="p-10 text-center border-b border-gray-100 dark:border-gray-800">
                    <h1 class="text-2xl font-black uppercase tracking-tighter dark:text-white">Buku Jurnal Umum</h1>
                    <p class="text-md uppercase mt-1 font-bold text-indigo-600">Laporan Transaksi Konstruksi</p>
                    <div class="mt-3 inline-block px-4 py-1">
                        <p class="text-xs font-bold uppercase tracking-widest">
                            Periode: {{ date('F', mktime(0, 0, 0, $bulan, 1)) }} {{ $tahun }}
                        </p>
                    </div>
                </div>

                <div class="p-8">
                    <table id="jurnalTable" class="w-full text-[13px] dark:text-gray-300">
                        <thead>
                            <tr class="bg-gray-50 dark:bg-gray-800 font-bold uppercase text-gray-700 dark:text-gray-200 border-b-2 border-black dark:border-gray-600">
                                <th class="p-4 text-left">Tanggal</th>
                                <th class="p-4 text-left">No. Ref</th>
                                <th class="p-4 text-left">Kode</th>
                                <th class="p-4 text-left">Keterangan Akun & Deskripsi</th>
                                <th class="p-4 text-right">Debit (Rp)</th>
                                <th class="p-4 text-right">Kredit (Rp)</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                            @forelse ($jurnals as $j)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/50 transition">
                                    <td class="p-4 text-gray-600 dark:text-gray-400">
                                        {{ \Carbon\Carbon::parse($j->tanggal)->format('d/m/y') }}
                                    </td>
                                    <td class="p-4 font-bold text-indigo-600">
                                        {{ $j->no_ref ?? '-' }}
                                    </td>
                                    <td class="p-4 font-mono text-xs">
                                        {{ $j->kode_akun }}
                                    </td>
                                    <td class="p-4">
                                        <div class="{{ $j->posisi_dr_cr == 'cr' ? 'ml-8 italic text-gray-600 dark:text-gray-400' : 'font-bold text-gray-900 dark:text-white' }}">
                                            {{ $j->nama_akun }}
                                        </div>
                                        @if($j->deskripsi)
                                            <div class="text-[10px] text-gray-400 italic mt-1 leading-tight">{{ $j->deskripsi }}</div>
                                        @endif
                                    </td>
                                    <td class="p-4 text-right font-mono font-semibold">
                                        {{ $j->posisi_dr_cr == 'dr' && $j->nominal > 0 ? number_format($j->nominal, 0, ',', '.') : '-' }}
                                    </td>
                                    <td class="p-4 text-right font-mono font-semibold">
                                        {{ $j->posisi_dr_cr == 'cr' && $j->nominal > 0 ? number_format($j->nominal, 0, ',', '.') : '-' }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="p-20 text-center text-gray-300 font-bold uppercase tracking-[0.2em]">
                                        Data jurnal tidak ditemukan.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                        <tfoot class="bg-gray-900 text-white dark:bg-white dark:text-black font-black uppercase">
                            <tr>
                                <td colspan="4" class="p-5 text-right tracking-tighter">Total Akumulasi</td>
                                <td class="p-5 text-right font-mono text-lg">
                                    {{ number_format($jurnals->where('posisi_dr_cr', 'dr')->sum('nominal'), 0, ',', '.') }}
                                </td>
                                <td class="p-5 text-right font-mono text-lg border-l border-gray-700 dark:border-gray-200">
                                    {{ number_format($jurnals->where('posisi_dr_cr', 'cr')->sum('nominal'), 0, ',', '.') }}
                                </td>
                            </tr>
                        </tfoot>
                    </table>

                    {{-- Footer Tanda Tangan --}}
                    <div class="hidden print:grid grid-cols-2 gap-20 mt-16 text-center">
                        <div>
                            <p class="font-bold uppercase text-xs">Dibuat Oleh,</p>
                            <div class="mt-20 border-b border-black w-48 mx-auto"></div>
                            <p class="mt-2 text-[10px] uppercase font-bold text-gray-500 italic">Staf Akuntansi</p>
                        </div>
                        <div>
                            <p class="font-bold uppercase text-xs">Disetujui Oleh,</p>
                            <div class="mt-20 border-b border-black w-48 mx-auto"></div>
                            <p class="mt-2 text-[10px] uppercase font-bold text-gray-500 italic">Manajer Keuangan</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- SweetAlert2 Integration --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            @if (session('error'))
                Swal.fire({
                    title: 'Sistem Error',
                    text: "{{ session('error') }}",
                    icon: 'error',
                    confirmButtonColor: '#ef4444'
                });
            @elseif ($jurnals->isEmpty())
                Swal.fire({
                    title: 'Data Kosong',
                    text: 'Tidak ada transaksi jurnal untuk periode ini.',
                    icon: 'info',
                    confirmButtonColor: '#4f46e5'
                });
            @endif
        });
    </script>
</x-app-layout>