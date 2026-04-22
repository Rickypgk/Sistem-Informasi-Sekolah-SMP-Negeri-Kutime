{{-- resources/views/guru/dashboard/performance-summary.blade.php --}}
@php
    $kpiCards = [
        [
            'icon'    => '👨‍🎓',
            'bg'      => '#eef2ff',
            'bgDark'  => 'rgba(99,102,241,.18)',
            'val'     => $totalSiswa ?? 0,
            'fmt'     => 'int',
            'label'   => 'Total Siswa',
            'sub'     => 'Semua kelas diajar',
            'color'   => '#4f46e5',
        ],
        [
            'icon'    => '✅',
            'bg'      => '#ecfdf5',
            'bgDark'  => 'rgba(5,150,105,.18)',
            'val'     => $kehadiranPct ?? 0,
            'fmt'     => 'pct',
            'label'   => 'Kehadiran',
            'sub'     => 'Bulan ' . now()->isoFormat('MMMM'),
            'color'   => '#059669',
        ],
        [
            'icon'    => '📊',
            'bg'      => '#eff6ff',
            'bgDark'  => 'rgba(37,99,235,.18)',
            'val'     => $rataNilai ?? 0,
            'fmt'     => 'dec',
            'label'   => 'Rata Nilai',
            'sub'     => 'Semua mapel',
            'color'   => '#2563eb',
        ],
        [
            'icon'    => '⚠️',
            'bg'      => '#fef2f2',
            'bgDark'  => 'rgba(220,38,38,.18)',
            'val'     => $siswaRisiko ?? 0,
            'fmt'     => 'int',
            'label'   => 'Butuh Perhatian',
            'sub'     => 'Absensi < 75%',
            'color'   => '#dc2626',
        ],
    ];
@endphp

<div class="grid grid-cols-2 lg:grid-cols-4 gap-3">
    @foreach($kpiCards as $c)
    <div class="bg-white dark:bg-slate-800 rounded-xl border border-slate-200 dark:border-slate-700 p-4 shadow-sm hover:shadow-md transition-shadow">
        <div class="flex items-start justify-between gap-2">
            <div class="w-10 h-10 rounded-xl flex items-center justify-center text-xl shrink-0"
                 style="background:{{ $c['bg'] }}">
                {{ $c['icon'] }}
            </div>
        </div>
        <div class="mt-3">
            <p class="font-bold leading-none" style="font-size:1.5rem;color:{{ $c['color'] }};">
                @if($c['fmt'] === 'pct') {{ number_format($c['val'], 1) }}%
                @elseif($c['fmt'] === 'dec') {{ number_format($c['val'], 1) }}
                @else {{ $c['val'] }}
                @endif
            </p>
            <p class="font-semibold text-slate-700 dark:text-slate-200 mt-1" style="font-size:.75rem;">
                {{ $c['label'] }}
            </p>
            <p class="text-slate-400 dark:text-slate-500 mt-0.5" style="font-size:.6rem;">
                {{ $c['sub'] }}
            </p>
        </div>
    </div>
    @endforeach
</div>

{{-- Baris kedua: statistik absensi hari ini --}}
@if(isset($absensiHariIni))
<div class="bg-white dark:bg-slate-800 rounded-xl border border-slate-200 dark:border-slate-700 px-4 py-3 shadow-sm">
    <div class="flex items-center justify-between mb-2">
        <p class="font-semibold text-slate-700 dark:text-slate-200" style="font-size:.75rem;">
            📋 Absensi Hari Ini
            <span class="text-slate-400 font-normal" style="font-size:.6rem;">
                — {{ now()->isoFormat('dddd, D MMM') }}
            </span>
        </p>
        <a href="{{ route('guru.absensi-siswa.index') }}"
           style="font-size:.6rem;font-weight:600;color:#4f46e5;text-decoration:none;">
            Catat Absensi →
        </a>
    </div>
    <div class="grid grid-cols-4 gap-2">
        @php
            $abStatuses = [
                ['key'=>'hadir',  'label'=>'Hadir',  'bg'=>'#ecfdf5','color'=>'#059669'],
                ['key'=>'sakit',  'label'=>'Sakit',  'bg'=>'#fef9c3','color'=>'#a16207'],
                ['key'=>'izin',   'label'=>'Izin',   'bg'=>'#e0f2fe','color'=>'#0369a1'],
                ['key'=>'alpha',  'label'=>'Alpha',  'bg'=>'#fee2e2','color'=>'#b91c1c'],
            ];
        @endphp
        @foreach($abStatuses as $st)
        <div class="rounded-lg px-3 py-2 text-center" style="background:{{ $st['bg'] }}">
            <p class="font-bold" style="font-size:1rem;color:{{ $st['color'] }};">
                {{ $absensiHariIni[$st['key']] ?? 0 }}
            </p>
            <p style="font-size:.58rem;font-weight:600;color:{{ $st['color'] }};opacity:.8;">
                {{ $st['label'] }}
            </p>
        </div>
        @endforeach
    </div>
</div>
@endif