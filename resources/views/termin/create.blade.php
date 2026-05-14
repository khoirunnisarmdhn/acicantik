<x-app-layout>
    @section('title', 'Buat Data Termin')

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Buat Termin Proyek') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 shadow-2xl rounded-2xl overflow-hidden">
                <div class="bg-emerald-600 px-6 py-4 text-white font-bold flex items-center gap-2">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    Input Penerimaan Kas Baru
                </div>

                <form id="formKasMasuk" action="{{ route('kas-masuk.store') }}" method="POST"
                    enctype="multipart/form-data" class="p-8 grid grid-cols-1 md:grid-cols-2 gap-6">
                    @csrf

                    <div>
                        <label class="block text-xs font-bold text-gray-400 uppercase mb-2">No. Form (Auto)</label>
                        <input type="text" name="no_form" value="{{ $no_form }}" readonly
                            class="w-full rounded-xl bg-gray-50 border-gray-200 font-mono text-indigo-600 font-bold dark:bg-gray-700 dark:border-gray-600 dark:text-indigo-400">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-400 uppercase mb-2">Tanggal Masuk</label>
                        <input type="date" name="tanggal" value="{{ date('Y-m-d') }}" required
                            class="w-full rounded-xl border-gray-200 dark:bg-gray-900 dark:border-gray-700 dark:text-white">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-400 uppercase mb-2">Kategori Penerimaan</label>
                        <select name="id_kategori" required
                            class="w-full rounded-xl border-gray-200 dark:bg-gray-900 dark:border-gray-700 dark:text-white">
                            <option value="">-- Pilih Kategori --</option>
                            @foreach ($kategori as $kat)
                                <option value="{{ $kat->id_kategori }}">{{ $kat->nama_kategori }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-400 uppercase mb-2">Terkait Proyek
                            (Optional)</label>
                        <select name="id_proyek" id="id_proyek"
                            class="w-full rounded-xl border-gray-200 dark:bg-gray-900 dark:border-gray-700 dark:text-white">
                            <option value="">-- Tanpa Proyek (Umum) --</option>
                            @foreach ($proyek as $p)
                                {{-- Kita asumsikan ada kolom 'nilai_kontrak' di tabel proyek --}}
                                <option value="{{ $p->id_proyek }}" data-kontrak="{{ $p->nilai_kontrak }}">
                                    {{ $p->nama }} (Kontrak: Rp
                                    {{ number_format($p->nilai_kontrak, 0, ',', '.') }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div id="termin_wrapper" class="hidden col-span-1">
                        <label class="block text-xs font-bold text-gray-400 uppercase mb-2">Pilih Termin</label>
                        <select name="id_termin_proyek" id="id_termin_proyek"
                            class="w-full rounded-xl border-gray-200 dark:bg-gray-900 dark:border-gray-700 dark:text-white">
                            <option value="">-- Pilih Termin --</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-400 uppercase mb-2">Nominal (Rp)</label>
                        <input type="text" name="nominal" id="nominal" required
                            class="rupiah w-full rounded-xl border-gray-200 font-bold text-emerald-600 dark:bg-gray-900 dark:border-gray-700"
                            placeholder="0">
                        <small id="nominal_info" class="text-[10px] text-gray-500 mt-1"></small>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-400 uppercase mb-2">Metode Pembayaran</label>
                        <select name="id_metode_bayar" required
                            class="w-full rounded-xl border-gray-200 dark:bg-gray-900 dark:border-gray-700 dark:text-white">
                            @foreach ($metode as $m)
                                <option value="{{ $m->id_metode_bayar }}">{{ $m->nama_metode_bayar }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-400 uppercase mb-2">Bukti Bayar (File)</label>
                        <input type="file" name="upload_bukti"
                            class="w-full text-xs text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-semibold file:bg-emerald-50 file:text-emerald-700 hover:file:bg-emerald-100">
                    </div>

                    <div class="col-span-2">
                        <label class="block text-xs font-bold text-gray-400 uppercase mb-2">Keterangan</label>
                        <textarea name="keterangan" id="keterangan" rows="3" required
                            class="w-full rounded-xl border-gray-200 dark:bg-gray-900 dark:border-gray-700 dark:text-white"
                            placeholder="Contoh: Pembayaran Termin 1 Proyek Pembangunan Jembatan"></textarea>
                    </div>

                    <div
                        class="col-span-2 flex justify-end gap-3 mt-4 border-t pt-6 border-gray-100 dark:border-gray-700">
                        <a href="{{ route('kas-masuk.index') }}"
                            class="px-6 py-2 rounded-xl bg-gray-100 text-gray-600 font-bold hover:bg-gray-200 transition">Batal</a>
                        <button type="submit" id="btnSimpan"
                            class="px-8 py-2 rounded-xl bg-emerald-600 text-white font-bold shadow-lg shadow-emerald-200 hover:bg-emerald-700 transition">
                            Simpan Transaksi
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
                    let maxNominal = Infinity;

                    // 1. AJAX Load Termin saat Proyek dipilih
                    $('#id_proyek').on('change', function() {
                        var idProyek = $(this).val();
                        maxNominal = $(this).find(':selected').data('kontrak') || Infinity;

                        if (idProyek) {
                            $('#termin_wrapper').fadeIn().removeClass('hidden');
                            $('#nominal_info').text('Maksimal nominal: Rp ' + maxNominal.toLocaleString());

                            $.ajax({
                                url: '/get-termin/' + idProyek,
                                type: 'GET',
                                dataType: 'json',
                                success: function(data) {
                                    $('#id_termin_proyek').empty().append(
                                        '<option value="">-- Pilih Termin --</option>');
                                    $.each(data, function(key, value) {
                                        // Sesuai schema: nama_tipe_termin (bisa join dari tipe_termin)
                                        $('#id_termin_proyek').append('<option value="' + value
                                            .id_termin_proyek + '" data-nominal="' + value
                                            .nominal + '" data-label="' + value
                                            .nama_tipe_termin + '">' + value
                                            .nama_tipe_termin + ' (Rp ' + parseFloat(value
                                                .nominal).toLocaleString() + ')</option>');
                                    });
                                }
                            });
                        } else {
                            $('#termin_wrapper').fadeOut().addClass('hidden');
                            $('#id_termin_proyek').empty();
                            $('#nominal_info').text('');
                            maxNominal = Infinity;
                        }
                    });

                    // 2. Auto-fill nominal & keterangan saat termin dipilih
                    $('#id_termin_proyek').on('change', function() {
                        var selected = $(this).find(':selected');
                        var nominal = selected.data('nominal');
                        var label = selected.data('label');
                        var namaProyek = $('#id_proyek find(":selected").text().split('(')[0];

                            if (nominal) {
                                $('#nominal').val(nominal);
                                $('#keterangan').val('Pembayaran ' + label + ' - ' + namaProyek);
                            }
                        });

                        // 3. Validasi Form & SweetAlert2
                        $('#formKasMasuk').on('submit', function(e) {
                            e.preventDefault();
                            let nominalInput = parseFloat($('#nominal').val());

                            // Validasi Nominal vs Nilai Kontrak
                            if (nominalInput > maxNominal) {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Nominal Terlalu Besar!',
                                    text: 'Nominal tidak boleh melebihi nilai kontrak (Rp ' +
                                        maxNominal.toLocaleString() + ')',
                                    confirmButtonColor: '#059669'
                                });
                                return;
                            }

                            // Konfirmasi Simpan
                            Swal.fire({
                                title: 'Simpan Transaksi?',
                                text: "Pastikan data yang diinput sudah benar.",
                                icon: 'question',
                                showCancelButton: true,
                                confirmButtonColor: '#059669',
                                cancelButtonColor: '#9ca3af',
                                confirmButtonText: 'Ya, Simpan!',
                                cancelButtonText: 'Cek Lagi'
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    this.submit(); // Submit form asli
                                }
                            });
                        });
                    });

                    // Global SweetAlert untuk session Laravel (Instruksi Memori)
                    @if (session('success'))
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil!',
                            text: "{{ session('success') }}",
                            timer: 3000,
                            showConfirmButton: false
                        });
                    @endif
                    @if (session('error') || $errors->any())
                        Swal.fire({
                            icon: 'error',
                            title: 'Error!',
                            text: "{{ session('error') ?? 'Ada data yang belum valid.' }}",
                            confirmButtonColor: '#059669'
                        });
                    @endif
    </script>
</x-app-layout>
