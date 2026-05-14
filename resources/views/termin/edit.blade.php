<x-app-layout>
    @section('title', 'Update Data Termin')

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Update Termin Proyek') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 shadow-2xl rounded-2xl overflow-hidden">
                @php
                    $isLunas = strtolower($termin->status_pembayaran) === 'lunas';
                @endphp

                <div
                    class="{{ $isLunas ? 'bg-gray-500' : 'bg-indigo-600' }} px-6 py-4 text-white font-bold flex justify-between items-center">
                    <div class="flex items-center gap-2">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        </svg>
                        Edit Detail Termin: {{ $termin->nama_proyek }}
                    </div>
                    @if ($isLunas)
                        <span class="bg-white/20 px-3 py-1 rounded-lg text-xs uppercase tracking-widest">Locked
                            (Lunas)</span>
                    @endif
                </div>

                <form id="formUpdateTermin" action="{{ route('termin.update', $termin->id_termin_proyek) }}"
                    method="POST" class="p-8">
                    @csrf
                    @method('PUT')

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="col-span-2">
                            <label class="block text-xs font-bold text-gray-400 uppercase mb-2">Nama Proyek</label>
                            <input type="text" value="{{ $termin->nama_proyek }}" readonly
                                class="w-full rounded-xl bg-gray-100 border-gray-200 font-bold dark:bg-gray-700 dark:border-gray-600">
                            <input type="hidden" id="max_kontrak_limit" value="{{ $termin->nilai_kontrak }}">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-gray-400 uppercase mb-2">Status Saat Ini</label>
                            <div
                                class="px-4 py-3 rounded-xl bg-gray-50 border border-gray-200 dark:bg-gray-900 dark:border-gray-700">
                                <span class="flex items-center gap-2 font-bold text-indigo-600">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z"
                                            clip-rule="evenodd" />
                                    </svg>
                                    {{ $termin->status_pembayaran }}
                                </span>
                                <p class="text-[10px] text-gray-400 mt-1 italic">*Status berubah otomatis saat transaksi
                                    kas masuk.</p>
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-gray-400 uppercase mb-2">Jatuh Tempo</label>
                            <input type="date" name="due_date" value="{{ $termin->due_date }}" required
                                class="w-full rounded-xl border-gray-200 dark:bg-gray-900 dark:border-gray-700">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-gray-400 uppercase mb-2">Nominal Termin
                                (Rp)</label>
                            <input type="text" name="nominal" id="nominal_input" value="{{ $termin->nominal }}"
                                required
                                class="rupiah w-full rounded-xl border-gray-200 font-bold text-emerald-600 dark:bg-gray-900 dark:border-gray-700">
                            <small class="text-[10px] text-gray-500 italic">Nilai Kontrak: Rp
                                {{ number_format($termin->nilai_kontrak, 0, ',', '.') }}</small>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-gray-400 uppercase mb-2">Tipe Termin</label>
                            <select name="id_tipe_termin"
                                class="w-full rounded-xl border-gray-200 dark:bg-gray-900 dark:border-gray-700">
                                @foreach ($tipe_termin as $tipe)
                                    <option value="{{ $tipe->id_tipe_termin }}"
                                        {{ $termin->id_tipe_termin == $tipe->id_tipe_termin ? 'selected' : '' }}>
                                        {{ $tipe->nama_termin }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="flex justify-end gap-3 mt-8 border-t pt-6">
                        <a href="{{ route('termin.index') }}"
                            class="px-6 py-2 rounded-xl bg-gray-100 text-gray-600 font-bold hover:bg-gray-200 transition">Batal</a>
                        <button type="submit"
                            class="px-8 py-2 rounded-xl bg-emerald-600 text-white font-bold shadow-lg shadow-emerald-200 hover:bg-emerald-700 transition">
                            Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        $('#formUpdateTermin').on('submit', function(e) {
            e.preventDefault();
            let nominal = parseFloat($('#nominal_input').val());
            let limit = parseFloat($('#max_kontrak_limit').val());

            // Validasi client-side
            if (nominal > limit) {
                Swal.fire({
                    icon: 'error',
                    title: 'Nominal Berlebih!',
                    text: 'Nominal tidak boleh melampaui total nilai kontrak proyek.',
                    confirmButtonColor: '#059669'
                });
                return;
            }

            Swal.fire({
                title: 'Update Data?',
                text: "Pastikan nominal dan tanggal sudah benar.",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#059669',
                confirmButtonText: 'Ya, Simpan!'
            }).then((result) => {
                if (result.isConfirmed) this.submit();
            });
        });

        // Handler Pesan Error/Success dari Backend
        @if (session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: "{{ session('success') }}",
                timer: 2000,
                showConfirmButton: false
            });
        @endif
        @if (session('error') || $errors->any())
            Swal.fire({
                icon: 'error',
                title: 'Gagal!',
                text: "{{ session('error') ?? 'Cek kembali nominal atau tanggal.' }}",
                confirmButtonColor: '#4f46e5'
            });
        @endif
    </script>
</x-app-layout>
