{{-- resources/views/admin/dashboard.blade.php --}}
@extends('layouts.app')
@section('title', 'Dashboard Admin')

@section('content')

{{-- ── Fallback: pastikan semua variabel tersedia ──────────── --}}
@php
    $widgetPengumuman ??= collect();
    $stats            ??= ['total_guru'=>0,'total_siswa'=>0,'total_kelas'=>0,'guru_hadir'=>0];
    $jadwalHariIni    ??= collect();
    $activityLogs     ??= collect();
    $absensiMinggu    ??= ['hadir'=>0,'sakit'=>0,'izin'=>0,'alpha'=>0,'telat'=>0];
    $guruUltah        ??= collect();
    $kelasTanpaWali   ??= 0;
@endphp

<div class="space-y-4">

    {{-- ── Greeting & tanggal ────────────────────────────── --}}
    <div class="flex items-center justify-between flex-wrap gap-2">
        <div>
            <h2 class="text-sm font-bold text-slate-800 dark:text-slate-100 leading-tight">
                👋 Selamat datang, {{ auth()->user()->name ?? 'Admin' }}!
            </h2>
            <p class="text-[10px] text-slate-400 dark:text-slate-500 mt-0.5">
                {{ now()->isoFormat('dddd, D MMMM Y · HH:mm') }} WIB
            </p>
        </div>
        {{-- Quick actions --}}
        <div class="flex items-center gap-2 flex-wrap">
            @if(Route::has('admin.users.index'))
            <a href="{{ route('admin.users.index') }}"
               class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl
                      bg-indigo-600 text-white text-[10px] font-bold
                      hover:bg-indigo-700 transition shadow-sm">
                ➕ Tambah User
            </a>
            @endif
            @if(Route::has('admin.pengumuman.create'))
            <a href="{{ route('admin.pengumuman.create') }}"
               class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl
                      bg-white dark:bg-slate-800 text-slate-700 dark:text-slate-300
                      border border-slate-200 dark:border-slate-700
                      text-[10px] font-bold hover:bg-slate-50 transition shadow-sm">
                📢 Buat Pengumuman
            </a>
            @endif
        </div>
    </div>

    {{-- ── Statistik Ringkasan ────────────────────────────── --}}
    @include('admin.dashboard.stats', [
        'stats'          => $stats,
        'kelasTanpaWali' => $kelasTanpaWali,
    ])

    {{-- ── Absensi Minggu Ini ─────────────────────────────── --}}
    @include('admin.dashboard.absensi_minggu', [
        'absensiMinggu' => $absensiMinggu,
    ])

    {{-- ── Grid utama: Jadwal | Pengumuman ───────────────── --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">

        {{-- Jadwal Hari Ini --}}
        @include('admin.dashboard.schedule', [
            'jadwalHariIni' => $jadwalHariIni,
        ])

        {{-- Pengumuman --}}
        @include('admin.dashboard.announcement', [
            'widgetPengumuman' => $widgetPengumuman,
        ])

    </div>

    {{-- ── Grid bawah: Activity Log | Ulang Tahun ────────── --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">

        {{-- Activity Log — span 2 kolom --}}
        <div class="lg:col-span-2">
            @include('admin.dashboard.activity_log', [
                'activityLogs' => $activityLogs,
            ])
        </div>

        {{-- Ulang Tahun Guru --}}
        <div class="lg:col-span-1 space-y-4">
            @include('admin.dashboard.ultah_guru', [
                'guruUltah' => $guruUltah,
            ])

            {{-- Quick Links --}}
            <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200
                        dark:border-slate-700 shadow-sm p-4">
                <p class="text-[10px] font-bold text-slate-500 dark:text-slate-400
                           uppercase tracking-wider mb-3">
                    ⚡ Akses Cepat
                </p>
                <div class="grid grid-cols-2 gap-2">
                    @php
                        $quickLinks = [
                            ['icon'=>'📋','label'=>'Absensi Guru', 'route'=>'admin.absensi-guru.index',  'color'=>'bg-indigo-50 dark:bg-indigo-900/20 text-indigo-700 dark:text-indigo-300'],
                            ['icon'=>'📊','label'=>'Rekap Absensi','route'=>'admin.absensi-guru.rekap',  'color'=>'bg-emerald-50 dark:bg-emerald-900/20 text-emerald-700 dark:text-emerald-300'],
                            ['icon'=>'👥','label'=>'Data Guru',    'route'=>'admin.users.index',          'color'=>'bg-violet-50 dark:bg-violet-900/20 text-violet-700 dark:text-violet-300'],
                            ['icon'=>'🏫','label'=>'Kelola Kelas', 'route'=>'admin.kelas.index',          'color'=>'bg-sky-50 dark:bg-sky-900/20 text-sky-700 dark:text-sky-300'],
                            ['icon'=>'📢','label'=>'Pengumuman',   'route'=>'admin.pengumuman',           'color'=>'bg-amber-50 dark:bg-amber-900/20 text-amber-700 dark:text-amber-300'],
                            ['icon'=>'🎓','label'=>'Data Siswa',   'route'=>'admin.users.index',          'color'=>'bg-pink-50 dark:bg-pink-900/20 text-pink-700 dark:text-pink-300'],
                        ];
                    @endphp

                    @foreach($quickLinks as $ql)
                    @if(Route::has($ql['route']))
                    <a href="{{ route($ql['route']) }}"
                       class="flex items-center gap-2 px-3 py-2.5 rounded-xl
                              {{ $ql['color'] }} hover:opacity-80
                              transition-opacity text-xs font-semibold">
                        <span>{{ $ql['icon'] }}</span>
                        <span class="leading-tight">{{ $ql['label'] }}</span>
                    </a>
                    @endif
                    @endforeach
                </div>
            </div>
        </div>

    </div>

</div>

@endsection