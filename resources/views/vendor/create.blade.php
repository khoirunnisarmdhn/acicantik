<x-app-layout>
    @section('title', 'Tambah Data Vendor')

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Tambah Vendor') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div
                class="bg-white dark:bg-gray-800 shadow-2xl rounded-2xl overflow-hidden border border-gray-100 dark:border-gray-700">

                <div class="bg-indigo-600 px-6 py-4 text-white font-bold text-lg flex items-center gap-2">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                    </svg>
                    Tambah Data Vendor Baru
                </div>

                <form id="formVendor" action="{{ route('vendor.store') }}" method="POST"
                    class="p-8 grid grid-cols-1 md:grid-cols-2 gap-6">
                    @csrf

                    <div class="col-span-2">
                        <label
                            class="block text-xs font-bold text-gray-500 dark:text-gray-400 uppercase mb-2 tracking-wider">
                            Nama Vendor / Perusahaan
                        </label>
                        <input type="text" name="nama" value="{{ old('nama') }}" required
                            placeholder="Masukkan nama resmi perusahaan..."
                            class="w-full rounded-xl border-gray-200 dark:bg-gray-900 dark:border-gray-700 dark:text-gray-300 focus:ring-2 focus:ring-indigo-500 transition-all">
                    </div>

                    <div class="col-span-2">
                        <label
                            class="block text-xs font-bold text-gray-500 dark:text-gray-400 uppercase mb-2 tracking-wider">
                            Alamat Kantor
                        </label>
                        <input type="text" name="alamat" value="{{ old('alamat') }}"
                            placeholder="Jl. Nama Jalan No. XX, Kota..."
                            class="w-full rounded-xl border-gray-200 dark:bg-gray-900 dark:border-gray-700 dark:text-gray-300 focus:ring-2 focus:ring-indigo-500 transition-all">
                    </div>

                    <div>
                        <label
                            class="block text-xs font-bold text-gray-500 dark:text-gray-400 uppercase mb-2 tracking-wider">
                            Nama Penanggung Jawab
                        </label>
                        <input type="text" name="penanggung_jawab" value="{{ old('penanggung_jawab') }}" required
                            placeholder="Nama person in charge..."
                            class="w-full rounded-xl border-gray-200 dark:bg-gray-900 dark:border-gray-700 dark:text-gray-300 focus:ring-2 focus:ring-indigo-500 transition-all">
                    </div>

                    <div>
                        <label
                            class="block text-xs font-bold text-gray-500 dark:text-gray-400 uppercase mb-2 tracking-wider">
                            Nomor Telepon / WA
                        </label>
                        <input type="tel" name="no_telp" id="no_telp" value="{{ old('no_telp') }}" required
                            placeholder="0812xxxx..." inputmode="numeric"
                            oninput="this.value = this.value.replace(/[^0-9+]/g, '')"
                            class="w-full rounded-xl border-gray-200 dark:bg-gray-900 dark:border-gray-700 dark:text-gray-300 focus:ring-2 focus:ring-indigo-500 transition-all">
                        <p class="text-[10px] text-gray-400 mt-1 italic">* Hanya angka dan simbol +</p>
                    </div>

                    <div class="col-span-2">
                        <label
                            class="block text-xs font-bold text-gray-500 dark:text-gray-400 uppercase mb-2 tracking-wider">
                            Email Vendor
                        </label>
                        <input type="email" name="email" value="{{ old('email') }}" required
                            placeholder="vendor@email.com"
                            class="w-full rounded-xl border-gray-200 dark:bg-gray-900 dark:border-gray-700 dark:text-gray-300 focus:ring-2 focus:ring-indigo-500 transition-all">
                    </div>

                    <div
                        class="col-span-2 flex justify-end gap-3 mt-4 border-t pt-6 border-gray-100 dark:border-gray-700">
                        <a href="{{ route('vendor.index') }}"
                            class="px-6 py-2.5 rounded-xl bg-gray-100 text-gray-600 font-bold hover:bg-gray-200 transition dark:bg-gray-700 dark:text-gray-300 dark:hover:bg-gray-600">
                            Batal
                        </a>
                        <button type="submit"
                            class="px-8 py-2.5 rounded-xl bg-indigo-600 text-white font-bold shadow-lg shadow-indigo-200 hover:bg-indigo-700 transition transform active:scale-95">
                            Simpan Vendor
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            // Konfirmasi sebelum simpan
            $('#formVendor').on('submit', function(e) {
                e.preventDefault();

                Swal.fire({
                    title: 'Simpan Data Vendor?',
                    text: "Pastikan kontak dan email sudah benar.",
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#4f46e5',
                    cancelButtonColor: '#9ca3af',
                    confirmButtonText: 'Ya, Simpan!',
                    cancelButtonText: 'Cek Lagi'
                }).then((result) => {
                    if (result.isConfirmed) {
                        this.submit();
                    }
                });
            });
        });

        // SweetAlert untuk notifikasi Flash Message dari Controller
        @if (session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: "{{ session('success') }}",
                timer: 3000,
                showConfirmButton: false
            });
        @endif

        @if (session('error') || $errors->any())
            Swal.fire({
                icon: 'error',
                title: 'Error!',
                text: "{{ session('error') ?? 'Ada data yang belum lengkap atau salah format.' }}",
                confirmButtonColor: '#4f46e5'
            });
        @endif
    </script>
</x-app-layout>
