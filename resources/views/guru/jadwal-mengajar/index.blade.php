{{-- resources/views/guru/jadwal-mengajar/index.blade.php --}}
@extends('layouts.app')
@section('title', 'Jadwal Mengajar')

@section('content')
<div class="space-y-4">

    {{-- ── Header ─────────────────────────────────────────────── --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
        <div>
            <h2 class="text-sm font-bold text-slate-800 dark:text-slate-100">Jadwal Mengajar</h2>
            <p class="text-xs text-slate-400 dark:text-slate-500 mt-0.5">
                Kelola jadwal kelas yang Anda ampu.
            </p>
        </div>
        <div class="flex items-center gap-2">
            {{-- Tombol Kelola Mata Pelajaran --}}
            <button onclick="openModal('modalKelolaMapel')"
                class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl bg-white dark:bg-slate-800
                           border border-slate-200 dark:border-slate-700
                           text-slate-700 dark:text-slate-200 text-xs font-semibold
                           hover:bg-slate-50 dark:hover:bg-slate-700
                           active:scale-95 transition shadow-sm w-fit">
                <svg class="w-3.5 h-3.5 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168
                             18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5
                             16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5
                             18c-1.746 0-3.332.477-4.5 1.253" />
                </svg>
                Mata Pelajaran
            </button>
            <button onclick="openModal('modalTambahJadwal')"
                class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl bg-indigo-600
                           text-white text-xs font-semibold hover:bg-indigo-700
                           active:scale-95 transition shadow-sm w-fit">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 4v16m8-8H4" />
                </svg>
                Tambah Jadwal
            </button>
        </div>
    </div>

    {{-- ── Alert ───────────────────────────────────────────────── --}}
    @if(session('success'))
    <div class="flex items-center gap-2 px-3.5 py-2.5 rounded-xl bg-emerald-50
                    dark:bg-emerald-900/20 border border-emerald-100 dark:border-emerald-800">
        <svg class="w-3.5 h-3.5 text-emerald-500 shrink-0" fill="none" stroke="currentColor"
            viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>
        <p class="text-[11px] text-emerald-700 dark:text-emerald-300 font-medium">
            {{ session('success') }}
        </p>
    </div>
    @endif
    @if(session('error'))
    <div class="flex items-center gap-2 px-3.5 py-2.5 rounded-xl bg-red-50
                    dark:bg-red-900/20 border border-red-100 dark:border-red-800">
        <svg class="w-3.5 h-3.5 text-red-500 shrink-0" fill="none" stroke="currentColor"
            viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71
                         3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z" />
        </svg>
        <p class="text-[11px] text-red-700 dark:text-red-300 font-medium">
            {{ session('error') }}
        </p>
    </div>
    @endif

    {{-- ── KPI Row ─────────────────────────────────────────────── --}}
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
        <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200
                    dark:border-slate-700 shadow-sm px-4 py-3">
            <p class="text-lg font-black text-indigo-600 dark:text-indigo-400 leading-none">
                {{ $totalJadwal }}
            </p>
            <p class="text-[10px] text-slate-400 mt-0.5">Total Jadwal</p>
        </div>
        <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200
                    dark:border-slate-700 shadow-sm px-4 py-3">
            <p class="text-lg font-black text-emerald-600 dark:text-emerald-400 leading-none">
                {{ $totalKelas }}
            </p>
            <p class="text-[10px] text-slate-400 mt-0.5">Kelas Diampu</p>
        </div>
        <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200
                    dark:border-slate-700 shadow-sm px-4 py-3">
            <p class="text-lg font-black text-amber-600 dark:text-amber-400 leading-none">
                {{ $totalMapel }}
            </p>
            <p class="text-[10px] text-slate-400 mt-0.5">Mata Pelajaran</p>
        </div>
        <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200
                    dark:border-slate-700 shadow-sm px-4 py-3">
            <p class="text-lg font-black text-sky-600 dark:text-sky-400 leading-none">
                {{ $totalJamPerMinggu }}
            </p>
            <p class="text-[10px] text-slate-400 mt-0.5">Jam / Minggu</p>
        </div>
    </div>

    {{-- ── Jadwal per Hari ─────────────────────────────────────── --}}
    @php
    $hariList = ['Senin','Selasa','Rabu','Kamis','Jumat','Sabtu'];
    @endphp

    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-4">
        @foreach($hariList as $hari)
        @php $jadwalHari = $jadwalByDay[$hari] ?? collect(); @endphp
        <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200
                        dark:border-slate-700 shadow-sm flex flex-col overflow-hidden">

            {{-- Card header --}}
            <div class="flex items-center justify-between px-3.5 py-3 border-b
                            border-slate-100 dark:border-slate-700 bg-slate-50/70
                            dark:bg-slate-900/30">
                <div class="flex items-center gap-2">
                    <div class="w-1.5 h-5 rounded-full
                                    {{ $jadwalHari->count() > 0
                                       ? 'bg-indigo-500'
                                       : 'bg-slate-200 dark:bg-slate-700' }}">
                    </div>
                    <span class="text-xs font-bold text-slate-700 dark:text-slate-200">
                        {{ $hari }}
                    </span>
                </div>
                <span class="text-[10px] font-semibold px-2 py-0.5 rounded-full
                                 {{ $jadwalHari->count() > 0
                                    ? 'bg-indigo-100 text-indigo-700 dark:bg-indigo-900/40 dark:text-indigo-300'
                                    : 'bg-slate-100 text-slate-400 dark:bg-slate-700 dark:text-slate-500' }}">
                    {{ $jadwalHari->count() }} sesi
                </span>
            </div>

            {{-- Jadwal items --}}
            <div class="flex-1 divide-y divide-slate-50 dark:divide-slate-700/30">
                @forelse($jadwalHari->sortBy('start_time') as $tt)
                <div class="flex items-start gap-2.5 px-3.5 py-3
                                    hover:bg-slate-50 dark:hover:bg-slate-700/20 transition group">

                    {{-- Color strip --}}
                    <div class="flex flex-col items-center gap-1 shrink-0 pt-0.5">
                        <div class="w-1 h-12 rounded-full"
                            style="background: {{ $tt->studySubject->color ?? '#6366f1' }}"></div>
                    </div>

                    {{-- Info --}}
                    <div class="flex-1 min-w-0">
                        <div class="flex items-start justify-between gap-1">
                            <p class="text-xs font-semibold text-slate-800 dark:text-slate-100
                                              leading-tight truncate">
                                {{ $tt->studySubject->name }}
                            </p>
                            <span class="text-[9px] font-semibold px-1.5 py-0.5 rounded-md shrink-0
                                                 {{ $tt->session_type === 'praktikum'
                                                    ? 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-400'
                                                    : 'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400' }}">
                                {{ ucfirst($tt->session_type ?? 'teori') }}
                            </span>
                        </div>

                        <div class="flex items-center gap-1.5 mt-1">
                            <svg class="w-3 h-3 text-slate-400 shrink-0" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <span class="text-[10px] text-slate-500 dark:text-slate-400">
                                {{ substr($tt->start_time,0,5) }} – {{ substr($tt->end_time,0,5) }}
                            </span>
                        </div>

                        <div class="flex items-center gap-1.5 mt-0.5 flex-wrap">
                            <span class="inline-flex items-center gap-0.5 text-[10px] font-medium
                                                 text-indigo-700 dark:text-indigo-300">
                                <svg class="w-2.5 h-2.5" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2
                                                     0h-5m-9 0H3m2 0h5" />
                                </svg>
                                {{ $tt->studyGroup->name }}
                            </span>
                            @if($tt->room)
                            <span class="text-[10px] text-slate-400">
                                · {{ $tt->room }}
                            </span>
                            @endif
                        </div>
                    </div>

                    {{-- Aksi --}}
                    <div class="flex items-center gap-0.5 shrink-0 opacity-0
                                        group-hover:opacity-100 transition-opacity">
                        <button onclick="openEditJadwal({{ $tt->id }})"
                            title="Edit"
                            class="p-1 rounded-lg text-slate-400 hover:text-amber-600
                                               hover:bg-amber-50 dark:hover:bg-amber-900/30 transition">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0
                                                 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828
                                                 15H9v-2.828l8.586-8.586z" />
                            </svg>
                        </button>
                        <button onclick="openDeleteJadwal({{ $tt->id }}, '{{ addslashes($tt->studySubject->name) }}')"
                            title="Hapus"
                            class="p-1 rounded-lg text-slate-400 hover:text-red-600
                                               hover:bg-red-50 dark:hover:bg-red-900/30 transition">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0
                                                 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0
                                                 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                        </button>
                    </div>
                </div>
                @empty
                <div class="flex flex-col items-center justify-center py-6 text-slate-300
                                    dark:text-slate-600">
                    <svg class="w-8 h-8 mb-1 opacity-50" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1"
                            d="M8 7V3m8 4V3M5 11h14M5 19h14M5 5h2m10 0h2" />
                    </svg>
                    <p class="text-[10px]">Tidak ada jadwal</p>
                </div>
                @endforelse
            </div>

            {{-- Footer card --}}
            <div class="px-3.5 py-2 border-t border-slate-100 dark:border-slate-700
                            bg-slate-50/50 dark:bg-slate-900/20">
                <button onclick="openTambahHari('{{ $hari }}')"
                    class="w-full text-[10px] font-medium text-indigo-500 dark:text-indigo-400
                                   hover:text-indigo-700 flex items-center justify-center gap-1 py-0.5
                                   hover:underline transition">
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 4v16m8-8H4" />
                    </svg>
                    Tambah Jadwal {{ $hari }}
                </button>
            </div>
        </div>
        @endforeach
    </div>

</div>

{{-- Data JSON untuk edit --}}
<script id="jadwalData" type="application/json">
    {!! json_encode($allTimetables->map(fn($t) => [
        'id'               => $t->id,
        'study_subject_id' => $t->study_subject_id,
        'study_group_id'   => $t->study_group_id,
        'day_of_week'      => $t->day_of_week,
        'start_time'       => substr($t->start_time, 0, 5),
        'end_time'         => substr($t->end_time, 0, 5),
        'room'             => $t->room,
        'session_type'     => $t->session_type,
        'academic_year'    => $t->academic_year,
        'semester'         => $t->semester,
        'notes'            => $t->notes,
    ])->keyBy('id')) !!}
</script>

{{-- Data JSON Mata Pelajaran untuk JS --}}
<script id="mapelData" type="application/json">
    {!! json_encode($studySubjects->map(fn($s) => [
        'id'          => $s->id,
        'name'        => $s->name,
        'code'        => $s->code,
        'color'       => $s->color ?? '#6366f1',
        'description' => $s->description ?? '',
    ])->keyBy('id')) !!}
</script>


{{-- ══════════════════════════════════════════════════════════
     MODAL KELOLA MATA PELAJARAN (list + tambah inline)
     ══════════════════════════════════════════════════════════ --}}
<div id="modalKelolaMapel"
    class="fixed inset-0 z-50 hidden items-center justify-center p-4"
    role="dialog" aria-modal="true">
    <div class="absolute inset-0 bg-slate-900/50 backdrop-blur-sm"
        onclick="closeModal('modalKelolaMapel')"></div>

    <div class="relative bg-white dark:bg-slate-800 rounded-2xl shadow-2xl w-full max-w-lg
                max-h-[92vh] flex flex-col animate-modal">

        {{-- Header --}}
        <div class="flex items-center justify-between px-5 py-4 border-b border-slate-100
                    dark:border-slate-700 shrink-0">
            <div class="flex items-center gap-2.5">
                <div class="w-7 h-7 rounded-lg bg-amber-100 dark:bg-amber-900/30
                            flex items-center justify-center shrink-0">
                    <svg class="w-4 h-4 text-amber-600 dark:text-amber-400" fill="none"
                        stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477
                                 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5
                                 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477
                                 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746
                                 0-3.332.477-4.5 1.253" />
                    </svg>
                </div>
                <div>
                    <h2 class="text-sm font-bold text-slate-800 dark:text-slate-100">
                        Kelola Mata Pelajaran
                    </h2>
                    <p class="text-[10px] text-slate-400 mt-0.5">
                        Tambah, edit, atau hapus mata pelajaran Anda
                    </p>
                </div>
            </div>
            <button onclick="closeModal('modalKelolaMapel')"
                class="p-1.5 rounded-lg text-slate-400 hover:text-slate-600
                           hover:bg-slate-100 dark:hover:bg-slate-700 transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        {{-- Body --}}
        <div class="overflow-y-auto flex-1 px-5 py-4 space-y-3">

            {{-- Daftar Mata Pelajaran --}}
            <div id="daftarMapelList" class="space-y-2">
                @forelse($studySubjects as $subj)
                <div id="mapelRow_{{ $subj->id }}"
                    class="flex items-center gap-3 p-3 rounded-xl border border-slate-100
                               dark:border-slate-700 bg-slate-50/50 dark:bg-slate-900/20
                               hover:bg-white dark:hover:bg-slate-700/30 transition group">
                    {{-- Warna dot --}}
                    <div class="w-3 h-3 rounded-full shrink-0 ring-2 ring-white dark:ring-slate-800"
                        style="background:{{ $subj->color ?? '#6366f1' }}"></div>
                    {{-- Info --}}
                    <div class="flex-1 min-w-0">
                        <p class="text-xs font-semibold text-slate-800 dark:text-slate-100 truncate">
                            {{ $subj->name }}
                        </p>
                        <p class="text-[10px] text-slate-400">{{ $subj->code }}</p>
                    </div>
                    {{-- Aksi --}}
                    <div class="flex items-center gap-0.5 shrink-0">
                        <button onclick="openEditMapel({{ $subj->id }})"
                            title="Edit"
                            class="p-1.5 rounded-lg text-slate-400 hover:text-amber-600
                                           hover:bg-amber-50 dark:hover:bg-amber-900/30 transition">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0
                                             002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828
                                             15H9v-2.828l8.586-8.586z" />
                            </svg>
                        </button>
                        <button onclick="openDeleteMapel({{ $subj->id }}, '{{ addslashes($subj->name) }}')"
                            title="Hapus"
                            class="p-1.5 rounded-lg text-slate-400 hover:text-red-600
                                           hover:bg-red-50 dark:hover:bg-red-900/30 transition">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0
                                             01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0
                                             00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                        </button>
                    </div>
                </div>
                @empty
                <div id="emptyMapelMsg"
                    class="flex flex-col items-center justify-center py-8 text-slate-300 dark:text-slate-600">
                    <svg class="w-10 h-10 mb-2 opacity-50" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1"
                            d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477
                                 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5
                                 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477
                                 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746
                                 0-3.332.477-4.5 1.253" />
                    </svg>
                    <p class="text-[10px]">Belum ada mata pelajaran</p>
                </div>
                @endforelse
            </div>

            {{-- Divider --}}
            <div class="flex items-center gap-2 pt-1">
                <div class="flex-1 h-px bg-slate-100 dark:bg-slate-700"></div>
                <span class="text-[10px] text-slate-400 font-medium">Tambah Baru</span>
                <div class="flex-1 h-px bg-slate-100 dark:bg-slate-700"></div>
            </div>

            {{-- Form Tambah Mata Pelajaran --}}
            <form id="formTambahMapel"
                action="{{ route('guru.study-subject.store') }}"
                method="POST"
                class="space-y-3 bg-indigo-50/40 dark:bg-indigo-900/10 rounded-xl
                           border border-indigo-100 dark:border-indigo-800/40 p-4">
                @csrf

                <div class="grid grid-cols-2 gap-3">
                    <div class="col-span-2 sm:col-span-1">
                        <label class="block text-[10px] font-semibold text-slate-500 dark:text-slate-400
                                      uppercase tracking-wide mb-1">
                            Nama Mata Pelajaran <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="name" required maxlength="100"
                            placeholder="cth: Matematika"
                            class="w-full rounded-xl border border-slate-200 dark:border-slate-600
                                       px-3 py-2 text-xs focus:outline-none focus:ring-2
                                       focus:ring-indigo-300 bg-white dark:bg-slate-700
                                       dark:text-slate-200 transition">
                    </div>
                    <div class="col-span-2 sm:col-span-1">
                        <label class="block text-[10px] font-semibold text-slate-500 dark:text-slate-400
                                      uppercase tracking-wide mb-1">
                            Kode <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="code" required maxlength="20"
                            placeholder="cth: MTK"
                            class="w-full rounded-xl border border-slate-200 dark:border-slate-600
                                       px-3 py-2 text-xs focus:outline-none focus:ring-2
                                       focus:ring-indigo-300 bg-white dark:bg-slate-700
                                       dark:text-slate-200 transition">
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-[10px] font-semibold text-slate-500 dark:text-slate-400
                                      uppercase tracking-wide mb-1">
                            Warna Label
                        </label>
                        <div class="flex items-center gap-2">
                            <input type="color" name="color" value="#6366f1"
                                class="w-8 h-8 rounded-lg border border-slate-200 dark:border-slate-600
                                           cursor-pointer p-0.5 bg-white dark:bg-slate-700">
                            <span class="text-[10px] text-slate-400">Pilih warna penanda</span>
                        </div>
                    </div>
                    <div>
                        <label class="block text-[10px] font-semibold text-slate-500 dark:text-slate-400
                                      uppercase tracking-wide mb-1">
                            Deskripsi
                        </label>
                        <input type="text" name="description" maxlength="200"
                            placeholder="Opsional"
                            class="w-full rounded-xl border border-slate-200 dark:border-slate-600
                                       px-3 py-2 text-xs focus:outline-none focus:ring-2
                                       focus:ring-indigo-300 bg-white dark:bg-slate-700
                                       dark:text-slate-200 transition">
                    </div>
                </div>

                <button type="submit"
                    class="w-full px-4 py-2 rounded-xl bg-indigo-600 text-white text-xs font-semibold
                               hover:bg-indigo-700 active:scale-95 transition flex items-center
                               justify-center gap-1.5">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 4v16m8-8H4" />
                    </svg>
                    Simpan Mata Pelajaran
                </button>
            </form>
        </div>

        {{-- Footer --}}
        <div class="px-5 py-3 border-t border-slate-100 dark:border-slate-700
                    bg-slate-50/50 dark:bg-slate-900/20 rounded-b-2xl shrink-0">
            <button type="button" onclick="closeModal('modalKelolaMapel')"
                class="w-full px-4 py-2 rounded-xl border border-slate-200 dark:border-slate-600
                           text-slate-600 dark:text-slate-400 text-xs font-medium
                           hover:bg-white dark:hover:bg-slate-700 transition">
                Tutup
            </button>
        </div>
    </div>
</div>


{{-- ══════════════════════════════════════════════════════════
     MODAL EDIT MATA PELAJARAN
     ══════════════════════════════════════════════════════════ --}}
<div id="modalEditMapel"
    class="fixed inset-0 z-[60] hidden items-center justify-center p-4"
    role="dialog" aria-modal="true">
    <div class="absolute inset-0 bg-slate-900/50 backdrop-blur-sm"
        onclick="closeModal('modalEditMapel')"></div>

    <div class="relative bg-white dark:bg-slate-800 rounded-2xl shadow-2xl w-full max-w-sm
                animate-modal">

        <div class="flex items-center justify-between px-5 py-4 border-b border-slate-100
                    dark:border-slate-700">
            <div class="flex items-center gap-2.5">
                <div class="w-7 h-7 rounded-lg bg-amber-100 dark:bg-amber-900/30
                            flex items-center justify-center shrink-0">
                    <svg class="w-4 h-4 text-amber-600 dark:text-amber-400" fill="none"
                        stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0
                                 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828
                                 15H9v-2.828l8.586-8.586z" />
                    </svg>
                </div>
                <h2 class="text-sm font-bold text-slate-800 dark:text-slate-100">
                    Edit Mata Pelajaran
                </h2>
            </div>
            <button onclick="closeModal('modalEditMapel')"
                class="p-1.5 rounded-lg text-slate-400 hover:text-slate-600
                           hover:bg-slate-100 dark:hover:bg-slate-700 transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <div class="px-5 py-4">
            <form id="formEditMapel" method="POST" class="space-y-3">
                @csrf @method('PUT')

                <div>
                    <label class="block text-[10px] font-semibold text-slate-500 dark:text-slate-400
                                  uppercase tracking-wide mb-1">
                        Nama Mata Pelajaran <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="name" id="editMapelName" required maxlength="100"
                        class="w-full rounded-xl border border-slate-200 dark:border-slate-600
                                   px-3 py-2 text-xs focus:outline-none focus:ring-2
                                   focus:ring-indigo-300 bg-white dark:bg-slate-700
                                   dark:text-slate-200 transition">
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-[10px] font-semibold text-slate-500 dark:text-slate-400
                                      uppercase tracking-wide mb-1">
                            Kode <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="code" id="editMapelCode" required maxlength="20"
                            class="w-full rounded-xl border border-slate-200 dark:border-slate-600
                                       px-3 py-2 text-xs focus:outline-none focus:ring-2
                                       focus:ring-indigo-300 bg-white dark:bg-slate-700
                                       dark:text-slate-200 transition">
                    </div>
                    <div>
                        <label class="block text-[10px] font-semibold text-slate-500 dark:text-slate-400
                                      uppercase tracking-wide mb-1">
                            Warna Label
                        </label>
                        <input type="color" name="color" id="editMapelColor"
                            class="w-full h-[34px] rounded-xl border border-slate-200
                                       dark:border-slate-600 cursor-pointer p-0.5
                                       bg-white dark:bg-slate-700">
                    </div>
                </div>

                <div>
                    <label class="block text-[10px] font-semibold text-slate-500 dark:text-slate-400
                                  uppercase tracking-wide mb-1">
                        Deskripsi
                    </label>
                    <input type="text" name="description" id="editMapelDesc" maxlength="200"
                        class="w-full rounded-xl border border-slate-200 dark:border-slate-600
                                   px-3 py-2 text-xs focus:outline-none focus:ring-2
                                   focus:ring-indigo-300 bg-white dark:bg-slate-700
                                   dark:text-slate-200 transition">
                </div>

            </form>
        </div>

        <div class="flex gap-2 px-5 py-3.5 border-t border-slate-100 dark:border-slate-700
                    bg-slate-50/50 dark:bg-slate-900/20 rounded-b-2xl">
            <button type="button" onclick="closeModal('modalEditMapel')"
                class="flex-1 px-4 py-2 rounded-xl border border-slate-200 dark:border-slate-600
                           text-slate-600 dark:text-slate-400 text-xs font-medium
                           hover:bg-white dark:hover:bg-slate-700 transition">
                Batal
            </button>
            <button type="submit" form="formEditMapel"
                class="flex-1 px-4 py-2 rounded-xl bg-amber-500 text-white text-xs font-semibold
                           hover:bg-amber-600 active:scale-95 transition">
                Simpan Perubahan
            </button>
        </div>
    </div>
</div>


{{-- ══════════════════════════════════════════════════════════
     MODAL HAPUS MATA PELAJARAN
     ══════════════════════════════════════════════════════════ --}}
<div id="modalHapusMapel"
    class="fixed inset-0 z-[60] hidden items-center justify-center p-4"
    role="dialog" aria-modal="true">
    <div class="absolute inset-0 bg-slate-900/50 backdrop-blur-sm"
        onclick="closeModal('modalHapusMapel')"></div>
    <div class="relative bg-white dark:bg-slate-800 rounded-2xl shadow-2xl
                w-full max-w-xs p-5 animate-modal text-center">
        <div class="w-12 h-12 rounded-2xl bg-red-100 dark:bg-red-900/30
                    flex items-center justify-center mx-auto mb-3">
            <svg class="w-6 h-6 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732
                         4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
            </svg>
        </div>
        <h3 class="text-sm font-bold text-slate-800 dark:text-slate-100">Hapus Mata Pelajaran?</h3>
        <p class="text-xs text-slate-500 dark:text-slate-400 mt-1.5 mb-1 leading-relaxed">
            <strong id="hapusMapelName" class="text-slate-700 dark:text-slate-300"></strong>
            akan dihapus permanen.
        </p>
        <p class="text-[10px] text-red-500 mb-4">
            Semua jadwal yang menggunakan mata pelajaran ini juga akan terpengaruh.
        </p>
        <form id="formHapusMapel" method="POST">
            @csrf @method('DELETE')
            <div class="flex gap-2">
                <button type="button" onclick="closeModal('modalHapusMapel')"
                    class="flex-1 px-4 py-2 rounded-xl border border-slate-200 dark:border-slate-600
                               text-slate-600 dark:text-slate-400 text-xs font-medium
                               hover:bg-slate-50 dark:hover:bg-slate-700 transition">
                    Batal
                </button>
                <button type="submit"
                    class="flex-1 px-4 py-2 rounded-xl bg-red-600 text-white text-xs font-semibold
                               hover:bg-red-700 active:scale-95 transition">
                    Hapus
                </button>
            </div>
        </form>
    </div>
</div>


{{-- ══════════════════════════════════════════════════════════
     MODAL TAMBAH JADWAL
     ══════════════════════════════════════════════════════════ --}}
<div id="modalTambahJadwal"
    class="fixed inset-0 z-50 hidden items-center justify-center p-4"
    role="dialog" aria-modal="true">
    <div class="absolute inset-0 bg-slate-900/50 backdrop-blur-sm"
        onclick="closeModal('modalTambahJadwal')"></div>

    <div class="relative bg-white dark:bg-slate-800 rounded-2xl shadow-2xl w-full max-w-md
                max-h-[92vh] flex flex-col animate-modal">

        <div class="flex items-center justify-between px-5 py-4 border-b border-slate-100
                    dark:border-slate-700 shrink-0">
            <div class="flex items-center gap-2.5">
                <div class="w-7 h-7 rounded-lg bg-indigo-100 dark:bg-indigo-900/40
                            flex items-center justify-center shrink-0">
                    <svg class="w-4 h-4 text-indigo-600 dark:text-indigo-400" fill="none"
                        stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 4v16m8-8H4" />
                    </svg>
                </div>
                <div>
                    <h2 class="text-sm font-bold text-slate-800 dark:text-slate-100">
                        Tambah Jadwal Mengajar
                    </h2>
                    <p class="text-[10px] text-slate-400 mt-0.5">Pilih kelas dan mata pelajaran</p>
                </div>
            </div>
            <button onclick="closeModal('modalTambahJadwal')"
                class="p-1.5 rounded-lg text-slate-400 hover:text-slate-600
                           hover:bg-slate-100 dark:hover:bg-slate-700 transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <div class="overflow-y-auto px-5 py-4 flex-1">
            <form id="formTambahJadwal"
                action="{{ route('guru.jadwal-mengajar.store') }}"
                method="POST"
                class="space-y-3">
                @csrf

                {{-- Mata Pelajaran dengan shortcut ke kelola --}}
                <div>
                    <div class="flex items-center justify-between mb-1">
                        <label class="block text-[10px] font-semibold text-slate-500 dark:text-slate-400
                                      uppercase tracking-wide">
                            Mata Pelajaran <span class="text-red-500">*</span>
                        </label>
                        <button type="button"
                            onclick="closeModal('modalTambahJadwal'); openModal('modalKelolaMapel')"
                            class="text-[10px] text-indigo-500 hover:text-indigo-700 hover:underline
                                       flex items-center gap-0.5 transition">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 4v16m8-8H4" />
                            </svg>
                            Kelola
                        </button>
                    </div>
                    <select name="study_subject_id" id="tambahJadwalSubject" required
                        class="w-full rounded-xl border border-slate-200 dark:border-slate-600
                                   px-3 py-2 text-xs focus:outline-none focus:ring-2
                                   focus:ring-indigo-300 bg-white dark:bg-slate-700
                                   dark:text-slate-200 transition">
                        <option value="">— Pilih Mata Pelajaran —</option>
                        @foreach($studySubjects as $subj)
                        <option value="{{ $subj->id }}">
                            {{ $subj->name }} ({{ $subj->code }})
                        </option>
                        @endforeach
                    </select>
                    @if($studySubjects->isEmpty())
                    <p class="text-[10px] text-amber-600 mt-1">
                        Belum ada mata pelajaran.
                        <button type="button"
                            onclick="closeModal('modalTambahJadwal'); openModal('modalKelolaMapel')"
                            class="underline font-semibold">
                            Tambah sekarang
                        </button>
                    </p>
                    @endif
                </div>

                {{-- Kelas --}}
                <div>
                    <label class="block text-[10px] font-semibold text-slate-500 dark:text-slate-400
                                  uppercase tracking-wide mb-1">
                        Kelas <span class="text-red-500">*</span>
                    </label>
                    <select name="study_group_id" required
                        class="w-full rounded-xl border border-slate-200 dark:border-slate-600
                                   px-3 py-2 text-xs focus:outline-none focus:ring-2
                                   focus:ring-indigo-300 bg-white dark:bg-slate-700
                                   dark:text-slate-200 transition">
                        <option value="">— Pilih Kelas —</option>
                        @foreach($studyGroups as $group)
                        <option value="{{ $group->id }}">
                            {{ $group->name }}
                            @if($group->academic_year) ({{ $group->academic_year }}) @endif
                        </option>
                        @endforeach
                    </select>
                </div>

                {{-- Hari --}}
                <div>
                    <label class="block text-[10px] font-semibold text-slate-500 dark:text-slate-400
                                  uppercase tracking-wide mb-1">
                        Hari <span class="text-red-500">*</span>
                    </label>
                    <select name="day_of_week" id="tambahJadwalHari" required
                        class="w-full rounded-xl border border-slate-200 dark:border-slate-600
                                   px-3 py-2 text-xs focus:outline-none focus:ring-2
                                   focus:ring-indigo-300 bg-white dark:bg-slate-700
                                   dark:text-slate-200 transition">
                        <option value="">— Pilih Hari —</option>
                        @foreach(['Senin','Selasa','Rabu','Kamis','Jumat','Sabtu'] as $h)
                        <option value="{{ $h }}">{{ $h }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- Jam Mulai & Selesai --}}
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-[10px] font-semibold text-slate-500 dark:text-slate-400
                                      uppercase tracking-wide mb-1">
                            Jam Mulai <span class="text-red-500">*</span>
                        </label>
                        <input type="time" name="start_time" value="07:00" required
                            class="w-full rounded-xl border border-slate-200 dark:border-slate-600
                                      px-3 py-2 text-xs focus:outline-none focus:ring-2
                                      focus:ring-indigo-300 bg-white dark:bg-slate-700
                                      dark:text-slate-200 transition">
                    </div>
                    <div>
                        <label class="block text-[10px] font-semibold text-slate-500 dark:text-slate-400
                                      uppercase tracking-wide mb-1">
                            Jam Selesai <span class="text-red-500">*</span>
                        </label>
                        <input type="time" name="end_time" value="08:30" required
                            class="w-full rounded-xl border border-slate-200 dark:border-slate-600
                                      px-3 py-2 text-xs focus:outline-none focus:ring-2
                                      focus:ring-indigo-300 bg-white dark:bg-slate-700
                                      dark:text-slate-200 transition">
                    </div>
                </div>

                {{-- Ruang + Sesi --}}
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-[10px] font-semibold text-slate-500 dark:text-slate-400
                                      uppercase tracking-wide mb-1">
                            Ruang
                        </label>
                        <input type="text" name="room" placeholder="Lab / Kelas"
                            maxlength="50"
                            class="w-full rounded-xl border border-slate-200 dark:border-slate-600
                                      px-3 py-2 text-xs focus:outline-none focus:ring-2
                                      focus:ring-indigo-300 bg-white dark:bg-slate-700
                                      dark:text-slate-200 transition">
                    </div>
                    <div>
                        <label class="block text-[10px] font-semibold text-slate-500 dark:text-slate-400
                                      uppercase tracking-wide mb-1">
                            Sesi <span class="text-red-500">*</span>
                        </label>
                        <select name="session_type" required
                            class="w-full rounded-xl border border-slate-200 dark:border-slate-600
                                       px-3 py-2 text-xs focus:outline-none focus:ring-2
                                       focus:ring-indigo-300 bg-white dark:bg-slate-700
                                       dark:text-slate-200 transition">
                            <option value="teori">Teori</option>
                            <option value="praktikum">Praktikum</option>
                        </select>
                    </div>
                </div>

                {{-- Tahun Ajaran + Semester --}}
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-[10px] font-semibold text-slate-500 dark:text-slate-400
                                      uppercase tracking-wide mb-1">
                            Tahun Ajaran <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="academic_year"
                            value="{{ date('Y').'/'.((int)date('Y')+1) }}"
                            maxlength="9" required
                            class="w-full rounded-xl border border-slate-200 dark:border-slate-600
                                      px-3 py-2 text-xs focus:outline-none focus:ring-2
                                      focus:ring-indigo-300 bg-white dark:bg-slate-700
                                      dark:text-slate-200 transition">
                    </div>
                    <div>
                        <label class="block text-[10px] font-semibold text-slate-500 dark:text-slate-400
                                      uppercase tracking-wide mb-1">
                            Semester <span class="text-red-500">*</span>
                        </label>
                        <select name="semester" required
                            class="w-full rounded-xl border border-slate-200 dark:border-slate-600
                                       px-3 py-2 text-xs focus:outline-none focus:ring-2
                                       focus:ring-indigo-300 bg-white dark:bg-slate-700
                                       dark:text-slate-200 transition">
                            <option value="1">Semester 1</option>
                            <option value="2">Semester 2</option>
                        </select>
                    </div>
                </div>

                {{-- Catatan --}}
                <div>
                    <label class="block text-[10px] font-semibold text-slate-500 dark:text-slate-400
                                  uppercase tracking-wide mb-1">Catatan</label>
                    <textarea name="notes" rows="2" maxlength="500"
                        placeholder="Catatan tambahan (opsional)"
                        class="w-full rounded-xl border border-slate-200 dark:border-slate-600
                                     px-3 py-2 text-xs focus:outline-none focus:ring-2
                                     focus:ring-indigo-300 bg-white dark:bg-slate-700
                                     dark:text-slate-200 transition resize-none"></textarea>
                </div>

            </form>
        </div>

        <div class="flex gap-2 px-5 py-3.5 border-t border-slate-100 dark:border-slate-700
                    bg-slate-50/50 dark:bg-slate-900/20 rounded-b-2xl shrink-0">
            <button type="button" onclick="closeModal('modalTambahJadwal')"
                class="flex-1 px-4 py-2 rounded-xl border border-slate-200 dark:border-slate-600
                           text-slate-600 dark:text-slate-400 text-xs font-medium
                           hover:bg-white dark:hover:bg-slate-700 transition">
                Batal
            </button>
            <button type="submit" form="formTambahJadwal"
                class="flex-1 px-4 py-2 rounded-xl bg-indigo-600 text-white text-xs font-semibold
                           hover:bg-indigo-700 active:scale-95 transition">
                Simpan Jadwal
            </button>
        </div>

    </div>
</div>


{{-- ══════════════════════════════════════════════════════════
     MODAL EDIT JADWAL
     ══════════════════════════════════════════════════════════ --}}
<div id="modalEditJadwal"
    class="fixed inset-0 z-50 hidden items-center justify-center p-4"
    role="dialog" aria-modal="true">
    <div class="absolute inset-0 bg-slate-900/50 backdrop-blur-sm"
        onclick="closeModal('modalEditJadwal')"></div>

    <div class="relative bg-white dark:bg-slate-800 rounded-2xl shadow-2xl w-full max-w-md
                max-h-[92vh] flex flex-col animate-modal">

        <div class="flex items-center justify-between px-5 py-4 border-b border-slate-100
                    dark:border-slate-700 shrink-0">
            <div class="flex items-center gap-2.5">
                <div class="w-7 h-7 rounded-lg bg-amber-100 dark:bg-amber-900/30
                            flex items-center justify-center shrink-0">
                    <svg class="w-4 h-4 text-amber-600 dark:text-amber-400" fill="none"
                        stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5
                                 m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                </div>
                <h2 class="text-sm font-bold text-slate-800 dark:text-slate-100">Edit Jadwal</h2>
            </div>
            <button onclick="closeModal('modalEditJadwal')"
                class="p-1.5 rounded-lg text-slate-400 hover:text-slate-600
                           hover:bg-slate-100 dark:hover:bg-slate-700 transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <div class="overflow-y-auto px-5 py-4 flex-1">
            <form id="formEditJadwal" method="POST" class="space-y-3">
                @csrf @method('PUT')

                <div>
                    <div class="flex items-center justify-between mb-1">
                        <label class="block text-[10px] font-semibold text-slate-500 dark:text-slate-400
                                      uppercase tracking-wide">
                            Mata Pelajaran <span class="text-red-500">*</span>
                        </label>
                        <button type="button"
                            onclick="closeModal('modalEditJadwal'); openModal('modalKelolaMapel')"
                            class="text-[10px] text-indigo-500 hover:text-indigo-700 hover:underline
                                       flex items-center gap-0.5 transition">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0
                                             002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828
                                             15H9v-2.828l8.586-8.586z" />
                            </svg>
                            Kelola
                        </button>
                    </div>
                    <select name="study_subject_id" id="editJadwalSubject" required
                        class="w-full rounded-xl border border-slate-200 dark:border-slate-600
                                   px-3 py-2 text-xs focus:outline-none focus:ring-2
                                   focus:ring-indigo-300 bg-white dark:bg-slate-700
                                   dark:text-slate-200 transition">
                        @foreach($studySubjects as $subj)
                        <option value="{{ $subj->id }}">{{ $subj->name }} ({{ $subj->code }})</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-[10px] font-semibold text-slate-500 dark:text-slate-400
                                  uppercase tracking-wide mb-1">
                        Kelas <span class="text-red-500">*</span>
                    </label>
                    <select name="study_group_id" id="editJadwalGroup" required
                        class="w-full rounded-xl border border-slate-200 dark:border-slate-600
                                   px-3 py-2 text-xs focus:outline-none focus:ring-2
                                   focus:ring-indigo-300 bg-white dark:bg-slate-700
                                   dark:text-slate-200 transition">
                        @foreach($studyGroups as $group)
                        <option value="{{ $group->id }}">{{ $group->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-[10px] font-semibold text-slate-500 dark:text-slate-400
                                  uppercase tracking-wide mb-1">
                        Hari <span class="text-red-500">*</span>
                    </label>
                    <select name="day_of_week" id="editJadwalHari" required
                        class="w-full rounded-xl border border-slate-200 dark:border-slate-600
                                   px-3 py-2 text-xs focus:outline-none focus:ring-2
                                   focus:ring-indigo-300 bg-white dark:bg-slate-700
                                   dark:text-slate-200 transition">
                        @foreach(['Senin','Selasa','Rabu','Kamis','Jumat','Sabtu'] as $h)
                        <option value="{{ $h }}">{{ $h }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-[10px] font-semibold text-slate-500 dark:text-slate-400
                                      uppercase tracking-wide mb-1">Jam Mulai</label>
                        <input type="time" name="start_time" id="editJadwalStart" required
                            class="w-full rounded-xl border border-slate-200 dark:border-slate-600
                                      px-3 py-2 text-xs focus:outline-none focus:ring-2
                                      focus:ring-indigo-300 bg-white dark:bg-slate-700
                                      dark:text-slate-200 transition">
                    </div>
                    <div>
                        <label class="block text-[10px] font-semibold text-slate-500 dark:text-slate-400
                                      uppercase tracking-wide mb-1">Jam Selesai</label>
                        <input type="time" name="end_time" id="editJadwalEnd" required
                            class="w-full rounded-xl border border-slate-200 dark:border-slate-600
                                      px-3 py-2 text-xs focus:outline-none focus:ring-2
                                      focus:ring-indigo-300 bg-white dark:bg-slate-700
                                      dark:text-slate-200 transition">
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-[10px] font-semibold text-slate-500 dark:text-slate-400
                                      uppercase tracking-wide mb-1">Ruang</label>
                        <input type="text" name="room" id="editJadwalRoom" maxlength="50"
                            class="w-full rounded-xl border border-slate-200 dark:border-slate-600
                                      px-3 py-2 text-xs focus:outline-none focus:ring-2
                                      focus:ring-indigo-300 bg-white dark:bg-slate-700
                                      dark:text-slate-200 transition">
                    </div>
                    <div>
                        <label class="block text-[10px] font-semibold text-slate-500 dark:text-slate-400
                                      uppercase tracking-wide mb-1">Sesi</label>
                        <select name="session_type" id="editJadwalSession" required
                            class="w-full rounded-xl border border-slate-200 dark:border-slate-600
                                       px-3 py-2 text-xs focus:outline-none focus:ring-2
                                       focus:ring-indigo-300 bg-white dark:bg-slate-700
                                       dark:text-slate-200 transition">
                            <option value="teori">Teori</option>
                            <option value="praktikum">Praktikum</option>
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-[10px] font-semibold text-slate-500 dark:text-slate-400
                                      uppercase tracking-wide mb-1">Tahun Ajaran</label>
                        <input type="text" name="academic_year" id="editJadwalYear"
                            maxlength="9" required
                            class="w-full rounded-xl border border-slate-200 dark:border-slate-600
                                      px-3 py-2 text-xs focus:outline-none focus:ring-2
                                      focus:ring-indigo-300 bg-white dark:bg-slate-700
                                      dark:text-slate-200 transition">
                    </div>
                    <div>
                        <label class="block text-[10px] font-semibold text-slate-500 dark:text-slate-400
                                      uppercase tracking-wide mb-1">Semester</label>
                        <select name="semester" id="editJadwalSemester" required
                            class="w-full rounded-xl border border-slate-200 dark:border-slate-600
                                       px-3 py-2 text-xs focus:outline-none focus:ring-2
                                       focus:ring-indigo-300 bg-white dark:bg-slate-700
                                       dark:text-slate-200 transition">
                            <option value="1">Semester 1</option>
                            <option value="2">Semester 2</option>
                        </select>
                    </div>
                </div>

                <div>
                    <label class="block text-[10px] font-semibold text-slate-500 dark:text-slate-400
                                  uppercase tracking-wide mb-1">Catatan</label>
                    <textarea name="notes" id="editJadwalNotes" rows="2" maxlength="500"
                        class="w-full rounded-xl border border-slate-200 dark:border-slate-600
                                     px-3 py-2 text-xs focus:outline-none focus:ring-2
                                     focus:ring-indigo-300 bg-white dark:bg-slate-700
                                     dark:text-slate-200 transition resize-none"></textarea>
                </div>

            </form>
        </div>

        <div class="flex gap-2 px-5 py-3.5 border-t border-slate-100 dark:border-slate-700
                    bg-slate-50/50 dark:bg-slate-900/20 rounded-b-2xl shrink-0">
            <button type="button" onclick="closeModal('modalEditJadwal')"
                class="flex-1 px-4 py-2 rounded-xl border border-slate-200 dark:border-slate-600
                           text-slate-600 dark:text-slate-400 text-xs font-medium
                           hover:bg-white dark:hover:bg-slate-700 transition">
                Batal
            </button>
            <button type="submit" form="formEditJadwal"
                class="flex-1 px-4 py-2 rounded-xl bg-amber-500 text-white text-xs font-semibold
                           hover:bg-amber-600 active:scale-95 transition">
                Simpan Perubahan
            </button>
        </div>
    </div>
</div>


{{-- ══════════════════════════════════════════════════════════
     MODAL HAPUS JADWAL
     ══════════════════════════════════════════════════════════ --}}
<div id="modalHapusJadwal"
    class="fixed inset-0 z-50 hidden items-center justify-center p-4"
    role="dialog" aria-modal="true">
    <div class="absolute inset-0 bg-slate-900/50 backdrop-blur-sm"
        onclick="closeModal('modalHapusJadwal')"></div>
    <div class="relative bg-white dark:bg-slate-800 rounded-2xl shadow-2xl
                w-full max-w-xs p-5 animate-modal text-center">
        <div class="w-12 h-12 rounded-2xl bg-red-100 dark:bg-red-900/30
                    flex items-center justify-center mx-auto mb-3">
            <svg class="w-6 h-6 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732
                         4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
            </svg>
        </div>
        <h3 class="text-sm font-bold text-slate-800 dark:text-slate-100">Hapus Jadwal?</h3>
        <p class="text-xs text-slate-500 dark:text-slate-400 mt-1.5 mb-4 leading-relaxed">
            Jadwal <strong id="hapusJadwalName" class="text-slate-700 dark:text-slate-300"></strong>
            akan dihapus permanen.
        </p>
        <form id="formHapusJadwal" method="POST">
            @csrf @method('DELETE')
            <div class="flex gap-2">
                <button type="button" onclick="closeModal('modalHapusJadwal')"
                    class="flex-1 px-4 py-2 rounded-xl border border-slate-200 dark:border-slate-600
                               text-slate-600 dark:text-slate-400 text-xs font-medium
                               hover:bg-slate-50 dark:hover:bg-slate-700 transition">
                    Batal
                </button>
                <button type="submit"
                    class="flex-1 px-4 py-2 rounded-xl bg-red-600 text-white text-xs font-semibold
                               hover:bg-red-700 active:scale-95 transition">
                    Hapus
                </button>
            </div>
        </form>
    </div>
</div>


@push('styles')
<style>
    .animate-modal {
        animation: modalPop .22s cubic-bezier(.34, 1.56, .64, 1);
    }

    @keyframes modalPop {
        from {
            opacity: 0;
            transform: scale(.92) translateY(10px);
        }

        to {
            opacity: 1;
            transform: scale(1) translateY(0);
        }
    }
</style>
@endpush

@push('scripts')
<script>
    /* ── Data ─────────────────────────────────────────────────── */
    const JADWAL_DATA = JSON.parse(document.getElementById('jadwalData').textContent);
    let   MAPEL_DATA  = JSON.parse(document.getElementById('mapelData').textContent);

    /* ── Modal helpers ────────────────────────────────────────── */
    function openModal(id) {
        document.getElementById(id).classList.remove('hidden');
        document.getElementById(id).classList.add('flex');
        document.body.style.overflow = 'hidden';
    }

    function closeModal(id) {
        document.getElementById(id).classList.add('hidden');
        document.getElementById(id).classList.remove('flex');
        document.body.style.overflow = '';
    }

    document.addEventListener('keydown', e => {
        if (e.key === 'Escape') {
            ['modalTambahJadwal','modalEditJadwal','modalHapusJadwal',
             'modalKelolaMapel','modalEditMapel','modalHapusMapel'].forEach(closeModal);
        }
    });

    /* ── Jadwal: Tambah dengan hari terisi ────────────────────── */
    function openTambahHari(hari) {
        document.getElementById('tambahJadwalHari').value = hari;
        openModal('modalTambahJadwal');
    }

    /* ── Jadwal: Edit ─────────────────────────────────────────── */
    function openEditJadwal(id) {
        const d = JADWAL_DATA[id];
        if (!d) return;
        document.getElementById('formEditJadwal').action =
            '{{ url("guru/jadwal-mengajar") }}/' + id;
        document.getElementById('editJadwalSubject').value  = d.study_subject_id ?? '';
        document.getElementById('editJadwalGroup').value    = d.study_group_id ?? '';
        document.getElementById('editJadwalHari').value     = d.day_of_week ?? '';
        document.getElementById('editJadwalStart').value    = d.start_time ?? '';
        document.getElementById('editJadwalEnd').value      = d.end_time ?? '';
        document.getElementById('editJadwalRoom').value     = d.room ?? '';
        document.getElementById('editJadwalSession').value  = d.session_type ?? 'teori';
        document.getElementById('editJadwalYear').value     = d.academic_year ?? '';
        document.getElementById('editJadwalSemester').value = d.semester ?? '1';
        document.getElementById('editJadwalNotes').value    = d.notes ?? '';
        openModal('modalEditJadwal');
    }

    /* ── Jadwal: Hapus ────────────────────────────────────────── */
    function openDeleteJadwal(id, nama) {
        document.getElementById('hapusJadwalName').textContent = nama;
        document.getElementById('formHapusJadwal').action =
            '{{ url("guru/jadwal-mengajar") }}/' + id;
        openModal('modalHapusJadwal');
    }

    /* ══════════════════════════════════════════════════════════
       MATA PELAJARAN
       ══════════════════════════════════════════════════════════ */

    /* ── Mapel: Edit ──────────────────────────────────────────── */
    function openEditMapel(id) {
        const d = MAPEL_DATA[id];
        if (!d) return;
        document.getElementById('formEditMapel').action =
            '{{ url("guru/study-subject") }}/' + id;
        document.getElementById('editMapelName').value  = d.name ?? '';
        document.getElementById('editMapelCode').value  = d.code ?? '';
        document.getElementById('editMapelColor').value = d.color ?? '#6366f1';
        document.getElementById('editMapelDesc').value  = d.description ?? '';
        openModal('modalEditMapel');
    }

    /* ── Mapel: Hapus ─────────────────────────────────────────── */
    function openDeleteMapel(id, nama) {
        document.getElementById('hapusMapelName').textContent = nama;
        document.getElementById('formHapusMapel').action =
            '{{ url("guru/study-subject") }}/' + id;
        openModal('modalHapusMapel');
    }

    /* ── Sync dropdown mata pelajaran setelah operasi AJAX/reload */
    /* Jika Anda menggunakan full-page reload, tidak perlu fungsi ini.
       Namun jika ingin update DOM tanpa reload, gunakan fungsi di bawah. */

    /**
     * Refresh semua <select> mata pelajaran di halaman
     * Dipanggil setelah AJAX berhasil tambah/edit/hapus mapel.
     * (Opsional – hanya jika Anda mengimplementasikan AJAX.)
     */
    function refreshMapelDropdowns(newList) {
        // newList = array of {id, name, code}
        const selectors = ['#tambahJadwalSubject', '#editJadwalSubject'];
        selectors.forEach(sel => {
            const el = document.querySelector(sel);
            if (!el) return;
            const current = el.value;
            // Hapus semua option kecuali placeholder
            while (el.options.length > 1) el.remove(1);
            newList.forEach(s => {
                const opt = new Option(`${s.name} (${s.code})`, s.id);
                el.add(opt);
            });
            el.value = current; // pertahankan pilihan sebelumnya bila masih ada
        });
    }
</script>
@endpush

@endsection