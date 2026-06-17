<x-app-layout>
    @section('title', 'Input Kas Keluar')

    <div class="py-12" x-data="{
        isProyek: false,
        showVendor: false,
        placeholderRincian: 'Contoh: Semen 50 sak',
        placeholderKeterangan: 'Contoh: Pembelian material semen 50 sak...',
        kategoriUmum: @js($kategoriUmum),
        kategoriProyek: @js($kategoriProyek),
        kategoriAktif: @js($kategoriUmum),
        rincian: [{ nama: '', nominal: '' }],
        handleProyekChange(id) {
            if (id !== '') {
                this.isProyek = true;
                this.kategoriAktif = this.kategoriProyek;
            } else {
                this.isProyek = false;
                this.kategoriAktif = this.kategoriUmum;
                this.showVendor = false;
            }
        },
        handleKategoriChange(el) {
            const nama = el.options[el.selectedIndex].text.toLowerCase();
            this.showVendor = nama.includes('material') || nama.includes('pembelian');
            if (nama.includes('material') || nama.includes('pembelian')) {
                this.placeholderRincian = 'Contoh: Semen 50 sak';
                this.placeholderKeterangan = 'Contoh: Pembelian material semen 50 sak...';
            } else if (nama.includes('tenaga') || nama.includes('kerja')) {
                this.placeholderRincian = 'Contoh: Gaji 5 Orang Karyawan';
                this.placeholderKeterangan = 'Contoh: Pembayaran gaji tukang dan mandor...';
            } else if (nama.includes('operasional')) {
                this.placeholderRincian = 'Contoh: Biaya Listrik 2 Bulan';
                this.placeholderKeterangan = 'Contoh: Biaya operasional lapangan...';
            } else {
                this.placeholderRincian = 'Nama item pengeluaran';
                this.placeholderKeterangan = 'Keterangan pengeluaran...';
            }
        }
    }" x-init="@if (old('id_proyek')) handleProyekChange('{{ old('id_proyek') }}') @endif">

        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div
                class="bg-white dark:bg-gray-800 shadow-2xl rounded-[2rem] overflow-hidden border border-gray-100 dark:border-gray-700">

                <div class="px-8 py-8 bg-gradient-to-br from-rose-600 to-red-700 text-white relative">
                    <div class="relative z-10 flex justify-between items-center">
                        <div>
                            <p class="text-[10px] font-black uppercase tracking-[0.3em] opacity-70 mb-1">Formulir
                                Pengeluaran</p>
                            <h3 class="text-2xl font-black tracking-tighter uppercase">Kas Keluar</h3>
                        </div>
                        <div class="text-right">
                            <span class="block text-[10px] font-bold opacity-70 uppercase">No. Referensi</span>
                            <span class="text-xl font-mono font-bold">{{ $no_form }}</span>
                        </div>
                    </div>
                </div>

                <form action="{{ route('kas-keluar.store') }}" method="POST" enctype="multipart/form-data"
                    class="p-8 md:p-10">
                    @csrf
                    <input type="hidden" name="no_form" value="{{ $no_form }}">

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-6">

                        <div class="md:col-span-2">
                            <label class="block text-[11px] font-black text-gray-400 uppercase mb-2 tracking-widest">1.
                                Alokasi Proyek (Opsional)</label>
                            <select name="id_proyek" @change="handleProyekChange($event.target.value)"
                                class="w-full border-2 border-gray-100 rounded-2xl focus:ring-4 focus:ring-rose-500/10 focus:border-rose-500 font-bold text-gray-700 transition-all">
                                <option value="">-- PENGELUARAN UMUM (NON-PROYEK) --</option>
                                @foreach ($proyek as $p)
                                    <option value="{{ $p->id_proyek }}"
                                        {{ old('id_proyek') == $p->id_proyek ? 'selected' : '' }}>{{ $p->nama }}
                                    </option>
                                @endforeach
                            </select>
                            <p class="mt-1 text-[10px] text-gray-400">Pilih proyek jika pengeluaran ini dibebankan ke
                                proyek tertentu.</p>
                        </div>

                        <div class="md:col-span-2">
                            <label class="block text-[11px] font-black text-gray-400 uppercase mb-2 tracking-widest">2.
                                Kategori Pengeluaran</label>
                            <select name="id_kategori" required @change="handleKategoriChange($el)"
                                class="w-full border-2 border-gray-100 rounded-2xl focus:ring-4 focus:ring-rose-500/10 focus:border-rose-500 font-medium">
                                <option value="">-- Pilih Kategori --</option>
                                <template x-for="kat in kategoriAktif" :key="kat.id_kategori">
                                    <option :value="kat.id_kategori"
                                        :selected="kat.id_kategori == '{{ old('id_kategori') }}'"
                                        x-text="kat.nama_kategori"></option>
                                </template>
                            </select>
                        </div>

                        <!-- if Kategori Pengeluaran selected, show a text input and number input, theris a plus icn to ad more kategori pengeluaran -->

                        <!-- RINCIAN PENGELUARAN -->
                        <div class="md:col-span-2 space-y-4" x-show="true">

                            <div class="flex items-center justify-between">
                                <label class="block text-[11px] font-black text-gray-400 uppercase tracking-widest">
                                    Rincian Pengeluaran
                                </label>

                                <button type="button"
                                    @click="rincian.push({
                nama: '',
                nominal: ''
            })"
                                    class="w-9 h-9 rounded-full bg-rose-600 text-white font-black hover:bg-rose-700 transition-all">
                                    +
                                </button>
                            </div>

                            <template x-for="(item, index) in rincian" :key="index">
                                <div class="grid grid-cols-12 gap-3 items-center">

                                    <!-- TEXT -->
                                    <div class="col-span-7">
                                        <input type="text" :name="`rincian[${index}][nama]`" x-model="item.nama"
                                            :placeholder="placeholderRincian"
                                            class="w-full border-2 border-gray-100 rounded-2xl px-4 py-3 focus:ring-4 focus:ring-rose-500/10 focus:border-rose-500">
                                    </div>

                                    <!-- NOMINAL -->
                                    <div class="col-span-4">
                                        <div class="relative">
                                            <div
                                                class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                                <span class="text-gray-400 font-black text-sm">
                                                    Rp
                                                </span>
                                            </div>

                                            <input type="text" min="0" :name="`rincian[${index}][nominal]`"
                                                x-model="item.nominal" placeholder="0"
                                                class="rupiah w-full pl-12 pr-4 py-3 border-2 border-gray-100 rounded-2xl font-bold text-rose-700 focus:ring-4 focus:ring-rose-500/10">
                                        </div>
                                    </div>

                                    <!-- DELETE -->
                                    <div class="col-span-1 flex justify-end">
                                        <button type="button" x-show="rincian.length > 1"
                                            @click="rincian.splice(index, 1)"
                                            class="w-10 h-10 rounded-full bg-red-100 text-red-600 font-black hover:bg-red-200 transition-all">
                                            ×
                                        </button>
                                    </div>
                                </div>
                            </template>

                            <!-- TOTAL -->
                            {{-- <div class="md:col-span-2"> --}}

                            <div class="flex justify-center pt-4">
                                <div
                                    class="inline-flex flex-col items-center bg-rose-50 border border-rose-100 rounded-2xl px-8 py-5">

                                    <p class="text-[10px] uppercase tracking-widest font-black text-rose-400 mb-2">
                                        Total Pengeluaran
                                    </p>

                                    <h3 class="text-3xl font-black text-rose-700 leading-none">
                                        Rp
                                        <span
                                            x-text="
        new Intl.NumberFormat('id-ID').format(
            rincian.reduce((sum, item) => {
                return sum + (
                    parseInt(
                        (item.nominal || '').toString().replace(/\./g, '')
                    ) || 0
                )
            }, 0)
        )
    ">
                                        </span>
                                    </h3>
                                </div>
                            </div>
                        </div>

                        <div class="md:col-span-2">
                            <label class="block text-[11px] font-black text-gray-400 uppercase mb-2 tracking-widest">3.
                                Tanggal Bayar</label>
                            <input type="date" name="tanggal" required value="{{ old('tanggal', date('Y-m-d')) }}"
                                class="w-full border-2 border-gray-100 rounded-2xl focus:ring-4 focus:ring-rose-500/10 focus:border-rose-500 font-bold">
                        </div>

                        <div class="md:col-span-2" x-show="showVendor" x-transition x-cloak>
                            <label class="block text-[11px] font-black text-rose-500 uppercase mb-2 tracking-widest">4.
                                Vendor / Penerima</label>
                            <select name="id_vendor"
                                class="w-full border-2 border-rose-50 border-dashed bg-rose-50/30 rounded-2xl font-bold text-rose-700 focus:ring-4 focus:ring-rose-500/10">
                                <option value="">-- Tanpa Vendor (Langsung) --</option>
                                @foreach ($vendor as $v)
                                    <option value="{{ $v->id_vendor }}"
                                        {{ old('id_vendor') == $v->id_vendor ? 'selected' : '' }}>{{ $v->nama }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div
                            class="md:col-span-2 grid grid-cols-1 md:grid-cols-2 gap-6 p-6 bg-slate-50 rounded-[2rem] border border-slate-100">
                            <div>
                                <label
                                    class="block text-[11px] font-black text-gray-400 uppercase mb-2 tracking-widest text-center">Metode
                                    Pembayaran</label>
                                <select name="id_metode_bayar" required
                                    class="w-full border-none rounded-xl font-bold shadow-sm">
                                    @foreach ($metode as $m)
                                        <option value="{{ $m->id_metode_bayar }}"
                                            {{ old('id_metode_bayar') == $m->id_metode_bayar ? 'selected' : '' }}>
                                            {{ $m->nama_metode_bayar }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label
                                    class="block text-[11px] font-black text-gray-400 uppercase mb-2 tracking-widest text-center">Bukti
                                    Bayar (Nota/Struk)</label>
                                <input type="file" name="upload_bukti"
                                    class="w-full text-xs text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-[10px] file:font-black file:uppercase file:bg-rose-600 file:text-white hover:file:bg-rose-700 transition-all">
                            </div>
                        </div>

                        <div class="md:col-span-2">
                            <label
                                class="block text-[11px] font-black text-gray-400 uppercase mb-2 tracking-widest">Keterangan
                                Pengeluaran</label>
                            <textarea name="keterangan" rows="3" :placeholder="placeholderKeterangan"
                                class="w-full border-2 border-gray-100 rounded-2xl focus:ring-4 focus:ring-rose-500/10 focus:border-rose-500">{{ old('keterangan') }}</textarea>
                        </div>
                    </div>

                    <div
                        class="mt-12 flex flex-col md:flex-row items-center justify-between gap-6 border-t border-gray-100 pt-8">
                        <a href="{{ route('kas-keluar.index') }}"
                            class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-gray-100 text-gray-600 font-bold text-xs uppercase tracking-widest hover:bg-gray-200 transition">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                            Back
                        </a>
                        <button type="submit"
                            class="w-full md:w-auto px-12 py-5 bg-rose-600 hover:bg-rose-700 text-white rounded-2xl font-black text-xs uppercase tracking-[0.3em] shadow-xl shadow-rose-200 active:scale-95 transition-all">
                            Simpan Pengeluaran
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @if ($errors->any())
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Validasi Gagal',
                html: '<ul class="text-left text-sm">@foreach ($errors->all() as $error)<li>- {{ $error }}</li>@endforeach</ul>',
                confirmButtonColor: '#e11d48',
                customClass: {
                    popup: 'rounded-[2rem]'
                }
            });
        </script>
    @endif

    <style>
        [x-cloak] {
            display: none !important;
        }
    </style>
</x-app-layout>
