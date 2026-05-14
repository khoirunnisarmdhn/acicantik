<x-app-layout>
    @section('title', 'Tambah Kategori Kas')

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Tambah Kategori Baru') }}
        </h2>
    </x-slot>

    <div class="py-12" x-data="{ 
        jenisArus: 'masuk', 
        klasifikasi: 'non-proyek',
        namaKategori: '' 
    }">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 shadow-2xl rounded-3xl overflow-hidden border border-gray-100 dark:border-gray-700">

                <div class="px-8 py-6 text-white font-bold flex items-center justify-between transition-all duration-500 shadow-inner"
                    :class="jenisArus === 'masuk' ? 'bg-gradient-to-r from-emerald-600 to-teal-500' : 'bg-gradient-to-r from-rose-600 to-pink-500'">
                    <div class="flex items-center gap-3">
                        <div class="p-2 bg-white/20 rounded-lg backdrop-blur-md">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-[10px] uppercase tracking-[0.2em] opacity-80 mb-0.5">Master Data Kategori</p>
                            <h3 class="text-xl tracking-tight">Buat Baru: <span x-text="namaKategori || '...'"></span></h3>
                        </div>
                    </div>
                    <span class="text-[10px] bg-black/10 border border-white/20 px-3 py-1.5 rounded-full tracking-widest uppercase font-black backdrop-blur-sm">
                        <span x-text="jenisArus"></span> Mode
                    </span>
                </div>

                <form action="{{ route('kategori.store') }}" method="POST" class="p-8">
                    @csrf
                    
                    <div class="space-y-8">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                            <div>
                                <label class="block text-[10px] font-black text-gray-400 uppercase mb-3 tracking-[0.15em]">Jenis Arus Kas</label>
                                <div class="flex gap-3">
                                    <label class="flex-1 cursor-pointer group">
                                        <input type="radio" name="arus" value="masuk" x-model="jenisArus" class="hidden peer" checked>
                                        <div class="p-3 text-center rounded-2xl border-2 border-gray-100 peer-checked:border-emerald-500 peer-checked:bg-emerald-50 peer-checked:text-emerald-700 font-bold text-xs uppercase transition-all duration-300">
                                            📥 Masuk
                                        </div>
                                    </label>
                                    <label class="flex-1 cursor-pointer group">
                                        <input type="radio" name="arus" value="keluar" x-model="jenisArus" class="hidden peer">
                                        <div class="p-3 text-center rounded-2xl border-2 border-gray-100 peer-checked:border-rose-500 peer-checked:bg-rose-50 peer-checked:text-rose-700 font-bold text-xs uppercase transition-all duration-300">
                                            📤 Keluar
                                        </div>
                                    </label>
                                </div>
                            </div>

                            <div>
                                <label class="block text-[10px] font-black text-gray-400 uppercase mb-3 tracking-[0.15em]">Klasifikasi</label>
                                <div class="flex gap-3">
                                    <label class="flex-1 cursor-pointer group">
                                        <input type="radio" name="jenis" value="proyek" x-model="klasifikasi" class="hidden peer">
                                        <div class="p-3 text-center rounded-2xl border-2 border-gray-100 peer-checked:border-indigo-500 peer-checked:bg-indigo-50 peer-checked:text-indigo-700 font-bold transition-all text-[10px] uppercase">
                                            🚧 Proyek
                                        </div>
                                    </label>
                                    <label class="flex-1 cursor-pointer group">
                                        <input type="radio" name="jenis" value="non-proyek" x-model="klasifikasi" class="hidden peer" checked>
                                        <div class="p-3 text-center rounded-2xl border-2 border-gray-100 peer-checked:border-slate-500 peer-checked:bg-slate-50 peer-checked:text-slate-700 font-bold transition-all text-[10px] uppercase">
                                            🏢 Umum
                                        </div>
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div>
                            <label class="block text-[10px] font-black text-gray-400 uppercase mb-2 tracking-[0.15em]">Nama Kategori</label>
                            <input type="text" name="nama_kategori" required x-model="namaKategori" value="{{ old('nama_kategori') }}"
                                class="w-full h-12 px-4 rounded-2xl border-gray-200 dark:bg-gray-900 dark:text-white focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all font-bold text-lg"
                                placeholder="Contoh: Pendapatan Jasa / Biaya Listrik...">
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 bg-gray-50/50 dark:bg-gray-900/50 p-6 rounded-3xl border border-gray-100 dark:border-gray-700">
                            <div>
                                <label class="block text-[10px] font-black text-emerald-600 uppercase mb-2 tracking-[0.15em]">Mapping Akun Debit (+)</label>
                                <select name="id_coa_debit" required
                                    class="w-full h-11 rounded-xl border-gray-200 dark:bg-gray-800 focus:ring-4 focus:ring-emerald-500/10 focus:border-emerald-500 text-sm font-medium">
                                    <option value="">-- Pilih Akun Debit --</option>
                                    @foreach ($coa as $c)
                                        <option value="{{ $c->id_coa }}">
                                            [{{ $c->kode_akun }}] — {{ $c->nama_akun }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label class="block text-[10px] font-black text-rose-600 uppercase mb-2 tracking-[0.15em]">Mapping Akun Kredit (-)</label>
                                <select name="id_coa_kredit" required
                                    class="w-full h-11 rounded-xl border-gray-200 dark:bg-gray-800 focus:ring-4 focus:ring-rose-500/10 focus:border-rose-500 text-sm font-medium">
                                    <option value="">-- Pilih Akun Kredit --</option>
                                    @foreach ($coa as $c)
                                        <option value="{{ $c->id_coa }}">
                                            [{{ $c->kode_akun }}] — {{ $c->nama_akun }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div>
                            <label class="block text-[10px] font-black text-gray-400 uppercase mb-2 tracking-[0.15em]">Catatan / Deskripsi Tambahan</label>
                            <textarea name="deskripsi" rows="3"
                                class="w-full rounded-2xl border-gray-200 dark:bg-gray-900 dark:text-white focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all text-sm p-4"
                                placeholder="Tambahkan keterangan jika perlu...">{{ old('deskripsi') }}</textarea>
                        </div>
                    </div>

                    <div class="flex justify-end gap-4 mt-10 border-t pt-8 border-gray-100 dark:border-gray-700">
                        <a href="{{ route('kategori.index') }}"
                            class="px-6 py-3 rounded-2xl bg-gray-100 dark:bg-gray-700 text-gray-500 font-bold hover:bg-gray-200 transition-all text-[10px] uppercase tracking-widest">
                            Batal
                        </a>
                        <button type="submit"
                            class="px-10 py-3 rounded-2xl text-white font-black shadow-2xl transition-all duration-300 transform active:scale-95 text-[10px] uppercase tracking-[0.2em]"
                            :class="jenisArus === 'masuk' ? 'bg-emerald-600 shadow-emerald-200 hover:bg-emerald-700' : 'bg-rose-600 shadow-rose-200 hover:bg-rose-700'">
                            Simpan Kategori
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        @if (session('error'))
            Swal.fire({
                icon: 'error',
                title: 'GAGAL SIMPAN',
                text: "{{ session('error') }}",
                confirmButtonColor: '#ef4444',
                customClass: { popup: 'rounded-3xl' }
            });
        @endif

        @if ($errors->any())
            Swal.fire({
                icon: 'error',
                title: 'VALIDASI GAGAL',
                html: '<div class="text-left text-xs">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</div>',
                confirmButtonColor: '#ef4444',
                customClass: { popup: 'rounded-3xl' }
            });
        @endif
    </script>
</x-app-layout>