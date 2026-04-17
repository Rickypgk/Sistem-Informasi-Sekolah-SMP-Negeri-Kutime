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
                },
            },
        }
    </script>

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    @stack('styles')

    <style>
        /* ═══════════════════════════════════════════
           UKURAN PERMANEN — GLOBAL CONTENT SCALE
           Semua konten dalam layout ini menggunakan
           skala kompak yang konsisten
        ═══════════════════════════════════════════ */

        /* Reset base */
        *, *::before, *::after { box-sizing: border-box; }

        /* ── Root font size: 13px kompak ── */
        html { font-size: 13px; }

        /* ── Scrollbar tipis ── */
        ::-webkit-scrollbar { width: 4px; height: 4px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 99px; }
        ::-webkit-scrollbar-thumb:hover { background: #94a3b8; }

        /* ══════════════════════════════════════════
           TYPOGRAPHY SCALE — PERMANEN
        ══════════════════════════════════════════ */
        body {
            font-size: 0.846rem;   /* ~11px efektif */
            line-height: 1.55;
            font-family: 'Inter', system-ui, sans-serif;
        }

        /* Heading permanen di dalam konten */
        #mainContent h1,
        #mainContent .heading-xl  { font-size: 1.077rem; font-weight: 600; line-height: 1.3; }  /* ~14px */

        #mainContent h2,
        #mainContent .heading-lg  { font-size: 0.923rem; font-weight: 600; line-height: 1.35; } /* ~12px */

        #mainContent h3,
        #mainContent .heading-md  { font-size: 0.846rem; font-weight: 600; line-height: 1.4; }  /* ~11px */

        #mainContent h4,
        #mainContent .heading-sm  { font-size: 0.769rem; font-weight: 500; line-height: 1.4; }  /* ~10px */

        /* Label / caption kecil */
        #mainContent .label-xs,
        #mainContent .text-2xs    { font-size: 0.692rem; line-height: 1.3; }  /* ~9px */

        /* ══════════════════════════════════════════
           SPACING PERMANEN — CARD & SECTION
        ══════════════════════════════════════════ */

        /* Card / panel standar */
        #mainContent .card {
            background: #ffffff;
            border-radius: 0.692rem;
            border: 1px solid #e2e8f0;
            padding: 0.923rem 1rem;
            font-size: 0.846rem;
        }
        .dark #mainContent .card {
            background: #1e293b;
            border-color: #334155;
        }

        /* Card header row */
        #mainContent .card-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 0.769rem;
            padding-bottom: 0.615rem;
            border-bottom: 1px solid #f1f5f9;
        }
        .dark #mainContent .card-header { border-color: #334155; }

        /* Card title */
        #mainContent .card-title {
            font-size: 0.846rem;
            font-weight: 600;
            color: #1e293b;
        }
        .dark #mainContent .card-title { color: #e2e8f0; }

        /* ── KPI / Stat card ── */
        #mainContent .stat-card {
            background: #f8fafc;
            border-radius: 0.615rem;
            border: 1px solid #e2e8f0;
            padding: 0.769rem 0.923rem;
        }
        .dark #mainContent .stat-card {
            background: #0f172a;
            border-color: #334155;
        }
        #mainContent .stat-value {
            font-size: 1.385rem;  /* ~18px — angka besar tapi tidak berlebihan */
            font-weight: 700;
            line-height: 1.1;
            color: #1e293b;
        }
        .dark #mainContent .stat-value { color: #f1f5f9; }

        #mainContent .stat-label {
            font-size: 0.692rem;
            font-weight: 500;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 0.04em;
            margin-top: 0.25rem;
        }

        #mainContent .stat-badge {
            font-size: 0.692rem;
            font-weight: 600;
            padding: 0.154rem 0.462rem;
            border-radius: 99px;
        }

        /* ══════════════════════════════════════════
           TABLE — PERMANEN
        ══════════════════════════════════════════ */
        #mainContent table {
            width: 100%;
            border-collapse: collapse;
            font-size: 0.769rem;
        }
        #mainContent table th {
            font-size: 0.692rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.04em;
            color: #64748b;
            padding: 0.462rem 0.615rem;
            border-bottom: 1px solid #e2e8f0;
            text-align: left;
            background: #f8fafc;
        }
        .dark #mainContent table th {
            color: #94a3b8;
            border-color: #334155;
            background: #0f172a;
        }
        #mainContent table td {
            padding: 0.462rem 0.615rem;
            border-bottom: 1px solid #f1f5f9;
            color: #374151;
            vertical-align: middle;
        }
        .dark #mainContent table td {
            border-color: #1e293b;
            color: #cbd5e1;
        }
        #mainContent table tbody tr:hover {
            background: #f8fafc;
        }
        .dark #mainContent table tbody tr:hover { background: #1e293b; }
        #mainContent table tbody tr:last-child td { border-bottom: none; }

        /* ══════════════════════════════════════════
           FORM ELEMENTS — PERMANEN
        ══════════════════════════════════════════ */
        #mainContent input,
        #mainContent select,
        #mainContent textarea {
            font-size: 0.769rem;
            line-height: 1.5;
            padding: 0.385rem 0.615rem;
            border-radius: 0.462rem;
            border: 1px solid #e2e8f0;
            background: #ffffff;
            color: #1e293b;
            width: 100%;
            transition: border-color .15s, box-shadow .15s;
            outline: none;
        }
        .dark #mainContent input,
        .dark #mainContent select,
        .dark #mainContent textarea {
            background: #0f172a;
            border-color: #334155;
            color: #e2e8f0;
        }
        #mainContent input:focus,
        #mainContent select:focus,
        #mainContent textarea:focus {
            border-color: #6366f1;
            box-shadow: 0 0 0 2px rgba(99,102,241,.15);
        }
        #mainContent label {
            font-size: 0.692rem;
            font-weight: 500;
            color: #475569;
            display: block;
            margin-bottom: 0.3rem;
        }
        .dark #mainContent label { color: #94a3b8; }

        /* ── Form group spacing ── */
        #mainContent .form-group { margin-bottom: 0.769rem; }

        /* ══════════════════════════════════════════
           BUTTON — PERMANEN
        ══════════════════════════════════════════ */
        #mainContent .btn {
            display: inline-flex;
            align-items: center;
            gap: 0.308rem;
            padding: 0.385rem 0.769rem;
            border-radius: 0.462rem;
            font-size: 0.769rem;
            font-weight: 500;
            cursor: pointer;
            transition: all .15s;
            border: 1px solid transparent;
            text-decoration: none;
            white-space: nowrap;
        }
        #mainContent .btn svg { width: 0.923rem; height: 0.923rem; flex-shrink: 0; }

        #mainContent .btn-primary {
            background: #4f46e5;
            color: #ffffff;
            border-color: #4f46e5;
        }
        #mainContent .btn-primary:hover { background: #4338ca; border-color: #4338ca; }

        #mainContent .btn-secondary {
            background: #f8fafc;
            color: #475569;
            border-color: #e2e8f0;
        }
        .dark #mainContent .btn-secondary {
            background: #1e293b;
            color: #94a3b8;
            border-color: #334155;
        }
        #mainContent .btn-secondary:hover { background: #f1f5f9; }

        #mainContent .btn-danger {
            background: #fef2f2;
            color: #dc2626;
            border-color: #fecaca;
        }
        #mainContent .btn-danger:hover { background: #fee2e2; }

        #mainContent .btn-sm {
            padding: 0.231rem 0.538rem;
            font-size: 0.692rem;
            border-radius: 0.385rem;
        }
        #mainContent .btn-sm svg { width: 0.769rem; height: 0.769rem; }

        /* ══════════════════════════════════════════
           BADGE / PILL — PERMANEN
        ══════════════════════════════════════════ */
        #mainContent .badge {
            display: inline-flex;
            align-items: center;
            gap: 0.231rem;
            padding: 0.154rem 0.462rem;
            border-radius: 99px;
            font-size: 0.615rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.04em;
        }
        #mainContent .badge-success  { background: #ecfdf5; color: #059669; border: 1px solid #a7f3d0; }
        #mainContent .badge-warning  { background: #fffbeb; color: #d97706; border: 1px solid #fde68a; }
        #mainContent .badge-danger   { background: #fef2f2; color: #dc2626; border: 1px solid #fecaca; }
        #mainContent .badge-info     { background: #eff6ff; color: #2563eb; border: 1px solid #bfdbfe; }
        #mainContent .badge-neutral  { background: #f8fafc; color: #475569; border: 1px solid #e2e8f0; }

        .dark #mainContent .badge-success { background: rgba(5,150,105,.15); color: #34d399; border-color: rgba(5,150,105,.3); }
        .dark #mainContent .badge-warning { background: rgba(217,119,6,.15);  color: #fbbf24; border-color: rgba(217,119,6,.3); }
        .dark #mainContent .badge-danger  { background: rgba(220,38,38,.15);  color: #f87171; border-color: rgba(220,38,38,.3); }
        .dark #mainContent .badge-info    { background: rgba(37,99,235,.15);  color: #60a5fa; border-color: rgba(37,99,235,.3); }
        .dark #mainContent .badge-neutral { background: #1e293b; color: #64748b; border-color: #334155; }

        /* ══════════════════════════════════════════
           ALERT / FLASH — PERMANEN
        ══════════════════════════════════════════ */
        #mainContent .alert {
            display: flex;
            align-items: flex-start;
            gap: 0.538rem;
            padding: 0.615rem 0.769rem;
            border-radius: 0.538rem;
            font-size: 0.769rem;
            font-weight: 500;
            margin-bottom: 0.769rem;
            border: 1px solid transparent;
        }
        #mainContent .alert svg { width: 0.923rem; height: 0.923rem; flex-shrink: 0; margin-top: 0.1rem; }
        #mainContent .alert-success { background: #ecfdf5; color: #065f46; border-color: #a7f3d0; }
        #mainContent .alert-error   { background: #fef2f2; color: #991b1b; border-color: #fecaca; }
        #mainContent .alert-warning { background: #fffbeb; color: #92400e; border-color: #fde68a; }
        #mainContent .alert-info    { background: #eff6ff; color: #1e40af; border-color: #bfdbfe; }

        /* ══════════════════════════════════════════
           GRID UTILITIES — PERMANEN
        ══════════════════════════════════════════ */
        #mainContent .grid-kpi-2 { display: grid; grid-template-columns: repeat(2, 1fr); gap: 0.615rem; }
        #mainContent .grid-kpi-3 { display: grid; grid-template-columns: repeat(3, 1fr); gap: 0.615rem; }
        #mainContent .grid-kpi-4 { display: grid; grid-template-columns: repeat(4, 1fr); gap: 0.615rem; }

        @media (max-width: 640px) {
            #mainContent .grid-kpi-3,
            #mainContent .grid-kpi-4 { grid-template-columns: repeat(2, 1fr); }
        }
        @media (max-width: 400px) {
            #mainContent .grid-kpi-2,
            #mainContent .grid-kpi-3,
            #mainContent .grid-kpi-4 { grid-template-columns: 1fr; }
        }

        /* ══════════════════════════════════════════
           DIVIDER
        ══════════════════════════════════════════ */
        #mainContent .divider {
            border: none;
            border-top: 1px solid #f1f5f9;
            margin: 0.615rem 0;
        }
        .dark #mainContent .divider { border-color: #334155; }

        /* ══════════════════════════════════════════
           AVATAR
        ══════════════════════════════════════════ */
        #mainContent .avatar {
            border-radius: 0.462rem;
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            flex-shrink: 0;
        }
        #mainContent .avatar-sm { width: 1.846rem; height: 1.846rem; font-size: 0.615rem; }
        #mainContent .avatar-md { width: 2.308rem; height: 2.308rem; font-size: 0.769rem; }
        #mainContent .avatar-lg { width: 3rem;     height: 3rem;     font-size: 1rem; }

        /* ══════════════════════════════════════════
           LIST ITEM
        ══════════════════════════════════════════ */
        #mainContent .list-item {
            display: flex;
            align-items: center;
            gap: 0.538rem;
            padding: 0.462rem 0;
            border-bottom: 1px solid #f1f5f9;
            font-size: 0.769rem;
        }
        .dark #mainContent .list-item { border-color: #1e293b; }
        #mainContent .list-item:last-child { border-bottom: none; padding-bottom: 0; }

        /* ══════════════════════════════════════════
           SECTION HEADER (judul section konten)
        ══════════════════════════════════════════ */
        #mainContent .section-title {
            font-size: 0.846rem;
            font-weight: 600;
            color: #1e293b;
            margin-bottom: 0.615rem;
        }
        .dark #mainContent .section-title { color: #e2e8f0; }

        /* ══════════════════════════════════════════
           EMPTY STATE
        ══════════════════════════════════════════ */
        #mainContent .empty-state {
            text-align: center;
            padding: 2rem 1rem;
            color: #94a3b8;
            font-size: 0.769rem;
        }
        #mainContent .empty-state svg {
            width: 2.5rem;
            height: 2.5rem;
            margin: 0 auto 0.615rem;
            opacity: .4;
        }

        /* ══════════════════════════════════════════
           PAGINATION
        ══════════════════════════════════════════ */
        #mainContent .pagination {
            display: flex;
            align-items: center;
            gap: 0.308rem;
            font-size: 0.692rem;
        }
        #mainContent .pagination a,
        #mainContent .pagination span {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 1.692rem;
            height: 1.692rem;
            padding: 0 0.384rem;
            border-radius: 0.385rem;
            border: 1px solid #e2e8f0;
            color: #475569;
            text-decoration: none;
            transition: all .15s;
        }
        #mainContent .pagination a:hover { background: #f1f5f9; }
        #mainContent .pagination .active { background: #4f46e5; color: #fff; border-color: #4f46e5; font-weight: 600; }
        #mainContent .pagination .disabled { opacity: .4; pointer-events: none; }

        /* ══════════════════════════════════════════
           PROGRESS BAR
        ══════════════════════════════════════════ */
        #mainContent .progress-bar {
            height: 0.385rem;
            background: #e2e8f0;
            border-radius: 99px;
            overflow: hidden;
        }
        .dark #mainContent .progress-bar { background: #334155; }
        #mainContent .progress-bar-fill {
            height: 100%;
            border-radius: 99px;
            background: #4f46e5;
            transition: width .4s ease;
        }

        /* ══════════════════════════════════════════
           ICON BUTTON
        ══════════════════════════════════════════ */
        #mainContent .icon-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 1.692rem;
            height: 1.692rem;
            border-radius: 0.385rem;
            border: 1px solid #e2e8f0;
            background: transparent;
            color: #64748b;
            cursor: pointer;
            transition: all .15s;
        }
        #mainContent .icon-btn svg { width: 0.846rem; height: 0.846rem; }
        #mainContent .icon-btn:hover { background: #f1f5f9; color: #1e293b; border-color: #cbd5e1; }
        .dark #mainContent .icon-btn { border-color: #334155; color: #64748b; }
        .dark #mainContent .icon-btn:hover { background: #1e293b; color: #e2e8f0; }

        /* ══════════════════════════════════════════
           MODAL — PERMANEN
        ══════════════════════════════════════════ */
        .modal-overlay {
            position: fixed;
            inset: 0;
            background: rgba(0,0,0,.45);
            z-index: 100;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1rem;
        }
        .modal-box {
            background: #fff;
            border-radius: 0.923rem;
            width: 100%;
            max-width: 28rem;
            max-height: 90vh;
            overflow-y: auto;
            box-shadow: 0 20px 60px rgba(0,0,0,.15);
        }
        .dark .modal-box { background: #1e293b; }
        .modal-header {
            padding: 0.923rem 1rem;
            border-bottom: 1px solid #f1f5f9;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .dark .modal-header { border-color: #334155; }
        .modal-title { font-size: 0.923rem; font-weight: 600; color: #1e293b; }
        .dark .modal-title { color: #e2e8f0; }
        .modal-body { padding: 0.923rem 1rem; font-size: 0.769rem; }
        .modal-footer {
            padding: 0.769rem 1rem;
            border-top: 1px solid #f1f5f9;
            display: flex;
            justify-content: flex-end;
            gap: 0.462rem;
        }
        .dark .modal-footer { border-color: #334155; }

        /* ══════════════════════════════════════════
           SIDEBAR NAV
        ══════════════════════════════════════════ */
        .nav-link-item {
            display: flex;
            align-items: center;
            gap: 0.538rem;
            padding: 0.385rem 0.692rem;
            border-radius: 0.538rem;
            font-size: 0.769rem;
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
        .nav-link-item svg { width: 0.923rem; height: 0.923rem; flex-shrink: 0; }

        /* ── Logo area sidebar ── */
        .sidebar-logo-area {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 1rem 1rem 0.923rem;
            border-bottom: 1px solid #e2e8f0;
            gap: 0.538rem;
            position: relative;
        }
        .dark .sidebar-logo-area { border-color: #334155; }

        .sidebar-logo-img {
            width: 2.615rem;
            height: 2.615rem;
            border-radius: 0.769rem;
            overflow: hidden;
            border: 1.5px solid rgba(99,102,241,.18);
            background: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 2px 8px rgba(99,102,241,.1);
            flex-shrink: 0;
        }

        .sidebar-logo-text { text-align: center; }
        .sidebar-logo-text .singkatan {
            display: block;
            font-size: 0.769rem;
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
            font-size: .615rem;
        }

        /* ── User dropdown ── */
        .user-dropdown { transform-origin: top right; }
        [x-cloak] { display: none !important; }

        /* ── Topbar height ── */
        .topbar { height: 3rem; }

        /* ── Main content padding ── */
        #mainContent { padding: 0.923rem; }
        @media (min-width: 768px) { #mainContent { padding: 1rem 1.154rem; } }

        /* Section spacing dalam konten */
        #mainContent .content-section + .content-section { margin-top: 0.923rem; }
        #mainContent .space-content > * + * { margin-top: 0.769rem; }
    </style>
</head>

<body class="min-h-screen bg-slate-50 text-slate-800 antialiased dark:bg-slate-950 dark:text-slate-200">

<div class="flex h-screen overflow-hidden">

    {{-- ════════════════════════════════════════
         SIDEBAR
    ════════════════════════════════════════ --}}
    @php
        $logoUrl     = \App\Models\SchoolSetting::logoUrl();
        $singkatan   = \App\Models\SchoolSetting::get('singkatan', 'SMPN Kutime');
        $namaSekolah = \App\Models\SchoolSetting::get('nama_sekolah', 'SMP Negeri Kutime');
    @endphp

    <aside
        x-data="{ sidebarOpen: window.innerWidth >= 1024 }"
        class="fixed inset-y-0 left-0 z-50 w-56 bg-white dark:bg-slate-900 shadow-lg
               border-r border-slate-200 dark:border-slate-700 flex flex-col
               transition-transform duration-300 ease-in-out
               lg:relative lg:translate-x-0"
        :class="{ '-translate-x-full': !sidebarOpen, 'translate-x-0': sidebarOpen }">

        {{-- Logo area --}}
        <div class="sidebar-logo-area">

            {{-- Tombol close (mobile) --}}
            <button @click="sidebarOpen = false"
                    class="lg:hidden absolute top-2.5 right-2.5 p-1 text-slate-400
                           hover:text-slate-600 dark:text-slate-500 rounded-lg
                           hover:bg-slate-100 dark:hover:bg-slate-800 transition">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>

            <a href="{{ auth()->user()->isAdmin()
                            ? route('admin.dashboard')
                            : (auth()->user()->isGuru()
                                ? route('guru.dashboard')
                                : route('siswa.dashboard')) }}"
               class="flex flex-col items-center gap-1.5 group w-full">

                <div class="sidebar-logo-img group-hover:shadow-md transition-shadow">
                    @if($logoUrl)
                        <img src="{{ $logoUrl }}"
                             alt="Logo {{ $singkatan }}"
                             class="w-full h-full object-contain p-1">
                    @else
                        <span style="font-size:1.25rem">🏫</span>
                    @endif
                </div>

                <div class="sidebar-logo-text">
                    <span class="singkatan">{{ $singkatan }}</span>
                    <span class="nama">{{ $namaSekolah }}</span>
                </div>

            </a>
        </div>

        {{-- Navigasi --}}
        <nav class="flex-1 overflow-y-auto px-2 py-2.5 space-y-px">

            @if(auth()->user()->isAdmin())
                <p class="px-2.5 mb-1.5 mt-0.5"
                   style="font-size:.6rem;font-weight:600;text-transform:uppercase;
                          letter-spacing:.06em;color:#94a3b8">Admin Panel</p>

                @php
                    $adminNav = [
                        ['route'=>'admin.dashboard',            'label'=>'Dashboard',       'match'=>'admin.dashboard',
                         'icon'=>'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1v-5m10-10l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-5'],
                        ['route'=>'admin.profil',               'label'=>'Data Diri',        'match'=>'admin.profil*',
                         'icon'=>'M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z'],
                        ['route'=>'admin.users.index',          'label'=>'Kelola User',      'match'=>'admin.users*',
                         'icon'=>'M17 14v6m-3-3h6M6 10h2a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v2a2 2 0 002 2zm10 0h2a2 2 0 002-2v-2a2 2 0 00-2-2h-2a2 2 0 00-2 2v2a2 2 0 002 2zM6 20h2a2 2 0 002-2v-2a2 2 0 00-2-2H6a2 2 0 00-2 2v2a2 2 0 002 2'],
                        ['route'=>'admin.kelas.index',          'label'=>'Kelola Kelas',     'match'=>'admin.kelas*',
                         'icon'=>'M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4'],
                        ['route'=>'admin.pengumuman',           'label'=>'Pengumuman',       'match'=>'admin.pengumuman*',
                         'icon'=>'M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9'],
                        ['route'=>'admin.absensi-guru.index',   'label'=>'Absensi Guru',     'match'=>'admin.absensi-guru*',
                         'icon'=>'M8 7V3m8 4V3M5 11h14M5 19h14M5 5h2m10 0h2'],
                        ['route'=>'admin.data-akademik.index',  'label'=>'Data Akademik',    'match'=>'admin.data-akademik*',
                         'icon'=>'M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z'],
                        ['route'=>'admin.kelola-website',       'label'=>'Kelola Website',   'match'=>'admin.kelola-website*',
                         'icon'=>'M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z'],
                    ];
                @endphp

                @foreach($adminNav as $item)
                    <a href="{{ route($item['route']) }}"
                       class="nav-link-item {{ request()->routeIs($item['match']) ? 'active' : '' }}">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  stroke-width="1.8" d="{{ $item['icon'] }}"/>
                        </svg>
                        {{ $item['label'] }}
                    </a>
                @endforeach

            @elseif(auth()->user()->isGuru())
                <p class="px-2.5 mb-1.5 mt-0.5"
                   style="font-size:.6rem;font-weight:600;text-transform:uppercase;
                          letter-spacing:.06em;color:#94a3b8">Guru Panel</p>

                @php
                    $guruNav = [
                        ['route'=>'guru.dashboard',           'label'=>'Dashboard',        'match'=>'guru.dashboard',
                         'icon'=>'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1v-5m10-10l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-5'],
                        ['route'=>'guru.profil',              'label'=>'Data Diri',         'match'=>'guru.profil*',
                         'icon'=>'M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z'],
                        ['route'=>'guru.wali-kelas',          'label'=>'Wali Kelas',        'match'=>'guru.wali-kelas*',
                         'icon'=>'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z'],
                        ['route'=>'guru.jadwal-mengajar',     'label'=>'Jadwal Mengajar',   'match'=>'guru.jadwal-mengajar*',
                         'icon'=>'M8 7V3m8 4V3M5 11h14M5 19h14M5 5h2m10 0h2'],
                        ['route'=>'guru.absensi-siswa.index', 'label'=>'Absensi Siswa',     'match'=>'guru.absensi-siswa*',
                         'icon'=>'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4'],
                        ['route'=>'guru.pengumuman',          'label'=>'Pengumuman',        'match'=>'guru.pengumuman*',
                         'icon'=>'M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9'],
                    ];
                @endphp

                @foreach($guruNav as $item)
                    <a href="{{ route($item['route']) }}"
                       class="nav-link-item {{ request()->routeIs($item['match']) ? 'active' : '' }}">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  stroke-width="1.8" d="{{ $item['icon'] }}"/>
                        </svg>
                        {{ $item['label'] }}
                    </a>
                @endforeach

            @else
                <p class="px-2.5 mb-1.5 mt-0.5"
                   style="font-size:.6rem;font-weight:600;text-transform:uppercase;
                          letter-spacing:.06em;color:#94a3b8">Siswa Panel</p>

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
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  stroke-width="1.8" d="{{ $item['icon'] }}"/>
                        </svg>
                        {{ $item['label'] }}
                    </a>
                @endforeach
            @endif

        </nav>

        {{-- Footer sidebar --}}
        <div class="px-3 py-2.5 border-t border-slate-200 dark:border-slate-700 shrink-0">
            <p class="text-slate-400 dark:text-slate-600 text-center"
               style="font-size:.55rem">
                © {{ date('Y') }} {{ $singkatan }} • Semua hak dilindungi
            </p>
        </div>

    </aside>

    {{-- Overlay mobile --}}
    <div x-show="sidebarOpen && window.innerWidth < 1024"
         @click="sidebarOpen = false"
         x-cloak
         class="fixed inset-0 bg-black/50 z-40 lg:hidden"></div>

    {{-- ════════════════════════════════════════
         MAIN AREA
    ════════════════════════════════════════ --}}
    <div class="flex-1 flex flex-col min-w-0 overflow-hidden">

        {{-- TOPBAR --}}
        <header class="topbar bg-white dark:bg-slate-900 border-b border-slate-200
                       dark:border-slate-700 shadow-sm flex items-center px-3 gap-2.5 shrink-0">

            {{-- Hamburger (mobile) --}}
            <button @click="sidebarOpen = !sidebarOpen"
                    class="lg:hidden p-1.5 rounded-lg text-slate-500 hover:bg-slate-100
                           dark:hover:bg-slate-800 transition">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
            </button>

            {{-- Page title --}}
            <h1 class="flex-1 font-semibold text-slate-800 dark:text-slate-100 truncate"
                style="font-size:.846rem">
                @yield('title', 'Dashboard')
            </h1>

            {{-- USER DROPDOWN --}}
            <div class="relative shrink-0" x-data="{ open: false }">

                <button @click="open = !open"
                        class="flex items-center gap-1.5 px-2 py-1 rounded-xl
                               hover:bg-slate-100 dark:hover:bg-slate-800
                               transition focus:outline-none">

                    {{-- Avatar --}}
                    <div class="w-6 h-6 rounded-lg overflow-hidden ring-2 ring-indigo-200
                                dark:ring-indigo-700 flex items-center justify-center
                                bg-slate-100 dark:bg-slate-700 shrink-0">
                        @if(!empty(auth()->user()->photo))
                            <img src="{{ asset('storage/' . auth()->user()->photo) }}"
                                 alt="" class="w-full h-full object-cover">
                        @else
                            <span style="font-size:.615rem;font-weight:700;color:#4f46e5">
                                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                            </span>
                        @endif
                    </div>

                    {{-- Nama & Role --}}
                    <div class="hidden sm:block text-left">
                        <p class="font-semibold text-slate-700 dark:text-slate-200
                                  leading-tight truncate max-w-[110px]"
                           style="font-size:.692rem">
                            {{ auth()->user()->name }}
                        </p>
                        <p class="leading-tight text-slate-400 dark:text-slate-500"
                           style="font-size:.577rem">
                            @if(auth()->user()->isAdmin()) Admin
                            @elseif(auth()->user()->isGuru()) Guru
                            @else Siswa
                            @endif
                        </p>
                    </div>

                    {{-- Chevron --}}
                    <svg class="w-3 h-3 text-slate-400 transition-transform duration-200 shrink-0"
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
                     class="absolute right-0 mt-1.5 w-48 bg-white dark:bg-slate-800
                            rounded-2xl shadow-xl border border-slate-200 dark:border-slate-700
                            py-1 z-50 user-dropdown">

                    {{-- Info akun --}}
                    <div class="px-3.5 py-2 border-b border-slate-100 dark:border-slate-700 mb-0.5">
                        <p class="font-semibold text-slate-700 dark:text-slate-200 truncate"
                           style="font-size:.692rem">
                            {{ auth()->user()->name }}
                        </p>
                        <p class="text-slate-400 dark:text-slate-500 truncate mt-0.5"
                           style="font-size:.577rem">
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
                       class="flex items-center gap-2 px-3.5 py-1.5
                              text-slate-700 dark:text-slate-300
                              hover:bg-slate-50 dark:hover:bg-slate-700/60 transition"
                       style="font-size:.692rem;font-weight:500">
                        <span class="w-5 h-5 rounded-md bg-indigo-50 dark:bg-indigo-900/30
                                     flex items-center justify-center shrink-0">
                            <svg class="w-3 h-3 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                        </span>
                        Profil Saya
                    </a>

                    <div class="border-t border-slate-100 dark:border-slate-700 my-0.5"></div>

                    {{-- Keluar --}}
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit"
                                class="w-full flex items-center gap-2 px-3.5 py-1.5
                                       text-red-600 dark:text-red-400
                                       hover:bg-red-50 dark:hover:bg-red-900/20 transition"
                                style="font-size:.692rem;font-weight:500">
                            <span class="w-5 h-5 rounded-md bg-red-50 dark:bg-red-900/20
                                         flex items-center justify-center shrink-0">
                                <svg class="w-3 h-3 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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

        {{-- KONTEN UTAMA --}}
        <main id="mainContent"
              class="flex-1 overflow-y-auto bg-slate-50 dark:bg-slate-950">

            {{-- Flash success --}}
            @if(session('success'))
                <div class="alert alert-success">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    {{ session('success') }}
                </div>
            @endif

            {{-- Flash error --}}
            @if(session('error'))
                <div class="alert alert-error">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
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