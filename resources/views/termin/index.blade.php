<x-app-layout>
    @section('title', 'Data Termin')

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Daftar Termin Proyek') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 shadow-xl rounded-2xl overflow-hidden border border-gray-100 dark:border-gray-700">

                <div class="p-6 border-b border-gray-100 dark:border-gray-700 flex justify-between items-center bg-gray-50/50 dark:bg-gray-900/20">
                    <div>
                        <h3 class="text-lg font-bold text-gray-800 dark:text-white">Jadwal Penagihan Proyek</h3>
                    </div>
                </div>

                <div class="p-6">
                    <table id="terminTable" class="w-full cell-border stripe hover">
                        <thead>
                            <tr class="text-left text-gray-500 uppercase text-xs tracking-wider">
                                <th>Proyek</th>
                                <th>Tipe Termin</th>
                                <th>Progress</th>
                                <th>%</th>
                                <th>Nominal Plan</th>
                                <th>Due Date</th>
                                <th>Status Bayar</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                            <tr class="bg-gray-50 dark:bg-gray-900/50 filter-row">
                                <th class="p-2"><input type="text" placeholder="Cari Proyek..." class="w-full text-[10px] rounded-lg border-gray-200 dark:bg-gray-800 dark:border-gray-700"></th>
                                <th class="p-2"><input type="text" placeholder="Filter Tipe" class="w-full text-[10px] rounded-lg border-gray-200 dark:bg-gray-800 dark:border-gray-700"></th>
                                <th class="p-2"></th>
                                <th class="p-2"></th>
                                <th class="p-2"></th>
                                <th class="p-2"></th>
                                <th class="p-2">
                                    <select id="filterStatus" class="w-full text-[10px] rounded-lg border-gray-200 dark:bg-gray-800 dark:border-gray-700">
                                        <option value="">Semua Status</option>
                                        <option value="Lunas">Lunas</option>
                                        <option value="Belum Dibayar">Belum Dibayar</option>
                                        <option value="Telat">Telat</option>
                                    </select>
                                </th>
                                <th class="p-2"></th>
                            </tr>
                        </thead>
                        <tbody class="text-sm text-gray-600 dark:text-gray-300">
                            @foreach ($termins as $t)
                                @php
                                    $dbStatus = strtolower($t->status_pembayaran);
                                    $isLunas = ($dbStatus === 'lunas');
                                    
                                    $dueDate = \Carbon\Carbon::parse($t->due_date);
                                    $isOverdue = !$isLunas && now()->greaterThan($dueDate);
                                    
                                    // Tentukan label pencarian
                                    if ($isLunas) {
                                        $label = 'Lunas';
                                        $class = 'bg-green-100 text-green-700 border-green-200';
                                    } elseif ($isOverdue) {
                                        $label = 'Telat';
                                        $class = 'bg-rose-100 text-rose-700 border-rose-200 animate-pulse';
                                    } else {
                                        $label = 'Belum Dibayar';
                                        $class = 'bg-amber-100 text-amber-700 border-amber-200';
                                    }
                                @endphp
                                <tr>
                                    <td class="font-bold text-gray-800 dark:text-white">{{ $t->nama_proyek }}</td>
                                    <td>
                                        <span class="px-2 py-1 rounded bg-blue-50 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400 text-[10px] font-bold uppercase tracking-tight border border-blue-100 dark:border-blue-800">
                                            {{ $t->nama_termin }}
                                        </span>
                                    </td>
                                    <td class="text-xs text-gray-500">{{ $t->progress_keterangan ?? '-' }}</td>
                                    <td class="font-bold text-indigo-600">{{ $t->persentase }}%</td>
                                    <td class="font-semibold text-emerald-600 text-right">
                                        Rp {{ number_format($t->nominal, 0, ',', '.') }}
                                    </td>
                                    <td class="font-medium {{ $isOverdue ? 'text-rose-600' : '' }}">
                                        {{ $dueDate->format('d/m/Y') }}
                                    </td>
                                    {{-- KUNCI FILTER: data-search --}}
                                    <td data-search="{{ $label }}">
                                        <span class="px-2 py-1 rounded text-[10px] font-bold uppercase border {{ $class }}">
                                            {{ $label }}
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <div class="flex justify-center gap-2">
                                            @if(!$isLunas)
                                                <a href="{{ route('termin.edit', $t->id_termin_proyek) }}"
                                                    class="p-2 text-indigo-600 hover:bg-indigo-50 dark:hover:bg-indigo-900/20 rounded-lg transition">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                    </svg>
                                                </a>
                                            @else
                                                <span class="p-2 text-gray-400 opacity-50" title="Data lunas tidak dapat diubah">
                                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd" /></svg>
                                                </span>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <style>
        .filter-row th { background-image: none !important; cursor: default !important; border-bottom: 1px solid #e5e7eb !important; }
        .dark .filter-row th { border-bottom: 1px solid #374151 !important; }
        table.dataTable thead th { background-color: #f9fafb; border-bottom: 1px solid #f3f4f6 !important; }
        .dark table.dataTable thead th { background-color: #111827; border-bottom: 1px solid #374151 !important; color: #9ca3af; }
    </style>

    <script>
        $(document).ready(function() {
            var table = $('#terminTable').DataTable({
                responsive: true,
                "dom": '<"flex flex-col md:flex-row justify-between items-center mb-4 gap-4"l>t<"flex flex-col md:flex-row justify-between items-center mt-4 gap-4"ip>',
                "orderCellsTop": true,
            });

            // Filter Proyek (Input Text Kolom 0)
            $('.filter-row th:eq(0) input').on('input', function() {
                table.column(0).search(this.value).draw();
            });

            // Filter Tipe (Input Text Kolom 1)
            $('.filter-row th:eq(1) input').on('input', function() {
                table.column(1).search(this.value).draw();
            });

            // Filter Status (Select Kolom 4)
            $('#filterStatus').on('change', function() {
                var val = $(this).val();
                // Kita gunakan search tanpa regex strict dulu untuk tes
                table.column(4).search(val).draw();
            });
        });

        // SweetAlert2 (Gaya AI Wit)
        @if (session('success'))
            Swal.fire({ icon: 'success', title: 'Sukses!', text: "{{ session('success') }}", timer: 2500, showConfirmButton: false });
        @endif
        @if (session('error') || $errors->any())
            Swal.fire({ 
                icon: 'error', 
                title: 'Error!', 
                text: "{{ session('error') ?? 'Ada yang nggak beres sama datanya.' }}",
                confirmButtonColor: '#4f46e5' 
            });
        @endif
    </script>
</x-app-layout>