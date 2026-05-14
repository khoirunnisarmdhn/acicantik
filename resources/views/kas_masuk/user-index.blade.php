<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Riwayat Kas Masuk') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div
                class="bg-white dark:bg-gray-800 shadow-xl rounded-2xl overflow-hidden border border-gray-100 dark:border-gray-700">

                <div class="p-6 border-b border-gray-100 flex justify-between items-center bg-gray-50/50">
                    <div>
                        <h3 class="text-lg font-bold text-gray-800 text-emerald-600">Data Penerimaan Kas</h3>
                        <p class="text-xs text-gray-500 font-medium">Monitoring seluruh dana masuk termin & umum</p>
                    </div>
                </div>

                <div class="p-6">
                    <table id="kasMasukTable" class="w-full cell-border stripe hover">
                        <thead>
                            <tr class="text-left text-gray-500 uppercase text-[10px] tracking-wider">
                                <th>No. Form</th>
                                <th>Tanggal</th>
                                <th>Kategori & Proyek</th>
                                <th>Metode</th>
                                <th>Nominal</th>
                                <th class="text-center">Detail</th>
                            </tr>
                        </thead>
                        <tbody class="text-sm">
                            @foreach ($kas_masuks as $k)
                                <tr>
                                    <td class="font-mono font-bold text-indigo-600">{{ $k->no_form }}</td>
                                    <td>{{ \Carbon\Carbon::parse($k->tanggal)->format('d/m/Y') }}</td>
                                    <td>
                                        <div class="font-bold text-gray-800">{{ $k->nama_kategori }}</div>
                                        <div class="text-[10px] text-gray-400 uppercase leading-none italic">
                                            {{ $k->nama_proyek ?? 'Penerimaan Umum' }}
                                        </div>
                                    </td>
                                    <td>
                                        <span
                                            class="px-2 py-0.5 rounded text-[10px] font-bold uppercase {{ $k->nama_metode_bayar == 'Bank' ? 'bg-blue-100 text-blue-700' : 'bg-amber-100 text-amber-700' }}">
                                            {{ $k->nama_metode_bayar }}
                                        </span>
                                    </td>
                                    <td class="text-emerald-600 font-black">
                                        Rp {{ number_format($k->nominal, 0, ',', '.') }}
                                    </td>
                                    <td class="text-center">
                                        <div class="flex justify-center">
                                            <a href="{{ route('kas-masuk.show', $k->id_kas) }}"
                                                class="p-2 text-gray-500 hover:bg-gray-100 hover:text-emerald-600 rounded-lg transition"
                                                title="Lihat Detail">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                </svg>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot class="bg-gray-50">
                            <tr>
                                <th><input type="text" placeholder="No Form"
                                        class="w-full text-[10px] rounded border-gray-200 p-1"></th>
                                <th><input type="text" placeholder="Tanggal"
                                        class="w-full text-[10px] rounded border-gray-200 p-1"></th>
                                <th><input type="text" placeholder="Kategori/Proyek"
                                        class="w-full text-[10px] rounded border-gray-200 p-1"></th>
                                <th><input type="text" placeholder="Metode"
                                        class="w-full text-[10px] rounded border-gray-200 p-1"></th>
                                <th class="text-right">Total Page:</th>
                                <th></th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <style>
        .dataTables_wrapper .dataTables_length select {
            border-radius: 0.75rem;
            font-size: 0.75rem;
            border-color: #f3f4f6;
        }

        .dataTables_wrapper .dataTables_filter input {
            border-radius: 0.75rem;
            border-color: #f3f4f6;
            padding: 0.5rem 1rem;
        }

        table.dataTable.cell-border tbody td {
            border-top: 1px solid #f9fafb !important;
            border-left: none !important;
            border-right: none !important;
            padding: 12px 10px;
        }

        table.dataTable thead th {
            border-bottom: 2px solid #f3f4f6 !important;
        }
    </style>

    <script>
        $(document).ready(function() {
            var table = $('#kasMasukTable').DataTable({
                responsive: true,
                dom: '<"flex flex-col md:flex-row justify-between items-center mb-4 gap-4"lfr>t<"flex flex-col md:flex-row justify-between items-center mt-4 gap-4"ip>',
                language: {
                    search: "_INPUT_",
                    searchPlaceholder: "Cari data..."
                }
            });

            // Filter per kolom
            table.columns().every(function() {
                var that = this;
                $('input', this.footer()).on('keyup change', function() {
                    if (that.search() !== this.value) {
                        that.search(this.value).draw();
                    }
                });
            });
        });

        // SweetAlert2 hanya untuk Notifikasi Umum (Welcome/Maintenance)
        // Fungsi delete dihapus total
        document.addEventListener('DOMContentLoaded', function() {
            @if (session('success'))
                Swal.fire({
                    icon: 'success',
                    title: 'Info',
                    text: "{{ session('success') }}",
                    timer: 2000,
                    showConfirmButton: false
                });
            @endif

            @if (session('error'))
                Swal.fire({
                    icon: 'error',
                    title: 'Akses Ditolak / Error',
                    text: "{!! addslashes(session('error')) !!}",
                    confirmButtonColor: '#e11d48'
                });
            @endif
        });
    </script>
</x-app-layout>
