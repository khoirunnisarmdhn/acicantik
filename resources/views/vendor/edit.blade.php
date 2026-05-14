<x-app-layout>
    @section('title', 'Edit Data Vendor')

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Edit Vendor') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 shadow-2xl rounded-2xl overflow-hidden border border-gray-100 dark:border-gray-700">

                <div class="bg-amber-500 px-6 py-4 text-white font-bold text-lg flex items-center gap-2">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                    Edit Data: {{ $vendor->nama }}
                </div>

                <form id="formEditVendor" action="{{ route('vendor.update', $vendor->id_vendor) }}" method="POST"
                    class="p-8 grid grid-cols-1 md:grid-cols-2 gap-6">
                    @csrf
                    @method('PUT')

                    <div class="col-span-2">
                        <label class="block text-xs font-bold text-gray-500 dark:text-gray-400 uppercase mb-2 tracking-wider">
                            Nama Vendor / Perusahaan
                        </label>
                        <input type="text" name="nama" value="{{ old('nama', $vendor->nama) }}" required
                            placeholder="Masukkan nama resmi perusahaan..."
                            class="w-full rounded-xl border-gray-200 dark:bg-gray-900 dark:border-gray-700 dark:text-gray-300 focus:ring-2 focus:ring-amber-500 transition-all">
                    </div>

                    <div class="col-span-2">
                        <label class="block text-xs font-bold text-gray-500 dark:text-gray-400 uppercase mb-2 tracking-wider">
                            Alamat Kantor
                        </label>
                        <input type="text" name="alamat" value="{{ old('alamat', $vendor->alamat) }}" required
                            placeholder="Jl. Nama Jalan No. XX, Kota..."
                            class="w-full rounded-xl border-gray-200 dark:bg-gray-900 dark:border-gray-700 dark:text-gray-300 focus:ring-2 focus:ring-amber-500 transition-all">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-500 dark:text-gray-400 uppercase mb-2 tracking-wider">
                            Nama Penanggung Jawab
                        </label>
                        <input type="text" name="penanggung_jawab" value="{{ old('penanggung_jawab', $vendor->penanggung_jawab) }}" required
                            placeholder="Nama person in charge..."
                            class="w-full rounded-xl border-gray-200 dark:bg-gray-900 dark:border-gray-700 dark:text-gray-300 focus:ring-2 focus:ring-amber-500 transition-all">
                    </div>

                    {{-- UPDATE: Input Nomor Telepon --}}
                    <div>
                        <label class="block text-xs font-bold text-gray-500 dark:text-gray-400 uppercase mb-2 tracking-wider">
                            Nomor Telepon / WA
                        </label>
                        <input type="tel" name="no_telp" id="no_telp" value="{{ old('no_telp', $vendor->no_telp) }}" required
                            placeholder="0812xxxx..."
                            inputmode="numeric" 
                            oninput="this.value = this.value.replace(/[^0-9+]/g, '')"
                            class="w-full rounded-xl border-gray-200 dark:bg-gray-900 dark:border-gray-700 dark:text-gray-300 focus:ring-2 focus:ring-amber-500 transition-all">
                        <p class="text-[10px] text-gray-400 mt-1 italic">* Hanya angka dan simbol +</p>
                    </div>

                    {{-- UPDATE: Input Email --}}
                    <div class="col-span-2">
                        <label class="block text-xs font-bold text-gray-500 dark:text-gray-400 uppercase mb-2 tracking-wider">
                            Email Vendor
                        </label>
                        <input type="email" name="email" value="{{ old('email', $vendor->email) }}" required
                            placeholder="vendor@email.com"
                            class="w-full rounded-xl border-gray-200 dark:bg-gray-900 dark:border-gray-700 dark:text-gray-300 focus:ring-2 focus:ring-amber-500 transition-all">
                    </div>

                    <div class="col-span-2 flex justify-end gap-3 mt-4 border-t pt-6 border-gray-100 dark:border-gray-700">
                        <a href="{{ route('vendor.index') }}"
                            class="px-6 py-2.5 rounded-xl bg-gray-100 text-gray-600 font-bold hover:bg-gray-200 transition dark:bg-gray-700 dark:text-gray-300 dark:hover:bg-gray-600">
                            Batal
                        </a>
                        <button type="submit"
                            class="px-8 py-2.5 rounded-xl bg-amber-500 text-white font-bold shadow-lg shadow-amber-200 hover:bg-amber-600 transition transform active:scale-95">
                            Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            // Konfirmasi sebelum update
            $('#formEditVendor').on('submit', function(e) {
                e.preventDefault();

                Swal.fire({
                    title: 'Update Data Vendor?',
                    text: "Data yang lama akan diperbarui dengan data baru ini.",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#f59e0b',
                    cancelButtonColor: '#9ca3af',
                    confirmButtonText: 'Ya, Perbarui!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        this.submit();
                    }
                });
            });
        });

        // --- HANDLER NOTIFIKASI (SWEETALERT2) ---
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
                title: 'Gagal Update!',
                text: "{{ session('error') ?? 'Ada data yang tidak valid. Periksa kembali inputan Anda.' }}",
                html: `@if($errors->any())<ul class='text-left text-sm mt-2'>@foreach($errors->all() as $error)<li>• {{ $error }}</li>@endforeach</ul>@endif`,
                confirmButtonColor: '#f59e0b'
            });
        @endif
    </script>
</x-app-layout>