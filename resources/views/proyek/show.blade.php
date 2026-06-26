<x-app-layout>
    @section('title', 'Detail Proyek')

    <div class="py-12">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- Header Info Proyek --}}
            <div class="bg-white dark:bg-gray-800 shadow-xl rounded-2xl overflow-hidden border border-gray-100 dark:border-gray-700">
                <div class="bg-indigo-600 px-8 py-6 text-white flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                    <div>
                        <h1 class="text-2xl font-black tracking-tight">{{ $proyek->nama }}</h1>
                        <p class="text-indigo-200 text-sm mt-1">{{ $proyek->nama_pemberi }} ({{ $proyek->jenis }})</p>
                    </div>
                    <div>
                        <a href="{{ route('proyek.rab', $proyek->id_proyek) }}" target="_blank"
                            class="inline-flex items-center gap-2 transition"
                            style="background-color: #fbbf24; color: #111827; padding: 14px 28px; font-weight: 800; border-radius: 12px; font-size: 16px; box-shadow: 0 10px 15px -3px rgba(251, 191, 36, 0.3), 0 4px 6px -4px rgba(251, 191, 36, 0.3); text-decoration: none;"
                            onmouseover="this.style.backgroundColor='#f59e0b'; this.style.transform='translateY(-1px)';"
                            onmouseout="this.style.backgroundColor='#fbbf24'; this.style.transform='translateY(0)';">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="stroke: #111827; stroke-width: 2.5; width: 20px; height: 20px;">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                            </svg>
                            <span style="color: #111827; font-weight: 900;">Cetak RAB Penawaran</span>
                        </a>
                    </div>
                </div>

                <div class="p-8 grid grid-cols-2 md:grid-cols-4 gap-6">
                    <div>
                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Nilai Kontrak</p>
                        <p class="text-lg font-black text-gray-800 dark:text-white mt-1">Rp {{ number_format($proyek->nilai_kontrak, 0, ',', '.') }}</p>
                    </div>
                    <div>
                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Target Laba</p>
                        <p class="text-lg font-black text-emerald-600 mt-1">{{ $proyek->target_laba }}%</p>
                    </div>
                    <div>
                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Periode</p>
                        <p class="text-sm font-bold text-gray-700 dark:text-gray-300 mt-1">
                            {{ \Carbon\Carbon::parse($proyek->tanggal_mulai)->format('d/m/Y') }} —
                            {{ \Carbon\Carbon::parse($proyek->tanggal_selesai)->format('d/m/Y') }}
                        </p>
                    </div>
                    <div>
                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Status</p>
                        <span class="inline-block mt-1 px-3 py-1 rounded-full text-xs font-bold uppercase
                            {{ strtolower($proyek->status) == 'aktif' ? 'bg-emerald-100 text-emerald-700' : 'bg-gray-200 text-gray-600' }}">
                            {{ $proyek->status }}
                        </span>
                    </div>
                </div>

                {{-- Ringkasan Keuangan --}}
                <div class="px-8 pb-8 grid grid-cols-3 gap-4">
                    <div class="bg-blue-50 rounded-xl p-4 text-center">
                        <p class="text-[10px] font-bold text-blue-500 uppercase">Total Kas Masuk</p>
                        <p class="text-xl font-black text-blue-700 mt-1">Rp {{ number_format($totalMasuk, 0, ',', '.') }}</p>
                    </div>
                    <div class="bg-rose-50 rounded-xl p-4 text-center">
                        <p class="text-[10px] font-bold text-rose-500 uppercase">Total Kas Keluar</p>
                        <p class="text-xl font-black text-rose-700 mt-1">Rp {{ number_format($totalKeluar, 0, ',', '.') }}</p>
                    </div>
                    <div class="bg-emerald-50 rounded-xl p-4 text-center">
                        <p class="text-[10px] font-bold text-emerald-500 uppercase">Saldo Proyek</p>
                        <p class="text-xl font-black text-emerald-700 mt-1">Rp {{ number_format($totalMasuk - $totalKeluar, 0, ',', '.') }}</p>
                    </div>
                </div>
            </div>

            {{-- Daftar Termin --}}
            <div class="bg-white dark:bg-gray-800 shadow-xl rounded-2xl overflow-hidden border border-gray-100 dark:border-gray-700">
                <div class="p-6 border-b border-gray-100 dark:border-gray-700 bg-gray-50/50 dark:bg-gray-900/20">
                    <h3 class="text-lg font-bold text-gray-800 dark:text-white">Jadwal Termin Pembayaran</h3>
                </div>
                <div class="p-6">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="text-left text-[10px] uppercase tracking-wider text-gray-400 border-b-2 border-gray-100">
                                <th class="pb-3">Tipe Termin</th>
                                <th class="pb-3 text-center">%</th>
                                <th class="pb-3 text-right">Nominal</th>
                                <th class="pb-3">Due Date</th>
                                <th class="pb-3 text-center">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            @foreach($termins as $t)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/30 transition">
                                <td class="py-3 font-bold text-gray-700 dark:text-gray-300">{{ $t->nama_termin }}</td>
                                <td class="py-3 text-center text-gray-500">{{ number_format($t->persentase, 0) }}%</td>
                                <td class="py-3 text-right font-bold">Rp {{ number_format($t->nominal, 0, ',', '.') }}</td>
                                <td class="py-3 text-gray-500">{{ $t->due_date ? \Carbon\Carbon::parse($t->due_date)->format('d/m/Y') : '-' }}</td>
                                <td class="py-3 text-center">
                                    <span class="px-2 py-1 rounded-full text-[10px] font-bold uppercase
                                        {{ $t->status_pembayaran == 'Lunas' ? 'bg-emerald-100 text-emerald-700' : 'bg-amber-100 text-amber-700' }}">
                                        {{ $t->status_pembayaran }}
                                    </span>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Daftar Kas Masuk --}}
            @if($kasMasuk->count() > 0)
            <div class="bg-white dark:bg-gray-800 shadow-xl rounded-2xl overflow-hidden border border-gray-100 dark:border-gray-700">
                <div class="p-6 border-b border-gray-100 dark:border-gray-700 bg-gray-50/50 dark:bg-gray-900/20">
                    <h3 class="text-lg font-bold text-gray-800 dark:text-white">Riwayat Kas Masuk</h3>
                </div>
                <div class="p-6">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="text-left text-[10px] uppercase tracking-wider text-gray-400 border-b-2 border-gray-100">
                                <th class="pb-3">No. Form</th>
                                <th class="pb-3">Tanggal</th>
                                <th class="pb-3">Kategori</th>
                                <th class="pb-3 text-right">Nominal</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            @foreach($kasMasuk as $km)
                            <tr class="hover:bg-gray-50 transition">
                                <td class="py-3 font-mono font-bold text-blue-600">{{ $km->no_form }}</td>
                                <td class="py-3 text-gray-500">{{ \Carbon\Carbon::parse($km->tanggal)->format('d/m/Y') }}</td>
                                <td class="py-3">{{ $km->nama_kategori }}</td>
                                <td class="py-3 text-right font-bold text-blue-600">Rp {{ number_format($km->nominal, 0, ',', '.') }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr class="border-t-2 border-gray-200">
                                <td colspan="3" class="py-3 font-bold text-right">Total Masuk:</td>
                                <td class="py-3 text-right font-black text-blue-700">Rp {{ number_format($totalMasuk, 0, ',', '.') }}</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
            @endif

            {{-- Daftar Kas Keluar --}}
            @if($kasKeluar->count() > 0)
            <div class="bg-white dark:bg-gray-800 shadow-xl rounded-2xl overflow-hidden border border-gray-100 dark:border-gray-700">
                <div class="p-6 border-b border-gray-100 dark:border-gray-700 bg-gray-50/50 dark:bg-gray-900/20">
                    <h3 class="text-lg font-bold text-gray-800 dark:text-white">Riwayat Kas Keluar</h3>
                </div>
                <div class="p-6">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="text-left text-[10px] uppercase tracking-wider text-gray-400 border-b-2 border-gray-100">
                                <th class="pb-3">No. Form</th>
                                <th class="pb-3">Tanggal</th>
                                <th class="pb-3">Kategori</th>
                                <th class="pb-3">Vendor</th>
                                <th class="pb-3 text-right">Nominal</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            @foreach($kasKeluar as $kk)
                            <tr class="hover:bg-gray-50 transition">
                                <td class="py-3 font-mono font-bold text-rose-600">{{ $kk->no_form }}</td>
                                <td class="py-3 text-gray-500">{{ \Carbon\Carbon::parse($kk->tanggal)->format('d/m/Y') }}</td>
                                <td class="py-3">{{ $kk->nama_kategori }}</td>
                                <td class="py-3 text-gray-500">{{ $kk->nama_vendor ?? '-' }}</td>
                                <td class="py-3 text-right font-bold text-rose-600">Rp {{ number_format($kk->nominal, 0, ',', '.') }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr class="border-t-2 border-gray-200">
                                <td colspan="4" class="py-3 font-bold text-right">Total Keluar:</td>
                                <td class="py-3 text-right font-black text-rose-700">Rp {{ number_format($totalKeluar, 0, ',', '.') }}</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
            @endif

            {{-- Tombol Kembali --}}
             <div class="flex justify-start">
                 <a href="{{ route('proyek.index') }}"
                     class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-gray-100 text-gray-600 font-bold hover:bg-gray-200 transition">
                     <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                     Back
                 </a>
             </div>
        </div>
    </div>
</x-app-layout>
