<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    {{-- <title>{{ config('app.name', 'Laravel') }}</title> --}}
    <title>@yield('title') | SIM Konstruksi</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        [x-cloak] { display: none !important; }

        /* =============================================
           THEME SYSTEM — data-theme attribute on <html>
           ============================================= */

        /* --- PURPLE (default) --- */
        :root, [data-theme="purple"] {
            --c-50:  #eef2ff;
            --c-100: #e0e7ff;
            --c-200: #c7d2fe;
            --c-400: #818cf8;
            --c-500: #6366f1;
            --c-600: #4f46e5;
            --c-700: #4338ca;
            --c-900: #312e81;
            --c-ring: #a5b4fc;
            --c-bg:  #eff6ff;
            --c-sidebar: #dbeafe;
            --c-header: rgba(219, 234, 254, 0.7);
            --c-submenu: rgba(191, 219, 254, 0.6);
        }

        /* --- GREEN --- */
        [data-theme="green"] {
            --c-50:  #ecfdf5;
            --c-100: #d1fae5;
            --c-200: #a7f3d0;
            --c-400: #34d399;
            --c-500: #10b981;
            --c-600: #059669;
            --c-700: #047857;
            --c-900: #064e3b;
            --c-ring: #6ee7b7;
            --c-bg:  #ecfdf5;
            --c-sidebar: #d1fae5;
            --c-header: rgba(209, 250, 229, 0.7);
            --c-submenu: rgba(167, 243, 208, 0.6);
        }

        /* --- PINK --- */
        [data-theme="pink"] {
            --c-50:  #fdf2f8;
            --c-100: #fce7f3;
            --c-200: #fbcfe8;
            --c-400: #f472b6;
            --c-500: #ec4899;
            --c-600: #db2777;
            --c-700: #be185d;
            --c-900: #831843;
            --c-ring: #f9a8d4;
            --c-bg:  #fdf2f8;
            --c-sidebar: #fce7f3;
            --c-header: rgba(252, 231, 243, 0.7);
            --c-submenu: rgba(251, 207, 232, 0.6);
        }

        /* =============================================
           APPLY THEME VARIABLES TO TAILWIND CLASSES
           ============================================= */

        /* Backgrounds */
        .bg-indigo-50, .bg-indigo-600, .bg-indigo-900\/20,
        [class*="bg-indigo"] { --tw-bg-opacity: 1; }

        .bg-indigo-600  { background-color: var(--c-600) !important; }
        .bg-indigo-50   { background-color: var(--c-50)  !important; }
        .bg-indigo-100  { background-color: var(--c-100) !important; }
        .bg-indigo-900\/20 { background-color: color-mix(in srgb, var(--c-600) 20%, transparent) !important; }
        .bg-indigo-900\/30 { background-color: color-mix(in srgb, var(--c-600) 30%, transparent) !important; }

        /* Text */
        .text-indigo-600 { color: var(--c-600) !important; }
        .text-indigo-500 { color: var(--c-500) !important; }
        .text-indigo-400 { color: var(--c-400) !important; }

        /* Borders */
        .border-indigo-100 { border-color: var(--c-100) !important; }
        .border-indigo-200 { border-color: var(--c-200) !important; }
        .border-indigo-800 { border-color: var(--c-700) !important; }

        /* Hover backgrounds */
        .hover\:bg-indigo-50:hover  { background-color: var(--c-50)  !important; }
        .hover\:bg-indigo-700:hover { background-color: var(--c-700) !important; }
        .hover\:text-indigo-600:hover { color: var(--c-600) !important; }

        /* Focus ring */
        .focus\:ring-indigo-500:focus { --tw-ring-color: var(--c-ring) !important; }
        .focus\:border-indigo-500:focus { border-color: var(--c-500) !important; }

        /* Shadow */
        .shadow-indigo-200 { --tw-shadow-color: var(--c-200) !important; }

        /* Ring offset (theme buttons) */
        .ring-indigo-300 { --tw-ring-color: var(--c-ring) !important; }

        /* Active nav item */
        .bg-indigo-50.text-indigo-600 {
            background-color: var(--c-50) !important;
            color: var(--c-600) !important;
        }

        /* Scrollbar */
        .custom-scrollbar::-webkit-scrollbar { width: 4px; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #e5e7eb; border-radius: 10px; }
        .dark .custom-scrollbar::-webkit-scrollbar-thumb { background: #374151; }

        /* Background halaman ikut tema, konten tetap putih */
        body { background-color: var(--c-bg) !important; }
        html.dark body { background-color: #111827 !important; }

        /* Sidebar dark mode override */
        html.dark aside[style] { background-color: #1f2937 !important; }

        /* Pastikan kotak konten tetap putih */
        .bg-white { background-color: #ffffff !important; }
        .dark .bg-white { background-color: #1f2937 !important; }
        .bg-gray-50 { background-color: #f9fafb !important; }
        .dark .bg-gray-50 { background-color: #111827 !important; }

        /* Sub-menu sidebar ikut warna tema */
        aside .bg-white\/70 { background-color: var(--c-submenu) !important; }
        aside .hover\:bg-white:hover { background-color: var(--c-100) !important; }

        /* =============================================
           ANIMASI ZOOM-IN SAAT LOAD
           ============================================= */
        @keyframes zoomFadeIn {
            from {
                opacity: 0;
                transform: scale(0.95);
            }
            to {
                opacity: 1;
                transform: scale(1);
            }
        }

        @keyframes slideInLeft {
            from {
                opacity: 0;
                transform: translateX(-20px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        /* Sidebar animasi slide-in dari kiri */
        aside {
            animation: slideInLeft 0.4s ease-out;
        }

        /* Konten utama animasi zoom-in */
        aside ~ div > .flex-1 {
            animation: zoomFadeIn 0.5s ease-out;
        }

        /* Header animasi fade-in */
        header {
            animation: zoomFadeIn 0.4s ease-out 0.1s both;
        }

        /* Main content animasi zoom-in dengan delay */
        main {
            animation: zoomFadeIn 0.5s ease-out 0.15s both;
        }

        /* Card/tabel animasi zoom-in */
        main .bg-white {
            animation: zoomFadeIn 0.4s ease-out 0.2s both;
        }
    </style>

    <script>
        (function() {
            const savedTheme = localStorage.getItem('theme-name') || 'purple';
            const isDark = localStorage.getItem('dark-mode') === 'true';
            document.documentElement.setAttribute('data-theme', savedTheme);
            if (isDark) document.documentElement.classList.add('dark');
        })();

        function setTheme(themeName) {
            document.documentElement.setAttribute('data-theme', themeName);
            localStorage.setItem('theme-name', themeName);
        }

        function toggleDarkMode() {
            const isDark = document.documentElement.classList.toggle('dark');
            localStorage.setItem('dark-mode', isDark);
        }

        // ===== ZOOM FUNCTIONS =====
        const ZOOM_MIN = 70;
        const ZOOM_MAX = 150;
        const ZOOM_STEP = 10;

        function applyZoom(level) {
            document.body.style.zoom = level + '%';
            localStorage.setItem('page-zoom', level);
            const label = document.getElementById('zoom-label');
            if (label) label.textContent = level + '%';
        }

        function adjustZoom(delta) {
            const current = parseInt(localStorage.getItem('page-zoom') || '100');
            const next = Math.min(ZOOM_MAX, Math.max(ZOOM_MIN, current + delta));
            applyZoom(next);
        }

        function resetZoom() {
            applyZoom(100);
        }

        // Apply saved zoom on page load
        (function() {
            const savedZoom = parseInt(localStorage.getItem('page-zoom') || '100');
            document.body.style.zoom = savedZoom + '%';
            document.addEventListener('DOMContentLoaded', function() {
                const label = document.getElementById('zoom-label');
                if (label) label.textContent = savedZoom + '%';
            });
        })();

        $(document).ready(function() {

            $(document).on('keyup', '.rupiah-display', function() {

                let display = $(this)

                let num = display.val()

                // ambil angka saja
                num = num.replace(/\D/g, '')

                // set hidden input terdekat
                display.closest('div').find('.rupiah-hidden').val(num)

                // format ribuan
                display.val(addCommas(num))
            })

        })

        function addCommas(nStr) {

            nStr += ''

            nStr = nStr.replace(/,/g, '')

            let x = nStr.split('.')

            let x1 = x[0]

            let x2 = x.length > 1 ? '.' + x[1] : ''

            let rgx = /(\d+)(\d{3})/

            while (rgx.test(x1)) {
                x1 = x1.replace(rgx, '$1' + ',' + '$2')
            }

            return x1 + x2
        }
    </script>
    <script>
        function updateTextView(_obj) {
            var num = getNumber(_obj.value);

            if (num == 0) {
                _obj.value = '';
            } else {
                _obj.value = num.toLocaleString('id-ID');
            }
        }

        function getNumber(_str) {
            var arr = _str.toString().split('');
            var out = [];

            for (var cnt = 0; cnt < arr.length; cnt++) {
                if (isNaN(arr[cnt]) == false) {
                    out.push(arr[cnt]);
                }
            }

            return Number(out.join(''));
        }

        document.addEventListener('input', function(e) {

            if (!e.target.classList.contains('rupiah')) return;

            updateTextView(e.target);
        });

        // bersihin sebelum submit
        document.addEventListener('submit', function(e) {

            const inputs = e.target.querySelectorAll('.rupiah');

            inputs.forEach(input => {
                input.value = getNumber(input.value);
            });
        });
    </script>

    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.tailwind.min.css">

    <script src="https://cdn.jsdelivr.net/npm/autoNumeric@4.1.0/dist/autoNumeric.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body
    class="font-sans antialiased dark:bg-gray-900 text-gray-900 dark:text-gray-100 transition-colors duration-300">
    <div class="min-h-screen flex relative">
        {{-- Background Image FULL (behind everything) --}}
        <div class="fixed inset-0 z-0">
            <img src="{{ asset('images/bg-user.jpg') }}" alt="" class="w-full h-full object-cover">
            <div class="absolute inset-0 bg-white/30 dark:bg-gray-900/50"></div>
        </div>

        <aside
            class="border-r border-white/30 dark:border-gray-700 hidden sm:block fixed h-full transition-colors duration-300 z-10 backdrop-blur-md"
            style="width: 340px; background-color: var(--c-header);">
            @include('layouts.navigation')
        </aside>

        <div class="flex-1 flex flex-col relative z-10" style="margin-left: 340px">
            <nav class="backdrop-blur-md border-b border-white/30 dark:border-gray-700 sm:hidden" style="background-color: var(--c-header);">
                @include('layouts.navigation')
            </nav>

            {{-- Content area --}}
            <div class="flex-1">
                <div class="relative z-10">
                    @isset($header)
                        <header class="py-6 px-4 sm:px-6 lg:px-8 backdrop-blur-sm shadow-sm" style="background-color: var(--c-header);">
                            <div class="max-w-7xl mx-auto">
                                {{ $header }}
                            </div>
                        </header>
                    @endisset

                    <main class="p-6">
                        {{ $slot }}
                    </main>
                </div>
            </div>
        </div>
    </div>
</body>

</html>
