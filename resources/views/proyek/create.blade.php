<x-app-layout>
    @section('title', 'Tambah Data Proyek')
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Tambah Proyek Baru') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 shadow-2xl rounded-2xl overflow-hidden">
                <div class="bg-indigo-600 px-6 py-4 text-white font-bold flex justify-between items-center">
                    <span>Pendaftaran Kontrak Proyek Baru</span>
                    <span id="due_date_badge"
                        class="hidden bg-indigo-500/50 text-[10px] px-3 py-1 rounded-full border border-indigo-300 backdrop-blur-sm">
                        Estimasi Durasi: <span id="duration_days" class="font-black">0</span> Hari
                    </span>
                </div>

                <form action="{{ route('proyek.store') }}" method="POST" id="proyekForm"
                    class="p-8 grid grid-cols-1 md:grid-cols-2 gap-6">
                    @csrf

                    <div class="col-span-2">
                        <label class="block text-xs font-bold text-gray-400 uppercase mb-2">Nama Proyek</label>
                        <input type="text" name="nama" required value="{{ old('nama') }}"
                            class="w-full rounded-xl border-gray-200 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white placeholder:text-gray-400"
                            placeholder="Nama Proyek">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-400 uppercase mb-2">Pemberi Proyek</label>
                        <select name="id_pemberi" required
                            class="w-full rounded-xl border-gray-200 dark:bg-gray-700 dark:border-gray-600 dark:text-white placeholder:text-gray-400">
                            <option value="" disabled selected>Pilih Pemberi Proyek</option>
                            @foreach ($pemberis as $pb)
                                <option value="{{ $pb->id_pemberi }}"
                                    {{ old('id_pemberi') == $pb->id_pemberi ? 'selected' : '' }}>{{ $pb->nama }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-400 uppercase mb-2">Nilai Kontrak (Rp)</label>
                        <div class="relative group">
                            <div class="absolute inset-y-0 left-0 pl-5 flex items-center pointer-events-none">
                                <span class="text-gray-400 font-black text-sm">Rp</span>
                            </div>
                            <input type="text" name="nilai_kontrak" x-model="nilai_kontrak"
                                :readonly="readonlyNilaiKontrak" required
                                class="rupiah w-full pl-12 rounded-xl border-gray-200 dark:bg-gray-700 dark:border-gray-600 dark:text-white placeholder:text-gray-400"
                                {{-- class="w-full pl-12 pr-4 py-4 border-2 rounded-2xl font-black text-2xl focus:ring-4 focus:ring-emerald-500/10 transition-all outline-none" --}}
                                :class="readonlyNilaiKontrak ? 'bg-gray-50 border-gray-200 text-gray-400' :
                                    'bg-white border-emerald-100 text-emerald-700'">
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-400 uppercase mb-2">Tanggal Mulai</label>
                        <input type="date" name="tanggal_mulai" id="tanggal_mulai" required
                            value="{{ old('tanggal_mulai') }}"
                            class="w-full rounded-xl border-gray-200 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-400 uppercase mb-2">Estimasi Selesai</label>
                        <input type="date" name="tanggal_selesai" id="tanggal_selesai" required
                            value="{{ old('tanggal_selesai') }}"
                            class="w-full rounded-xl border-gray-200 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                        <p class="text-[10px] text-rose-500 mt-1 hidden font-bold" id="date_error">⚠️ Tanggal selesai
                            harus ≥ tanggal mulai!</p>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-400 uppercase mb-2">Jumlah Termin (termasuk DP & Akhir)</label>
                        <select name="jumlah_termin" id="jumlah_termin" required
                            class="w-full rounded-xl border-gray-200 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                            <option value="1" {{ old('jumlah_termin') == '1' ? 'selected' : '' }}>Full Payment</option>
                            <option value="3" {{ old('jumlah_termin', '3') == '3' ? 'selected' : '' }}>3 Termin (DP + 1 Progress + Akhir)</option>
                            <option value="4" {{ old('jumlah_termin') == '4' ? 'selected' : '' }}>4 Termin (DP + 2 Progress + Akhir)</option>
                        </select>
                        <p class="text-[10px] text-amber-600 dark:text-amber-400 mt-1 italic font-medium">
                            * Pola: Full Payment (100% sekaligus) atau Multi-Termin (DP 20% + Termin Progress + Akhir 10%). Tidak bisa diubah setelah disimpan.
                        </p>
                    </div>

                    <!-- Alokasi Anggaran (RAB) & Target Laba -->
                    <div class="col-span-2 border-t pt-6 mt-4 dark:border-gray-700">
                        <h4 class="text-sm font-bold text-indigo-600 dark:text-indigo-400 mb-4 uppercase tracking-wider">
                            Rencana Anggaran Biaya (RAB) & Target Laba Proyek
                        </h4>
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                            <!-- Target Laba -->
                            <div>
                                <label class="block text-xs font-bold text-gray-400 uppercase mb-2">Target Laba (%)</label>
                                <input type="number" name="target_laba" id="target_laba" required min="1" max="100"
                                    value="{{ old('target_laba', '20') }}"
                                    class="rab-input w-full rounded-xl border-gray-200 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                                <p class="text-[9px] text-gray-400 mt-1 italic">* Default: 20%</p>
                            </div>

                            <!-- Dynamic LRA items -->
                            @foreach ($globalLras as $lra)
                                <div>
                                    <label class="block text-xs font-bold text-gray-400 uppercase mb-2">{{ $lra->keterangan }} (%)</label>
                                    <input type="number" name="lra_persen[{{ $lra->id_lra }}]" required min="0" max="100"
                                        value="{{ old('lra_persen.' . $lra->id_lra, $lra->persentase) }}"
                                        class="rab-input w-full rounded-xl border-gray-200 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                                    <p class="text-[9px] text-gray-400 mt-1 italic">* Default: {{ $lra->persentase }}%</p>
                                </div>
                            @endforeach
                        </div>
                        <p class="text-[10px] text-indigo-500 mt-3 font-semibold" id="rab_info">
                            ℹ️ Total penjumlahan persentase Target Laba + Alokasi Pos Biaya harus tepat **100%**. 
                            (Saat ini: <span id="rab_total_display" class="font-black">100</span>%)
                        </p>
                        <p class="text-[10px] text-rose-500 mt-1 hidden font-bold" id="rab_error">
                            ⚠️ Total persentase alokasi + laba tidak sama dengan 100%! Silakan sesuaikan kembali.
                        </p>
                    </div>

                    <div class="col-span-2 flex justify-end gap-3 mt-4 border-t pt-6 dark:border-gray-700">
                        <a href="{{ route('proyek.index') }}"
                            class="inline-flex items-center gap-2 px-5 py-2 rounded-xl bg-gray-100 text-gray-600 font-bold hover:bg-gray-200 transition">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                            Back
                        </a>
                        <button type="submit" id="btnSubmit"
                            class="px-8 py-2 rounded-xl bg-indigo-600 text-white font-bold hover:bg-indigo-700 transition shadow-lg shadow-indigo-200">
                            Simpan Proyek
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            const tglMulai = $('#tanggal_mulai');
            const tglSelesai = $('#tanggal_selesai');
            const dateError = $('#date_error');
            const btnSubmit = $('#btnSubmit');
            const badgeDuration = $('#due_date_badge');
            const spanDays = $('#duration_days');
            const rabInputs = $('.rab-input');
            const rabTotalDisplay = $('#rab_total_display');
            const rabError = $('#rab_error');

            function validateForm() {
                let hasDateError = false;
                if (tglMulai.val() && tglSelesai.val()) {
                    const start = new Date(tglMulai.val());
                    const end = new Date(tglSelesai.val());
                    if (end < start) {
                        hasDateError = true;
                    }
                }

                let rabTotal = 0;
                rabInputs.each(function() {
                    rabTotal += parseFloat($(this).val()) || 0;
                });
                rabTotalDisplay.text(rabTotal);
                let hasRabError = (rabTotal !== 100);

                if (hasDateError) {
                    dateError.removeClass('hidden');
                    badgeDuration.addClass('hidden');
                } else {
                    dateError.addClass('hidden');
                    if (tglMulai.val() && tglSelesai.val()) {
                        const start = new Date(tglMulai.val());
                        const end = new Date(tglSelesai.val());
                        const diffTime = Math.abs(end - start);
                        const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
                        spanDays.text(diffDays);
                        badgeDuration.removeClass('hidden');
                    }
                }

                if (hasRabError) {
                    rabError.removeClass('hidden');
                } else {
                    rabError.addClass('hidden');
                }

                if (hasDateError || hasRabError) {
                    btnSubmit.prop('disabled', true).addClass('opacity-50 cursor-not-allowed');
                } else {
                    btnSubmit.prop('disabled', false).removeClass('opacity-50 cursor-not-allowed');
                }
            }

            tglMulai.on('change', validateForm);
            tglSelesai.on('change', validateForm);

            // Store initial values as defaults on page load
            const lraInputs = rabInputs.not('#target_laba');
            lraInputs.each(function() {
                $(this).attr('data-default', $(this).val());
            });

            // Auto-adjust LRA inputs when Target Laba changes
            $('#target_laba').on('input change', function() {
                let targetLabaVal = parseInt($(this).val()) || 0;
                if (targetLabaVal > 100) {
                    targetLabaVal = 100;
                    $(this).val(100);
                }
                if (targetLabaVal < 0) {
                    targetLabaVal = 0;
                    $(this).val(0);
                }

                let remaining = 100 - targetLabaVal;
                let defaults = [];
                let totalDefault = 0;

                lraInputs.each(function() {
                    let def = parseFloat($(this).attr('data-default')) || 0;
                    defaults.push(def);
                    totalDefault += def;
                });

                if (totalDefault > 0) {
                    let distributedSum = 0;
                    let newValues = [];

                    lraInputs.each(function(index) {
                        let def = defaults[index];
                        let newVal = Math.floor((def / totalDefault) * remaining);
                        newValues.push(newVal);
                        distributedSum += newVal;
                    });

                    let diff = remaining - distributedSum;
                    let i = 0;
                    while (diff > 0) {
                        newValues[i % newValues.length]++;
                        diff--;
                        i++;
                    }
                    while (diff < 0) {
                        if (newValues[i % newValues.length] > 0) {
                            newValues[i % newValues.length]--;
                            diff++;
                        }
                        i++;
                    }

                    lraInputs.each(function(index) {
                        $(this).val(newValues[index]);
                    });
                }
                validateForm();
            });

            lraInputs.on('input change', validateForm);
            
            // Jalankan kalkulasi saat halaman load (antisipasi old value)
            validateForm();
        });

        // --- HANDLER NOTIFIKASI (SWEETALERT2) ---

        // 1. Alert Sukses
        @if (session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: "{{ session('success') }}",
                timer: 3000,
                showConfirmButton: false,
                background: '#f8fafc'
            });
        @endif

        // 2. Alert Gagal (Error Session)
        @if (session('error'))
            Swal.fire({
                icon: 'error',
                title: 'Gagal Menyimpan!',
                text: "{{ session('error') }}",
                confirmButtonColor: '#4f46e5',
                background: '#fff5f5'
            });
        @endif

        // 3. Alert Gagal Validasi
        @if ($errors->any())
            Swal.fire({
                icon: 'warning',
                title: 'Input Tidak Valid!',
                html: `
                    <ul class="text-left text-sm">
                        @foreach ($errors->all() as $error)
                            <li>• {{ $error }}</li>
                        @endforeach
                    </ul>
                `,
                confirmButtonColor: '#f59e0b',
            });
        @endif
    </script>
</x-app-layout>
