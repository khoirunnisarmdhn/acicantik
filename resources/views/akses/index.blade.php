<x-app-layout>
    @section('title', 'Data Akses User')

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Master Data Hak Akses User') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div
                class="bg-white dark:bg-gray-800 shadow-xl rounded-2xl overflow-hidden border border-gray-100 dark:border-gray-700">

                <div
                    class="p-6 border-b border-gray-100 dark:border-gray-700 flex justify-between items-center bg-gray-50/50 dark:bg-gray-900/20">
                    <h3 class="text-lg font-bold text-gray-800 dark:text-white">Daftar Role & Hak Akses</h3>
                    <a href="{{ route('akses.create') }}"
                        class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-xl text-sm font-semibold transition-all shadow-md shadow-indigo-200">
                        + Tambah Akses
                    </a>
                </div>

                <div class="p-6">
                    <table id="aksesTable" class="w-full cell-border stripe hover">
                        <thead>
                            {{-- Baris 1: Judul Kolom --}}
                            <tr class="text-left text-gray-500 dark:text-gray-400 uppercase text-xs tracking-wider">
                                <th>Nama Akses</th>
                                <th>Izin Fitur (Menu)</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                            {{-- Baris 2: Filter (Pengganti tfoot) --}}
                            <tr class="bg-gray-50 dark:bg-gray-900/50 filter-row">
                                <th class="p-2">
                                    <input type="text" placeholder="Cari Nama"
                                        class="w-full text-xs rounded-lg border-gray-200 focus:ring-indigo-500 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-300">
                                </th>
                                <th class="p-2">
                                    <input type="text" placeholder="Cari Izin"
                                        class="w-full text-xs rounded-lg border-gray-200 focus:ring-indigo-500 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-300">
                                </th>
                                <th class="p-2"></th>
                            </tr>
                        </thead>
                        <tbody class="text-sm text-gray-600 dark:text-gray-300">
                            @foreach ($akses as $a)
                                <tr>
                                    <td class="font-bold text-indigo-600 dark:text-indigo-400 uppercase">
                                        {{ $a->nama_akses }}
                                    </td>
                                    <td>
                                        <div class="flex flex-wrap gap-1">
                                            @if (!empty($a->fitur_slug))
                                                @foreach (explode(',', $a->fitur_slug) as $slug)
                                                    <span
                                                        class="px-2 py-0.5 bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 border border-blue-100 dark:border-blue-800 rounded text-[10px] font-bold uppercase">
                                                        {{ trim($slug) }}
                                                    </span>
                                                @endforeach
                                            @else
                                                <span class="text-gray-400 italic text-xs">Tidak ada izin</span>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <div class="flex justify-center gap-2">
                                            <a href="{{ route('akses.edit', $a->id_akses) }}"
                                                class="p-2 text-indigo-600 hover:bg-indigo-50 dark:hover:bg-indigo-900/20 rounded-lg transition">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                </svg>
                                            </a>
                                            <button type="button"
                                                onclick="deleteAkses('{{ $a->id_akses }}', '{{ $a->nama_akses }}')"
                                                class="p-2 text-rose-600 hover:bg-rose-50 dark:hover:bg-rose-900/20 rounded-lg transition">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                            </button>
                                            <form id="delete-form-{{ $a->id_akses }}"
                                                action="{{ route('akses.destroy', $a->id_akses) }}" method="POST"
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
        /* Menghilangkan icon sorting di baris filter */
        .filter-row th {
            background-image: none !important;
            cursor: default !important;
            border-bottom: 1px solid #e5e7eb !important;
        }

        .dark .filter-row th {
            border-bottom: 1px solid #374151 !important;
        }

        .dataTables_wrapper .dataTables_length select {
            padding-right: 2rem;
            border-radius: 0.5rem;
        }

        .dataTables_wrapper .dataTables_filter input {
            border-radius: 0.5rem;
            padding: 0.4rem 1rem;
            border: 1px solid #e5e7eb;
        }

        table.dataTable {
            border-collapse: collapse !important;
            width: 100% !important;
            margin-bottom: 2rem !important;
        }

        table.dataTable thead th {
            background-color: #f9fafb;
            border-bottom: 1px solid #f3f4f6 !important;
        }

        .dark table.dataTable thead th {
            background-color: #111827;
            border-bottom: 1px solid #374151 !important;
        }
    </style>

    <script>
        $(document).ready(function() {
            var table = $('#aksesTable').DataTable({
                responsive: true,
                pageLength: 10,
                "dom": '<"flex flex-col md:flex-row justify-between items-center mb-4 gap-4"l>t<"flex flex-col md:flex-row justify-between items-center mt-4 gap-4"ip>',
                "orderCellsTop": true,
                "order": [
                    [0, 'asc']
                ],
                "language": {
                    "search": "Cari:",
                    "lengthMenu": "Tampilkan _MENU_ data",
                    "info": "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
                    "paginate": {
                        "next": "Berikutnya",
                        "previous": "Sebelumnya"
                    }
                }
            });

            // Filter Kolom Otomatis
            $('#aksesTable thead tr:eq(1) th').each(function(i) {
                // Menggunakan event 'input' agar langsung mendeteksi perubahan teks/copy-paste
                $('input', this).on('input', function() {
                    if (table.column(i).search() !== this.value) {
                        table.column(i)
                            .search(this.value)
                            .draw();
                    }
                });
            });
        });

        function deleteAkses(id, name) {
            Swal.fire({
                title: 'Hapus Hak Akses?',
                text: "Role " + name + " akan dihapus permanen.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#e11d48',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('delete-form-' + id).submit();
                }
            });
        }

        @if (session('error'))
            Swal.fire({
                icon: 'error',
                title: 'Gagal!',
                text: "{{ session('error') }}",
                confirmButtonColor: '#6366f1'
            });
        @endif

        @if (session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: "{{ session('success') }}",
                timer: 2000,
                showConfirmButton: false
            });
        @endif
    </script>
</x-app-layout>
