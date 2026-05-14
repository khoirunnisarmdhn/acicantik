<x-app-layout>
    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-2xl rounded-3xl overflow-hidden border border-gray-100 print:shadow-none print:border-none">
                
                <div class="bg-rose-600 px-8 py-8 text-white flex justify-between items-center print:bg-white print:text-black print:border-b print:px-0">
                    <div>
                        <h2 class="text-3xl font-black tracking-tighter">RINCIAN PENGELUARAN</h2>
                        <p class="text-sm opacity-80 font-mono">{{ $data->no_form }}</p>
                    </div>
                    <div class="text-right hidden sm:block print:hidden">
                        <button onclick="window.print()" class="bg-white/20 hover:bg-white/30 p-3 rounded-xl transition">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                        </button>
                    </div>
                </div>

                <div class="p-8">
                    <div class="grid grid-cols-2 gap-8 mb-10">
                        <div>
                            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Tanggal Transaksi</p>
                            <p class="text-lg font-bold text-gray-800">{{ \Carbon\Carbon::parse($data->tanggal_keluar)->translatedFormat('d F Y') }}</p>
                        </div>
                        <div class="text-right">
                            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Metode Pembayaran</p>
                            <p class="text-lg font-bold text-rose-600">{{ $data->nama_metode_bayar }}</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 p-6 bg-gray-50 rounded-2xl border border-gray-100 mb-10">
                        <div>
                            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Kategori</p>
                            <p class="font-bold text-gray-700">{{ $data->nama_kategori }}</p>
                        </div>
                        <div>
                            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Diterima Oleh (Vendor)</p>
                            <p class="font-bold text-gray-700">{{ $data->nama ?? '-' }}</p>
                        </div>
                        <div class="md:col-span-2">
                            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Proyek Terkait</p>
                            <p class="font-bold text-indigo-600">{{ $data->nama_proyek ?? 'Pengeluaran Umum / Non-Proyek' }}</p>
                        </div>
                    </div>

                    <div class="mb-10">
                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-2">Keterangan / Keperluan</p>
                        <p class="text-gray-700 leading-relaxed bg-white p-4 rounded-xl border border-gray-100 shadow-sm italic">
                            "{{ $data->keterangan }}"
                        </p>
                    </div>

                    <div class="flex justify-between items-center border-t-2 border-dashed border-gray-100 pt-8">
                        <div>
                            <p class="text-xs font-bold text-gray-400 uppercase">Total Pengeluaran</p>
                            <h3 class="text-4xl font-black text-rose-600">Rp {{ number_format($data->nominal, 0, ',', '.') }}</h3>
                        </div>
                        
                        <div class="text-right print:hidden">
                            @if($data->upload_bukti)
                                <a href="{{ asset('uploads/kas_keluar/'.$data->upload_bukti) }}" target="_blank" class="inline-flex items-center gap-2 px-4 py-2 bg-gray-800 text-white rounded-lg text-xs font-bold hover:bg-black transition">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                    LIHAT BUKTI FISIK
                                </a>
                            @else
                                <span class="text-xs text-gray-400 italic">Tanpa Lampiran Bukti</span>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="bg-gray-50 px-8 py-4 flex justify-between items-center border-t border-gray-100 print:hidden">
                    <a href="{{ url()->previous() }}" class="text-sm font-bold text-gray-500 hover:text-gray-700 flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                        Kembali
                    </a>
                    <p class="text-[10px] text-gray-400 font-mono">Dibuat pada: {{ $data->created_at }}</p>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            @if (session('error'))
                Swal.fire({
                    icon: 'error',
                    title: 'Masalah Akses',
                    text: "{!! addslashes(session('error')) !!}",
                    confirmButtonColor: '#e11d48',
                });
            @endif
        });
    </script>

    <style>
        @media print {
            body { background: white !important; }
            nav, .print\:hidden { display: none !important; }
            .max-w-4xl { max-width: 100% !important; width: 100% !important; margin: 0 !important; }
            .rounded-3xl { border-radius: 0 !important; }
        }
    </style>
</x-app-layout>