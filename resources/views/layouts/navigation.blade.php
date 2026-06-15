@php
    $isSuperAdmin = Auth::user()->id_level == 1;
    $activeRoleId = session('active_role_id');

    $semuaAksesTersedia = DB::table('akses')->get();

    $userAksesData = DB::table('user_akses')
        ->join('akses', 'user_akses.id_akses', '=', 'akses.id_akses')
        ->where('user_id', Auth::id())
        ->select('akses.id_akses', 'akses.nama_akses', 'akses.fitur_slug')
        ->get();

    // FIX LOGIC: Cari role aktif
    $activeRole = null;
    if ($activeRoleId) {
        $activeRole = DB::table('akses')->where('id_akses', $activeRoleId)->first();
    }

    // Fallback jika session kosong ATAU role yang di session tiba-tiba tidak ditemukan
    if (!$activeRole) {
        if ($isSuperAdmin) {
            // Ambil akses pertama milik user dari database
            $firstAkses = $userAksesData->first();
            $activeRole = $firstAkses
                ? (object) ['id_akses' => $firstAkses->id_akses, 'nama_akses' => $firstAkses->nama_akses, 'fitur_slug' => 'all']
                : (object) ['id_akses' => null, 'nama_akses' => 'Admin', 'fitur_slug' => 'all'];
        } else {
            $activeRole =
                $userAksesData->first() ?:
                (object) ['id_akses' => null, 'nama_akses' => 'No Access', 'fitur_slug' => ''];
        }
    }

    $slugs = explode(',', $activeRole->fitur_slug ?? '');

    $hasAkses = function ($targetMenu) use ($slugs) {
        return in_array('all', $slugs) || in_array($targetMenu, $slugs);
    };

    $displayAkses = $userAksesData;
@endphp

{{-- Mulai HTML Sidebar lo di bawah sini --}}

<nav x-data="{
    masterOpen: JSON.parse(localStorage.getItem('menu_master') ?? '{{ request()->is('master*') ? 'true' : 'false' }}'),
    transaksiOpen: JSON.parse(localStorage.getItem('menu_transaksi') ?? '{{ request()->is('transaksi*') ? 'true' : 'false' }}'),
    toggleMaster() {
        this.masterOpen = !this.masterOpen;
        localStorage.setItem('menu_master', this.masterOpen);
    },
    toggleTransaksi() {
        this.transaksiOpen = !this.transaksiOpen;
        localStorage.setItem('menu_transaksi', this.transaksiOpen);
    }
}"
    class="h-full flex flex-col border-r border-gray-200 dark:border-gray-700 transition-all duration-300">

    <div class="px-4 pt-4 pb-3 shrink-0">
        <a href="{{ route('dashboard') }}" class="flex items-center gap-3 group">
            <img src="{{ asset('images/logo.png') }}" alt="Logo"
                class="w-28 h-28 object-contain shrink-0 group-hover:scale-110 transition-transform duration-300">
            <div class="flex flex-col">
                <span class="font-extrabold text-[15px] leading-tight text-gray-800 dark:text-white">Manajemen Keuangan</span>
                <span class="font-semibold text-[12px] leading-tight text-gray-500 dark:text-gray-400 mt-0.5">Proyek Konstruksi</span>
            </div>
        </a>
    </div>

    <div class="flex-1 px-3 space-y-1 overflow-y-auto custom-scrollbar mt-8">

        <a href="{{ route('dashboard') }}"
            class="flex items-center px-4 py-3 text-sm font-semibold rounded-lg transition-all duration-200 
           {{ request()->routeIs('dashboard') ? 'bg-indigo-50 text-indigo-600 dark:bg-indigo-900/20' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-700/30 hover:text-indigo-600' }}">
            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6">
                </path>
            </svg>
            Dashboard
        </a>

        <div class="pt-4 pb-2">
            <p class="text-[10px] font-bold text-indigo-600 uppercase px-4 tracking-widest">Main Menu</p>
        </div>

        @if ($hasAkses('all') || $hasAkses('proyek') || $hasAkses('vendor') || $hasAkses('coa') || $hasAkses('pemberi_proyek') || $hasAkses('termin_proyek') || $hasAkses('kategori_kas') || $hasAkses('lra'))
            <div class="space-y-1">
                <button @click="toggleMaster()"
                    :class="masterOpen ? 'bg-gray-50 dark:bg-gray-700/50 text-indigo-600' :
                        'text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-700/30'"
                    class="w-full flex items-center justify-between px-4 py-3 text-sm font-semibold rounded-lg transition-all duration-200">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4m0 5c0 2.21-3.582 4-8 4s-8-1.79-8-4">
                            </path>
                        </svg>
                        Master Data
                    </div>
                    <svg :class="masterOpen ? 'rotate-180' : ''"
                        class="w-4 h-4 transition-transform duration-200" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </button>

                <div x-show="masterOpen" x-collapse x-cloak class="mt-1 space-y-1 px-3">

                    @if ($hasAkses('all'))
                        <div x-data="{ userSubOpen: {{ request()->routeIs('akses.*') || request()->routeIs('users.*') ? 'true' : 'false' }} }">
                            <button @click="userSubOpen = !userSubOpen"
                                class="w-full flex items-center justify-between px-3 py-2 rounded-lg text-sm font-semibold transition-all shadow-sm
                                {{ request()->routeIs('akses.*') || request()->routeIs('users.*') ? 'bg-indigo-100 text-indigo-700 dark:bg-indigo-900/40 dark:text-indigo-300' : 'bg-white/70 dark:bg-gray-700/60 text-gray-600 dark:text-gray-300 hover:bg-white dark:hover:bg-gray-600' }}">
                                <div class="flex items-center gap-2">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                                    <span>User Management</span>
                                </div>
                                <svg :class="userSubOpen ? 'rotate-180' : ''" class="w-3 h-3 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                </svg>
                            </button>
                            <div x-show="userSubOpen" x-collapse class="mt-1 ml-3 space-y-1 pl-3 border-l-2 border-indigo-100 dark:border-gray-600">
                                <a href="{{ route('akses.index') }}"
                                    class="flex items-center px-3 py-1.5 rounded-lg text-xs font-medium transition-all
                                    {{ request()->routeIs('akses.*') ? 'bg-indigo-50 text-indigo-700 dark:bg-indigo-900/30 dark:text-indigo-300 font-semibold' : 'text-gray-500 dark:text-gray-400 hover:bg-white dark:hover:bg-gray-700 hover:text-indigo-600' }}">
                                    Akses User
                                </a>
                                <a href="{{ route('users.index') }}"
                                    class="flex items-center px-3 py-1.5 rounded-lg text-xs font-medium transition-all
                                    {{ request()->routeIs('users.*') ? 'bg-indigo-50 text-indigo-700 dark:bg-indigo-900/30 dark:text-indigo-300 font-semibold' : 'text-gray-500 dark:text-gray-400 hover:bg-white dark:hover:bg-gray-700 hover:text-indigo-600' }}">
                                    Data User
                                </a>
                            </div>
                        </div>
                    @endif

                    @if ($hasAkses('proyek') || $hasAkses('termin_proyek') || $hasAkses('pemberi_proyek') || $hasAkses('all'))
                        <div x-data="{ subOpen: {{ request()->routeIs('pemberi.*') || request()->routeIs('proyek.*') || request()->routeIs('termin.*') ? 'true' : 'false' }} }">
                            <button @click="subOpen = !subOpen"
                                class="w-full flex items-center justify-between px-3 py-2 rounded-lg text-sm font-semibold transition-all shadow-sm
                                {{ request()->routeIs('pemberi.*') || request()->routeIs('proyek.*') || request()->routeIs('termin.*') ? 'bg-indigo-100 text-indigo-700 dark:bg-indigo-900/40 dark:text-indigo-300' : 'bg-white/70 dark:bg-gray-700/60 text-gray-600 dark:text-gray-300 hover:bg-white dark:hover:bg-gray-600' }}">
                                <div class="flex items-center gap-2">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                                    <span>Proyek</span>
                                </div>
                                <svg :class="subOpen ? 'rotate-180' : ''" class="w-3 h-3 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                </svg>
                            </button>
                            <div x-show="subOpen" x-collapse class="mt-1 ml-3 space-y-1 pl-3 border-l-2 border-indigo-100 dark:border-gray-600">
                                <a href="{{ route('pemberi.index') }}"
                                    class="flex items-center px-3 py-1.5 rounded-lg text-xs font-medium transition-all
                                    {{ request()->routeIs('pemberi.*') ? 'bg-indigo-50 text-indigo-700 dark:bg-indigo-900/30 dark:text-indigo-300 font-semibold' : 'text-gray-500 dark:text-gray-400 hover:bg-white dark:hover:bg-gray-700 hover:text-indigo-600' }}">
                                    Pemberi Proyek
                                </a>
                                <a href="{{ route('proyek.index') }}"
                                    class="flex items-center px-3 py-1.5 rounded-lg text-xs font-medium transition-all
                                    {{ request()->routeIs('proyek.*') ? 'bg-indigo-50 text-indigo-700 dark:bg-indigo-900/30 dark:text-indigo-300 font-semibold' : 'text-gray-500 dark:text-gray-400 hover:bg-white dark:hover:bg-gray-700 hover:text-indigo-600' }}">
                                    Data Proyek
                                </a>
                                <a href="{{ route('termin.index') }}"
                                    class="flex items-center px-3 py-1.5 rounded-lg text-xs font-medium transition-all
                                    {{ request()->routeIs('termin.*') ? 'bg-indigo-50 text-indigo-700 dark:bg-indigo-900/30 dark:text-indigo-300 font-semibold' : 'text-gray-500 dark:text-gray-400 hover:bg-white dark:hover:bg-gray-700 hover:text-indigo-600' }}">
                                    Termin Proyek
                                </a>
                            </div>
                        </div>
                    @endif

                    @if ($hasAkses('vendor') || $hasAkses('all'))
                        <a href="{{ route('vendor.index') }}"
                            class="flex items-center gap-2 px-3 py-2 rounded-lg text-sm font-semibold transition-all shadow-sm
                            {{ request()->routeIs('vendor.*') ? 'bg-indigo-100 text-indigo-700 dark:bg-indigo-900/40 dark:text-indigo-300' : 'bg-white/70 dark:bg-gray-700/60 text-gray-600 dark:text-gray-300 hover:bg-white dark:hover:bg-gray-600' }}">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                            Vendor
                        </a>
                    @endif

                    @if ($hasAkses('coa') || $hasAkses('all'))
                        <a href="{{ route('coa.index') }}"
                            class="flex items-center gap-2 px-3 py-2 rounded-lg text-sm font-semibold transition-all shadow-sm
                            {{ request()->routeIs('coa.*') ? 'bg-indigo-100 text-indigo-700 dark:bg-indigo-900/40 dark:text-indigo-300' : 'bg-white/70 dark:bg-gray-700/60 text-gray-600 dark:text-gray-300 hover:bg-white dark:hover:bg-gray-600' }}">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                            COA (Akun)
                        </a>
                    @endif

                    @if ($hasAkses('all'))
                        <a href="{{ route('kategori.index') }}"
                            class="flex items-center gap-2 px-3 py-2 rounded-lg text-sm font-semibold transition-all shadow-sm
                            {{ request()->routeIs('kategori.*') ? 'bg-indigo-100 text-indigo-700 dark:bg-indigo-900/40 dark:text-indigo-300' : 'bg-white/70 dark:bg-gray-700/60 text-gray-600 dark:text-gray-300 hover:bg-white dark:hover:bg-gray-600' }}">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/></svg>
                            Kategori Kas
                        </a>
                    @endif

                    @if ($hasAkses('all') || $hasAkses('lra'))
                        <a href="{{ route('lra.index') }}"
                            class="flex items-center gap-2 px-3 py-2 rounded-lg text-sm font-semibold transition-all shadow-sm
                            {{ request()->routeIs('lra.*') ? 'bg-indigo-100 text-indigo-700 dark:bg-indigo-900/40 dark:text-indigo-300' : 'bg-white/70 dark:bg-gray-700/60 text-gray-600 dark:text-gray-300 hover:bg-white dark:hover:bg-gray-600' }}">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z"/></svg>
                            LRA
                        </a>
                    @endif
                </div>
            </div>
        @endif

        @if ($hasAkses('all') || $hasAkses('kas_masuk') || $hasAkses('kas_keluar'))
            <div class="space-y-1">
                <button @click="toggleTransaksi()"
                    :class="transaksiOpen ? 'bg-gray-50 dark:bg-gray-700/50 text-indigo-600' :
                        'text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-700/30'"
                    class="w-full flex items-center justify-between px-4 py-3 text-sm font-semibold rounded-lg transition-all duration-200">
                    <div class="flex items-center font-semibold">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z">
                            </path>
                        </svg>
                        Transaksi
                    </div>
                    <svg :class="transaksiOpen ? 'rotate-180' : ''"
                        class="w-4 h-4 transition-transform duration-200" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7">
                        </path>
                    </svg>
                </button>

                <div x-show="transaksiOpen" x-collapse x-cloak class="mt-1 space-y-1 px-3">

                    @if ($hasAkses('kas_masuk') || $hasAkses('all'))
                        <a href="{{ route('kas-masuk.index') }}"
                            class="flex items-center gap-2 px-3 py-2 rounded-lg text-sm font-semibold transition-all shadow-sm
                            {{ request()->routeIs('kas-masuk.*') ? 'bg-indigo-100 text-indigo-700 dark:bg-indigo-900/40 dark:text-indigo-300' : 'bg-white/70 dark:bg-gray-700/60 text-gray-600 dark:text-gray-300 hover:bg-white dark:hover:bg-gray-600' }}">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4"/></svg>
                            Kas Masuk
                        </a>
                    @endif

                    @if ($hasAkses('kas_keluar') || $hasAkses('all'))
                        <a href="{{ route('kas-keluar.index') }}"
                            class="flex items-center gap-2 px-3 py-2 rounded-lg text-sm font-semibold transition-all shadow-sm
                            {{ request()->routeIs('kas-keluar.*') ? 'bg-indigo-100 text-indigo-700 dark:bg-indigo-900/40 dark:text-indigo-300' : 'bg-white/70 dark:bg-gray-700/60 text-gray-600 dark:text-gray-300 hover:bg-white dark:hover:bg-gray-600' }}">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8V20m0 0l4-4m-4 4l-4-4M7 20V8m0 0L3 12m4-4l4 4"/></svg>
                            Kas Keluar
                        </a>
                    @endif
                </div>
            </div>
        @endif

        <div class="pt-4 pb-2">
            <p class="text-[10px] font-bold text-indigo-600 uppercase px-4 tracking-widest">Laporan</p>
        </div>

        @if ($hasAkses('all') || $hasAkses('coa') || $hasAkses('jurnal') || $hasAkses('laporan_realisasi') || $hasAkses('laba_rugi'))
            {{-- Jurnal Umum --}}
            @if ($hasAkses('all') || $hasAkses('jurnal'))
            <a href="{{ route('jurnal.index') }}"
                class="flex items-center px-4 py-3 text-sm font-semibold rounded-lg transition-all duration-200 {{ request()->routeIs('jurnal.*') ? 'bg-indigo-50 text-indigo-600 dark:bg-indigo-900/20' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-700/30 hover:text-indigo-600' }}">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01">
                    </path>
                </svg>
                Jurnal Umum
            </a>
            @endif

            {{-- Laporan Realisasi Anggaran --}}
            @if ($hasAkses('all') || $hasAkses('laporan_realisasi'))
            <a href="{{ route('lra.laporan') }}"
                class="flex items-center px-4 py-3 text-sm font-semibold rounded-lg transition-all duration-200 {{ request()->fullUrlIs(route('lra.laporan')) ? 'bg-indigo-50 text-indigo-600 dark:bg-indigo-900/20' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-700/30 hover:text-indigo-600' }}">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z"></path>
                </svg>
                LRA Proyek
            </a>
            @endif

            {{-- Laporan Laba Rugi --}}
            @if ($hasAkses('all') || $hasAkses('laba_rugi'))
            <a href="{{ route('lra.labarugi') }}"
                class="flex items-center px-4 py-3 text-sm font-semibold rounded-lg transition-all duration-200 {{ request()->fullUrlIs(route('lra.labarugi')) ? 'bg-indigo-50 text-indigo-600 dark:bg-indigo-900/20' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-700/30 hover:text-indigo-600' }}">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                    </path>
                </svg>
                Laba Rugi Proyek
            </a>
            @endif
        @endif
    </div>

    {{-- Footer Section: Theme & Profile --}}
    <div class="bg-gray-50 dark:bg-gray-900/50 border-t border-gray-100 dark:border-gray-700 shrink-0">
        {{-- Zoom Controls --}}
        <div class="flex items-center justify-between px-3 py-2 border-b border-gray-100 dark:border-gray-800">
            <div class="flex items-center gap-2">
                <p class="text-[9px] font-bold text-indigo-600 uppercase tracking-widest">Zoom:</p>
                <div class="flex items-center gap-1">
                    <button onclick="adjustZoom(-10)" title="Zoom Out"
                        class="w-6 h-6 flex items-center justify-center rounded-md bg-white dark:bg-gray-700 border border-gray-200 dark:border-gray-600 text-gray-600 dark:text-gray-300 hover:bg-indigo-50 hover:text-indigo-600 hover:border-indigo-300 transition-all text-sm font-bold shadow-sm">
                        −
                    </button>
                    <span id="zoom-label" class="text-[9px] font-bold text-gray-500 dark:text-gray-400 w-8 text-center">100%</span>
                    <button onclick="adjustZoom(10)" title="Zoom In"
                        class="w-6 h-6 flex items-center justify-center rounded-md bg-white dark:bg-gray-700 border border-gray-200 dark:border-gray-600 text-gray-600 dark:text-gray-300 hover:bg-indigo-50 hover:text-indigo-600 hover:border-indigo-300 transition-all text-sm font-bold shadow-sm">
                        +
                    </button>
                    <button onclick="resetZoom()" title="Reset Zoom"
                        class="w-6 h-6 flex items-center justify-center rounded-md bg-white dark:bg-gray-700 border border-gray-200 dark:border-gray-600 text-gray-500 dark:text-gray-400 hover:bg-gray-100 transition-all shadow-sm"
                        style="font-size:9px; font-weight:bold;">
                        ↺
                    </button>
                </div>
            </div>
        </div>

        {{-- Theme Switcher --}}
        <div class="flex items-center justify-between px-3 py-2 border-b border-gray-100 dark:border-gray-800">
            <div class="flex items-center gap-2">
                <p class="text-[9px] font-bold text-indigo-600 uppercase tracking-widest">Tema:</p>
                <div class="flex gap-1.5">
                    <button onclick="setTheme('purple')" title="Ungu"
                        class="w-5 h-5 rounded-full hover:scale-125 transition ring-1 ring-offset-1 shadow-sm"
                        style="background-color: #4f46e5;"></button>
                    <button onclick="setTheme('green')" title="Hijau"
                        class="w-5 h-5 rounded-full hover:scale-125 transition ring-1 ring-offset-1 shadow-sm"
                        style="background-color: #059669;"></button>
                    <button onclick="setTheme('pink')" title="Pink"
                        class="w-5 h-5 rounded-full hover:scale-125 transition ring-1 ring-offset-1 shadow-sm"
                        style="background-color: #db2777;"></button>
                </div>
            </div>
            <button onclick="toggleDarkMode()"
                class="p-1.5 rounded-lg text-gray-400 dark:text-yellow-400 hover:bg-white dark:hover:bg-gray-800 transition-all">
                <svg class="w-4 h-4 dark:hidden" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z"></path>
                </svg>
                <svg class="w-4 h-4 hidden dark:block" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd"
                        d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4 8a4 4 0 11-8 0 4 4 0 018 0zm-.464 4.95l.707.707a1 1 0 001.414-1.414l-.707-.707a1 1 0 00-1.414 1.414zm2.12-10.607a1 1 0 010 1.414l-.706.707a1 1 0 11-1.414-1.414l.707-.707a1 1 0 011.414 0zM17 11a1 1 0 100-2h-1a1 1 0 100 2h1zm-7 4a1 1 0 011 1v1a1 1 0 11-2 0v-1a1 1 0 011-1zM5.05 6.464A1 1 0 106.465 5.05l-.708-.707a1 1 0 00-1.414 1.414l.707.707zm1.414 8.486l-.707.707a1 1 0 01-1.414-1.414l.707-.707a1 1 0 011.414 1.414zM4 11a1 1 0 100-2H3a1 1 0 000 2h1z"
                        clip-rule="evenodd"></path>
                </svg>
            </button>
        </div>

        {{-- Profile Dropdown --}}
        <div class="px-3 py-2" x-data="{ profileOpen: false }">
            <div class="relative">
                <button @click="profileOpen = !profileOpen"
                    class="w-full flex items-center gap-2 p-1.5 rounded-xl hover:bg-white dark:hover:bg-gray-800 transition-all duration-200">

                    {{-- Avatar --}}
                    <div
                        class="w-7 h-7 rounded-lg bg-indigo-600 flex items-center justify-center text-white text-[10px] font-bold shadow-lg shadow-indigo-500/20">
                        {{ strtoupper(substr(Auth::user()->name, 0, 2)) }}
                    </div>

                    {{-- Label Nama & Role --}}
                    <div class="flex-1 text-left overflow-hidden">
                        <p class="text-[9px] font-bold text-gray-800 dark:text-white truncate">
                            {{ Auth::user()->nama_lengkap ?? Auth::user()->name }}
                        </p>
                        <p class="text-[9px] text-gray-500 dark:text-gray-400 truncate">
                            {{ Auth::user()->email }}
                        </p>
                        <p class="text-[9px] text-indigo-500 dark:text-indigo-400 font-bold truncate uppercase mt-0.5">
                            Role: {{ $activeRole->nama_akses ?? 'Staff' }}
                        </p>
                    </div>

                    {{-- Arrow Icon --}}
                    <svg :class="profileOpen ? 'rotate-180' : ''" class="w-3 h-3 text-gray-400 transition-transform"
                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7">
                        </path>
                    </svg>
                </button>

                {{-- Dropdown Content --}}
                <div x-show="profileOpen" x-transition:enter="transition ease-out duration-200"
                    x-transition:enter-start="opacity-0 scale-95 translate-y-2"
                    x-transition:enter-end="opacity-100 scale-100 translate-y-0" @click.away="profileOpen = false"
                    class="absolute bottom-full left-0 w-full mb-2 bg-white dark:bg-gray-800 rounded-2xl shadow-xl border border-gray-100 dark:border-gray-700 overflow-hidden z-50">

                    <div class="p-2 space-y-1">

                        {{-- Switch Role: hanya tampil jika punya lebih dari 1 role --}}
                        @if ($displayAkses->count() > 1)
                            <div class="px-3 py-2 border-b border-gray-50 dark:border-gray-700 mb-1">
                                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Ganti Role</p>
                            </div>

                            <div class="space-y-1">
                                @foreach ($displayAkses as $akses)
                                    <form action="{{ route('switch.role') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="id_akses" value="{{ $akses->id_akses }}">
                                        <button type="submit"
                                            class="w-full flex items-center justify-between px-3 py-2 rounded-lg transition-all mb-1
                {{ $activeRole->id_akses == $akses->id_akses ? 'bg-indigo-600 text-white shadow-md' : 'bg-gray-50 hover:bg-indigo-50 text-gray-700 dark:bg-gray-700/50 dark:text-gray-300' }}">

                                            <span class="text-xs font-semibold">{{ $akses->nama_akses }}</span>

                                            @if ($activeRole->id_akses == $akses->id_akses)
                                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd"
                                                        d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                                        clip-rule="evenodd" />
                                                </svg>
                                            @endif
                                        </button>
                                    </form>
                                @endforeach
                            </div>

                            <div class="border-t border-gray-50 dark:border-gray-700 my-1"></div>
                        @endif

                        {{-- Tombol Profile --}}
                        <a href="{{ route('profile.edit') }}"
                            class="w-full flex items-center gap-2 px-3 py-2 text-xs font-bold text-gray-600 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 rounded-lg transition-all">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                            Edit Profil & Password
                        </a>

                        {{-- Tombol Logout --}}
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit"
                                class="w-full flex items-center gap-2 px-3 py-2 text-xs font-bold text-rose-500 hover:bg-rose-50 dark:hover:bg-rose-900/20 rounded-lg transition-all">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1">
                                    </path>
                                </svg>
                                Keluar Aplikasi
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</nav>
