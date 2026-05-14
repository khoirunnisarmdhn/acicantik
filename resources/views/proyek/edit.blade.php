<x-app-layout>
    @section('title', 'Edit Data Proyek')

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Edit Proyek: ') }} {{ $proyek->nama }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            <div
                class="bg-white dark:bg-gray-800 shadow-2xl rounded-2xl overflow-hidden border border-gray-100 dark:border-gray-700">
                <div class="bg-amber-500 px-6 py-4 text-white font-bold flex justify-between items-center">
                    <div class="flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                        </svg>
                        Update Informasi Kontrak
                    </div>
                    <span id="due_date_badge"
                        class="hidden bg-amber-600/50 text-[10px] px-3 py-1 rounded-full border border-amber-300 backdrop-blur-sm">
                        Estimasi Durasi: <span id="duration_days" class="font-black">0</span> Hari
                    </span>
                </div>

                <form action="{{ route('proyek.update', $proyek->id_proyek) }}" method="POST" id="proyekForm"
                    class="p-8 grid grid-cols-1 md:grid-cols-2 gap-6">
                    @csrf
                    @method('PUT')

                    <div class="col-span-2">
                        <label class="block text-xs font-bold text-gray-400 uppercase mb-2 tracking-widest">Nama
                            Proyek</label>
                        <input type="text" name="nama" value="{{ old('nama', $proyek->nama) }}" required
                            class="w-full rounded-xl border-gray-200 dark:bg-gray-900 dark:border-gray-700 focus:ring-amber-500 transition-all">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-400 uppercase mb-2 tracking-widest">Pemberi
                            Proyek (Client)</label>
                        <select name="id_pemberi" required
                            class="w-full rounded-xl border-gray-200 dark:bg-gray-900 dark:border-gray-700 focus:ring-amber-500">
                            @foreach ($pemberis as $pb)
                                <option value="{{ $pb->id_pemberi }}"
                                    {{ $proyek->id_pemberi == $pb->id_pemberi ? 'selected' : '' }}>
                                    {{ $pb->nama }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- LOCK: Nilai Kontrak --}}
                    <div>
                        <label class="block text-xs font-bold text-gray-400 uppercase mb-2 tracking-widest">Nilai
                            Kontrak (Rp)</label>
                        <input type="text" value="{{ $proyek->nilai_kontrak }}" readonly
                            class="rupiah w-full rounded-xl border-gray-100 bg-gray-50 dark:bg-gray-800 dark:border-gray-700 text-gray-500 cursor-not-allowed">
                        <p class="text-[10px] text-amber-600 mt-1 italic font-medium">* Nilai kontrak permanen.</p>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-400 uppercase mb-2 tracking-widest">Tanggal
                            Mulai</label>
                        <input type="date" name="tanggal_mulai" id="tanggal_mulai"
                            value="{{ old('tanggal_mulai', $proyek->tanggal_mulai) }}" required
                            class="w-full rounded-xl border-gray-200 dark:bg-gray-900 dark:border-gray-700 focus:ring-amber-500">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-400 uppercase mb-2 tracking-widest">Estimasi
                            Selesai</label>
                        <input type="date" name="tanggal_selesai" id="tanggal_selesai"
                            value="{{ old('tanggal_selesai', $proyek->tanggal_selesai) }}" required
                            class="w-full rounded-xl border-gray-200 dark:bg-gray-900 dark:border-gray-700 focus:ring-amber-500">
                        <p class="text-[10px] text-rose-500 mt-1 hidden font-bold" id="date_error">⚠️ Tanggal selesai
                            harus ≥ tanggal mulai!</p>
                    </div>

                    {{-- LOCK: Jumlah Termin --}}
                    <div>
                        <label class="block text-xs font-bold text-gray-400 uppercase mb-2 tracking-widest">Jumlah
                            Termin</label>
                        <input type="text" value="{{ $proyek->jumlah_termin }}" readonly
                            class="rupiah w-full rounded-xl border-gray-100 bg-gray-50 dark:bg-gray-800 dark:border-gray-700 text-gray-500 cursor-not-allowed">
                        <p class="text-[10px] text-amber-600 mt-1 italic font-medium">* Jumlah termin tidak dapat
                            diubah.</p>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-400 uppercase mb-2 tracking-widest">Status
                            Proyek</label>
                        <select name="status"
                            class="w-full rounded-xl border-gray-200 dark:bg-gray-900 dark:border-gray-700 focus:ring-amber-500">
                            <option value="aktif" {{ $proyek->status == 'aktif' ? 'selected' : '' }}>Aktif</option>
                            <option value="selesai" {{ $proyek->status == 'selesai' ? 'selected' : '' }}>Selesai
                            </option>
                        </select>
                    </div>

                    <div class="col-span-2">
                        <label class="block text-xs font-bold text-gray-400 uppercase mb-2 tracking-widest">Deskripsi /
                            Catatan Tambahan</label>
                        <textarea name="deskripsi" rows="3"
                            class="w-full rounded-xl border-gray-200 dark:bg-gray-900 dark:border-gray-700 focus:ring-amber-500">{{ old('deskripsi', $proyek->deskripsi) }}</textarea>
                    </div>

                    <div
                        class="col-span-2 flex justify-end gap-3 mt-4 border-t pt-6 border-gray-100 dark:border-gray-700">
                        <a href="{{ route('proyek.index') }}"
                            class="px-6 py-2.5 rounded-xl bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300 font-bold hover:bg-gray-200 transition">
                            Batal
                        </a>
                        <button type="submit" id="btnSubmit"
                            class="px-10 py-2.5 rounded-xl bg-amber-500 text-white font-bold shadow-lg shadow-amber-200 hover:bg-amber-600 transition transform active:scale-95">
                            Update Kontrak
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

            // Jalankan kalkulasi saat halaman load
            calculateDuration();
        });

        // --- HANDLER NOTIFIKASI (SWEETALERT2) ---
        @if (session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: "{{ session('success') }}",
                timer: 3000,
                showConfirmButton: false
            });
        @endif

        // --- HANDLER NOTIFIKASI (SWEETALERT2) ---

        // Jika ada session error (dari catch Exception)
        @if (session('error'))
            Swal.fire({
                icon: 'error',
                title: 'Terjadi Kesalahan!',
                text: "{{ session('error') }}", // Ini akan menampilkan pesan error asli
                confirmButtonColor: '#ef4444',
            });
        @endif

        // Jika ada error validasi (dari $errors Laravel)
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
