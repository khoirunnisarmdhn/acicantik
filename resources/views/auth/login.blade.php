<x-guest-layout>
    <title>SIM Konstruksi - Login</title>


    <div class="mb-8 text-center">
        <img src="{{ asset('images/logo.png') }}" alt="Logo"
            class="mx-auto w-20 h-20 object-contain mb-4 drop-shadow-md">
        <h2 class="text-2xl font-bold text-gray-800 dark:text-white">Selamat Datang Kembali</h2>
        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Silahkan masuk ke akun SIM Konstruksi Anda</p>
    </div>

    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}" class="space-y-5">
        @csrf

        <div>
            <x-input-label for="email" :value="__('Email')" class="font-semibold text-gray-700 dark:text-gray-300" />
            <div class="relative mt-1">
                <x-text-input id="email"
                    class="block w-full pl-10 border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-xl shadow-sm transition-all duration-200"
                    type="email" name="email" :value="old('email')" required autofocus placeholder="nama@email.com" />
            </div>
            <x-input-error :messages="$errors->get('email')" class="mt-2 text-xs" />
        </div>

        <div>
            {{-- <div class="flex items-center justify-between">
                <x-input-label for="password" :value="__('Password')"
                    class="font-semibold text-gray-700 dark:text-gray-300" />
                @if (Route::has('password.request'))
                    <a class="text-xs text-indigo-600 hover:text-indigo-500 font-medium transition-colors"
                        href="{{ route('password.request') }}">
                        {{ __('Lupa password?') }}
                    </a>
                @endif
            </div> --}}
            <div class="relative mt-1">

                <x-text-input id="password"
                    class="block w-full pl-10 border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-xl shadow-sm transition-all duration-200"
                    type="password" name="password" required placeholder="••••••••" />
            </div>
            <x-input-error :messages="$errors->get('password')" class="mt-2 text-xs" />
        </div>

        <div class="flex items-center">
            <input id="remember_me" type="checkbox"
                class="w-4 h-4 rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500 transition-all cursor-pointer"
                name="remember">
            <label for="remember_me"
                class="ms-2 text-sm text-gray-600 dark:text-gray-400 cursor-pointer italic">{{ __('Ingat saya di perangkat ini') }}</label>
        </div>

        <div class="pt-2">
            <x-primary-button
                class="w-full justify-center py-3 bg-indigo-600 hover:bg-indigo-700 active:bg-indigo-900 rounded-xl font-bold shadow-lg shadow-indigo-500/20 transition-all duration-200 text-sm tracking-widest">
                {{ __('MASUK KE SISTEM') }}
            </x-primary-button>
        </div>
    </form>

    {{-- <div class="mt-8 pt-6 border-t border-gray-100 dark:border-gray-800 text-center">
        <p class="text-sm text-gray-600 dark:text-gray-400">
            Belum punya akun?
            <a href="{{ route('register') }}"
                class="font-bold text-indigo-600 hover:text-indigo-500 hover:underline transition-all">
                Daftar Sekarang
            </a>
        </p>
    </div> --}}
</x-guest-layout>
