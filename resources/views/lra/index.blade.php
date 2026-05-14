<x-app-layout>
    @section('title', 'Master LRA')

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Master Laporan Realisasi Anggaran') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            {{-- Form Input --}}
            <div
                class="bg-white dark:bg-gray-800 p-6 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 mb-6 transition-colors duration-300">
                <h3 class="text-sm font-bold text-indigo-600 dark:text-indigo-400 mb-4 uppercase tracking-widest">Tambah
                    Struktur LRA</h3>
                <form action="{{ route('lra.store') }}" method="POST"
                    class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
                    @csrf
                    <div>
                        <label class="block text-[10px] font-bold text-gray-400 uppercase mb-1">Keterangan LRA</label>
                        <input type="text" name="keterangan" required placeholder="Misal: Budget Material"
                            class="w-full rounded-xl border-gray-200 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 text-sm focus:ring-indigo-500">
                    </div>

                    <div>
                        <label class="block text-[10px] font-bold text-gray-400 uppercase mb-1">Hubungkan Kategori
                            Kas</label>
                        <select name="id_kategori" required
                            class="w-full rounded-xl border-gray-200 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 text-sm focus:ring-indigo-500">
                            <option value="">-- Pilih Kategori --</option>
                            @foreach ($listKategori as $kat)
                                <option value="{{ $kat->id_kategori }}">{{ $kat->nama_kategori }}
                                    ({{ strtoupper($kat->arus) }})</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-[10px] font-bold text-gray-400 uppercase mb-1">Persentase (%)</label>
                        <input type="text" name="persentase" required min="0.01" max="100" step="0.01"
                            class="rupiah w-full rounded-xl border-gray-200 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 text-sm focus:ring-indigo-500">
                    </div>

                    <button type="submit"
                        class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-2.5 rounded-xl text-sm font-bold transition shadow-lg shadow-indigo-500/30">
                        + Simpan Data
                    </button>
                </form>
            </div>

            {{-- Table LRA --}}
            <div
                class="bg-white dark:bg-gray-800 shadow-xl rounded-2xl overflow-hidden border border-gray-100 dark:border-gray-700 transition-colors duration-300">
                <div class="p-6 border-b border-gray-100 dark:border-gray-700 bg-gray-50/50 dark:bg-gray-900/20">
                    <h3 class="text-lg font-bold text-gray-800 dark:text-white">Daftar Struktur LRA</h3>
                </div>

                <div class="p-6">
                    <table id="lraTable" class="w-full cell-border stripe hover">
                        <thead>
                            <tr class="text-left text-gray-500 dark:text-gray-400 uppercase text-xs tracking-wider">
                                <th>Keterangan LRA</th>
                                <th>Kategori Terhubung</th>
                                <th class="text-center">Alokasi</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                            <tr class="bg-gray-50 dark:bg-gray-900/50 filter-row">
                                <th class="p-2"><input type="text" placeholder="Cari Item"
                                        class="w-full text-[10px] rounded-lg border-gray-200 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-300 focus:ring-indigo-500">
                                </th>
                                <th class="p-2"><input type="text" placeholder="Cari Kategori"
                                        class="w-full text-[10px] rounded-lg border-gray-200 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-300 focus:ring-indigo-500">
                                </th>
                                <th class="p-2"></th>
                                <th class="p-2"></th>
                            </tr>
                        </thead>
                        <tbody class="text-sm text-gray-600 dark:text-gray-300">
                            @foreach ($lras as $l)
                                <tr>
                                    <td class="font-bold text-gray-900 dark:text-white">{{ $l->keterangan }}</td>
                                    <td>
                                        <span
                                            class="text-xs font-semibold px-2 py-1 bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 rounded-md">
                                            {{ $l->nama_kategori }}
                                        </span>
                                    </td>
                                    <td class="text-center font-mono text-indigo-600 dark:text-indigo-400 font-bold">
                                        {{ $l->persentase }}%
                                    </td>
                                    <td class="text-center">
                                        <div class="flex justify-center gap-2">
                                            {{-- Tombol Edit --}}
                                            <button type="button" onclick="openEditModal({{ json_encode($l) }})"
                                                class="p-2 text-amber-500 hover:bg-amber-50 dark:hover:bg-amber-900/20 rounded-lg transition">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                </svg>
                                            </button>
                                            {{-- Tombol Delete --}}
                                            <button type="button"
                                                onclick="deleteLra('{{ $l->id_lra }}', '{{ $l->keterangan }}')"
                                                class="p-2 text-rose-600 hover:bg-rose-50 dark:hover:bg-rose-900/20 rounded-lg transition">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                            </button>
                                            <form id="delete-form-{{ $l->id_lra }}"
                                                action="{{ route('lra.destroy', $l->id_lra) }}" method="POST"
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

    {{-- MODAL EDIT --}}
    <div id="editModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 transition-opacity" aria-hidden="true">
                <div class="absolute inset-0 bg-gray-500 dark:bg-gray-900 opacity-75"></div>
            </div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div
                class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <form id="editForm" method="POST">
                    @csrf @method('PUT')
                    <div class="p-6">
                        <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4">Edit Master LRA</h3>
                        <div class="space-y-4">
                            <div>
                                <label class="block text-xs font-bold text-gray-400 uppercase mb-1">Keterangan</label>
                                <input type="text" name="keterangan" id="edit_keterangan" required
                                    class="w-full rounded-xl border-gray-200 dark:bg-gray-700 dark:text-white focus:ring-indigo-500">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-gray-400 uppercase mb-1">Kategori Kas</label>
                                <select name="id_kategori" id="edit_id_kategori" required
                                    class="w-full rounded-xl border-gray-200 dark:bg-gray-700 dark:text-white focus:ring-indigo-500">
                                    @foreach ($listKategori as $kat)
                                        <option value="{{ $kat->id_kategori }}">{{ $kat->nama_kategori }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-gray-400 uppercase mb-1">Persentase
                                    (%)</label>
                                <input type="text" name="persentase" id="edit_persentase" step="0.01" required
                                    class="rupiah w-full rounded-xl border-gray-200 dark:bg-gray-700 dark:text-white focus:ring-indigo-500">
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 dark:bg-gray-700/50 px-6 py-4 flex justify-end gap-3">
                        <button type="button" onclick="closeEditModal()"
                            class="text-sm font-bold text-gray-500">Batal</button>
                        <button type="submit"
                            class="bg-indigo-600 text-white px-6 py-2 rounded-xl text-sm font-bold shadow-lg shadow-indigo-500/30">Simpan
                            Perubahan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <style>
        .filter-row th {
            background-image: none !important;
            cursor: default !important;
            border-bottom: 1px solid #e5e7eb !important;
        }

        .dark .filter-row th {
            border-bottom: 1px solid #374151 !important;
        }

        table.dataTable {
            border-collapse: collapse !important;
            width: 100% !important;
            margin-bottom: 2rem !important;
        }
    </style>

    <script>
        $(document).ready(function() {
            var table = $('#lraTable').DataTable({
                responsive: true,
                dom: '<"flex justify-between items-center mb-4"l>t<"flex justify-between items-center mt-4"ip>',
                orderCellsTop: true,
                order: [
                    [2, 'desc']
                ]
            });

            $('#lraTable thead tr:eq(1) th').each(function(i) {
                $('input', this).on('input change', function() {
                    if (table.column(i).search() !== this.value) {
                        table.column(i).search(this.value).draw();
                    }
                });
            });
        });

        function openEditModal(data) {
            const form = document.getElementById('editForm');
            form.action = `/lra/${data.id_lra}`; // Pastikan route update benar
            document.getElementById('edit_keterangan').value = data.keterangan;
            document.getElementById('edit_id_kategori').value = data.id_kategori;
            document.getElementById('edit_persentase').value = data.persentase;
            document.getElementById('editModal').classList.remove('hidden');
        }

        function closeEditModal() {
            document.getElementById('editModal').classList.add('hidden');
        }

        function deleteLra(id, name) {
            Swal.fire({
                title: 'Hapus Master LRA?',
                text: "Kategori " + name + " akan dihapus dari struktur anggaran!",
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

        @if (session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: "{{ session('success') }}",
                timer: 2500,
                showConfirmButton: false
            });
        @endif
        @if (session('error'))
            Swal.fire({
                icon: 'error',
                title: 'Gagal!',
                text: "{{ session('error') }}",
                confirmButtonColor: '#4f46e5'
            });
        @endif
    </script>
</x-app-layout>
