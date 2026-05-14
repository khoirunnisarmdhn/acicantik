<x-app-layout>
    @section('title', 'Tambah Data User')

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Tambah User Baru') }}
        </h2>
    </x-slot>

    <div class="max-w-4xl mx-auto py-10 sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-gray-800 shadow-2xl rounded-2xl overflow-hidden border border-gray-100 dark:border-gray-700">
            
            {{-- Header --}}
            <div class="bg-indigo-600 px-6 py-4 flex items-center gap-3">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                </svg>
                <h3 class="text-white font-bold text-lg">Konfigurasi Akun & Akses Staff</h3>
            </div>

            <form action="{{ route('users.store') }}" method="POST" class="p-8">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                    {{-- Nama Lengkap --}}
                    <div class="col-span-2">
                        <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Nama Lengkap</label>
                        <input type="text" name="nama_lengkap" value="{{ old('nama_lengkap') }}" required
                            class="w-full rounded-xl border-gray-200 dark:bg-gray-900 focus:ring-2 focus:ring-indigo-500 transition-all placeholder:text-gray-300"
                            placeholder="Contoh: Budi Santoso, S.T.">
                    </div>

                    {{-- Username (Field: name) --}}
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Username (ID Login)</label>
                        <input type="text" name="name" value="{{ old('name') }}" required
                            class="w-full rounded-xl border-gray-200 dark:bg-gray-900 focus:ring-2 focus:ring-indigo-500 transition-all font-mono"
                            placeholder="budi_santoso">
                    </div>

                    {{-- Email --}}
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Email Perusahaan</label>
                        <input type="email" name="email" value="{{ old('email') }}" required
                            class="w-full rounded-xl border-gray-200 dark:bg-gray-900 focus:ring-2 focus:ring-indigo-500 transition-all"
                            placeholder="budi@perusahaan.com">
                    </div>

                    {{-- Multi-Akses / Hak Akses (Ganti Select jadi Checkbox Grid) --}}
                    <div class="col-span-2">
                        <label class="block text-xs font-bold text-indigo-600 uppercase tracking-wider mb-3">Otoritas Akses Sistem (Bisa pilih lebih dari 1)</label>
                        
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 p-5 bg-gray-50 dark:bg-gray-900/50 rounded-2xl border border-gray-100 dark:border-gray-700">
                            @foreach ($akses as $a)
                                <label class="relative flex items-center p-3 rounded-xl border border-transparent bg-white dark:bg-gray-800 shadow-sm cursor-pointer hover:border-indigo-300 transition-all group">
                                    <div class="flex items-center h-5">
                                        <input name="id_akses[]" type="checkbox" value="{{ $a->id_akses }}"
                                            {{ is_array(old('id_akses')) && in_array($a->id_akses, old('id_akses')) ? 'checked' : '' }}
                                            class="w-5 h-5 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500">
                                    </div>
                                    <div class="ml-3">
                                        <span class="block text-sm font-bold text-gray-700 dark:text-gray-200 group-hover:text-indigo-600 transition-colors uppercase">
                                            {{ $a->nama_akses }}
                                        </span>
                                        <span class="text-[10px] text-gray-400 font-mono tracking-tight">Slug: {{ $a->fitur_slug }}</span>
                                    </div>
                                </label>
                            @endforeach
                        </div>
                        
                        <p class="text-[10px] text-gray-400 mt-3 italic">*Centang satu atau beberapa akses yang akan diberikan kepada user ini.</p>
                    </div>

                    {{-- Password --}}
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Password</label>
                        <input type="password" name="password" required
                            class="w-full rounded-xl border-gray-200 dark:bg-gray-900 focus:ring-2 focus:ring-indigo-500 transition-all">
                    </div>

                    {{-- Konfirmasi Password --}}
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Ulangi Password</label>
                        <input type="password" name="password_confirmation" required
                            class="w-full rounded-xl border-gray-200 dark:bg-gray-900 focus:ring-2 focus:ring-indigo-500 transition-all">
                    </div>
                </div>

                {{-- Action Buttons --}}
                <div class="mt-8 flex justify-end gap-3 border-t dark:border-gray-700 pt-6">
                    <a href="{{ route('users.index') }}"
                        class="px-6 py-2.5 rounded-xl bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300 font-bold hover:bg-gray-200 transition">Batal</a>
                    <button type="submit"
                        class="px-8 py-2.5 rounded-xl bg-indigo-600 text-white font-bold shadow-lg shadow-indigo-100 dark:shadow-none hover:bg-indigo-700 transition transform active:scale-95">
                        Simpan User Baru
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        {{-- Handle SweetAlert2 for Validation Errors --}}
        @if ($errors->any())
            Swal.fire({
                icon: 'error',
                title: 'Data Tidak Valid!',
                html: `
                    <div class="text-left text-sm">
                        @foreach ($errors->all() as $error)
                            <p>• {{ $error }}</p>
                        @endforeach
                    </div>
                `,
                confirmButtonColor: '#4f46e5'
            });
        @endif
    </script>
</x-app-layout>