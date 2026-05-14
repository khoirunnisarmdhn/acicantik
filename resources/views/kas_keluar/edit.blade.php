<x-app-layout>
    @section('title', 'Edit Kas Keluar')

    <div class="py-12" x-data="{
        isProyek: {{ $kas->id_proyek ? 'true' : 'false' }},
        kategori: @js($kategori),

        rincian: @js(
    old(
        'rincian',
        $rincian
            ->map(
                fn($d) => [
                    'nama' => $d->nama,
                    'nominal' => number_format($d->nominal, 0, ',', '.'),
                ],
            )
            ->values(),
    ),
),

        handleProyekChange(id) {
            if (id !== '') {
                this.isProyek = true;
                this.kategoriAktif = this.kategoriProyek;
            } else {
                this.isProyek = false;
                this.kategoriAktif = this.kategori;
            }
        }
    }" x-init="if ('{{ old('id_proyek', $kas->id_proyek) }}' !== '') {
        handleProyekChange('{{ old('id_proyek', $kas->id_proyek) }}')
    }">

        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div
                class="bg-white dark:bg-gray-800 shadow-2xl rounded-[2rem] overflow-hidden border border-gray-100 dark:border-gray-700">
                <!-- HEADER -->
                <div class="px-8 py-8 bg-gradient-to-br from-amber-500 to-orange-600 text-white relative">
                    <div class="relative z-10 flex justify-between items-center">
                        <div>
                            <p class="text-[10px] font-black uppercase tracking-[0.3em] opacity-70 mb-1">
                                Formulir Pengeluaran
                            </p>
                            <h3 class="text-2xl font-black tracking-tighter uppercase">
                                Edit Kas Keluar
                            </h3>
                        </div>

                        <div class="text-right">
                            <span class="block text-[10px] font-bold opacity-70 uppercase">
                                No. Referensi
                            </span>

                            <span class="text-xl font-mono font-bold">
                                {{ $kas->no_form }}
                            </span>
                        </div>
                    </div>
                </div>

                <!-- FORM -->
                <form action="{{ route('kas-keluar.update', $kas->id_kas) }}" method="POST"
                    enctype="multipart/form-data" class="p-8 md:p-10">

                    @csrf
                    @method('PUT')
                    <input type="hidden" name="no_form" value="{{ $kas->no_form }}">

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-6">

                        <!-- PROYEK -->
                        <div class="md:col-span-2">
                            <label class="block text-[11px] font-black text-gray-400 uppercase mb-2 tracking-widest">
                                1. Alokasi Proyek (Opsional)
                            </label>

                            <select name="id_proyek" @change="handleProyekChange($event.target.value)"
                                class="w-full border-2 border-gray-100 rounded-2xl focus:ring-4 focus:ring-orange-500/10 focus:border-orange-500 font-bold text-gray-700 transition-all">

                                <option value="">-- PENGELUARAN UMUM (NON-PROYEK) --</option>

                                @foreach ($proyek as $p)
                                    <option value="{{ $p->id_proyek }}"
                                        {{ old('id_proyek', $kas->id_proyek) == $p->id_proyek ? 'selected' : '' }}>
                                        {{ $p->nama }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- KATEGORI -->
                        <div class="md:col-span-2">
                            <label class="block text-[11px] font-black text-gray-400 uppercase mb-2 tracking-widest">
                                2. Kategori Pengeluaran
                            </label>

                            <select name="id_kategori" required
                                class="w-full border-2 border-gray-100 rounded-2xl focus:ring-4 focus:ring-orange-500/10 focus:border-orange-500 font-medium">

                                <option value="">-- Pilih Kategori --</option>

                                <template x-for="kat in kategori" :key="kat.id_kategori">
                                    <option :value="kat.id_kategori"
                                        :selected="kat.id_kategori == '{{ old('id_kategori', $kas->id_kategori) }}'"
                                        x-text="kat.nama_kategori">
                                    </option>
                                </template>
                            </select>
                        </div>

                        <!-- RINCIAN -->
                        <div class="md:col-span-2 space-y-4">

                            <div class="flex items-center justify-between">
                                <label class="block text-[11px] font-black text-gray-400 uppercase tracking-widest">
                                    Rincian Pengeluaran
                                </label>

                                <button type="button" @click="rincian.push({ nama: '', nominal: '' })"
                                    class="w-9 h-9 rounded-full bg-orange-500 text-white font-black hover:bg-orange-600 transition-all">
                                    +
                                </button>
                            </div>

                            <template x-for="(item, index) in rincian" :key="index">
                                <div class="grid grid-cols-12 gap-3 items-center">

                                    <!-- NAMA -->
                                    <div class="col-span-7">
                                        <input type="text" :name="`rincian[${index}][nama]`" x-model="item.nama"
                                            placeholder="Contoh: Semen 50 sak"
                                            class="w-full border-2 border-gray-100 rounded-2xl px-4 py-3 focus:ring-4 focus:ring-orange-500/10 focus:border-orange-500">
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

                                            <input type="text" :name="`rincian[${index}][nominal]`"
                                                x-model="item.nominal" placeholder="0"
                                                class="rupiah w-full pl-12 pr-4 py-3 border-2 border-gray-100 rounded-2xl font-bold text-orange-700 focus:ring-4 focus:ring-orange-500/10">
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
                            <div class="flex justify-center pt-4">
                                <div
                                    class="inline-flex flex-col items-center bg-orange-50 border border-orange-100 rounded-2xl px-8 py-5">

                                    <p class="text-[10px] uppercase tracking-widest font-black text-orange-400 mb-2">
                                        Total Pengeluaran
                                    </p>

                                    <h3 class="text-3xl font-black text-orange-700 leading-none">
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

                        <!-- TANGGAL -->
                        <div class="md:col-span-2">
                            <label class="block text-[11px] font-black text-gray-400 uppercase mb-2 tracking-widest">
                                3. Tanggal Bayar
                            </label>

                            <input type="date" name="tanggal" required
                                value="{{ old('tanggal', \Carbon\Carbon::parse($kas->tanggal)->format('Y-m-d')) }}"
                                class="w-full border-2 border-gray-100 rounded-2xl focus:ring-4 focus:ring-orange-500/10 focus:border-orange-500 font-bold">
                        </div>

                        <!-- VENDOR -->
                        <div class="md:col-span-2">
                            <label class="block text-[11px] font-black text-orange-500 uppercase mb-2 tracking-widest">
                                4. Vendor / Penerima
                            </label>

                            <select name="id_vendor"
                                class="w-full border-2 border-orange-50 border-dashed bg-orange-50/30 rounded-2xl font-bold text-orange-700 focus:ring-4 focus:ring-orange-500/10">

                                <option value="">-- Tanpa Vendor (Langsung) --</option>

                                @foreach ($vendor as $v)
                                    <option value="{{ $v->id_vendor }}"
                                        {{ old('id_vendor', $kas->id_vendor) == $v->id_vendor ? 'selected' : '' }}>
                                        {{ $v->nama }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- METODE + BUKTI -->
                        <div
                            class="md:col-span-2 grid grid-cols-1 md:grid-cols-2 gap-6 p-6 bg-slate-50 rounded-[2rem] border border-slate-100">

                            <div>
                                <label
                                    class="block text-[11px] font-black text-gray-400 uppercase mb-2 tracking-widest text-center">
                                    Metode Pembayaran
                                </label>

                                <select name="id_metode_bayar" required
                                    class="w-full border-none rounded-xl font-bold shadow-sm">

                                    @foreach ($metode as $m)
                                        <option value="{{ $m->id_metode_bayar }}"
                                            {{ old('id_metode_bayar', $kas->id_metode_bayar) == $m->id_metode_bayar ? 'selected' : '' }}>
                                            {{ $m->nama_metode_bayar }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label
                                    class="block text-[11px] font-black text-gray-400 uppercase mb-2 tracking-widest text-center">
                                    Bukti Bayar (Nota/Struk)
                                </label>

                                <input type="file" name="upload_bukti"
                                    class="w-full text-xs text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-[10px] file:font-black file:uppercase file:bg-orange-500 file:text-white hover:file:bg-orange-600 transition-all">

                                @if ($kas->upload_bukti)
                                    <div class="mt-3">
                                        <a href="{{ asset('storage/' . $kas->upload_bukti) }}" target="_blank"
                                            class="text-xs text-orange-600 font-bold hover:underline">
                                            Lihat bukti lama
                                        </a>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- KETERANGAN -->
                        <div class="md:col-span-2">
                            <label class="block text-[11px] font-black text-gray-400 uppercase mb-2 tracking-widest">
                                Keterangan Pengeluaran
                            </label>

                            <textarea name="keterangan" rows="3" required placeholder="Contoh: Pembelian material semen 50 sak..."
                                class="w-full border-2 border-gray-100 rounded-2xl focus:ring-4 focus:ring-orange-500/10 focus:border-orange-500">{{ old('keterangan', $kas->keterangan) }}</textarea>
                        </div>
                    </div>

                    <!-- BUTTON -->
                    <div
                        class="mt-12 flex flex-col md:flex-row items-center justify-between gap-6 border-t border-gray-100 pt-8">

                        <a href="{{ route('kas-keluar.index') }}"
                            class="text-xs font-black uppercase tracking-widest text-gray-400 hover:text-orange-600 transition-colors">
                            Batal
                        </a>

                        <button type="submit"
                            class="w-full md:w-auto px-12 py-5 bg-orange-500 hover:bg-orange-600 text-white rounded-2xl font-black text-xs uppercase tracking-[0.3em] shadow-xl shadow-orange-200 active:scale-95 transition-all">
                            Update Pengeluaran
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- ERROR -->
    @if ($errors->any())
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Validasi Gagal',
                html: `
                    <div class="text-left text-sm space-y-1">
                        @foreach ($errors->all() as $error)
                            <div>- {{ $error }}</div>
                        @endforeach
                    </div>
                `,
                confirmButtonColor: '#f97316',
                customClass: {
                    popup: 'rounded-[2rem]'
                }
            });

            console.error(@json($errors->all()));
        </script>
    @endif

</x-app-layout>
