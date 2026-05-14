<x-app-layout>
    @section('title', 'Laporan LRA')

    <script>
        function extractExcel() {
            let table = document.querySelector("#reportTable");
            let html = `
                <style>
                    table { border-collapse: collapse; width: 100%; }
                    th, td { border: 1px solid black; padding: 10px; text-align: left; font-family: Arial; }
                    .text-right { text-align: right; }
                    .text-center { text-align: center; }
                    .font-bold { font-weight: bold; }
                </style>
                ${table.outerHTML}
            `;
            let blob = new Blob([html], { type: 'application/vnd.ms-excel' });
            let url = URL.createObjectURL(blob);
            let a = document.createElement('a');
            a.href = url;
            a.download = 'Laporan_Realisasi_Anggaran.xls';
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
            
            /* Paksa border muncul saat print */
            table { border-collapse: collapse !important; width: 100% !important; margin-top: 20px; }
            th, td { border: 1px solid black !important; padding: 10px !important; color: black !important; }
            .bg-gray-100, .bg-gray-50 { background-color: #f3f4f6 !important; -webkit-print-color-adjust: exact; }
            tfoot { border-top: 2px solid black !important; }
        }
    </style>

    <div class="pt-0 pb-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            {{-- Toolbar --}}
            <div class="no-print bg-white dark:bg-gray-800 p-4 rounded-b-xl shadow-sm border-x border-b border-gray-100 dark:border-gray-700 mb-6 flex justify-between items-center sticky top-0 z-20">
                <form action="{{ route('lra.laporan') }}" method="GET" class="flex gap-3" id="filterForm">
                    <select name="proyek_id" onchange="document.getElementById('filterForm').submit()"
                        class="rounded-lg border-gray-300 text-sm focus:ring-indigo-500 font-bold dark:bg-gray-700 dark:text-white cursor-pointer">
                        <option value="">-- Silahkan Pilih Proyek --</option>
                        @foreach ($listProyek as $p)
                            <option value="{{ $p->id_proyek }}" {{ $selectedProyek == $p->id_proyek ? 'selected' : '' }}>
                                {{ $p->nama }}
                            </option>
                        @endforeach
                    </select>
                </form>

                @if ($selectedProyek && count($dataLra) > 0)
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
                @endif
            </div>

            {{-- Print Area --}}
            <div id="printArea" class="bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-700 shadow-sm rounded-2xl overflow-hidden">

                {{-- Header Laporan --}}
                <div class="p-10 text-center border-b border-gray-100 dark:border-gray-800">
                    <h1 class="text-2xl font-black uppercase dark:text-white tracking-tighter">Laporan Realisasi Anggaran</h1>
                    @if ($selectedProyek)
                        @php $proyekDetail = $listProyek->where('id_proyek', $selectedProyek)->first(); @endphp
                        <p class="text-lg uppercase mt-1 text-indigo-600 font-bold tracking-wide">{{ $proyekDetail->nama }}</p>
                        <div class="inline-block mt-2 px-4 py-1">
                            <p class="text-xs font-bold uppercase">Nilai Kontrak: Rp {{ number_format($proyekDetail->nilai_kontrak, 0, ',', '.') }}</p>
                        </div>
                    @else
                        <p class="text-md uppercase mt-1 text-gray-400 font-medium italic">Harap Pilih Proyek Terlebih Dahulu</p>
                    @endif
                </div>

                <div class="p-8">
                    @if (!$selectedProyek)
                        <div class="py-20 text-center border-2 border-dashed border-gray-200 dark:border-gray-700 rounded-2xl">
                            <svg class="w-16 h-16 mx-auto text-gray-200 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            <p class="text-gray-400 font-bold uppercase tracking-widest text-xs">Silakan pilih proyek melalui menu di atas.</p>
                        </div>
                    @else
                        <table id="reportTable" class="w-full text-sm dark:text-gray-300">
                            <thead>
                                <tr class="bg-gray-50 dark:bg-gray-800 font-bold uppercase text-gray-700 dark:text-gray-200 border-b-2 border-black dark:border-gray-600">
                                    <th class="p-4 text-left">Keterangan Anggaran</th>
                                    <th class="p-4 text-right">Budget (Rp)</th>
                                    <th class="p-4 text-right">Realisasi (Rp)</th>
                                    <th class="p-4 text-center">Penyerapan</th>
                                    <th class="p-4 text-right">Sisa Anggaran</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                                @php
                                    $grandTotalAnggaran = 0;
                                    $grandTotalRealisasi = 0;
                                @endphp

                                @forelse ($dataLra as $row)
                                    @php
                                        $grandTotalAnggaran += $row->anggaran;
                                        $grandTotalRealisasi += $row->realisasi;
                                        $persenSerap = $row->anggaran > 0 ? ($row->realisasi / $row->anggaran) * 100 : 0;
                                        $isOver = $row->selisih < 0;
                                    @endphp
                                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/50 transition">
                                        <td class="p-4 font-semibold text-gray-900 dark:text-white border-l-4 {{ $isOver ? 'border-rose-500' : 'border-indigo-500' }}">
                                            {{ $row->keterangan }}
                                            <div class="text-[10px] text-gray-400 font-normal italic">Alokasi Plafon: {{ $row->persentase }}%</div>
                                        </td>
                                        <td class="p-4 text-right bg-gray-50/30">Rp {{ number_format($row->anggaran, 0, ',', '.') }}</td>
                                        <td class="p-4 text-right font-bold {{ $isOver ? 'text-rose-600' : 'text-gray-900 dark:text-white' }}">
                                            Rp {{ number_format($row->realisasi, 0, ',', '.') }}
                                        </td>
                                        <td class="p-4 text-center">
                                            <div class="flex flex-col items-center">
                                                <span class="font-bold {{ $persenSerap > 100 ? 'text-rose-600' : 'text-indigo-600' }}">
                                                    {{ round($persenSerap, 1) }}%
                                                </span>
                                                <div class="w-24 bg-gray-200 rounded-full h-1 mt-1 dark:bg-gray-700 no-print">
                                                    <div class="h-1 rounded-full {{ $persenSerap > 100 ? 'bg-rose-500' : 'bg-indigo-500' }}"
                                                        style="width: {{ $persenSerap > 100 ? 100 : $persenSerap }}%">
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="p-4 text-right font-bold {{ $isOver ? 'text-rose-600' : 'text-emerald-600' }}">
                                            {{ $isOver ? '(' : '' }} Rp {{ number_format(abs($row->selisih), 0, ',', '.') }}{{ $isOver ? ')' : '' }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="p-10 text-center text-gray-400 italic">Data LRA tidak ditemukan.</td>
                                    </tr>
                                @endforelse
                            </tbody>

                            <tfoot class="bg-gray-900 text-white dark:bg-white dark:text-black font-black uppercase">
                                @php
                                    $totalPersenSerap = $grandTotalAnggaran > 0 ? ($grandTotalRealisasi / $grandTotalAnggaran) * 100 : 0;
                                    $totalSisa = $grandTotalAnggaran - $grandTotalRealisasi;
                                @endphp
                                <tr>
                                    <td class="p-5 text-lg tracking-tighter">Total Akumulasi</td>
                                    <td class="p-5 text-right">Rp {{ number_format($grandTotalAnggaran, 0, ',', '.') }}</td>
                                    <td class="p-5 text-right text-xl">Rp {{ number_format($grandTotalRealisasi, 0, ',', '.') }}</td>
                                    <td class="p-5 text-center">{{ round($totalPersenSerap, 1) }}%</td>
                                    <td class="p-5 text-right text-xl {{ $totalSisa < 0 ? 'text-rose-400' : 'text-emerald-400' }}">
                                        Rp {{ number_format($totalSisa, 0, ',', '.') }}
                                    </td>
                                </tr>
                            </tfoot>
                        </table>

                        {{-- Tanda Tangan (Hanya Muncul saat Print) --}}
                        <div class="hidden print:grid grid-cols-2 gap-20 mt-16 text-center">
                            <div>
                                <p class="font-bold uppercase text-xs">Dibuat Oleh,</p>
                                <div class="mt-20 border-b border-black w-48 mx-auto"></div>
                                <p class="mt-2 text-[10px] uppercase font-bold text-gray-500 italic">Admin Keuangan</p>
                            </div>
                            <div>
                                <p class="font-bold uppercase text-xs">Mengetahui,</p>
                                <div class="mt-20 border-b border-black w-48 mx-auto"></div>
                                <p class="mt-2 text-[10px] uppercase font-bold text-gray-500 italic">Manajer Proyek</p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- Script untuk Alert --}}
    @if (session('error'))
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        Swal.fire({
            icon: 'error',
            title: 'Gagal!',
            text: "{{ session('error') }}",
            confirmButtonColor: '#4f46e5'
        });
    </script>
    @endif
</x-app-layout>