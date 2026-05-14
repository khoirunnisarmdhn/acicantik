<x-app-layout>
    @section('title', 'Master Kategori Kas')
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Master Kategori Kas') }}
        </h2>
    </x-slot>

    <style>
        .dataTables_filter input {
            border-radius: 0.75rem !important;
            border: 1px solid #e5e7eb !important;
            padding: 0.4rem 1rem !important;
            font-size: 0.875rem !important;
            width: 250px !important;
            margin-bottom: 1.5rem !important;
        }

        .dataTables_filter label {
            font-size: 10px !important;
            font-weight: 800 !important;
            text-transform: uppercase !important;
            color: #9ca3af !important;
            letter-spacing: 0.05em;
        }

        table.dataTable thead th {
            background-color: #f9fafb;
            border-bottom: 1px solid #f3f4f6 !important;
            padding: 1rem !important;
        }

        .swal2-popup {
            border-radius: 1.5rem !important;
        }
    </style>

    <div class="py-12" x-data="{ tab: 'masuk' }">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 shadow-xl rounded-2xl overflow-hidden border border-gray-100 dark:border-gray-700">

                <div class="p-6 border-b border-gray-100 dark:border-gray-700 flex justify-between items-center bg-gray-50/50 dark:bg-gray-900/20">
                    <div>
                        <h3 class="text-lg font-bold text-gray-800 dark:text-white uppercase tracking-tighter">Kategori & Mapping Jurnal</h3>
                        <p class="text-gray-400 text-[10px] font-bold uppercase tracking-widest">Gunakan tab untuk memisahkan Arus Kas</p>
                    </div>
                    <a href="{{ route('kategori.create') }}"
                        class="bg-indigo-600 hover:bg-indigo-700 text-white px-5 py-2.5 rounded-xl text-sm font-bold transition-all shadow-lg shadow-indigo-100 uppercase">
                        + Kategori Baru
                    </a>
                </div>

                <div class="flex border-b border-gray-100 dark:border-gray-700 bg-gray-50/30">
                    <button @click="tab = 'masuk'"
                        :class="tab === 'masuk' ? 'border-b-2 border-emerald-500 text-emerald-600 bg-white' : 'text-gray-400'"
                        class="flex-1 py-4 font-black text-xs uppercase transition-all duration-300">
                        📥 Kas Masuk
                    </button>
                    <button @click="tab = 'keluar'"
                        :class="tab === 'keluar' ? 'border-b-2 border-rose-500 text-rose-600 bg-white' : 'text-gray-400'"
                        class="flex-1 py-4 font-black text-xs uppercase transition-all duration-300">
                        📤 Kas Keluar
                    </button>
                </div>

                <div class="p-6">
                    @foreach (['masuk', 'keluar'] as $type)
                        <div x-show="tab === '{{ $type }}'" 
                             x-transition:enter="transition ease-out duration-300"
                             x-transition:enter-start="opacity-0 transform translate-y-4"
                             x-transition:enter-end="opacity-100 transform translate-y-0">

                            <table id="table{{ ucfirst($type) }}" class="display nowrap text-sm w-full">
                                <thead>
                                    <tr class="text-gray-500 text-[10px] uppercase tracking-wider">
                                        <th>Nama Kategori</th>
                                        <th>Jenis</th>
                                        <th>Mapping Akun (COA)</th>
                                        <th>Deskripsi</th>
                                        <th class="text-center">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    {{-- Mengambil data dari variabel tunggal $kategori dengan filter collection --}}
                                    @foreach ($kategori->where('arus', $type) as $item)
                                        <tr class="border-b border-gray-50 hover:bg-gray-50/50 transition">
                                            <td class="font-bold text-gray-700">{{ $item->nama_kategori }}</td>
                                            <td>
                                                @if ($item->jenis == 'proyek')
                                                    <span class="bg-amber-50 text-amber-700 text-[10px] font-black px-2 py-1 rounded-lg border border-amber-100 uppercase tracking-tighter">🚧 Proyek</span>
                                                @else
                                                    <span class="bg-blue-50 text-blue-700 text-[10px] font-black px-2 py-1 rounded-lg border border-blue-100 uppercase tracking-tighter">🏢 Non-Proyek</span>
                                                @endif
                                            </td>
                                            <td class="p-4">
                                                <div class="flex flex-col gap-1">
                                                    <div class="flex items-center gap-2">
                                                        <span class="bg-emerald-50 text-emerald-600 text-[9px] font-black px-1.5 py-0.5 rounded border border-emerald-100">DR</span>
                                                        <span class="text-[11px] font-mono text-gray-500">{{ $item->kode_debit ?? '---' }}</span>
                                                        <span class="text-xs text-gray-700 font-medium line-clamp-1">{{ $item->nama_debit ?? 'Belum diset' }}</span>
                                                    </div>
                                                    <div class="flex items-center gap-2">
                                                        <span class="bg-rose-50 text-rose-600 text-[9px] font-black px-1.5 py-0.5 rounded border border-rose-100">CR</span>
                                                        <span class="text-[11px] font-mono text-gray-500">{{ $item->kode_kredit ?? '---' }}</span>
                                                        <span class="text-xs text-gray-700 font-medium line-clamp-1">{{ $item->nama_kredit ?? 'Belum diset' }}</span>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="text-gray-400 text-xs italic">{{ $item->deskripsi ?? '-' }}</td>
                                            <td class="text-center">
                                                <div class="flex justify-center gap-2 items-center">
                                                    <a href="{{ route('kategori.edit', $item->id_kategori) }}"
                                                       class="text-indigo-600 hover:text-indigo-900 transition font-bold text-xs uppercase">Edit</a>
                                                    <span class="text-gray-200">|</span>
                                                    <button type="button" onclick="confirmDelete('{{ $item->id_kategori }}')"
                                                            class="text-rose-500 hover:text-rose-700 font-bold text-xs uppercase transition">Hapus</button>
                                                    
                                                    {{-- Form Delete Hidden --}}
                                                    <form id="delete-form-{{ $item->id_kategori }}" 
                                                          action="{{ route('kategori.destroy', $item->id_kategori) }}" 
                                                          method="POST" style="display: none;">
                                                        @csrf
                                                        @method('DELETE')
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <script>
        // 1. Handling Error & Success Messages (SweetAlert2)
        @if ($errors->any())
            Swal.fire({
                icon: 'error',
                title: 'VALIDASI GAGAL',
                html: `<div class="text-left text-sm"><ul class="list-disc ml-5">@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>`,
                confirmButtonColor: '#ef4444'
            });
        @endif

        @if (session('error'))
            Swal.fire({
                icon: 'error',
                title: 'Error!',
                text: "{{ session('error') }}",
                confirmButtonColor: '#ef4444'
            });
        @endif

        @if (session('success'))
            Swal.fire({
                icon: 'success',
                title: 'BERHASIL!',
                text: "{{ session('success') }}",
                showConfirmButton: false,
                timer: 2000
            });
        @endif

        // 2. Initialize DataTables
        $(document).ready(function() {
            const dtConfig = {
                pageLength: 10,
                lengthChange: false,
                language: {
                    search: "FILTER DATA:",
                    info: "Menampilkan _TOTAL_ data kategori",
                    paginate: { next: "→", previous: "←" }
                }
            };

            $('#tableMasuk').DataTable(dtConfig);
            $('#tableKeluar').DataTable(dtConfig);
        });

        // 3. Delete Confirmation
        function confirmDelete(id) {
            Swal.fire({
                title: 'Yakin mau hapus?',
                text: "Data kategori ini akan dihapus permanen dari sistem!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#111827',
                cancelButtonColor: '#9ca3af',
                confirmButtonText: 'YA, HAPUS SEKARANG',
                cancelButtonText: 'BATAL',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('delete-form-' + id).submit();
                }
            });
        }
    </script>
</x-app-layout>