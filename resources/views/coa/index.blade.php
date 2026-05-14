<x-app-layout>
    @section('title', 'Data COA')

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Master Data Chart of Accounts (COA)') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 shadow-xl rounded-2xl overflow-hidden border border-gray-100 dark:border-gray-700">

                <div class="p-6 border-b border-gray-100 dark:border-gray-700 flex justify-between items-center bg-gray-50/50 dark:bg-gray-900/20">
                    <h3 class="text-lg font-bold text-gray-800 dark:text-white">Daftar Akun Keuangan</h3>
                    <a href="{{ route('coa.create') }}"
                        class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-xl text-sm font-semibold transition-all shadow-md">
                        + Tambah Akun
                    </a>
                </div>

                <div class="p-6">
                    <table id="coaTable" class="w-full cell-border stripe hover">
                        <thead>
                            {{-- Baris 1: Judul Kolom --}}
                            <tr class="text-left text-gray-500 uppercase text-xs tracking-wider">
                                <th>Kode Akun</th>
                                <th>Nama Akun</th>
                                <th>Level</th>
                                <th>Parent Akun</th>
                                <th>Urutan</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                            {{-- Baris 2: Filter (Pindahan dari Footer) --}}
                            <tr class="bg-gray-50 dark:bg-gray-900/50 filter-row">
                                <th class="p-2"><input type="text" placeholder="Cari Kode..." class="column-search w-full text-[10px] rounded-lg border-gray-200 dark:bg-gray-800 dark:border-gray-700"></th>
                                <th class="p-2"><input type="text" placeholder="Cari Nama..." class="column-search w-full text-[10px] rounded-lg border-gray-200 dark:bg-gray-800 dark:border-gray-700"></th>
                                <th class="p-2"><input type="text" placeholder="Level..." class="column-search w-full text-[10px] rounded-lg border-gray-200 dark:bg-gray-800 dark:border-gray-700"></th>
                                <th class="p-2"><input type="text" placeholder="Parent..." class="column-search w-full text-[10px] rounded-lg border-gray-200 dark:bg-gray-800 dark:border-gray-700"></th>
                                <th class="p-2"></th>
                                <th class="p-2"></th>
                            </tr>
                        </thead>
                        <tbody class="text-sm text-gray-600 dark:text-gray-300">
                            @foreach ($coas as $c)
                                <tr>
                                    {{-- Kolom Kode Akun --}}
                                    <td class="font-mono font-bold text-indigo-600">{{ $c->kode_akun }}</td>
                                    <td>
                                        <span style="padding-left: {{ ($c->level - 1) * 20 }}px"
                                            class="{{ $c->level == 1 ? 'font-black uppercase text-gray-900 dark:text-white' : '' }}">
                                            {{ $c->nama_akun }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="px-2 py-1 rounded text-[10px] font-bold uppercase
                                            {{ $c->level == 1 ? 'bg-red-100 text-red-700' : ($c->level == 2 ? 'bg-orange-100 text-orange-700' : 'bg-blue-100 text-blue-700') }}">
                                            Lvl {{ $c->level }}
                                        </span>
                                    </td>
                                    <td class="text-gray-400 italic text-xs">{{ $c->nama_parent ?? '-' }}</td>
                                    <td>{{ $c->urutan }}</td>
                                    <td class="text-center">
                                        <div class="flex justify-center gap-2">
                                            <a href="{{ route('coa.edit', $c->id_coa) }}" class="p-2 text-indigo-600 hover:bg-indigo-50 rounded-lg transition">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>
                                            </a>
                                            <button type="button" onclick="deleteCoa('{{ $c->id_coa }}', '{{ $c->nama_akun }}')" class="p-2 text-rose-600 hover:bg-rose-50 rounded-lg transition">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                            </button>
                                            <form id="delete-form-{{ $c->id_coa }}" action="{{ route('coa.destroy', $c->id_coa) }}" method="POST" class="hidden">@csrf @method('DELETE')</form>
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
        .filter-row th { background-image: none !important; cursor: default !important; }
        .dataTables_wrapper .dataTables_filter { display: none; }
        table.dataTable thead th { background-color: #f9fafb; border-bottom: 1px solid #f3f4f6 !important; }
        .dark table.dataTable thead th { background-color: #111827; border-bottom: 1px solid #374151 !important; color: #9ca3af; }
    </style>

    <script>
        $(document).ready(function() {
            // Inisialisasi DataTable
            var table = $('#coaTable').DataTable({
                responsive: true,
                orderCellsTop: true, // Pastikan baris atas yang buat sorting
                pageLength: 10,
                dom: '<"flex justify-between items-center mb-4"l>t<"flex justify-between items-center mt-4"ip>',
                
                // LOGIKA SORTING HIERARKIS (1, 11, 112, 12, dst)
                // Kita gunakan sorting tipe 'string' agar 10 tidak lari ke atas 2
                "columnDefs": [
                    { "type": "string", "targets": 0 }
                ],
                "order": [[0, 'asc']] 
            });

            // Logika Auto Search di Header
            $('#coaTable thead .filter-row th').each(function(i) {
                $('input', this).on('keyup input change clear', function(e) {
                    e.stopPropagation(); // Biar gak trigger sorting pas ngetik
                    if (table.column(i).search() !== this.value) {
                        table.column(i).search(this.value).draw();
                    }
                });
                
                // Stop sorting pas input di-klik
                $('input', this).on('click', function(e) {
                    e.stopPropagation();
                });
            });
        });

        // SweetAlert Delete & Flash Messages
        function deleteCoa(id, name) {
            Swal.fire({
                title: 'Hapus Akun?',
                text: "Menghapus " + name + " akan berdampak pada histori jurnal!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#e11d48',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) document.getElementById('delete-form-' + id).submit();
            });
        }

        @if (session('success'))
            Swal.fire({ icon: 'success', title: 'Berhasil!', text: "{{ session('success') }}", timer: 2000, showConfirmButton: false });
        @endif

        @if (session('error'))
            Swal.fire({ icon: 'error', title: 'Gagal!', text: "{{ session('error') }}", confirmButtonColor: '#6366f1' });
        @endif
    </script>
</x-app-layout>