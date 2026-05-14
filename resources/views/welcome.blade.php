<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>SIM Konstruksi - Management System</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />

    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif
</head>

<body
    class="bg-[#FDFDFC] dark:bg-[#0a0a0a] text-[#1b1b18] flex p-6 lg:p-8 items-center lg:justify-center min-h-screen flex-col">
    <header class="w-full lg:max-w-4xl max-w-[335px] text-sm mb-6 not-has-[nav]:hidden">
        @if (Route::has('login'))
            <nav class="flex items-center justify-end gap-4">
                @auth
                    <a href="{{ url('/dashboard') }}"
                        class="inline-block px-5 py-1.5 dark:text-[#EDEDEC] border-[#19140035] hover:border-[#1915014a] border text-[#1b1b18] dark:border-[#3E3E3A] dark:hover:border-[#62605b] rounded-sm text-sm leading-normal">
                        Dashboard
                    </a>
                @else
                    {{-- <a href="{{ route('login') }}" class="inline-block px-5 py-1.5 dark:text-[#EDEDEC] text-[#1b1b18] border border-transparent hover:border-[#19140035] dark:hover:border-[#3E3E3A] rounded-sm text-sm leading-normal">
                            Log in
                        </a> --}}
                @endauth
            </nav>
        @endif
    </header>

    <div
        class="flex items-center justify-center w-full transition-opacity opacity-100 duration-750 lg:grow starting:opacity-0">
        <main
            class="flex max-w-[335px] w-full flex-col-reverse lg:max-w-4xl lg:flex-row shadow-2xl rounded-lg overflow-hidden">
            <div class="flex-1 p-6 pb-12 lg:p-16 bg-white dark:bg-[#161615] dark:text-[#EDEDEC]">
                <div class="mb-8">
                    <img src="{{ asset('images/logo.png') }}" alt="Logo" class="w-16 h-16 object-contain mb-4">
                    <h1 class="text-2xl font-bold mb-2">SIM Konstruksi</h1>
                    <p class="text-[#706f6c] dark:text-[#A1A09A] leading-relaxed">
                        Sistem Manajemen Proyek terintegrasi untuk pengelolaan data proyek, vendor, transaksi kas, dan
                        penjurnalan otomatis.
                    </p>
                </div>

                <div class="space-y-4">
                    <div class="flex items-start gap-3">
                        <div class="mt-1 bg-emerald-100 dark:bg-emerald-900/30 p-1 rounded">
                            <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M5 13l4 4L19 7"></path>
                            </svg>
                        </div>
                        <div>
                            <h3 class="font-medium text-sm">Monitoring Proyek</h3>
                            <p class="text-[12px] text-gray-500">Pantau termin dan progres pemberi proyek.</p>
                        </div>
                    </div>

                    <div class="flex items-start gap-3">
                        <div class="mt-1 bg-indigo-100 dark:bg-indigo-900/30 p-1 rounded">
                            <svg class="w-4 h-4 text-indigo-600" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z">
                                </path>
                            </svg>
                        </div>
                        <div>
                            <h3 class="font-medium text-sm">Manajemen Keuangan</h3>
                            <p class="text-[12px] text-gray-500">Otomasi jurnal umum dari kas masuk dan keluar.</p>
                        </div>
                    </div>
                </div>

                <div class="mt-10">
                    @auth
                        <a href="{{ url('/dashboard') }}"
                            class="inline-block px-8 py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold rounded-lg transition-all shadow-md">
                            Buka Dashboard
                        </a>
                    @else
                        <a href="{{ route('login') }}"
                            class="inline-block px-8 py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold rounded-lg transition-all shadow-md">
                            Masuk ke Sistem
                        </a>
                    @endauth
                </div>
            </div>

            <div class="relative lg:w-[450px] shrink-0 overflow-hidden bg-gray-100">
                <img src="{{ asset('images/login.jpg') }}" alt="Project Management" class="w-full h-full object-cover">
                <div
                    class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent flex items-end p-8 lg:hidden">
                    <p class="text-white font-medium italic text-sm">"Build with precision, manage with ease."</p>
                </div>
                <div
                    class="absolute inset-0 shadow-[inset_0px_0px_0px_1px_rgba(26,26,0,0.16)] dark:shadow-[inset_0px_0px_0px_1px_#fffaed2d]">
                </div>
            </div>
        </main>
    </div>

    <footer class="py-6 text-center text-[11px] text-gray-400 uppercase tracking-widest">
        &copy; {{ date('Y') }} SIM Konstruksi - All Rights Reserved
    </footer>
</body>

</html>
