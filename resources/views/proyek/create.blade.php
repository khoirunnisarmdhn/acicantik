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
                    <input type="hidden" name="target_laba" value="20">

                    <div class="col-span-2">
                        <label class="block text-xs font-bold text-gray-400 uppercase mb-2">Nama Proyek</label>
                        <input type="text" name="nama" required value="{{ old('nama') }}"
                            class="w-full rounded-xl border-gray-200 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white placeholder:text-gray-400"
                            placeholder="Nama Proyek">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-400 uppercase mb-2">Pemberi Proyek
                            (Client)</label>
                        <select name="id_pemberi" required
                            class="w-full rounded-xl border-gray-200 dark:bg-gray-700 dark:border-gray-600 dark:text-white placeholder:text-gray-400">
                            <option value="" disabled selected>Pilih Client</option>
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
                            <option value="3" {{ old('jumlah_termin', '3') == '3' ? 'selected' : '' }}>3 Termin (DP + 1 Progress + Akhir)</option>
                            <option value="4" {{ old('jumlah_termin') == '4' ? 'selected' : '' }}>4 Termin (DP + 2 Progress + Akhir)</option>
                        </select>
                        <p class="text-[10px] text-amber-600 dark:text-amber-400 mt-1 italic font-medium">
                            * Pola: DP 20% + Termin Progress (70% dibagi rata) + Akhir 10%. Tidak bisa diubah setelah disimpan.
                        </p>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-400 uppercase mb-2">Status Proyek</label>
                        <select name="status"
                            class="w-full rounded-xl border-gray-200 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                            <option value="aktif" {{ old('status') == 'aktif' ? 'selected' : '' }}>Aktif</option>
                            <option value="selesai" {{ old('status') == 'selesai' ? 'selected' : '' }}>Selesai</option>
                        </select>
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

            function calculateDuration() {
                if (tglMulai.val() && tglSelesai.val()) {
                    const start = new Date(tglMulai.val());
                    const end = new Date(tglSelesai.val());

                    if (end < start) {
                        dateError.removeClass('hidden');
                        btnSubmit.prop('disabled', true).addClass('opacity-50 cursor-not-allowed');
                        badgeDuration.addClass('hidden');
                    } else {
                        dateError.addClass('hidden');
                        btnSubmit.prop('disabled', false).removeClass('opacity-50 cursor-not-allowed');

                        const diffTime = Math.abs(end - start);
                        const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));

                        spanDays.text(diffDays);
                        badgeDuration.removeClass('hidden');
                    }
                }
            }

            tglMulai.on('change', calculateDuration);
            tglSelesai.on('change', calculateDuration);

            // Jalankan kalkulasi saat halaman load (antisipasi old value)
            calculateDuration();
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

        // 2. Alert Gagal (Error Session atau Validasi Laravel)
        @if (session('error') || $errors->any())
            Swal.fire({
                icon: 'error',
                title: 'Gagal Menyimpan!',
                text: "{{ session('error') ?? 'Pastikan semua inputan sudah benar.' }}",
                confirmButtonColor: '#4f46e5',
                background: '#fff5f5'
            });
        @endif
    </script>
</x-app-layout>
