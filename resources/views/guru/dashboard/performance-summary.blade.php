{{-- resources/views/guru/dashboard/performance-summary.blade.php --}}
@php
    $kpiCards = [
        [
            'icon'   => '👨‍🎓',
            'bg'     => 'linear-gradient(135deg,#eef2ff,#e0e7ff)',
            'val'    => $totalSiswa ?? 0,
            'fmt'    => 'int',
            'label'  => 'Total Siswa',
            'sub'    => 'Semua kelas diajar',
            'color'  => '#4f46e5',
            'border' => '#c7d2fe',
        ],
        [
            'icon'   => '✅',
            'bg'     => 'linear-gradient(135deg,#ecfdf5,#d1fae5)',
            'val'    => $kehadiranPct ?? 0,
            'fmt'    => 'pct',
            'label'  => 'Kehadiran Bulan Ini',
            'sub'    => now()->isoFormat('MMMM Y'),
            'color'  => '#059669',
            'border' => '#a7f3d0',
        ],
        [
            'icon'   => '⚠️',
            'bg'     => 'linear-gradient(135deg,#fef2f2,#fee2e2)',
            'val'    => $siswaRisiko ?? 0,
            'fmt'    => 'int',
            'label'  => 'Butuh Perhatian',
            'sub'    => 'Kehadiran < 75%',
            'color'  => '#dc2626',
            'border' => '#fecaca',
        ],
    ];
@endphp

{{-- ─── KPI Cards ───────────────────────────────────────────────── --}}
<div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
    @foreach($kpiCards as $c)
    <div style="background:#fff;border-radius:12px;border:1px solid {{ $c['border'] }};
                padding:14px 16px;box-shadow:0 1px 4px rgba(0,0,0,.05);
                transition:box-shadow .15s;"
         onmouseover="this.style.boxShadow='0 4px 16px rgba(0,0,0,.1)'"
         onmouseout="this.style.boxShadow='0 1px 4px rgba(0,0,0,.05)'">
        <div style="display:flex;align-items:center;gap:10px;">
            <div style="width:38px;height:38px;border-radius:11px;flex-shrink:0;
                         display:flex;align-items:center;justify-content:center;
                         font-size:1.1rem;background:{{ $c['bg'] }};">
                {{ $c['icon'] }}
            </div>
            <div style="flex:1;min-width:0;">
                <p style="font-size:1.35rem;font-weight:800;line-height:1.1;color:{{ $c['color'] }};margin:0;">
                    @if($c['fmt'] === 'pct')
                        {{ number_format($c['val'], 1) }}%
                    @else
                        {{ number_format($c['val']) }}
                    @endif
                </p>
                <p style="font-size:.65rem;font-weight:700;color:#1e293b;margin:2px 0 1px;">
                    {{ $c['label'] }}
                </p>
                <p style="font-size:.58rem;color:#94a3b8;margin:0;">{{ $c['sub'] }}</p>
            </div>
        </div>
    </div>
    @endforeach
</div>

{{-- ─── Absensi Hari Ini ────────────────────────────────────────── --}}
@if(isset($absensiHariIni) && is_array($absensiHariIni))
@php
    $totalAbsensiHariIni = array_sum($absensiHariIni);
@endphp
<div style="background:#fff;border-radius:12px;border:1px solid #e2e8f0;
             padding:12px 14px;box-shadow:0 1px 4px rgba(0,0,0,.05);">
    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:10px;">
        <div style="display:flex;align-items:center;gap:6px;">
            <span style="font-size:.85rem;">📋</span>
            <p style="font-size:.72rem;font-weight:700;color:#1e293b;margin:0;">Absensi Hari Ini</p>
            <span style="font-size:.58rem;color:#94a3b8;">
                — {{ now()->isoFormat('ddd, D MMM') }}
            </span>
        </div>
        <div style="display:flex;align-items:center;gap:6px;">
            @if($totalAbsensiHariIni > 0)
                <span style="font-size:.58rem;color:#64748b;background:#f8fafc;
                             border:1px solid #e2e8f0;border-radius:99px;padding:2px 8px;">
                    {{ $totalAbsensiHariIni }} tercatat
                </span>
            @endif
            @if(Route::has('guru.absensi-siswa.index'))
                <a href="{{ route('guru.absensi-siswa.index') }}"
                   style="font-size:.6rem;font-weight:700;color:#4f46e5;text-decoration:none;
                          background:#eef2ff;border:1px solid #c7d2fe;border-radius:6px;
                          padding:3px 9px;white-space:nowrap;">
                    Catat Absensi →
                </a>
            @endif
        </div>
    </div>

    <div style="display:grid;grid-template-columns:repeat(4,1fr);gap:8px;">
        @php
            $abSt = [
                ['key' => 'hadir', 'label' => 'Hadir', 'bg' => '#ecfdf5', 'color' => '#059669', 'icon' => '✅'],
                ['key' => 'sakit', 'label' => 'Sakit', 'bg' => '#fef9c3', 'color' => '#a16207', 'icon' => '🤒'],
                ['key' => 'izin',  'label' => 'Izin',  'bg' => '#e0f2fe', 'color' => '#0369a1', 'icon' => '📋'],
                ['key' => 'alpha', 'label' => 'Alpha', 'bg' => '#fee2e2', 'color' => '#b91c1c', 'icon' => '❌'],
            ];
        @endphp
        @foreach($abSt as $st)
        @php
            $val = $absensiHariIni[$st['key']] ?? 0;
        @endphp
        <div style="border-radius:9px;padding:10px 8px;text-align:center;
                     background:{{ $st['bg'] }};position:relative;">
            <p style="font-size:.75rem;margin-bottom:2px;">{{ $st['icon'] }}</p>
            <p style="font-size:1.3rem;font-weight:800;color:{{ $st['color'] }};line-height:1;margin:0 0 2px;">
                {{ $val }}
            </p>
            <p style="font-size:.58rem;font-weight:700;color:{{ $st['color'] }};opacity:.8;margin:0;">
                {{ $st['label'] }}
            </p>
            @if($totalAbsensiHariIni > 0 && $val > 0)
                <p style="font-size:.5rem;color:{{ $st['color'] }};opacity:.6;margin:2px 0 0;">
                    {{ round($val / $totalAbsensiHariIni * 100) }}%
                </p>
            @endif
        </div>
        @endforeach
    </div>

    @if($totalAbsensiHariIni === 0)
        <p style="font-size:.62rem;color:#94a3b8;text-align:center;margin-top:10px;padding-top:8px;
                   border-top:1px solid #f1f5f9;">
            Belum ada absensi tercatat hari ini
        </p>
    @endif
</div>
@endif

{{-- ─── Siswa Berisiko ─────────────────────────────────────────── --}}
@if(isset($siswaBerisiko) && $siswaBerisiko->isNotEmpty())
<div style="background:#fff;border-radius:12px;border:1px solid #fecaca;
             padding:12px 14px;box-shadow:0 1px 4px rgba(0,0,0,.05);">
    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:8px;">
        <div style="display:flex;align-items:center;gap:6px;">
            <span style="font-size:.85rem;">⚠️</span>
            <p style="font-size:.72rem;font-weight:700;color:#1e293b;margin:0;">Siswa Butuh Perhatian</p>
        </div>
        <span style="font-size:.58rem;font-weight:700;background:#fee2e2;color:#b91c1c;
                     border:1px solid #fecaca;border-radius:99px;padding:2px 8px;">
            {{ $siswaBerisiko->count() }} siswa
        </span>
    </div>

    <div style="display:flex;flex-direction:column;gap:6px;">
        @foreach($siswaBerisiko->take(5) as $sb)
        @php
            $sbColor = $sb->kehadiran >= 60 ? '#a16207' : '#b91c1c';
            $sbBg    = $sb->kehadiran >= 60 ? '#fef9c3' : '#fee2e2';
            $namaSb  = $sb->nama ?? $sb->name ?? '—';
            $kelasSb = $sb->namaKelas ?? $sb->kelas ?? '—';
        @endphp
        <div style="display:flex;align-items:center;gap:8px;padding:6px 8px;
                     background:#fafafa;border-radius:8px;border:1px solid #f3f4f6;">
            <div style="width:28px;height:28px;border-radius:8px;background:{{ $sbBg }};flex-shrink:0;
                         display:flex;align-items:center;justify-content:center;
                         font-size:.65rem;font-weight:800;color:{{ $sbColor }};">
                {{ round($sb->kehadiran) }}
            </div>
            <div style="flex:1;min-width:0;">
                <p style="font-size:.65rem;font-weight:700;color:#1e293b;margin:0;
                           white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">
                    {{ $namaSb }}
                </p>
                <p style="font-size:.55rem;color:#94a3b8;margin:0;">
                    {{ is_string($kelasSb) ? $kelasSb : ($kelasSb->nama ?? '—') }}
                    · Alpha: {{ $sb->alpha ?? 0 }}x
                </p>
            </div>
            <div style="width:60px;text-align:right;">
                <div style="height:4px;background:#e2e8f0;border-radius:99px;overflow:hidden;margin-bottom:2px;">
                    <div style="height:100%;width:{{ $sb->kehadiran }}%;background:{{ $sbColor }};border-radius:99px;"></div>
                </div>
                <p style="font-size:.55rem;font-weight:700;color:{{ $sbColor }};margin:0;">
                    {{ $sb->kehadiran }}%
                </p>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endif