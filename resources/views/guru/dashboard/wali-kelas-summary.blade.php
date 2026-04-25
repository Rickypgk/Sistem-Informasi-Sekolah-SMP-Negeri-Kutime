{{-- resources/views/guru/dashboard/wali-kelas-summary.blade.php --}}
@php
    $kelasWali  = $kelasWaliData ?? null;
    $jmlSiswa   = $totalSiswaWali ?? 0;
    $rekapDash  = $rekapDataDashboard ?? [];
    $bulanNm    = now()->isoFormat('MMMM Y');

    // ── Distribusi kehadiran bulan ini ──────────────────────────
    // Hitung hanya siswa yang punya data absensi (total > 0)
    $pctTinggi = 0;
    $pctSedang = 0;
    $pctRendah = 0;
    $adaData   = false;

    foreach ($rekapDash as $r) {
        $tot = is_array($r) ? array_sum($r) : 0;
        if ($tot <= 0) continue;
        $adaData = true;
        $hadir   = is_array($r) ? ($r['hadir'] ?? 0) : 0;
        $pct     = round($hadir / $tot * 100);
        if ($pct >= 80)     $pctTinggi++;
        elseif ($pct >= 60) $pctSedang++;
        else                $pctRendah++;
    }

    // Siswa tanpa data absensi sama sekali
    $totalDenganData = $pctTinggi + $pctSedang + $pctRendah;
    $belumAbsensi    = max(0, $jmlSiswa - $totalDenganData);
@endphp

{{-- Widget hanya tampil untuk guru wali kelas --}}
@if(isset($isWaliKelas) && $isWaliKelas)

<div style="background:#fff;border-radius:12px;border:1.5px solid #fde68a;
             box-shadow:0 1px 4px rgba(0,0,0,.05);overflow:hidden;">

    {{-- ── Header ── --}}
    <div style="background:linear-gradient(135deg,#fffbeb,#fef3c7);
                padding:10px 14px;border-bottom:1px solid #fde68a;">
        <div style="display:flex;align-items:center;gap:8px;">
            <div style="width:28px;height:28px;border-radius:8px;
                        background:linear-gradient(135deg,#f59e0b,#d97706);
                        display:flex;align-items:center;justify-content:center;
                        font-size:.88rem;flex-shrink:0;">
                ⭐
            </div>
            <div style="flex:1;min-width:0;">
                <p style="font-size:.72rem;font-weight:700;color:#92400e;
                           margin:0;line-height:1.2;">
                    Wali Kelas
                </p>
                @if($kelasWali)
                    <p style="font-size:.58rem;color:#a16207;margin:0;line-height:1.3;">
                        {{ $kelasWali->nama ?? $kelasWali->name ?? '—' }}
                        @if($kelasWali->tingkat ?? null)
                            &nbsp;·&nbsp; {{ $kelasWali->tingkat }}
                        @endif
                    </p>
                @endif
            </div>
            {{-- Pill total siswa --}}
            <div style="background:rgba(217,119,6,.15);border:1px solid rgba(217,119,6,.3);
                        border-radius:8px;padding:3px 9px;text-align:center;flex-shrink:0;">
                <span style="font-size:.9rem;font-weight:800;color:#92400e;
                             display:block;line-height:1.1;">{{ $jmlSiswa }}</span>
                <span style="font-size:.5rem;color:#a16207;display:block;">siswa</span>
            </div>
        </div>
    </div>

    <div style="padding:12px 14px;">

        {{-- ── Distribusi kehadiran ── --}}
        <div style="margin-bottom:10px;">
            <p style="font-size:.58rem;font-weight:700;color:#64748b;text-transform:uppercase;
                      letter-spacing:.05em;margin:0 0 6px;">
                Kehadiran {{ $bulanNm }}
            </p>

            @if($jmlSiswa === 0)
                <p style="font-size:.62rem;color:#94a3b8;text-align:center;
                           padding:8px 0;margin:0;">
                    Belum ada siswa terdaftar
                </p>

            @elseif(!$adaData)
                <p style="font-size:.62rem;color:#94a3b8;text-align:center;
                           padding:8px 0;margin:0;">
                    Belum ada data absensi bulan ini
                </p>
                {{-- Tetap tampilkan siswa sebagai "belum absensi" --}}
                @if($jmlSiswa > 0)
                <div style="display:flex;align-items:center;justify-content:space-between;
                             padding:5px 9px;border-radius:7px;background:#f1f5f9;
                             margin-top:4px;">
                    <span style="font-size:.6rem;font-weight:600;color:#64748b;">
                        Belum Absensi
                    </span>
                    <span style="font-size:.72rem;font-weight:800;color:#64748b;">
                        {{ $jmlSiswa }}
                        <span style="font-size:.52rem;font-weight:600;">siswa</span>
                    </span>
                </div>
                @endif

            @else
                <div style="display:flex;flex-direction:column;gap:5px;">
                    @php
                        $distItems = [
                            ['label'=>'≥ 80% Hadir',    'val'=>$pctTinggi,   'color'=>'#059669','bg'=>'#ecfdf5'],
                            ['label'=>'60–79% Hadir',   'val'=>$pctSedang,   'color'=>'#a16207','bg'=>'#fef9c3'],
                            ['label'=>'< 60% Hadir',    'val'=>$pctRendah,   'color'=>'#b91c1c','bg'=>'#fee2e2'],
                            ['label'=>'Belum Absensi',  'val'=>$belumAbsensi,'color'=>'#64748b','bg'=>'#f1f5f9'],
                        ];
                    @endphp
                    @foreach($distItems as $di)
                        @if($di['val'] > 0)
                        <div style="display:flex;align-items:center;justify-content:space-between;
                                     padding:5px 9px;border-radius:7px;background:{{ $di['bg'] }};">
                            <span style="font-size:.6rem;font-weight:600;color:{{ $di['color'] }};">
                                {{ $di['label'] }}
                            </span>
                            <span style="font-size:.72rem;font-weight:800;color:{{ $di['color'] }};">
                                {{ $di['val'] }}
                                <span style="font-size:.52rem;font-weight:600;">siswa</span>
                            </span>
                        </div>
                        @endif
                    @endforeach
                </div>

                {{-- Progress bar estimasi rata-rata --}}
                @php
                    $avgPct   = $totalDenganData > 0
                        ? round(($pctTinggi * 90 + $pctSedang * 70 + $pctRendah * 40) / $totalDenganData)
                        : 0;
                    $avgColor = $avgPct >= 80 ? '#059669' : ($avgPct >= 60 ? '#a16207' : '#b91c1c');
                @endphp
                <div style="margin-top:8px;padding:7px 10px;background:#f8fafc;
                             border-radius:8px;border:1px solid #e2e8f0;">
                    <div style="display:flex;justify-content:space-between;
                                 align-items:center;margin-bottom:4px;">
                        <p style="font-size:.57rem;font-weight:600;color:#64748b;margin:0;">
                            Est. Rata-rata Kelas
                        </p>
                        <p style="font-size:.7rem;font-weight:800;
                                   color:{{ $avgColor }};margin:0;">
                            {{ $avgPct }}%
                        </p>
                    </div>
                    <div style="height:5px;background:#e2e8f0;border-radius:99px;overflow:hidden;">
                        <div style="height:100%;width:{{ $avgPct }}%;background:{{ $avgColor }};
                                     border-radius:99px;transition:width .4s;"></div>
                    </div>
                </div>
            @endif
        </div>

        {{-- ── Quick Links ── --}}
        <div style="display:flex;flex-direction:column;gap:5px;
                     padding-top:8px;border-top:1px solid #f1f5f9;">

            @if(Route::has('guru.wali-kelas'))
            <a href="{{ route('guru.wali-kelas') }}"
               style="display:flex;align-items:center;gap:6px;padding:6px 9px;
                      border-radius:7px;font-size:.63rem;font-weight:600;
                      color:#92400e;text-decoration:none;
                      background:#fffbeb;border:1px solid #fde68a;
                      transition:background .15s;"
               onmouseover="this.style.background='#fef3c7'"
               onmouseout="this.style.background='#fffbeb'">
                👥 Data Kelas Saya
            </a>
            @endif

            @if(Route::has('guru.absensi-siswa.rekap'))
            <a href="{{ route('guru.absensi-siswa.rekap', array_filter(['kelas_id' => $kelasWali->id ?? null])) }}"
               style="display:flex;align-items:center;gap:6px;padding:6px 9px;
                      border-radius:7px;font-size:.63rem;font-weight:600;
                      color:#4338ca;text-decoration:none;
                      background:#eef2ff;border:1px solid #c7d2fe;
                      transition:background .15s;"
               onmouseover="this.style.background='#e0e7ff'"
               onmouseout="this.style.background='#eef2ff'">
                📊 Rekap Absensi Kelas
            </a>
            @endif

            @if(Route::has('guru.absensi-siswa.index'))
            <a href="{{ route('guru.absensi-siswa.index', array_filter(['kelas_id' => $kelasWali->id ?? null])) }}"
               style="display:flex;align-items:center;gap:6px;padding:6px 9px;
                      border-radius:7px;font-size:.63rem;font-weight:600;
                      color:#065f46;text-decoration:none;
                      background:#ecfdf5;border:1px solid #a7f3d0;
                      transition:background .15s;"
               onmouseover="this.style.background='#d1fae5'"
               onmouseout="this.style.background='#ecfdf5'">
                ✅ Catat Absensi Hari Ini
            </a>
            @endif

        </div>

    </div>
</div>

@endif {{-- end isWaliKelas --}}