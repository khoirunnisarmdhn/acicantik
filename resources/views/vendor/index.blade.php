<x-app-layout>
    @section('title', 'Data Vendor')
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Master Data Vendor') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div
                class="bg-white dark:bg-gray-800 shadow-xl rounded-2xl overflow-hidden border border-gray-100 dark:border-gray-700">

                <div
                    class="p-6 border-b border-gray-100 dark:border-gray-700 flex justify-between items-center bg-gray-50/50 dark:bg-gray-900/20">
                    <h3 class="text-lg font-bold text-gray-800 dark:text-white">Daftar Rekanan Vendor</h3>
                    <a href="{{ route('vendor.create') }}"
                        class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-xl text-sm font-semibold transition-all shadow-md">
                        + Tambah Vendor
                    </a>
                </div>

                <div class="p-6">
                    <table id="vendorTable" class="w-full cell-border stripe hover">
                        <thead>
                            {{-- Baris 1: Judul Kolom --}}
                            <tr class="text-left text-gray-500 uppercase text-xs tracking-wider">
                                <th>Nama Vendor</th>
                                <th>Penanggung Jawab</th>
                                <th>Alamat</th>
                                <th>Kontak</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                            {{-- Baris 2: Filter Input --}}
                            <tr class="bg-gray-50 dark:bg-gray-900/50 filter-row">
                                <th class="p-2">
                                    <input type="text" placeholder="Cari Vendor..."
                                        class="column-filter w-full text-[10px] rounded-lg border-gray-200 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-300 focus:ring-indigo-500">
                                </th>
                                <th class="p-2">
                                    <input type="text" placeholder="Filter PJ..."
                                        class="column-filter w-full text-[10px] rounded-lg border-gray-200 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-300 focus:ring-indigo-500">
                                </th>
                                <th class="p-2"></th>
                                <th class="p-2"></th>
                                <th class="p-2"></th>
                            </tr>
                        </thead>
                        <tbody class="text-sm text-gray-600 dark:text-gray-300">
                            @foreach ($vendors as $v)
                                <tr>
                                    <td class="font-bold text-gray-900 dark:text-white">{{ $v->nama }}</td>
                                    <td>{{ $v->penanggung_jawab }}</td>
                                    <td class="text-xs">{{ $v->alamat }}</td>
                                    <td>
                                        <div class="text-xs font-semibold">{{ $v->no_telp }}</div>
                                        <div class="text-[10px] text-gray-400">{{ $v->email }}</div>
                                    </td>
                                    <td class="text-center">
                                        <div class="flex justify-center gap-2">
                                            <a href="{{ route('vendor.edit', $v->id_vendor) }}"
                                                class="p-2 text-indigo-600 hover:bg-indigo-50 dark:hover:bg-indigo-900/30 rounded-lg transition">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                </svg>
                                            </a>
                                            <button type="button"
                                                onclick="deleteVendor('{{ $v->id_vendor }}', '{{ $v->nama }}')"
                                                class="p-2 text-rose-600 hover:bg-rose-50 dark:hover:bg-rose-900/30 rounded-lg transition">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                            </button>
                                            <form id="delete-form-{{ $v->id_vendor }}"
                                                action="{{ route('vendor.destroy', $v->id_vendor) }}" method="POST"
                                                class="hidden">
                                                @csrf @method('DELETE')
                                            </form>
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
        .filter-row th {
            background-image: none !important;
            cursor: default !important;
        }

        .dataTables_wrapper .dataTables_filter {
            display: none;
        }

        /* Pakai filter custom saja */
        table.dataTable thead th {
            background-color: #f9fafb;
            border-bottom: 1px solid #f3f4f6 !important;
        }

        .dark table.dataTable thead th {
            background-color: #111827;
            border-bottom: 1px solid #374151 !important;
            color: #9ca3af;
        }
    </style>

    <script>
        $(document).ready(function() {
            // 1. Inisialisasi DataTable
            var table = $('#vendorTable').DataTable({
                responsive: true,
                orderCellsTop: true, // Kunci: baris sorting ada di paling atas
                dom: '<"flex justify-between items-center mb-4"l>t<"flex justify-between items-center mt-4"ip>',
            });

            // 2. Logika Search Otomatis per Kolom
            $('#vendorTable thead .filter-row th').each(function(i) {
                $('input', this).on('keyup input change clear', function(e) {
                    // Stop sorting saat klik input
                    e.stopPropagation();

                    if (table.column(i).search() !== this.value) {
                        table.column(i).search(this.value).draw();
                    }
                });

                // Biar nggak trigger sorting kalau input di-klik
                $('input', this).on('click', function(e) {
                    e.stopPropagation();
                });
            });
        });

        // 3. SweetAlert2 Flash Messages
        @if (session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: "{{ session('success') }}",
                timer: 2500,
                showConfirmButton: false
            });
        @endif

        @if (session('error') || $errors->any())
            Swal.fire({
                icon: 'error',
                title: 'Error!',
                text: "{{ session('error') ?? 'Ada masalah saat memproses data.' }}",
                confirmButtonColor: '#4f46e5'
            });
        @endif

        function deleteVendor(id, name) {
            Swal.fire({
                title: 'Hapus Vendor?',
                text: "Data " + name + " akan dihapus permanen!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#e11d48',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('delete-form-' + id).submit();
                }
            });
        }
    </script>
</x-app-layout>
