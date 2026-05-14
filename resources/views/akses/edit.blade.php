<x-app-layout>
    @section('title', 'Edit Akses')

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Edit Hak Akses User') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 shadow-xl rounded-2xl overflow-hidden border border-gray-100">
                <div class="p-6 border-b border-gray-100 bg-gray-50/50">
                    <h3 class="text-lg font-bold text-gray-800">Update Konfigurasi Role: {{ $akses->nama_akses }}</h3>
                </div>

                <form action="{{ route('akses.update', $akses->id_akses) }}" method="POST" class="p-6 space-y-6">
                    @csrf
                    @method('PUT')
                    
                    {{-- Nama Akses --}}
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Nama Role / Akses</label>
                        <input type="text" name="nama_akses" value="{{ old('nama_akses', $akses->nama_akses) }}" 
                            placeholder="Contoh: ADM_KEUANGAN"
                            class="w-full rounded-xl border-gray-200 focus:ring-indigo-500 focus:border-indigo-500 uppercase font-mono">
                        @error('nama_akses') <p class="text-rose-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    {{-- Grid Checkbox Izin Menu --}}
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-3">Izin Akses Menu (Fitur)</label>
                        <div class="grid grid-cols-2 md:grid-cols-3 gap-4 p-4 bg-gray-50 rounded-2xl border border-gray-100">
                            @php 
                                $currentSlugs = !empty($akses->fitur_slug) ? explode(',', $akses->fitur_slug) : []; 
                            @endphp
                            @foreach ($menus as $menu)
                                <label class="flex items-center space-x-3 cursor-pointer group">
                                    <input type="checkbox" name="fitur_slug[]" value="{{ $menu }}"
                                        {{ in_array($menu, $currentSlugs) ? 'checked' : '' }}
                                        class="w-5 h-5 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                                    <span class="text-sm text-gray-600 group-hover:text-indigo-600 font-medium uppercase">{{ $menu }}</span>
                                </label>
                            @endforeach
                        </div>
                        @error('fitur_slug') <p class="text-rose-500 text-xs mt-2">{{ $message }}</p> @enderror
                    </div>

                    <div class="flex justify-end gap-3 pt-4">
                        <a href="{{ route('akses.index') }}" class="px-6 py-2.5 rounded-xl text-sm font-semibold text-gray-600 hover:bg-gray-100 transition">Batal</a>
                        <button type="submit" class="px-6 py-2.5 rounded-xl text-sm font-semibold text-white bg-indigo-600 hover:bg-indigo-700 shadow-md transition">Update Akses</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        @if ($errors->any())
            Swal.fire({
                icon: 'error',
                title: 'Validasi Gagal!',
                html: `<ul style="text-align: left; font-size: 14px;">@foreach ($errors->all() as $error)<li>- {{ $error }}</li>@endforeach</ul>`,
                confirmButtonColor: '#6366f1'
            });
        @endif

        @if (session('error'))
            Swal.fire({
                icon: 'error',
                title: 'Gagal!',
                text: "{{ session('error') }}",
                confirmButtonColor: '#e11d48'
            });
        @endif
    </script>
</x-app-layout>