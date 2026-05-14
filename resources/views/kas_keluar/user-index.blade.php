<x-app-layout>
    @section('title', 'Data Kas Keluar')

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Transaksi Kas Keluar') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 shadow-xl rounded-2xl overflow-hidden border border-gray-100 dark:border-gray-700">

                <div class="p-6 border-b border-gray-100 flex justify-between items-center bg-gray-50/50">
                    <div>
                        <h3 class="text-lg font-bold text-gray-800">Pengeluaran Kas</h3>
                        <p class="text-xs text-gray-500 font-medium">Monitoring dana keluar untuk operasional, vendor, & proyek</p>
                    </div>
                    <a href="{{ route('kas-keluar.create') }}"
                        class="bg-rose-600 hover:bg-rose-700 text-white px-4 py-2 rounded-xl text-sm font-semibold transition-all shadow-md shadow-rose-100 uppercase tracking-tighter">
                        + Input Kas Keluar
                    </a>
                </div>

                <div class="p-6">
                    <table id="kasKeluarTable" class="w-full cell-border stripe hover">
                        <thead>
                            <tr class="text-left text-gray-500 uppercase text-[10px] tracking-wider">
                                <th>No. Form</th>
                                <th>Tanggal</th>
                                <th>Kategori & Detail</th>
                                <th>Vendor/Penerima</th>
                                <th>Metode</th>
                                <th>Nominal</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="text-sm">
                            @foreach ($kas_keluars as $k)
                                <tr>
                                    <td class="font-mono font-bold text-rose-600">{{ $k->no_form }}</td>
                                    <td>{{ \Carbon\Carbon::parse($k->tanggal)->format('d/m/Y') }}</td>
                                    <td>
                                        <div class="font-bold text-gray-800">{{ $k->nama_kategori }}</div>
                                        <div class="text-[10px] text-gray-400 uppercase leading-none italic">
                                            {{ $k->nama_proyek ?? 'Pengeluaran Umum' }}
                                        </div>
                                    </td>
                                    <td>
                                        <span class="text-gray-600 font-medium">{{ $k->nama ?? '-' }}</span>
                                    </td>
                                    <td>
                                        <span
                                            class="px-2 py-0.5 rounded text-[10px] font-bold uppercase {{ $k->nama_metode_bayar == 'Bank' ? 'bg-blue-100 text-blue-700' : 'bg-amber-100 text-amber-700' }}">
                                            {{ $k->nama_metode_bayar }}
                                        </span>
                                    </td>
                                    <td class="text-rose-600 font-black">
                                        Rp {{ number_format($k->nominal, 0, ',', '.') }}
                                    </td>
                                    <td class="text-center">
                                        <div class="flex justify-center gap-2">
                                            <a href="{{ route('kas-keluar.edit', $k->id_kas) }}"
                                                class="p-2 text-indigo-600 hover:bg-indigo-50 rounded-lg transition">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                </svg>
                                            </a>
                                            <button type="button"
                                                onclick="confirmDelete('{{ $k->id_kas }}', '{{ $k->no_form }}')"
                                                class="p-2 text-rose-600 hover:bg-rose-50 rounded-lg transition"
                                                title="Hapus Transaksi">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                            </button>

                                            <form id="delete-form-{{ $k->id_kas }}"
                                                action="{{ route('kas-keluar.destroy', $k->id_kas) }}" method="POST"
                                                class="hidden">
                                                @csrf
                                                @method('DELETE')
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot class="bg-gray-50">
                            <tr>
                                <th><input type="text" placeholder="No Form" class="w-full text-[10px] rounded border-gray-200 p-1"></th>
                                <th><input type="text" placeholder="Tanggal" class="w-full text-[10px] rounded border-gray-200 p-1"></th>
                                <th><input type="text" placeholder="Kategori" class="w-full text-[10px] rounded border-gray-200 p-1"></th>
                                <th><input type="text" placeholder="Vendor" class="w-full text-[10px] rounded border-gray-200 p-1"></th>
                                <th><input type="text" placeholder="Metode" class="w-full text-[10px] rounded border-gray-200 p-1"></th>
                                <th class="text-right">Total:</th>
                                <th></th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <style>
        /* Reusing your existing styles */
        .dataTables_wrapper .dataTables_length select { border-radius: 0.75rem; font-size: 0.75rem; border-color: #f3f4f6; }
        .dataTables_wrapper .dataTables_filter input { border-radius: 0.75rem; border-color: #f3f4f6; padding: 0.5rem 1rem; }
        table.dataTable.cell-border tbody td { border-top: 1px solid #f9fafb !important; padding: 12px 10px; }
        table.dataTable thead th { border-bottom: 2px solid #f3f4f6 !important; }
    </style>

    <script>
        $(document).ready(function() {
            var table = $('#kasKeluarTable').DataTable({
                responsive: true,
                dom: '<"flex flex-col md:flex-row justify-between items-center mb-4 gap-4"lfr>t<"flex flex-col md:flex-row justify-between items-center mt-4 gap-4"ip>',
                language: {
                    search: "_INPUT_",
                    searchPlaceholder: "Cari pengeluaran..."
                }
            });

            table.columns().every(function() {
                var that = this;
                $('input', this.footer()).on('keyup change', function() {
                    if (that.search() !== this.value) {
                        that.search(this.value).draw();
                    }
                });
            });
        });

        function confirmDelete(id, no_form) {
            Swal.fire({
                title: 'Hapus Transaksi Keluar?',
                text: "Data " + no_form + " akan dihapus permanen!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#e11d48',
                cancelButtonColor: '#64748b',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('delete-form-' + id).submit();
                }
            });
        }

        document.addEventListener('DOMContentLoaded', function() {
            @if (session('success'))
                Swal.fire({ icon: 'success', title: 'Berhasil!', text: "{{ session('success') }}", timer: 2000, showConfirmButton: false });
            @endif
            @if (session('error'))
                Swal.fire({ icon: 'error', title: 'Gagal!', text: "{!! addslashes(session('error')) !!}", confirmButtonColor: '#e11d48' });
            @endif
        });
    </script>
</x-app-layout>