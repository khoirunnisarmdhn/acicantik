<x-app-layout>
    @section('title', 'Edit Data COA')

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Edit Akun (COA)') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div
                class="bg-white dark:bg-gray-800 shadow-2xl rounded-2xl overflow-hidden border border-gray-100 dark:border-gray-700">

                <div class="bg-amber-500 px-6 py-4 text-white font-bold flex items-center gap-2 shadow-inner">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                    Edit Akun: {{ $coa->nama_akun }}
                </div>

                <form id="coaEditForm" action="{{ route('coa.update', $coa->id_coa) }}" method="POST" class="p-8">
                    @csrf
                    @method('PUT')

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                        <div class="col-span-2 md:col-span-1">
                            <label class="block text-xs font-bold text-gray-400 uppercase mb-2 tracking-widest">Kode
                                Akun</label>
                            <input type="text" name="kode_akun" value="{{ old('kode_akun', $coa->kode_akun) }}"
                                required
                                class="w-full rounded-xl border-gray-200 dark:bg-gray-900 dark:text-white focus:ring-2 focus:ring-amber-500 transition-all @error('kode_akun') border-red-500 @enderror">
                            @error('kode_akun')
                                <p class="text-red-500 text-[10px] mt-1 font-bold italic">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="col-span-2 md:col-span-1">
                            <label class="block text-xs font-bold text-gray-400 uppercase mb-2 tracking-widest">Nama
                                Akun</label>
                            <input type="text" name="nama_akun" value="{{ old('nama_akun', $coa->nama_akun) }}"
                                required
                                class="w-full rounded-xl border-gray-200 dark:bg-gray-900 dark:text-white focus:ring-2 focus:ring-amber-500 transition-all">
                        </div>

                        <div>
                            <label
                                class="block text-xs font-bold text-gray-400 uppercase mb-2 tracking-widest">Level</label>
                            <select name="level" id="level_select" required
                                class="w-full rounded-xl border-gray-200 dark:bg-gray-900 dark:text-white focus:ring-2 focus:ring-amber-500">
                                <option value="1" {{ old('level', $coa->level) == 1 ? 'selected' : '' }}>Level 1
                                    (Header Utama)</option>
                                <option value="2" {{ old('level', $coa->level) == 2 ? 'selected' : '' }}>Level 2
                                    (Sub-Header)</option>
                                <option value="3" {{ old('level', $coa->level) == 3 ? 'selected' : '' }}>Level 3
                                    (Akun Transaksi)</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-gray-400 uppercase mb-2 tracking-widest">Parent
                                Akun (Induk)</label>
                            <select name="parent_id" id="parent_id"
                                class="w-full rounded-xl border-gray-200 dark:bg-gray-900 dark:text-white focus:ring-2 focus:ring-amber-500 disabled:opacity-50">
                                <option value="">-- Tanpa Induk --</option>
                                @foreach ($parents as $p)
                                    {{-- Mencegah akun jadi parent buat dirinya sendiri --}}
                                    @if ($p->id_coa != $coa->id_coa)
                                        <option value="{{ $p->id_coa }}"
                                            {{ old('parent_id', $coa->parent_id) == $p->id_coa ? 'selected' : '' }}>
                                            [{{ $p->kode_akun }}] {{ $p->nama_akun }}
                                        </option>
                                    @endif
                                @endforeach
                            </select>
                        </div>

                        <div class="md:col-span-1">
                            <label class="block text-xs font-bold text-gray-400 uppercase mb-2 tracking-widest">Urutan
                                Tampil</label>
                            <input type="text" name="urutan" id="urutan"
                                value="{{ old('urutan', $coa->urutan) }}" required min="1"
                                class="rupiah w-full rounded-xl border-gray-200 dark:bg-gray-900 dark:text-white focus:ring-2 focus:ring-amber-500">
                            <p id="urutan-error" class="text-red-500 text-[10px] mt-1 font-bold italic hidden">Urutan
                                harus lebih dari 0!</p>
                        </div>

                    </div>

                    <div class="flex justify-end gap-3 mt-10 border-t pt-6 border-gray-100 dark:border-gray-700">
                        <a href="{{ route('coa.index') }}"
                            class="px-6 py-2.5 rounded-xl bg-gray-100 text-gray-600 font-bold hover:bg-gray-200 transition duration-200 dark:bg-gray-700 dark:text-gray-300">
                            Batal
                        </a>
                        <button type="submit"
                            class="px-10 py-2.5 rounded-xl bg-amber-500 text-white font-bold shadow-lg shadow-amber-200 hover:bg-amber-600 transition duration-200 transform active:scale-95">
                            Perbarui Akun
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            const levelSelect = $('#level_select');
            const parentSelect = $('#parent_id');
            const urutanInput = $('#urutan');
            const coaForm = $('#coaEditForm');

            function toggleParent() {
                if (levelSelect.val() == "1") {
                    parentSelect.val("").prop('disabled', true).addClass('bg-gray-100 dark:bg-gray-800');
                } else {
                    parentSelect.prop('disabled', false).removeClass('bg-gray-100 dark:bg-gray-800');
                }
            }

            levelSelect.on('change', toggleParent);
            toggleParent();

            coaForm.on('submit', function(e) {
                e.preventDefault();
                const urutanVal = parseInt(urutanInput.val());

                if (urutanVal <= 0 || isNaN(urutanVal)) {
                    $('#urutan-error').removeClass('hidden');
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: 'Urutan tampil nggak boleh 0 atau negatif ya.',
                        confirmButtonColor: '#f59e0b'
                    });
                    return;
                }

                Swal.fire({
                    title: 'Update Data Akun?',
                    text: "Pastikan perubahan kode atau level sudah benar agar tidak mengganggu laporan.",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#f59e0b',
                    cancelButtonColor: '#9ca3af',
                    confirmButtonText: 'Ya, Update!',
                    cancelButtonText: 'Cek Lagi'
                }).then((result) => {
                    if (result.isConfirmed) {
                        this.submit();
                    }
                });
            });

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
                    title: 'Update Gagal!',
                    text: "{{ session('error') ?? 'Ada data yang nggak pas nih, coba cek lagi.' }}",
                    confirmButtonColor: '#ef4444'
                });
            @endif
        });
    </script>
</x-app-layout>
