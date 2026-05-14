<x-app-layout>
    @section('title', 'Atur Data Termin')

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Atur Termin: {{ $proyek->nama }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-xl rounded-2xl overflow-hidden border border-gray-100">

                <div class="p-6 bg-indigo-50 border-b border-indigo-100 flex justify-between items-center">
                    <div>
                        <p class="text-xs font-bold text-indigo-400 uppercase tracking-widest">Total Nilai Kontrak</p>
                        <h3 class="text-2xl font-black text-indigo-700">Rp
                            {{ number_format($proyek->nilai_kontrak, 0, ',', '.') }}</h3>
                    </div>
                    <div class="text-right">
                        <p class="text-xs font-bold text-gray-400 uppercase tracking-widest">Target Distribusi</p>
                        <h3 class="text-lg font-bold text-gray-700">{{ $proyek->jumlah_termin }} Kali Termin</h3>
                    </div>
                </div>

                <form action="{{ route('termin.store') }}" method="POST" class="p-8">
                    @csrf
                    <input type="hidden" name="id_proyek" value="{{ $proyek->id_proyek }}">

                    <div class="space-y-4">
                        @for ($i = 1; $i <= $proyek->jumlah_termin; $i++)
                            <div
                                class="p-4 border border-gray-100 rounded-2xl bg-gray-50/50 grid grid-cols-1 md:grid-cols-3 gap-4 items-end">
                                <div>
                                    <label
                                        class="block text-[10px] font-bold text-gray-400 uppercase mb-1 tracking-tighter">Tipe
                                        Termin {{ $i }}</label>
                                    <select name="id_tipe_termin[]"
                                        class="w-full rounded-xl border-gray-200 text-sm focus:ring-indigo-500">
                                        @foreach ($tipe_termins as $tt)
                                            <option value="{{ $tt->id_tipe_termin }}">{{ $tt->nama_termin }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label
                                        class="block text-[10px] font-bold text-gray-400 uppercase mb-1 tracking-tighter">Nominal
                                        Pembayaran (Rp)</label>
                                    <input type="text" name="nominal[]" step="0.01" value="0"
                                        class="rupiah nominal-input w-full rounded-xl border-gray-200 text-sm font-bold text-emerald-600 focus:ring-emerald-500">
                                </div>
                                <div>
                                    <label
                                        class="block text-[10px] font-bold text-gray-400 uppercase mb-1 tracking-tighter">Keterangan
                                        / Milestone</label>
                                    <input type="text" name="keterangan[]" placeholder="Misal: Progress 25%"
                                        class="w-full rounded-xl border-gray-200 text-sm focus:ring-indigo-500">
                                </div>
                            </div>
                        @endfor
                    </div>

                    <div class="mt-8 p-6 bg-gray-900 rounded-2xl flex justify-between items-center text-white">
                        <div>
                            <p class="text-[10px] text-gray-400 uppercase font-bold">Total Terinput</p>
                            <p class="text-xl font-bold" id="total-terinput">Rp 0</p>
                        </div>
                        <div class="text-right">
                            <p class="text-[10px] text-gray-400 uppercase font-bold">Selisih (Sisa)</p>
                            <p class="text-xl font-bold text-rose-400" id="selisih">Rp
                                {{ number_format($proyek->nilai_kontrak, 0, ',', '.') }}</p>
                        </div>
                    </div>

                    <div class="flex justify-end gap-3 mt-8">
                        <a href="{{ route('proyek.index') }}"
                            class="px-6 py-2.5 rounded-xl bg-gray-100 text-gray-600 font-bold">Batal</a>
                        <button type="submit" id="btn-simpan"
                            class="px-10 py-2.5 rounded-xl bg-indigo-600 text-white font-bold shadow-lg hover:bg-indigo-700 transition transform active:scale-95">
                            Simpan Struktur Termin
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        const nilaiKontrak = {{ $proyek->nilai_kontrak }};
        const inputs = document.querySelectorAll('.nominal-input');
        const displayTotal = document.getElementById('total-terinput');
        const displaySelisih = document.getElementById('selisih');
        const btnSimpan = document.getElementById('btn-simpan');

        function calculate() {
            let total = 0;
            inputs.forEach(input => {
                total += parseFloat(input.value) || 0;
            });

            let selisih = nilaiKontrak - total;

            displayTotal.innerText = 'Rp ' + total.toLocaleString('id-ID');
            displaySelisih.innerText = 'Rp ' + selisih.toLocaleString('id-ID');

            // Kasih warning kalau selisih tidak nol
            if (selisih !== 0) {
                displaySelisih.classList.add('text-rose-400');
                displaySelisih.classList.remove('text-emerald-400');
            } else {
                displaySelisih.classList.remove('text-rose-400');
                displaySelisih.classList.add('text-emerald-400');
            }
        }

        inputs.forEach(input => {
            input.addEventListener('input', calculate);
        });
    </script>
</x-app-layout>
