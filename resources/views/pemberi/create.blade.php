<x-app-layout>
    @section('title', 'Tambah Data Pemberi')

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Tambah Pemberi Proyek') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 shadow-2xl rounded-2xl overflow-hidden border border-gray-100">
                <div class="bg-indigo-600 px-6 py-4 text-white font-bold text-lg">Tambah Data Pemberi Proyek</div>
                <form action="{{ route('pemberi.store') }}" method="POST"
                    class="p-8 grid grid-cols-1 md:grid-cols-2 gap-6">
                    @csrf
                    <div class="col-span-2 md:col-span-1">
                        <label class="block text-xs font-bold text-gray-400 uppercase mb-2 tracking-widest">Jenis
                            Pemberi</label>
                        <select name="jenis" required
                            class="w-full rounded-xl border-gray-200 focus:ring-2 focus:ring-indigo-500">
                            <option value="Perorangan">Perorangan</option>
                            <option value="Swasta">Swasta</option>
                            <option value="Pemerintah">Pemerintah</option>
                        </select>
                    </div>
                    <div class="col-span-2 md:col-span-1">
                        <label class="block text-xs font-bold text-gray-400 uppercase mb-2 tracking-widest">Nama
                            Instansi / Owner</label>
                        <input type="text" name="nama" required
                            class="w-full rounded-xl border-gray-200 focus:ring-2 focus:ring-indigo-500"
                            placeholder="Contoh: PT. Maju Jaya">
                    </div>
                    <div class="col-span-2">
                        <label class="block text-xs font-bold text-gray-400 uppercase mb-2 tracking-widest">Alamat
                            Lengkap</label>
                        <input type="text" name="alamat"
                            class="w-full rounded-xl border-gray-200 focus:ring-2 focus:ring-indigo-500">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-400 uppercase mb-2 tracking-widest">Nama
                            Penanggung
                            Jawab</label>
                        <input type="text" name="penanggung_jawab" required
                            class="w-full rounded-xl border-gray-200 focus:ring-2 focus:ring-indigo-500">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-400 uppercase mb-2 tracking-widest">Nomor
                            Telepon</label>
                        <input type="text" name="no_telp"
                            class="rupiah w-full rounded-xl border-gray-200 focus:ring-2 focus:ring-indigo-500">
                    </div>
                    <div class="col-span-2">
                        <label
                            class="block text-xs font-bold text-gray-400 uppercase mb-2 tracking-widest">Email</label>
                        <input type="email" name="email"
                            class="w-full rounded-xl border-gray-200 focus:ring-2 focus:ring-indigo-500">
                    </div>
                    <div class="col-span-2 flex justify-end gap-3 mt-4">
                        <a href="{{ route('pemberi.index') }}"
                            class="px-6 py-2 rounded-xl bg-gray-100 text-gray-600 font-bold hover:bg-gray-200 transition">Batal</a>
                        <button type="submit"
                            class="px-8 py-2 rounded-xl bg-indigo-600 text-white font-bold shadow-lg shadow-indigo-200 hover:bg-indigo-700 transition">Simpan
                            Client</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Script SweetAlert2 --}}
    <script>
        // Alert untuk pesan Sukses
        @if (session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: "{{ session('success') }}",
                timer: 3000,
                showConfirmButton: false,
                timerProgressBar: true
            });
        @endif

        // Alert untuk pesan Error (Gagal Simpan)
        @if (session('error'))
            Swal.fire({
                icon: 'error',
                title: 'Gagal!',
                text: "{{ session('error') }}",
                confirmButtonColor: '#4f46e5'
            });
        @endif

        // Alert untuk Error Validasi (Inputan salah)
        @if ($errors->any())
            Swal.fire({
                icon: 'warning',
                title: 'Data Tidak Valid!',
                html: `
                    <div class="text-left text-sm">
                        @foreach ($errors->all() as $error)
                            <p>• {{ $error }}</p>
                        @endforeach
                    </div>
                `,
                confirmButtonColor: '#4f46e5'
            });
        @endif
    </script>
</x-app-layout>
