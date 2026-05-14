<x-guest-layout>
    <div class="mb-6 text-center">
        <div class="inline-flex items-center justify-center w-14 h-14 bg-indigo-600 rounded-2xl shadow-lg shadow-indigo-500/30 text-white text-xl font-bold mb-3">
            SIM
        </div>
        <h2 class="text-2xl font-bold text-gray-800 dark:text-white">Daftar Akun Baru</h2>
        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Gabung ke sistem manajemen konstruksi</p>
    </div>

    <form method="POST" action="{{ route('register') }}" class="space-y-4">
        @csrf

        <input type="hidden" name="id_level" value="2">
        <input type="hidden" name="id_jabatan" value="2">
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <x-input-label for="name" :value="__('Username')" class="font-semibold" />
                <x-text-input id="name" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 rounded-xl shadow-sm" type="text" name="name" :value="old('name')" required autofocus placeholder="Contoh: username" />
                <x-input-error :messages="$errors->get('name')" class="mt-1 text-xs" />
            </div>

            <div>
                <x-input-label for="nama_lengkap" :value="__('Nama Lengkap')" class="font-semibold" />
                <x-text-input id="nama_lengkap" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 rounded-xl shadow-sm" type="text" name="nama_lengkap" :value="old('nama_lengkap')" required placeholder="nama lengkap" />
                <x-input-error :messages="$errors->get('nama_lengkap')" class="mt-1 text-xs" />
            </div>
        </div>

        <div>
            <x-input-label for="email" :value="__('Email Kantor/Pribadi')" class="font-semibold" />
            <x-text-input id="email" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 rounded-xl shadow-sm" type="email" name="email" :value="old('email')" required placeholder="email@perusahaan.com" />
            <x-input-error :messages="$errors->get('email')" class="mt-1 text-xs" />
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <x-input-label for="password" :value="__('Password')" class="font-semibold" />
                <x-text-input id="password" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 rounded-xl shadow-sm"
                                type="password"
                                name="password"
                                required placeholder="••••••••" />
                <x-input-error :messages="$errors->get('password')" class="mt-1 text-xs" />
            </div>

            <div>
                <x-input-label for="password_confirmation" :value="__('Ulangi Password')" class="font-semibold" />
                <x-text-input id="password_confirmation" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 rounded-xl shadow-sm"
                                type="password"
                                name="password_confirmation" required placeholder="••••••••" />
                <x-input-error :messages="$errors->get('password_confirmation')" class="mt-1 text-xs" />
            </div>
        </div>

        <div class="pt-4">
            <x-primary-button class="w-full justify-center py-3 bg-indigo-600 hover:bg-indigo-700 rounded-xl font-bold shadow-lg shadow-indigo-500/20 transition-all text-sm tracking-widest">
                {{ __('DAFTAR SEKARANG') }}
            </x-primary-button>
        </div>

        <div class="text-center mt-6">
            <p class="text-sm text-gray-600 dark:text-gray-400">
                Sudah punya akun? 
                <a href="{{ route('login') }}" class="font-bold text-indigo-600 hover:underline">
                    Masuk di sini
                </a>
            </p>
        </div>
    </form>
</x-guest-layout>