<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') - {{ config('app.name', 'SMPN Kutime') }}</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        primary: {
                            50: '#f0f9ff', 100: '#e0f2fe', 200: '#bae6fd',
                            300: '#7dd3fc', 400: '#38bdf8', 500: '#0ea5e9',
                            600: '#0284c7', 700: '#0369a1', 800: '#075985', 900: '#0c4a6e',
                        },
                    },
                    fontFamily: {
                        sans: ['Inter', 'system-ui', 'sans-serif'],
                    },
                    fontSize: {
                        '2xs': ['0.65rem', { lineHeight: '1rem' }],
                    },
                },
            },
        }
    </script>

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    @stack('styles')

    <style>
        /* ── Scrollbar tipis ──────────────────────────────────── */
        ::-webkit-scrollbar { width: 4px; height: 4px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 99px; }
        ::-webkit-scrollbar-thumb:hover { background: #94a3b8; }

        /* ── Konten utama — ukuran kompak ─────────────────────── */
        #mainContent {
            font-size: 0.8125rem;
            line-height: 1.5;
        }
        #mainContent h1 { font-size: 1.125rem; }
        #mainContent h2 { font-size: 1rem; }
        #mainContent h3 { font-size: 0.9375rem; }
        #mainContent h4 { font-size: 0.875rem; }
        #mainContent table th,
        #mainContent table td { padding: 0.5rem 0.75rem; font-size: 0.8125rem; }
        #mainContent input,
        #mainContent select,
        #mainContent textarea { font-size: 0.8125rem; }

        /* ── Sidebar nav item ─────────────────────────────────── */
        .nav-link-item {
            display: flex;
            align-items: center;
            gap: 0.625rem;
            padding: 0.45rem 0.75rem;
            border-radius: 0.625rem;
            font-size: 0.8125rem;
            font-weight: 500;
            color: #475569;
            text-decoration: none;
            transition: background .15s, color .15s;
            white-space: nowrap;
        }
        .nav-link-item:hover { background: #f1f5f9; color: #1e293b; }
        .dark .nav-link-item { color: #94a3b8; }
        .dark .nav-link-item:hover { background: rgba(255,255,255,.06); color: #e2e8f0; }
        .nav-link-item.active { background: #eef2ff; color: #4338ca; font-weight: 600; }
        .dark .nav-link-item.active { background: rgba(99,102,241,.18); color: #a5b4fc; }

        /* ── Logo area sidebar ────────────────────────────────── */
        .sidebar-logo-area {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 1.25rem 1rem 1rem;
            border-bottom: 1px solid #e2e8f0;
            gap: 0.625rem;
        }
        .dark .sidebar-logo-area { border-color: #334155; }

        .sidebar-logo-img {
            width: 3rem;
            height: 3rem;
            border-radius: 0.875rem;
            overflow: hidden;
            border: 1.5px solid rgba(99,102,241,.18);
            background: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 2px 8px rgba(99,102,241,.12);
            flex-shrink: 0;
        }

        .sidebar-logo-text {
            text-align: center;
            min-width: 0;
        }
        .sidebar-logo-text .singkatan {
            display: block;
            font-size: 0.8125rem;
            font-weight: 700;
            letter-spacing: -.01em;
            color: #4f46e5;
            line-height: 1.2;
        }
        .dark .sidebar-logo-text .singkatan { color: #818cf8; }
        .sidebar-logo-text .nama {
            display: block;
            color: #94a3b8;
            line-height: 1.3;
            margin-top: 1px;
            font-size: .6rem;
        }
        .dark .sidebar-logo-text .nama { color: #64748b; }

        /* ── Dropdown user ────────────────────────────────────── */
        .user-dropdown { transform-origin: top right; }
        [x-cloak] { display: none !important; }
    </style>
</head>

<body class="min-h-screen bg-slate-50 text-slate-800 antialiased dark:bg-slate-950 dark:text-slate-200"
      style="font-size:0.8125rem">

<div class="flex h-screen overflow-hidden">

    {{-- ════════════════════════════════════════════════════
         SIDEBAR
    ════════════════════════════════════════════════════ --}}
    @php
        $logoUrl     = \App\Models\SchoolSetting::logoUrl();
        $singkatan   = \App\Models\SchoolSetting::get('singkatan', 'SMPN Kutime');
        $namaSekolah = \App\Models\SchoolSetting::get('nama_sekolah', 'SMP Negeri Kutime');
    @endphp

    <aside
        x-data="{ sidebarOpen: window.innerWidth >= 1024 }"
        class="fixed inset-y-0 left-0 z-50 w-60 bg-white dark:bg-slate-900 shadow-xl
               border-r border-slate-200 dark:border-slate-700 flex flex-col
               transition-transform duration-300 ease-in-out
               lg:relative lg:translate-x-0"
        :class="{ '-translate-x-full': !sidebarOpen, 'translate-x-0': sidebarOpen }">

        {{-- ── Logo: gambar di atas, teks di bawah, tengah ── --}}
        <div class="sidebar-logo-area">

            {{-- Tombol close (mobile) — posisikan di pojok kanan atas --}}
            <button @click="sidebarOpen = false"
                    class="lg:hidden absolute top-3 right-3 p-1 text-slate-400
                           hover:text-slate-600 dark:text-slate-500 rounded-lg
                           hover:bg-slate-100 dark:hover:bg-slate-800 transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>

            <a href="{{ auth()->user()->isAdmin()
                            ? route('admin.dashboard')
                            : (auth()->user()->isGuru()
                                ? route('guru.dashboard')
                                : route('siswa.dashboard')) }}"
               class="flex flex-col items-center gap-2 group w-full">

                {{-- Logo gambar ── di atas --}}
                <div class="sidebar-logo-img group-hover:shadow-md transition-shadow">
                    @if($logoUrl)
                        <img src="{{ $logoUrl }}"
                             alt="Logo {{ $singkatan }}"
                             class="w-full h-full object-contain p-1">
                    @else
                        <span style="font-size:1.5rem">🏫</span>
                    @endif
                </div>

                {{-- Teks ── di bawah logo --}}
                <div class="sidebar-logo-text">
                    <span class="singkatan">{{ $singkatan }}</span>
                    <span class="nama">{{ $namaSekolah }}</span>
                </div>

            </a>
        </div>

        {{-- ── Navigasi ────────────────────────────────────── --}}
        <nav class="flex-1 overflow-y-auto px-2 py-3 space-y-0.5">

            @if(auth()->user()->isAdmin())
                <p class="px-3 mb-1.5 mt-1 text-2xs font-semibold uppercase tracking-widest
                           text-slate-400 dark:text-slate-500">Admin Panel</p>

                @php
                    $adminNav = [
                        ['route'=>'admin.dashboard',     'label'=>'Dashboard',      'match'=>'admin.dashboard',
                         'icon'=>'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1v-5m10-10l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-5'],
                        ['route'=>'admin.profil',        'label'=>'Data Diri',       'match'=>'admin.profil*',
                         'icon'=>'M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z'],
                        ['route'=>'admin.users.index',   'label'=>'Kelola User',     'match'=>'admin.users*',
                         'icon'=>'M17 14v6m-3-3h6M6 10h2a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v2a2 2 0 002 2zm10 0h2a2 2 0 002-2v-2a2 2 0 00-2-2h-2a2 2 0 00-2 2v2a2 2 0 002 2zM6 20h2a2 2 0 002-2v-2a2 2 0 00-2-2H6a2 2 0 00-2 2v2a2 2 0 002 2'],
                        ['route'=>'admin.kelas.index',   'label'=>'Kelola Kelas',    'match'=>'admin.kelas*',
                         'icon'=>'M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4'],
                        ['route'=>'admin.pengumuman',    'label'=>'Pengumuman',      'match'=>'admin.pengumuman*',
                         'icon'=>'M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9'],
                        ['route'=>'admin.absensi-guru.index',  'label'=>'Absensi Guru',    'match'=>'admin.absensi-guru*',
                         'icon'=>'M8 7V3m8 4V3M5 11h14M5 19h14M5 5h2m10 0h2'],
                        ['route'=>'admin.data-akademik.index', 'label'=>'Data Akademik',   'match'=>'admin.data-akademik*',
                         'icon'=>'M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z'],
                        ['route'=>'admin.kelola-website','label'=>'Kelola Website',  'match'=>'admin.kelola-website*',
                         'icon'=>'M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z'],
                    ];
                @endphp

                @foreach($adminNav as $item)
                    <a href="{{ route($item['route']) }}"
                       class="nav-link-item {{ request()->routeIs($item['match']) ? 'active' : '' }}">
                        <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  stroke-width="1.8" d="{{ $item['icon'] }}"/>
                        </svg>
                        {{ $item['label'] }}
                    </a>
                @endforeach

            @elseif(auth()->user()->isGuru())
                <p class="px-3 mb-1.5 mt-1 text-2xs font-semibold uppercase tracking-widest
                           text-slate-400 dark:text-slate-500">Guru Panel</p>

                @php
                    $guruNav = [
                        ['route'=>'guru.dashboard',       'label'=>'Dashboard',       'match'=>'guru.dashboard',
                         'icon'=>'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1v-5m10-10l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-5'],
                        ['route'=>'guru.profil',          'label'=>'Data Diri',        'match'=>'guru.profil*',
                         'icon'=>'M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z'],
                         ['route'=>'guru.wali-kelas',          'label'=>'Wali Kelas',        'match'=>'guru.wali-kelas*',
                         'icon'=>'M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z'],
                        ['route'=>'guru.jadwal-mengajar', 'label'=>'Jadwal Mengajar',  'match'=>'guru.jadwal-mengajar*',
                         'icon'=>'M8 7V3m8 4V3M5 11h14M5 19h14M5 5h2m10 0h2'],
                        ['route'=>'guru.absensi-siswa.index',   'label'=>'Absensi Siswa',    'match'=>'guru.absensi-siswa*',
                         'icon'=>'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4'],
                        ['route'=>'guru.pengumuman',      'label'=>'Pengumuman',       'match'=>'guru.pengumuman*',
                         'icon'=>'M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9'],
                    ];
                @endphp

                @foreach($guruNav as $item)
                    <a href="{{ route($item['route']) }}"
                       class="nav-link-item {{ request()->routeIs($item['match']) ? 'active' : '' }}">
                        <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  stroke-width="1.8" d="{{ $item['icon'] }}"/>
                        </svg>
                        {{ $item['label'] }}
                    </a>
                @endforeach

            @else
                <p class="px-3 mb-1.5 mt-1 text-2xs font-semibold uppercase tracking-widest
                           text-slate-400 dark:text-slate-500">Siswa Panel</p>

                @php
                    $siswaNav = [
                        ['route'=>'siswa.dashboard',        'label'=>'Dashboard',        'match'=>'siswa.dashboard',
                         'icon'=>'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1v-5m10-10l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-5'],
                        ['route'=>'siswa.jadwal-pelajaran', 'label'=>'Jadwal Pelajaran', 'match'=>'siswa.jadwal-pelajaran*',
                         'icon'=>'M8 7V3m8 4V3M5 11h14M5 19h14M5 5h2m10 0h2'],
                        ['route'=>'siswa.pengumuman',       'label'=>'Pengumuman',        'match'=>'siswa.pengumuman*',
                         'icon'=>'M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9'],
                        ['route'=>'siswa.profil',           'label'=>'Data Diri',         'match'=>'siswa.profil*',
                         'icon'=>'M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z'],
                    ];
                @endphp

                @foreach($siswaNav as $item)
                    <a href="{{ route($item['route']) }}"
                       class="nav-link-item {{ request()->routeIs($item['match']) ? 'active' : '' }}">
                        <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  stroke-width="1.8" d="{{ $item['icon'] }}"/>
                        </svg>
                        {{ $item['label'] }}
                    </a>
                @endforeach
            @endif

        </nav>

        {{-- Footer sidebar --}}
        <div class="px-4 py-3 border-t border-slate-200 dark:border-slate-700 shrink-0">
            <p class="text-slate-400 dark:text-slate-600 text-center" style="font-size:.6rem">
                © {{ date('Y') }} {{ $singkatan }} • Semua hak dilindungi
            </p>
        </div>

    </aside>

    {{-- Overlay mobile --}}
    <div x-show="sidebarOpen && window.innerWidth < 1024"
         @click="sidebarOpen = false"
         x-cloak
         class="fixed inset-0 bg-black/50 z-40 lg:hidden"></div>

    {{-- ════════════════════════════════════════════════════
         MAIN AREA
    ════════════════════════════════════════════════════ --}}
    <div class="flex-1 flex flex-col min-w-0 overflow-hidden">

        {{-- ── TOPBAR ─────────────────────────────────────── --}}
        <header class="bg-white dark:bg-slate-900 border-b border-slate-200 dark:border-slate-700
                       shadow-sm flex items-center px-4 gap-3 shrink-0"
                style="height:3.25rem">

            {{-- Hamburger (mobile) --}}
            <button @click="sidebarOpen = !sidebarOpen"
                    class="lg:hidden p-1.5 rounded-lg text-slate-500 hover:bg-slate-100
                           dark:hover:bg-slate-800 transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
            </button>

            {{-- Page title --}}
            <h1 class="flex-1 text-sm font-semibold text-slate-800 dark:text-slate-100 truncate">
                @yield('title', 'Dashboard')
            </h1>

            {{-- ── USER DROPDOWN ──────────────────────────── --}}
            <div class="relative shrink-0" x-data="{ open: false }">

                {{-- Trigger --}}
                <button @click="open = !open"
                        class="flex items-center gap-2 px-2 py-1.5 rounded-xl
                               hover:bg-slate-100 dark:hover:bg-slate-800
                               transition focus:outline-none">

                    {{-- Avatar --}}
                    <div class="w-7 h-7 rounded-lg overflow-hidden ring-2 ring-indigo-200
                                dark:ring-indigo-700 flex items-center justify-center
                                bg-slate-100 dark:bg-slate-700 shrink-0">
                        @if(!empty(auth()->user()->photo))
                            <img src="{{ asset('storage/' . auth()->user()->photo) }}"
                                 alt="" class="w-full h-full object-cover">
                        @else
                            <span class="text-xs font-bold text-indigo-600 dark:text-indigo-300">
                                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                            </span>
                        @endif
                    </div>

                    {{-- Nama & Role --}}
                    <div class="hidden sm:block text-left">
                        <p class="text-xs font-semibold text-slate-700 dark:text-slate-200
                                  leading-tight truncate max-w-[120px]">
                            {{ auth()->user()->name }}
                        </p>
                        <p class="leading-tight text-slate-400 dark:text-slate-500"
                           style="font-size:.65rem">
                            @if(auth()->user()->isAdmin()) Admin
                            @elseif(auth()->user()->isGuru()) Guru
                            @else Siswa
                            @endif
                        </p>
                    </div>

                    {{-- Chevron --}}
                    <svg class="w-3.5 h-3.5 text-slate-400 transition-transform duration-200 shrink-0"
                         :class="open ? 'rotate-180' : ''"
                         fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M19 9l-7 7-7-7"/>
                    </svg>
                </button>

                {{-- Dropdown panel --}}
                <div x-show="open"
                     @click.outside="open = false"
                     x-transition:enter="transition ease-out duration-100"
                     x-transition:enter-start="opacity-0 scale-95"
                     x-transition:enter-end="opacity-100 scale-100"
                     x-transition:leave="transition ease-in duration-75"
                     x-transition:leave-start="opacity-100 scale-100"
                     x-transition:leave-end="opacity-0 scale-95"
                     x-cloak
                     class="absolute right-0 mt-2 w-52 bg-white dark:bg-slate-800
                            rounded-2xl shadow-xl border border-slate-200 dark:border-slate-700
                            py-1.5 z-50 user-dropdown">

                    {{-- Info akun --}}
                    <div class="px-4 py-2.5 border-b border-slate-100 dark:border-slate-700 mb-1">
                        <p class="text-xs font-semibold text-slate-700 dark:text-slate-200 truncate">
                            {{ auth()->user()->name }}
                        </p>
                        <p class="text-slate-400 dark:text-slate-500 truncate mt-0.5"
                           style="font-size:.65rem">
                            {{ auth()->user()->email }}
                        </p>
                    </div>

                    {{-- Profil Saya --}}
                    <a href="{{ auth()->user()->isAdmin()
                                    ? route('admin.profil')
                                    : (auth()->user()->isGuru()
                                        ? route('guru.profil')
                                        : route('siswa.profil')) }}"
                       @click="open = false"
                       class="flex items-center gap-2.5 px-4 py-2 text-xs font-medium
                              text-slate-700 dark:text-slate-300
                              hover:bg-slate-50 dark:hover:bg-slate-700/60 transition">
                        <span class="w-6 h-6 rounded-lg bg-indigo-50 dark:bg-indigo-900/30
                                     flex items-center justify-center shrink-0">
                            <svg class="w-3.5 h-3.5 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                        </span>
                        Profil Saya
                    </a>

                    <div class="border-t border-slate-100 dark:border-slate-700 my-1"></div>

                    {{-- Keluar --}}
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit"
                                class="w-full flex items-center gap-2.5 px-4 py-2 text-xs font-medium
                                       text-red-600 dark:text-red-400
                                       hover:bg-red-50 dark:hover:bg-red-900/20 transition">
                            <span class="w-6 h-6 rounded-lg bg-red-50 dark:bg-red-900/20
                                         flex items-center justify-center shrink-0">
                                <svg class="w-3.5 h-3.5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3
                                             3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                                </svg>
                            </span>
                            Keluar
                        </button>
                    </form>

                </div>
            </div>

        </header>

        {{-- ── KONTEN UTAMA ─────────────────────────────────── --}}
        <main id="mainContent"
              class="flex-1 p-4 md:p-5 overflow-y-auto bg-slate-50 dark:bg-slate-950">

            {{-- Flash success --}}
            @if(session('success'))
                <div class="mb-4 flex items-center gap-2 px-4 py-3 rounded-2xl text-xs font-medium
                            bg-emerald-50 dark:bg-emerald-900/20
                            border border-emerald-200 dark:border-emerald-700
                            text-emerald-700 dark:text-emerald-300">
                    <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    {{ session('success') }}
                </div>
            @endif

            {{-- Flash error --}}
            @if(session('error'))
                <div class="mb-4 flex items-center gap-2 px-4 py-3 rounded-2xl text-xs font-medium
                            bg-red-50 dark:bg-red-900/20
                            border border-red-200 dark:border-red-700
                            text-red-700 dark:text-red-300">
                    <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0
                                 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/>
                    </svg>
                    {{ session('error') }}
                </div>
            @endif

            @yield('content')

        </main>
    </div>
</div>

<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
@stack('scripts')

</body>
</html>