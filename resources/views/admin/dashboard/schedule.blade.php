{{-- resources/views/admin/dashboard/schedule.blade.php
     Variabel: $jadwalHariIni (Collection — hasil query teacher_schedules join gurus + study_groups)
--}}
@php
    $namaHariIni = \Carbon\Carbon::now()->isoFormat('dddd, D MMMM Y');
    $timeNow     = now()->format('H:i');

    // Kelompokkan per jam (start_time)
    $grouped = collect($jadwalHariIni)->groupBy('start_time');
@endphp

<div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200
            dark:border-slate-700 shadow-sm overflow-hidden">

    {{-- Header --}}
    <div class="flex items-center justify-between px-4 py-3
                border-b border-slate-100 dark:border-slate-700/60">
        <div class="flex items-center gap-2">
            <div class="w-7 h-7 rounded-xl bg-gradient-to-br from-amber-400 to-orange-500
                        flex items-center justify-center text-white text-sm shadow-sm shrink-0">
                📅
            </div>
            <div>
                <p class="text-xs font-bold text-slate-800 dark:text-slate-100 leading-tight">
                    Jadwal Hari Ini
                </p>
                <p class="text-[10px] text-slate-400 leading-none mt-0.5">
                    {{ $namaHariIni }}
                </p>
            </div>
        </div>
        <span class="text-[10px] font-bold text-amber-600 dark:text-amber-400
                     bg-amber-50 dark:bg-amber-900/30 px-2 py-1 rounded-lg">
            {{ collect($jadwalHariIni)->count() }} sesi
        </span>
    </div>

    {{-- Konten --}}
    @if(collect($jadwalHariIni)->isEmpty())
        <div class="flex flex-col items-center gap-2 py-10 text-center px-4">
            <div class="text-3xl">📭</div>
            <p class="text-xs font-semibold text-slate-500 dark:text-slate-400">
                Tidak ada jadwal hari ini
            </p>
            <p class="text-[10px] text-slate-400">
                Hari libur atau belum ada jadwal yang diatur.
            </p>
        </div>
    @else
        <div class="overflow-y-auto max-h-[380px]
                    scrollbar-thin scrollbar-thumb-slate-200 dark:scrollbar-thumb-slate-700">

            {{-- Timeline --}}
            <div class="relative px-4 py-3 space-y-1">

                @foreach(collect($jadwalHariIni) as $idx => $jadwal)
                @php
                    $startStr  = \Carbon\Carbon::parse($jadwal->start_time)->format('H:i');
                    $endStr    = \Carbon\Carbon::parse($jadwal->end_time)->format('H:i');
                    $isNow     = $timeNow >= $startStr && $timeNow <= $endStr;
                    $isPast    = $timeNow > $endStr;
                    $isUpcoming= $timeNow < $startStr;

                    if ($isNow)      { $dotColor = 'bg-emerald-500'; $rowBg = 'bg-emerald-50/60 dark:bg-emerald-900/10 border-emerald-200 dark:border-emerald-800'; }
                    elseif ($isPast) { $dotColor = 'bg-slate-300 dark:bg-slate-600'; $rowBg = 'bg-slate-50/50 dark:bg-slate-700/10 border-slate-100 dark:border-slate-700/50'; }
                    else             { $dotColor = 'bg-amber-400'; $rowBg = 'bg-white dark:bg-slate-800 border-slate-100 dark:border-slate-700/50'; }

                    // Inisial guru
                    $inisial = strtoupper(mb_substr($jadwal->guru_nama ?? '?', 0, 2));

                    // Warna kelas (variasi berdasarkan nama)
                    $kelasColors = [
                        'bg-indigo-100 text-indigo-700 dark:bg-indigo-900/30 dark:text-indigo-400',
                        'bg-violet-100 text-violet-700 dark:bg-violet-900/30 dark:text-violet-400',
                        'bg-sky-100 text-sky-700 dark:bg-sky-900/30 dark:text-sky-400',
                        'bg-teal-100 text-teal-700 dark:bg-teal-900/30 dark:text-teal-400',
                        'bg-pink-100 text-pink-700 dark:bg-pink-900/30 dark:text-pink-400',
                    ];
                    $kelasColor = $kelasColors[crc32($jadwal->kelas_nama ?? '') % 5];
                @endphp

                <div class="flex items-center gap-3 px-3 py-2.5 rounded-xl border
                            {{ $rowBg }} transition-all">

                    {{-- Dot & waktu --}}
                    <div class="flex flex-col items-center shrink-0 w-12 gap-0.5">
                        <span class="w-2 h-2 rounded-full {{ $dotColor }} shrink-0
                                     {{ $isNow ? 'animate-pulse' : '' }}"></span>
                        <span class="text-[9px] font-bold text-slate-600 dark:text-slate-400
                                     font-mono leading-none">{{ $startStr }}</span>
                        <span class="text-[8px] text-slate-400 font-mono leading-none">
                            –{{ $endStr }}
                        </span>
                    </div>

                    {{-- Guru avatar --}}
                    <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-indigo-400 to-violet-500
                                flex items-center justify-center text-white text-[10px] font-bold
                                shrink-0 shadow-sm">
                        {{ $inisial }}
                    </div>

                    {{-- Info --}}
                    <div class="flex-1 min-w-0">
                        <p class="text-xs font-semibold text-slate-800 dark:text-slate-100
                                   leading-tight truncate">
                            {{ $jadwal->guru_nama ?? '—' }}
                        </p>
                        <p class="text-[10px] text-slate-400 truncate leading-tight mt-0.5">
                            {{ $jadwal->subject ?? 'Mata Pelajaran' }}
                            @if($jadwal->room ?? null)
                                · {{ $jadwal->room }}
                            @endif
                        </p>
                    </div>

                    {{-- Kelas --}}
                    <span class="text-[10px] font-bold px-2 py-0.5 rounded-lg
                                 {{ $kelasColor }} shrink-0 whitespace-nowrap">
                        {{ $jadwal->kelas_nama ?? '—' }}
                    </span>

                    {{-- Badge SEDANG --}}
                    @if($isNow)
                        <span class="text-[9px] font-bold bg-emerald-500 text-white
                                     px-1.5 py-0.5 rounded-full shrink-0 animate-pulse">
                            LIVE
                        </span>
                    @endif
                </div>

                @endforeach
            </div>
        </div>
    @endif

    {{-- Footer: link ke halaman jadwal --}}
    @if(Route::has('admin.jadwal.index') && collect($jadwalHariIni)->isNotEmpty())
    <div class="px-4 py-2 border-t border-slate-100 dark:border-slate-700/60">
        <a href="{{ route('admin.jadwal.index') }}"
           class="flex items-center justify-center gap-1 text-[10px] font-semibold
                  text-amber-600 hover:text-amber-700 transition-colors py-0.5">
            Kelola semua jadwal
            <svg class="w-2.5 h-2.5" fill="none" viewBox="0 0 24 24"
                 stroke="currentColor" stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
            </svg>
        </a>
    </div>
    @endif
</div>