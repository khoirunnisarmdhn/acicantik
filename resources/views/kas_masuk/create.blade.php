<x-app-layout>
    @section('title', 'Input Kas Masuk')

    <div class="py-12" x-data="{
        isProyek: false,
        kategoriUmum: @js($kategoriUmum),
        kategoriProyek: @js($kategoriProyek),
        kategoriAktif: @js($kategoriUmum),
        terminData: [],
        nominal: '{{ old('nominal') }}',
        readonlyNominal: false,
        loadingTermin: false,

        handleProyekChange(id) {
            if (id !== '') {
                this.isProyek = true;
                this.kategoriAktif = this.kategoriProyek;
                this.fetchTermin(id);
            } else {
                this.isProyek = false;
                this.kategoriAktif = this.kategoriUmum;
                this.terminData = [];
                this.nominal = '';
                this.readonlyNominal = false;
            }
        },

        fetchTermin(proyekId) {
            this.loadingTermin = true;
            fetch(`{{ url('/api/proyek') }}/${proyekId}/termin`)
                .then(res => {
                    if (!res.ok) throw new Error('Gagal memuat data dari server');
                    return res.json();
                })
                .then(data => {
                    this.terminData = data;
                    this.loadingTermin = false;
                })
                .catch(err => {
                    this.loadingTermin = false;
                    Swal.fire({
                        icon: 'error',
                        title: 'Koneksi Error',
                        text: err.message
                    });
                });
        },

        handleTerminChange(e) {
            const selected = e.target.options[e.target.selectedIndex];
            // Sesuai field database: nominal
            const valNominal = selected.getAttribute('data-nominal');

            if (valNominal) {
                this.nominal = valNominal;
                this.readonlyNominal = true;
            } else {
                this.nominal = '';
                this.readonlyNominal = false;
            }
        }
    }" x-init="@if(old('id_proyek'))
    handleProyekChange('{{ old('id_proyek') }}')
    @endif">

        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div
                class="bg-white dark:bg-gray-800 shadow-2xl rounded-[2rem] overflow-hidden border border-gray-100 dark:border-gray-700">

                <div class="px-8 py-8 bg-gradient-to-br from-emerald-600 to-teal-700 text-white relative">
                    <div class="relative z-10 flex justify-between items-center">
                        <div>
                            <p class="text-[10px] font-black uppercase tracking-[0.3em] opacity-70 mb-1">Formulir
                                Penerimaan</p>
                            <h3 class="text-2xl font-black tracking-tighter uppercase">Kas Masuk</h3>
                        </div>
                        <div class="text-right">
                            <span class="block text-[10px] font-bold opacity-70 uppercase">No. Referensi</span>
                            <span class="text-xl font-mono font-bold">{{ $no_form }}</span>
                        </div>
                    </div>
                </div>

                <form action="{{ route('kas-masuk.store') }}" method="POST" enctype="multipart/form-data"
                    class="p-8 md:p-10">
                    @csrf
                    <input type="hidden" name="no_form" value="{{ $no_form }}">

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-6">

                        <div class="md:col-span-2">
                            <label class="block text-[11px] font-black text-gray-400 uppercase mb-2 tracking-widest">1.
                                Hubungkan ke Proyek (Opsional)</label>
                            <select name="id_proyek" @change="handleProyekChange($event.target.value)"
                                class="w-full border-2 border-gray-100 rounded-2xl focus:ring-4 focus:ring-emerald-500/10 focus:border-emerald-500 font-bold text-gray-700 transition-all">
                                <option value="">-- PENERIMAAN UMUM (NON-PROYEK) --</option>
                                @foreach ($proyek as $p)
                                    <option value="{{ $p->id_proyek }}"
                                        {{ old('id_proyek') == $p->id_proyek ? 'selected' : '' }}>{{ $p->nama }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-[11px] font-black text-gray-400 uppercase mb-2 tracking-widest">2.
                                Kategori Transaksi</label>
                            <select name="id_kategori" required
                                class="w-full border-2 border-gray-100 rounded-2xl focus:ring-4 focus:ring-emerald-500/10 focus:border-emerald-500 font-medium">
                                <option value="">-- Pilih Kategori --</option>
                                <template x-for="kat in kategoriAktif" :key="kat.id_kategori">
                                    <option :value="kat.id_kategori"
                                        :selected="kat.id_kategori == '{{ old('id_kategori') }}'"
                                        x-text="kat.nama_kategori"></option>
                                </template>
                            </select>
                        </div>

                        <div>
                            <label class="block text-[11px] font-black text-gray-400 uppercase mb-2 tracking-widest">3.
                                Tanggal Terima</label>
                            <input type="date" name="tanggal" required value="{{ old('tanggal', date('Y-m-d')) }}"
                                class="w-full border-2 border-gray-100 rounded-2xl focus:ring-4 focus:ring-emerald-500/10 focus:border-emerald-500 font-bold">
                        </div>

                        <div class="md:col-span-2 border-t border-gray-50 my-2"></div>

                        <div x-show="isProyek" x-transition x-cloak>
                            <label
                                class="block text-[11px] font-black text-indigo-500 uppercase mb-2 tracking-widest flex items-center gap-2">
                                4. Pilih Termin Pembayaran
                                <template x-if="loadingTermin">
                                    <span
                                        class="inline-block animate-spin h-3 w-3 border-2 border-indigo-500 border-t-transparent rounded-full"></span>
                                </template>
                            </label>
                            <select name="id_termin_proyek" @change="handleTerminChange($event)"
                                class="w-full border-2 border-indigo-50 border-dashed bg-indigo-50/30 rounded-2xl font-bold text-indigo-700 focus:ring-4 focus:ring-indigo-500/10">
                                <option value="">-- Pilih Termin --</option>
                                <template x-for="t in terminData" :key="t.id_termin_proyek">
                                    <option :value="t.id_termin_proyek"
                                        :selected="t.id_termin_proyek == '{{ old('id_termin_proyek') }}'"
                                        :data-nominal="t.nominal"
                                        x-text="t.nama_termin + ' (' + t.persentase + '%) - Rp ' + Number(t.nominal).toLocaleString('id-ID')">
                                    </option>
                                </template>
                            </select>
                        </div>

                        <div :class="isProyek ? 'md:col-span-1' : 'md:col-span-2'">
                            <label class="block text-[11px] font-black text-gray-400 uppercase mb-2 tracking-widest">5.
                                Nominal Diterima</label>
                            <div class="relative group">
                                <div class="absolute inset-y-0 left-0 pl-5 flex items-center pointer-events-none">
                                    <span class="text-gray-400 font-black text-sm">Rp</span>
                                </div>
                                <input type="text" name="nominal" x-model="nominal" :readonly="readonlyNominal"
                                    required
                                    class="rupiah w-full pl-12 pr-4 py-4 border-2 rounded-2xl font-black text-2xl focus:ring-4 focus:ring-emerald-500/10 transition-all outline-none"
                                    :class="readonlyNominal ? 'bg-gray-50 border-gray-200 text-gray-400' :
                                        'bg-white border-emerald-100 text-emerald-700'">
                            </div>
                        </div>

                        <div
                            class="md:col-span-2 grid grid-cols-1 md:grid-cols-2 gap-6 p-6 bg-slate-50 rounded-[2rem] border border-slate-100">
                            <div>
                                <label
                                    class="block text-[11px] font-black text-gray-400 uppercase mb-2 tracking-widest text-center">Metode
                                    Penerimaan</label>
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
                                    Transfer (WAJIB)</label>
                                <input type="file" name="upload_bukti"
                                    class="w-full text-xs text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-[10px] file:font-black file:uppercase file:bg-emerald-600 file:text-white hover:file:bg-emerald-700 transition-all">
                            </div>
                        </div>

                        <div class="md:col-span-2">
                            <label
                                class="block text-[11px] font-black text-gray-400 uppercase mb-2 tracking-widest">Keterangan
                                Tambahan</label>
                            <textarea name="keterangan" rows="3" required
                                class="w-full border-2 border-gray-100 rounded-2xl focus:ring-4 focus:ring-emerald-500/10 focus:border-emerald-500">{{ old('keterangan') }}</textarea>
                        </div>
                    </div>

                    <div
                        class="mt-12 flex flex-col md:flex-row items-center justify-between gap-6 border-t border-gray-100 pt-8">
                        <a href="{{ route('kas-masuk.index') }}"
                            class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-gray-100 text-gray-600 font-bold text-xs uppercase tracking-widest hover:bg-gray-200 transition">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                            Back
                        </a>
                        <button type="submit"
                            class="w-full md:w-auto px-12 py-5 bg-gray-900 text-white rounded-2xl font-black text-xs uppercase tracking-[0.3em] active:scale-95 transition-all">
                            Simpan Transaksi
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
                confirmButtonColor: '#ef4444',
                customClass: {
                    popup: 'rounded-[2rem]'
                }
            });
        </script>
    @endif

    @if (session('error'))
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Gagal Simpan',
                text: "{{ session('error') }}",
                confirmButtonColor: '#ef4444',
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
