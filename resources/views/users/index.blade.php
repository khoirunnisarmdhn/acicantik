<x-app-layout>
    @section('title', 'Data User')
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Master Data User') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 shadow-xl rounded-2xl overflow-hidden border border-gray-100 dark:border-gray-700">

                <div class="p-6 border-b border-gray-100 dark:border-gray-700 flex justify-between items-center bg-gray-50/50 dark:bg-gray-900/20">
                    <h3 class="text-lg font-bold text-gray-800 dark:text-white">Daftar Pengguna Sistem</h3>
                    <a href="{{ route('users.create') }}"
                        class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-xl text-sm font-semibold transition-all shadow-md shadow-indigo-200">
                        + Tambah User
                    </a>
                </div>

                <div class="p-6">
                    <table id="userTable" class="w-full cell-border stripe hover">
                        <thead>
                            {{-- Baris 1: Judul Kolom --}}
                            <tr class="text-left text-gray-500 uppercase text-xs tracking-wider">
                                <th>Nama Lengkap</th>
                                <th>Email</th>
                                <th>Akses Menu</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                            {{-- Baris 2: Filter Otomatis (Posisi di Atas) --}}
                            <tr class="bg-gray-50 dark:bg-gray-900/50 filter-row">
                                <th class="p-2">
                                    <input type="text" placeholder="Filter Nama" 
                                        class="w-full text-[10px] rounded-lg border-gray-200 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-300 focus:ring-indigo-500">
                                </th>
                                <th class="p-2">
                                    <input type="text" placeholder="Filter Email" 
                                        class="w-full text-[10px] rounded-lg border-gray-200 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-300 focus:ring-indigo-500">
                                </th>
                                <th class="p-2">
                                    <input type="text" placeholder="Filter Akses" 
                                        class="w-full text-[10px] rounded-lg border-gray-200 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-300 focus:ring-indigo-500">
                                </th>
                                <th class="p-2"></th>
                            </tr>
                        </thead>
                        <tbody class="text-gray-600 dark:text-gray-300 text-sm">
                            @foreach ($users as $user)
                                <tr>
                                    <td>
                                        <div class="flex flex-col">
                                            <span class="font-bold text-gray-900 dark:text-white">
                                                {{ $user->nama_lengkap ?? $user->name }}
                                            </span>
                                            <span class="text-[10px] font-mono text-gray-400">ID: {{ $user->name }}</span>
                                        </div>
                                    </td>
                                    <td>{{ $user->email }}</td>
                                    <td>
                                        <div class="flex flex-wrap gap-1">
                                            @php
                                                $userRoles = DB::table('user_akses')
                                                    ->join('akses', 'user_akses.id_akses', '=', 'akses.id_akses')
                                                    ->where('user_id', $user->id)
                                                    ->pluck('akses.nama_akses');
                                            @endphp
                                            
                                            @forelse($userRoles as $namaAkses)
                                                <span class="px-2 py-0.5 bg-indigo-50 dark:bg-indigo-900/30 text-indigo-600 dark:text-indigo-400 border border-indigo-100 dark:border-indigo-800 rounded text-[10px] font-bold uppercase">
                                                    {{ $namaAkses }}
                                                </span>
                                            @empty
                                                <span class="px-2 py-0.5 bg-gray-100 dark:bg-gray-700 text-gray-400 border border-gray-200 dark:border-gray-600 rounded text-[10px] italic">
                                                    BELUM ADA AKSES
                                                </span>
                                            @endforelse
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <div class="flex justify-center gap-2">
                                            <a href="{{ route('users.edit', $user->id) }}"
                                                class="p-2 text-indigo-600 hover:bg-indigo-50 dark:hover:bg-indigo-900/20 rounded-lg transition"
                                                title="Edit User">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                </svg>
                                            </a>
                                            <button type="button"
                                                onclick="deleteUser('{{ $user->id }}', '{{ $user->nama_lengkap ?? $user->name }}')"
                                                class="p-2 text-rose-600 hover:bg-rose-50 dark:hover:bg-rose-900/20 rounded-lg transition"
                                                title="Hapus User">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                            </button>
                                            <form id="delete-form-{{ $user->id }}" action="{{ route('users.destroy', $user->id) }}" method="POST" class="hidden">
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
        /* Hilangkan sorting icon di baris filter */
        .filter-row th { background-image: none !important; cursor: default !important; border-bottom: 1px solid #e5e7eb !important; }
        .dark .filter-row th { border-bottom: 1px solid #374151 !important; }
        
        .dataTables_wrapper .dataTables_length select { padding-right: 2rem; border-radius: 0.5rem; }
        table.dataTable { border-collapse: collapse !important; width: 100% !important; margin-bottom: 2rem !important; }
        table.dataTable thead th { background-color: #f9fafb; border-bottom: 1px solid #f3f4f6 !important; }
        .dark table.dataTable thead th { background-color: #111827; border-bottom: 1px solid #374151 !important; color: #9ca3af; }
    </style>

    <script>
        $(document).ready(function() {
            var table = $('#userTable').DataTable({
                responsive: true,
                // Hilangkan search box global (f) karena kita pakai per kolom
                "dom": '<"flex flex-col md:flex-row justify-between items-center mb-4 gap-4"l>t<"flex flex-col md:flex-row justify-between items-center mt-4 gap-4"ip>',
                "orderCellsTop": true,
                "order": [[0, 'asc']]
            });

            // Pencarian Otomatis (Tanpa Enter)
            $('#userTable thead tr:eq(1) th').each(function(i) {
                $('input', this).on('input', function() {
                    if (table.column(i).search() !== this.value) {
                        table.column(i)
                            .search(this.value)
                            .draw();
                    }
                });
            });
        });

        function deleteUser(id, name) {
            Swal.fire({
                title: 'Hapus User?',
                text: "User " + name + " akan dihapus permanen!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#e11d48',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('delete-form-' + id).submit();
                }
            });
        }

        @if (session('success'))
            Swal.fire({ icon: 'success', title: 'Berhasil!', text: "{{ session('success') }}", timer: 2500, showConfirmButton: false });
        @endif
        @if (session('error'))
            Swal.fire({ icon: 'error', title: 'Gagal!', text: "{{ session('error') }}", confirmButtonColor: '#4f46e5' });
        @endif
    </script>
</x-app-layout>