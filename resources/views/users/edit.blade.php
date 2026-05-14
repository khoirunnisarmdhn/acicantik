<x-app-layout>
    @section('title', 'Edit Data User')

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Edit User: ' . ($user->nama_lengkap ?? $user->name)) }}
        </h2>
    </x-slot>

    <div class="max-w-4xl mx-auto py-10 sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-gray-800 shadow-2xl rounded-2xl overflow-hidden border border-gray-100 dark:border-gray-700">
            
            {{-- Header --}}
            <div class="bg-amber-500 px-6 py-4 flex items-center gap-3">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                </svg>
                <h3 class="text-white font-bold text-lg">Perbarui Akun & Otoritas Akses</h3>
            </div>

            <form action="{{ route('users.update', $user->id) }}" method="POST" class="p-8">
                @csrf
                @method('PUT')
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                    {{-- Nama Lengkap --}}
                    <div class="col-span-2">
                        <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Nama Lengkap</label>
                        <input type="text" name="nama_lengkap" value="{{ old('nama_lengkap', $user->nama_lengkap) }}" required
                            class="w-full rounded-xl border-gray-200 dark:bg-gray-900 focus:ring-2 focus:ring-amber-500 transition-all"
                            placeholder="Contoh: Budi Santoso, S.T.">
                    </div>

                    {{-- Username --}}
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Username (ID Login)</label>
                        <input type="text" name="name" value="{{ old('name', $user->name) }}" required
                            class="w-full rounded-xl border-gray-200 dark:bg-gray-900 focus:ring-2 focus:ring-amber-500 transition-all font-mono">
                    </div>

                    {{-- Email --}}
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Email Perusahaan</label>
                        <input type="email" name="email" value="{{ old('email', $user->email) }}" required
                            class="w-full rounded-xl border-gray-200 dark:bg-gray-900 focus:ring-2 focus:ring-amber-500 transition-all">
                    </div>

                    {{-- Multi-Akses / Hak Akses --}}
                    <div class="col-span-2">
                        <label class="block text-xs font-bold text-amber-600 uppercase tracking-wider mb-3">Otoritas Akses Sistem</label>
                        
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 p-5 bg-gray-50 dark:bg-gray-900/50 rounded-2xl border border-gray-100 dark:border-gray-700">
                            @php
                                // Ambil ID akses yang sudah dimiliki user dari tabel pivot
                                $userAksesIds = DB::table('user_akses')
                                    ->where('user_id', $user->id)
                                    ->pluck('id_akses')
                                    ->toArray();
                            @endphp

                            @foreach ($akses as $a)
                                <label class="relative flex items-center p-3 rounded-xl border border-transparent bg-white dark:bg-gray-800 shadow-sm cursor-pointer hover:border-amber-300 transition-all group">
                                    <div class="flex items-center h-5">
                                        <input name="id_akses[]" type="checkbox" value="{{ $a->id_akses }}"
                                            {{ in_array($a->id_akses, old('id_akses', $userAksesIds)) ? 'checked' : '' }}
                                            class="w-5 h-5 text-amber-600 border-gray-300 rounded focus:ring-amber-500">
                                    </div>
                                    <div class="ml-3">
                                        <span class="block text-sm font-bold text-gray-700 dark:text-gray-200 group-hover:text-amber-600 transition-colors uppercase">
                                            {{ $a->nama_akses }}
                                        </span>
                                        <span class="text-[10px] text-gray-400 font-mono tracking-tight">Slug: {{ $a->fitur_slug }}</span>
                                    </div>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    {{-- Password (Opsional saat Edit) --}}
                    <div class="col-span-2 mt-4">
                        <div class="bg-amber-50 dark:bg-amber-900/20 p-4 rounded-xl border border-amber-100 dark:border-amber-800/50">
                            <p class="text-xs text-amber-700 dark:text-amber-400 font-bold mb-3 uppercase">Ganti Password (Kosongkan jika tidak ingin diubah)</p>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-[10px] font-bold text-gray-400 uppercase mb-1">Password Baru</label>
                                    <input type="password" name="password"
                                        class="w-full rounded-lg border-gray-200 dark:bg-gray-900 focus:ring-2 focus:ring-amber-500 transition-all">
                                </div>
                                <div>
                                    <label class="block text-[10px] font-bold text-gray-400 uppercase mb-1">Konfirmasi Password Baru</label>
                                    <input type="password" name="password_confirmation"
                                        class="w-full rounded-lg border-gray-200 dark:bg-gray-900 focus:ring-2 focus:ring-amber-500 transition-all">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Action Buttons --}}
                <div class="mt-8 flex justify-end gap-3 border-t dark:border-gray-700 pt-6">
                    <a href="{{ route('users.index') }}"
                        class="px-6 py-2.5 rounded-xl bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300 font-bold hover:bg-gray-200 transition">Batal</a>
                    <button type="submit"
                        class="px-8 py-2.5 rounded-xl bg-amber-500 text-white font-bold shadow-lg shadow-amber-100 dark:shadow-none hover:bg-amber-600 transition transform active:scale-95">
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        @if ($errors->any())
            Swal.fire({
                icon: 'error',
                title: 'Data Tidak Valid!',
                html: `<div class="text-left text-sm">@foreach ($errors->all() as $error)<p>• {{ $error }}</p>@endforeach</div>`,
                confirmButtonColor: '#f59e0b'
            });
        @endif
    </script>
</x-app-layout>