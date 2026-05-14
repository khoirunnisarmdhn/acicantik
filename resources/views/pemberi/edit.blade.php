<x-app-layout>
    @section('title', 'Edit Data Pemberi')

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Edit Pemberi Proyek') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div
                class="bg-white dark:bg-gray-800 shadow-2xl rounded-2xl overflow-hidden border border-gray-100 dark:border-gray-700">

                {{-- Header --}}
                <div class="bg-amber-500 px-6 py-4 text-white font-bold text-lg flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                    Edit Data: {{ $pemberi->nama }}
                </div>

                <form action="{{ route('pemberi.update', $pemberi->id_pemberi) }}" method="POST"
                    class="p-8 grid grid-cols-1 md:grid-cols-2 gap-6">
                    @csrf
                    @method('PUT')

                    <div class="col-span-2 md:col-span-1">
                        <label
                            class="block text-xs font-bold text-gray-400 dark:text-gray-500 uppercase mb-2 tracking-widest">Jenis
                            Pemberi</label>
                        <select name="jenis" required
                            class="w-full rounded-xl border-gray-200 dark:bg-gray-900 dark:border-gray-700 dark:text-gray-300 focus:ring-2 focus:ring-amber-500 transition-all">
                            <option value="Perorangan" {{ $pemberi->jenis == 'Perorangan' ? 'selected' : '' }}>
                                Perorangan</option>
                            <option value="Swasta" {{ $pemberi->jenis == 'Swasta' ? 'selected' : '' }}>Swasta</option>
                            <option value="Pemerintah" {{ $pemberi->jenis == 'Pemerintah' ? 'selected' : '' }}>
                                Pemerintah</option>
                        </select>
                    </div>

                    <div class="col-span-2 md:col-span-1">
                        <label
                            class="block text-xs font-bold text-gray-400 dark:text-gray-500 uppercase mb-2 tracking-widest">Nama
                            Instansi / Owner</label>
                        <input type="text" name="nama" value="{{ old('nama', $pemberi->nama) }}" required
                            class="w-full rounded-xl border-gray-200 dark:bg-gray-900 dark:border-gray-700 dark:text-gray-300 focus:ring-2 focus:ring-amber-500 transition-all">
                    </div>

                    <div class="col-span-2">
                        <label
                            class="block text-xs font-bold text-gray-400 dark:text-gray-500 uppercase mb-2 tracking-widest">Alamat
                            Lengkap</label>
                        <input type="text" name="alamat" value="{{ old('alamat', $pemberi->alamat) }}"
                            class="w-full rounded-xl border-gray-200 dark:bg-gray-900 dark:border-gray-700 dark:text-gray-300 focus:ring-2 focus:ring-amber-500 transition-all">
                    </div>

                    <div>
                        <label
                            class="block text-xs font-bold text-gray-400 dark:text-gray-500 uppercase mb-2 tracking-widest">Penanggung
                            Jawab</label>
                        <input type="text" name="penanggung_jawab"
                            value="{{ old('penanggung_jawab', $pemberi->penanggung_jawab) }}" required
                            class="w-full rounded-xl border-gray-200 dark:bg-gray-900 dark:border-gray-700 dark:text-gray-300 focus:ring-2 focus:ring-amber-500 transition-all">
                    </div>

                    <div>
                        <label
                            class="block text-xs font-bold text-gray-400 dark:text-gray-500 uppercase mb-2 tracking-widest">Nomor
                            Telepon</label>
                        <input type="text" name="no_telp" value="{{ old('no_telp', $pemberi->no_telp) }}"
                            class="w-full rounded-xl border-gray-200 dark:bg-gray-900 dark:border-gray-700 dark:text-gray-300 focus:ring-2 focus:ring-amber-500 transition-all">
                    </div>

                    <div class="col-span-2">
                        <label
                            class="block text-xs font-bold text-gray-400 dark:text-gray-500 uppercase mb-2 tracking-widest">Email</label>
                        <input type="email" name="email" value="{{ old('email', $pemberi->email) }}"
                            class="w-full rounded-xl border-gray-200 dark:bg-gray-900 dark:border-gray-700 dark:text-gray-300 focus:ring-2 focus:ring-amber-500 transition-all">
                    </div>

                    <div class="col-span-2 flex justify-end gap-3 mt-4">
                        <a href="{{ route('pemberi.index') }}"
                            class="px-6 py-2.5 rounded-xl bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300 font-bold hover:bg-gray-200 transition">Batal</a>
                        <button type="submit"
                            class="px-8 py-2.5 rounded-xl bg-amber-500 text-white font-bold shadow-lg shadow-amber-100 hover:bg-amber-600 transition transform active:scale-95">
                            Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Script SweetAlert2 --}}
    <script>
        $(document).ready(function() {
            // 1. Alert Sukses (Redirect dari Controller)
            @if (session('success'))
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: "{{ session('success') }}",
                    timer: 2500,
                    showConfirmButton: false,
                    timerProgressBar: true
                });
            @endif

            // 2. Alert Gagal/Error Sistem (Database error, dsb)
            @if (session('error'))
                Swal.fire({
                    icon: 'error',
                    title: 'Sistem Error!',
                    text: "{{ session('error') }}",
                    confirmButtonColor: '#e11d48', // Rose-600
                    footer: '<span class="text-xs text-gray-400">Hubungi IT Support jika masalah berlanjut.</span>'
                });
            @endif

            // 3. Alert Validasi Laravel (Field tidak sesuai/kosong)
            @if ($errors->any())
                Swal.fire({
                    icon: 'warning',
                    title: 'Data Tidak Valid!',
                    html: `
                        <div class="text-left px-4">
                            <ul class="list-disc list-inside text-sm text-rose-600">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    `,
                    confirmButtonColor: '#f59e0b', // Amber-500
                });
            @endif
        });
    </script>
</x-app-layout>
