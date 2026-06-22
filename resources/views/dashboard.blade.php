<x-app-layout>
    @section('title', 'Dashboard')

    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-bold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Dashboard') }}
            </h2>
            <span class="text-sm font-semibold text-gray-600 dark:text-gray-300">{{ now()->translatedFormat('d F Y') }}</span>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <div
                    class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="text-sm text-gray-500 font-medium">Total Proyek</p>
                            <h3 class="text-2xl font-bold mt-1 text-indigo-600">{{ $totalProyek }}</h3>
                        </div>
                        <div class="p-2 bg-indigo-50 dark:bg-indigo-900/20 rounded-lg text-indigo-600">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4">
                                </path>
                            </svg>
                        </div>
                    </div>
                    <p class="text-xs text-green-500 mt-4 flex items-center font-medium">
                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7 7 7">
                            </path>
                        </svg>
                        {{ $proyekAktif }} Proyek Aktif
                    </p>
                </div>

                <div
                    class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="text-sm text-gray-500 font-medium">Saldo Kas & Bank</p>
                            <h3 class="text-xl font-bold mt-1 text-emerald-600">
                                Rp {{ number_format($saldoKas, 0, ',', '.') }}
                            </h3>
                        </div>
                        <div class="p-2 bg-emerald-50 dark:bg-emerald-900/20 rounded-lg text-emerald-600">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                                </path>
                            </svg>
                        </div>
                    </div>
                    <p class="text-xs text-gray-400 mt-4 font-medium italic">Total seluruh kas masuk dikurangi kas keluar (semua proyek, seluruh waktu)</p>
                </div>

                <div
                    class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="text-sm text-gray-500 font-medium">Masuk ({{ now()->translatedFormat('M') }})</p>
                            <h3 class="text-xl font-bold mt-1 text-blue-600">Rp
                                {{ number_format($kasMasukBulanIni, 0, ',', '.') }}</h3>
                        </div>
                        <div class="p-2 bg-blue-50 dark:bg-blue-900/20 rounded-lg text-blue-600">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M7 11l5-5m0 0l5 5m-5-5v12"></path>
                            </svg>
                        </div>
                    </div>
                    <p class="text-xs text-blue-400 mt-4">Penerimaan bulan berjalan</p>
                </div>

                <div
                    class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="text-sm text-gray-500 font-medium">Keluar ({{ now()->translatedFormat('M') }})</p>
                            <h3 class="text-xl font-bold mt-1 text-rose-600">Rp
                                {{ number_format($kasKeluarBulanIni, 0, ',', '.') }}</h3>
                        </div>
                        <div class="p-2 bg-rose-50 dark:bg-rose-900/20 rounded-lg text-rose-600">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17 13l-5 5m0 0l-5-5m5 5V6"></path>
                            </svg>
                        </div>
                    </div>
                    <p class="text-xs text-rose-400 mt-4">Pengeluaran bulan berjalan</p>
                </div>
            </div>

            <div
                class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
                <div class="p-6 border-b border-gray-100 dark:border-gray-700">
                    <h3 class="font-bold text-gray-800 dark:text-white text-lg">Aktivitas Kas Terbaru</h3>
                    <p class="text-xs text-gray-500">5 Transaksi terakhir dari arus kas masuk dan keluar</p>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm">
                        <thead class="bg-gray-50 dark:bg-gray-900/50 text-gray-500 dark:text-gray-400">
                            <tr>
                                <th class="px-6 py-4 font-semibold uppercase text-xs">No. Form / Tanggal</th>
                                <th class="px-6 py-4 font-semibold uppercase text-xs">Informasi Transaksi</th>
                                <th class="px-6 py-4 font-semibold uppercase text-xs">Proyek</th>
                                <th class="px-6 py-4 font-semibold uppercase text-xs text-right">Nominal</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 dark:divide-gray-700 text-gray-600 dark:text-gray-300">
                            @forelse($transaksiTerbaru as $trx)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/30 transition">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="font-bold text-gray-900 dark:text-white">{{ $trx->no_form }}</div>
                                        <div class="text-xs text-gray-500">
                                            {{ \Carbon\Carbon::parse($trx->tanggal)->format('d M Y') }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-2 mb-1">
                                            @if ($trx->tipe == 'masuk')
                                                <span
                                                    class="px-2 py-0.5 text-[10px] bg-emerald-100 text-emerald-700 dark:bg-emerald-900/40 dark:text-emerald-400 rounded-full font-bold uppercase border border-emerald-200 dark:border-emerald-800">Masuk</span>
                                                <span class="text-xs font-medium text-gray-400">Kategori:
                                                    {{ $trx->nama_kategori }}</span>
                                            @else
                                                <span
                                                    class="px-2 py-0.5 text-[10px] bg-rose-100 text-rose-700 dark:bg-rose-900/40 dark:text-rose-400 rounded-full font-bold uppercase border border-rose-200 dark:border-rose-800">Keluar</span>
                                                <span class="text-xs font-medium text-gray-400">Vendor:
                                                    {{ $trx->nama_vendor ?? '-' }}</span>
                                            @endif
                                        </div>
                                        <div class="text-sm italic text-gray-500 line-clamp-1">"{{ $trx->keterangan }}"
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="font-medium text-gray-700 dark:text-gray-200">
                                            {{ $trx->nama_proyek ?? 'Umum / Non-Proyek' }}</div>
                                        <div class="text-[10px] text-gray-400 uppercase tracking-wider">
                                            {{ $trx->nama_metode_bayar }}</div>
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        <span
                                            class="text-base font-bold {{ $trx->tipe == 'masuk' ? 'text-emerald-600' : 'text-rose-600' }}">
                                            {{ $trx->tipe == 'masuk' ? '+' : '-' }} Rp
                                            {{ number_format($trx->nominal, 0, ',', '.') }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-6 py-12 text-center text-gray-400 italic">
                                        Belum ada data transaksi kas.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
