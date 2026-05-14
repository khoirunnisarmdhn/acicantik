<x-app-layout>
    @section('title', 'Tambah Data COA')

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Tambah Akun (COA)') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div
                class="bg-white dark:bg-gray-800 shadow-2xl rounded-2xl overflow-hidden border border-gray-100 dark:border-gray-700">

                <div class="bg-indigo-600 px-6 py-4 text-white font-bold flex items-center gap-2 shadow-inner">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 13h6m-3-3v6m5 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    Konfigurasi Akun Baru
                </div>

                <form id="coaForm" action="{{ route('coa.store') }}" method="POST" class="p-8">
                    @csrf

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                        <div class="col-span-2 md:col-span-1">
                            <label class="block text-xs font-bold text-gray-400 uppercase mb-2 tracking-widest">Kode
                                Akun</label>
                            <input type="text" name="kode_akun" value="{{ old('kode_akun') }}" required
                                class="w-full rounded-xl border-gray-200 dark:bg-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 transition-all @error('kode_akun') border-red-500 @enderror"
                                placeholder="Contoh: 1101">
                            @error('kode_akun')
                                <p class="text-red-500 text-[10px] mt-1 font-bold italic">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="col-span-2 md:col-span-1">
                            <label class="block text-xs font-bold text-gray-400 uppercase mb-2 tracking-widest">Nama
                                Akun</label>
                            <input type="text" name="nama_akun" value="{{ old('nama_akun') }}" required
                                class="w-full rounded-xl border-gray-200 dark:bg-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 transition-all"
                                placeholder="Contoh: Kas Operasional">
                        </div>

                        <div>
                            <label
                                class="block text-xs font-bold text-gray-400 uppercase mb-2 tracking-widest">Level</label>
                            <select name="level" id="level_select" required
                                class="w-full rounded-xl border-gray-200 dark:bg-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500">
                                <option value="1" {{ old('level') == 1 ? 'selected' : '' }}>Level 1 (Header Utama)
                                </option>
                                <option value="2" {{ old('level') == 2 ? 'selected' : '' }}>Level 2 (Sub-Header)
                                </option>
                                <option value="3" {{ old('level') == 3 ? 'selected' : '' }}>Level 3 (Akun
                                    Transaksi)</option>
                            </select>
                            <p class="text-[10px] text-gray-400 mt-1 italic">*Level 3 digunakan untuk posting jurnal.
                            </p>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-gray-400 uppercase mb-2 tracking-widest">Parent
                                Akun (Induk)</label>
                            <select name="parent_id" id="parent_id"
                                class="w-full rounded-xl border-gray-200 dark:bg-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 disabled:opacity-50">
                                <option value="">-- Tanpa Induk --</option>
                                @foreach ($parents as $p)
                                    <option value="{{ $p->id_coa }}"
                                        {{ old('parent_id') == $p->id_coa ? 'selected' : '' }}>
                                        [{{ $p->kode_akun }}] {{ $p->nama_akun }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="md:col-span-1">
                            <label class="block text-xs font-bold text-gray-400 uppercase mb-2 tracking-widest">Urutan
                                Tampil</label>
                            <input type="text" name="urutan" id="urutan" value="{{ old('urutan', 1) }}" required
                                min="1"
                                class="rupiah w-full rounded-xl border-gray-200 dark:bg-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500">
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
                            class="px-10 py-2.5 rounded-xl bg-indigo-600 text-white font-bold shadow-lg shadow-indigo-200 hover:bg-indigo-700 transition duration-200 transform active:scale-95">
                            Simpan Akun
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
            const coaForm = $('#coaForm');

            // 1. Logic Toggle Parent
            function toggleParent() {
                if (levelSelect.val() == "1") {
                    parentSelect.val("").prop('disabled', true).addClass('bg-gray-100 dark:bg-gray-800');
                } else {
                    parentSelect.prop('disabled', false).removeClass('bg-gray-100 dark:bg-gray-800');
                }
            }

            levelSelect.on('change', toggleParent);
            toggleParent();

            // 2. SweetAlert Konfirmasi Simpan & Validasi Urutan
            coaForm.on('submit', function(e) {
                e.preventDefault();
                const urutanVal = parseInt(urutanInput.val());

                if (urutanVal <= 0 || isNaN(urutanVal)) {
                    $('#urutan-error').removeClass('hidden');
                    Swal.fire({
                        icon: 'error',
                        title: 'Kesalahan Input',
                        text: 'Urutan tampil harus lebih besar dari 0!',
                        confirmButtonColor: '#4f46e5'
                    });
                    return;
                }

                $('#urutan-error').addClass('hidden');

                Swal.fire({
                    title: 'Simpan Akun COA?',
                    text: "Pastikan kode dan level sudah sesuai struktur.",
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#4f46e5',
                    cancelButtonColor: '#9ca3af',
                    confirmButtonText: 'Ya, Simpan!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        this.submit();
                    }
                });
            });

            // 3. SweetAlert Notifikasi Flash dari Controller
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
                    title: 'Gagal Menyimpan!',
                    text: "{{ session('error') ?? 'Cek kembali form anda, ada data yang tidak valid.' }}",
                    confirmButtonColor: '#ef4444'
                });
            @endif
        });
    </script>
</x-app-layout>
