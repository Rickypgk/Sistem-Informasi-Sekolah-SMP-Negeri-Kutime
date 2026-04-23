{{-- resources/views/guru/dashboard/jadwal-mengajar.blade.php --}}
@php
    $jadwalHariIni = $jadwalHariIni ?? collect();

    $hariMap = [
        'Sunday'    => 'Minggu',
        'Monday'    => 'Senin',
        'Tuesday'   => 'Selasa',
        'Wednesday' => 'Rabu',
        'Thursday'  => 'Kamis',
        'Friday'    => 'Jumat',
        'Saturday'  => 'Sabtu',
    ];
    $hariIniLabel = $hariMap[now()->format('l')] ?? now()->format('l');

    $warnaPalet = [
        '#4f46e5','#059669','#2563eb','#d97706',
        '#7c3aed','#0891b2','#db2777','#ea580c',
    ];

    $nowTime = now()->format('H:i');

    // Fungsi helper untuk cek status jadwal
    $getStatus = function($jamMulai, $jamSelesai) use ($nowTime) {
        if (!$jamMulai || !$jamSelesai) return 'akan';
        $m = substr($jamMulai, 0, 5);
        $s = substr($jamSelesai, 0, 5);
        if ($nowTime >= $m && $nowTime < $s) return 'berlangsung';
        if ($nowTime >= $s) return 'selesai';
        return 'akan';
    };
@endphp

<div class="bg-white dark:bg-slate-800 rounded-xl border border-slate-200 dark:border-slate-700 shadow-sm overflow-hidden">

    {{-- Header --}}
    <div style="display:flex;align-items:center;justify-content:space-between;
                padding:10px 14px;border-bottom:1px solid #f1f5f9;background:#fff;">
        <div style="display:flex;align-items:center;gap:8px;">
            <div style="width:30px;height:30px;border-radius:9px;background:linear-gradient(135deg,#4f46e5,#7c3aed);
                        display:flex;align-items:center;justify-content:center;font-size:.9rem;">
                🗓️
            </div>
            <div>
                <p style="font-size:.78rem;font-weight:700;color:#1e293b;line-height:1.2;margin:0;">
                    Jadwal Mengajar Hari Ini
                </p>
                <p style="font-size:.6rem;color:#94a3b8;line-height:1.3;margin:0;">
                    {{ $hariIniLabel }}, {{ now()->isoFormat('D MMMM Y') }}
                </p>
            </div>
        </div>
        <div style="display:flex;align-items:center;gap:6px;">
            @if($jadwalHariIni->isNotEmpty())
                <span style="font-size:.58rem;font-weight:700;background:#ecfdf5;color:#059669;
                             border:1px solid #a7f3d0;border-radius:99px;padding:2px 8px;">
                    {{ $jadwalHariIni->count() }} sesi
                </span>
            @endif
            <a href="{{ route('guru.jadwal-mengajar.index') }}"
               style="font-size:.6rem;font-weight:700;color:#4f46e5;text-decoration:none;
                      background:#eef2ff;border:1px solid #c7d2fe;border-radius:6px;
                      padding:4px 10px;white-space:nowrap;">
                Semua Jadwal →
            </a>
        </div>
    </div>

    @if($jadwalHariIni->isEmpty())
        {{-- Empty state --}}
        <div style="padding:40px 20px;text-align:center;">
            <div style="width:60px;height:60px;border-radius:18px;background:#f1f5f9;
                        display:flex;align-items:center;justify-content:center;
                        font-size:1.8rem;margin:0 auto 12px;">😊</div>
            <p style="font-size:.75rem;font-weight:700;color:#475569;margin:0 0 4px;">
                Tidak ada jadwal mengajar hari ini
            </p>
            <p style="font-size:.62rem;color:#94a3b8;margin:0;">
                Selamat menikmati hari {{ $hariIniLabel }}!
            </p>
        </div>

    @else
        {{-- Tabel jadwal --}}
        <div style="overflow-x:auto;">
            <table style="width:100%;border-collapse:collapse;min-width:500px;">
                <thead>
                    <tr style="background:#f8fafc;">
                        <th style="padding:8px 14px;text-align:left;font-size:.575rem;font-weight:700;
                                   color:#64748b;text-transform:uppercase;letter-spacing:.05em;
                                   border-bottom:1.5px solid #e2e8f0;white-space:nowrap;width:110px;">
                            Jam
                        </th>
                        <th style="padding:8px 12px;text-align:left;font-size:.575rem;font-weight:700;
                                   color:#64748b;text-transform:uppercase;letter-spacing:.05em;
                                   border-bottom:1.5px solid #e2e8f0;">
                            Mata Pelajaran
                        </th>
                        <th style="padding:8px 10px;text-align:center;font-size:.575rem;font-weight:700;
                                   color:#64748b;text-transform:uppercase;letter-spacing:.05em;
                                   border-bottom:1.5px solid #e2e8f0;white-space:nowrap;width:80px;">
                            Kelas
                        </th>
                        <th style="padding:8px 10px;text-align:center;font-size:.575rem;font-weight:700;
                                   color:#64748b;text-transform:uppercase;letter-spacing:.05em;
                                   border-bottom:1.5px solid #e2e8f0;white-space:nowrap;width:80px;">
                            Ruangan
                        </th>
                        <th style="padding:8px 10px;text-align:center;font-size:.575rem;font-weight:700;
                                   color:#64748b;text-transform:uppercase;letter-spacing:.05em;
                                   border-bottom:1.5px solid #e2e8f0;white-space:nowrap;width:110px;">
                            Status
                        </th>
                        <th style="padding:8px 14px;text-align:center;font-size:.575rem;font-weight:700;
                                   color:#64748b;text-transform:uppercase;letter-spacing:.05em;
                                   border-bottom:1.5px solid #e2e8f0;white-space:nowrap;width:90px;">
                            Aksi
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($jadwalHariIni as $idx => $jadwal)
                    @php
                        $stripColor = $warnaPalet[$idx % count($warnaPalet)];
                        $jamMulai   = $jadwal->jam_mulai   ?? null;
                        $jamSelesai = $jadwal->jam_selesai ?? null;

                        // Resolve nama mapel — support berbagai relasi/kolom
                        $mapel = $jadwal->mataPelajaran->nama
                               ?? $jadwal->mataPelajaran->name
                               ?? $jadwal->mata_pelajaran
                               ?? $jadwal->mapel
                               ?? $jadwal->nama_mapel
                               ?? '—';

                        // Resolve nama kelas
                        $namaKelas = $jadwal->kelas->nama
                                   ?? $jadwal->kelas->name
                                   ?? $jadwal->nama_kelas
                                   ?? $jadwal->kelas_nama
                                   ?? '—';

                        $ruangan   = $jadwal->ruangan ?? $jadwal->ruang ?? null;
                        $kelasId   = $jadwal->kelas_id ?? $jadwal->kelas->id ?? null;
                        $jadwalId  = $jadwal->id ?? null;
                        $sumber    = $jadwal->sumber ?? $jadwal->dibuat_oleh ?? null;

                        $status = $getStatus($jamMulai, $jamSelesai);
                        $isBerlangsung = $status === 'berlangsung';
                        $isSelesai     = $status === 'selesai';

                        // Style per status
                        if ($isBerlangsung) {
                            $rowBg        = '#f0fdf4';
                            $statusLabel  = 'Berlangsung';
                            $statusBg     = '#dcfce7';
                            $statusColor  = '#059669';
                            $statusBorder = '#a7f3d0';
                        } elseif ($isSelesai) {
                            $rowBg        = $idx % 2 === 0 ? '#fff' : '#fafbfd';
                            $statusLabel  = 'Selesai';
                            $statusBg     = '#f1f5f9';
                            $statusColor  = '#94a3b8';
                            $statusBorder = '#e2e8f0';
                        } else {
                            $rowBg        = $idx % 2 === 0 ? '#fff' : '#fafbfd';
                            $statusLabel  = 'Akan Datang';
                            $statusBg     = '#eff6ff';
                            $statusColor  = '#2563eb';
                            $statusBorder = '#bfdbfe';
                        }
                    @endphp
                    <tr style="background:{{ $rowBg }};border-bottom:1px solid #f1f5f9;"
                        onmouseover="this.style.background='{{ $isBerlangsung ? '#dcfce7' : '#f0f4ff' }}'"
                        onmouseout="this.style.background='{{ $rowBg }}'">

                        {{-- Jam --}}
                        <td style="padding:9px 14px;white-space:nowrap;">
                            <div style="display:flex;align-items:center;gap:8px;">
                                {{-- Color strip --}}
                                <div style="width:3px;height:40px;border-radius:3px;
                                             background:{{ $isBerlangsung ? '#059669' : ($isSelesai ? '#cbd5e1' : $stripColor) }};
                                             flex-shrink:0;"></div>
                                {{-- Jam box --}}
                                <div style="text-align:center;
                                             background:{{ $isBerlangsung ? '#dcfce7' : '#f8fafc' }};
                                             border:1.5px solid {{ $isBerlangsung ? '#a7f3d0' : '#e2e8f0' }};
                                             border-radius:8px;padding:5px 9px;min-width:62px;">
                                    @if($jamMulai && $jamSelesai)
                                        <p style="font-size:.7rem;font-weight:800;line-height:1.2;
                                                   color:{{ $isBerlangsung ? '#059669' : ($isSelesai ? '#94a3b8' : '#1e293b') }};">
                                            {{ substr($jamMulai,0,5) }}
                                        </p>
                                        <div style="width:16px;height:1px;background:#e2e8f0;margin:2px auto;"></div>
                                        <p style="font-size:.58rem;color:#94a3b8;line-height:1.2;">
                                            {{ substr($jamSelesai,0,5) }}
                                        </p>
                                    @else
                                        <p style="font-size:.62rem;color:#94a3b8;">—</p>
                                    @endif
                                </div>
                            </div>
                        </td>

                        {{-- Mata Pelajaran --}}
                        <td style="padding:9px 12px;">
                            <div style="display:flex;align-items:center;gap:8px;">
                                {{-- Inisial badge --}}
                                <div style="width:30px;height:30px;border-radius:9px;flex-shrink:0;
                                             display:flex;align-items:center;justify-content:center;
                                             font-size:.6rem;font-weight:800;color:#fff;
                                             background:{{ $isSelesai ? '#cbd5e1' : $stripColor }};
                                             opacity:{{ $isSelesai ? '.7' : '1' }};">
                                    {{ strtoupper(substr(preg_replace('/[^A-Za-z]/','',$mapel), 0, 2)) ?: '??' }}
                                </div>
                                <div style="min-width:0;">
                                    <p style="font-size:.72rem;font-weight:700;line-height:1.25;
                                               color:{{ $isSelesai ? '#94a3b8' : '#1e293b' }};
                                               white-space:nowrap;overflow:hidden;text-overflow:ellipsis;
                                               max-width:180px;">
                                        {{ $mapel }}
                                    </p>
                                    @if($isBerlangsung)
                                        <p style="font-size:.55rem;font-weight:700;color:#059669;line-height:1.2;">
                                            ● Sedang berlangsung
                                        </p>
                                    @elseif($sumber)
                                        <p style="font-size:.55rem;color:#cbd5e1;line-height:1.2;">
                                            Dari: {{ ucfirst($sumber) }}
                                        </p>
                                    @endif
                                </div>
                            </div>
                        </td>

                        {{-- Kelas --}}
                        <td style="padding:9px 10px;text-align:center;">
                            <span style="display:inline-flex;align-items:center;justify-content:center;
                                          padding:3px 10px;border-radius:6px;font-size:.65rem;font-weight:700;
                                          white-space:nowrap;
                                          background:{{ $isBerlangsung ? '#eef2ff' : ($isSelesai ? '#f8fafc' : '#f8fafc') }};
                                          color:{{ $isBerlangsung ? '#4338ca' : ($isSelesai ? '#94a3b8' : '#475569') }};
                                          border:1px solid {{ $isBerlangsung ? '#c7d2fe' : '#e2e8f0' }};">
                                {{ $namaKelas }}
                            </span>
                        </td>

                        {{-- Ruangan --}}
                        <td style="padding:9px 10px;text-align:center;">
                            @if($ruangan)
                                <span style="font-size:.65rem;font-weight:600;
                                              color:{{ $isSelesai ? '#94a3b8' : '#475569' }};">
                                    {{ $ruangan }}
                                </span>
                            @else
                                <span style="font-size:.65rem;color:#cbd5e1;">—</span>
                            @endif
                        </td>

                        {{-- Status --}}
                        <td style="padding:9px 10px;text-align:center;">
                            <span style="display:inline-flex;align-items:center;justify-content:center;
                                          gap:3px;padding:3px 9px;border-radius:99px;font-size:.58rem;
                                          font-weight:700;white-space:nowrap;
                                          background:{{ $statusBg }};
                                          color:{{ $statusColor }};
                                          border:1px solid {{ $statusBorder }};">
                                @if($isBerlangsung)🟢
                                @elseif($isSelesai)✓
                                @else🕐
                                @endif
                                {{ $statusLabel }}
                            </span>
                        </td>

                        {{-- Aksi --}}
                        <td style="padding:9px 14px;text-align:center;">
                            @if($isSelesai)
                                <a href="{{ route('guru.absensi-siswa.index', array_filter(['kelas_id' => $kelasId, 'jadwal_id' => $jadwalId])) }}"
                                   style="display:inline-flex;align-items:center;justify-content:center;
                                          gap:3px;padding:5px 10px;border-radius:7px;font-size:.6rem;
                                          font-weight:600;text-decoration:none;white-space:nowrap;
                                          background:#f8fafc;color:#94a3b8;border:1px solid #e2e8f0;">
                                    👁 Lihat
                                </a>
                            @elseif($isBerlangsung)
                                <a href="{{ route('guru.absensi-siswa.index', array_filter(['kelas_id' => $kelasId, 'jadwal_id' => $jadwalId])) }}"
                                   style="display:inline-flex;align-items:center;justify-content:center;
                                          gap:3px;padding:5px 10px;border-radius:7px;font-size:.6rem;
                                          font-weight:700;text-decoration:none;white-space:nowrap;
                                          background:#4f46e5;color:#fff;
                                          box-shadow:0 2px 8px rgba(79,70,229,.25);">
                                    ✅ Absensi
                                </a>
                            @else
                                <a href="{{ route('guru.absensi-siswa.index', array_filter(['kelas_id' => $kelasId, 'jadwal_id' => $jadwalId])) }}"
                                   style="display:inline-flex;align-items:center;justify-content:center;
                                          gap:3px;padding:5px 10px;border-radius:7px;font-size:.6rem;
                                          font-weight:600;text-decoration:none;white-space:nowrap;
                                          background:#f8fafc;color:#475569;border:1px solid #e2e8f0;">
                                    ✅ Absensi
                                </a>
                            @endif
                        </td>

                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- Footer ringkasan --}}
        @php
            $jmlBerlangsung = $jadwalHariIni->filter(fn($j) => $getStatus($j->jam_mulai ?? null, $j->jam_selesai ?? null) === 'berlangsung')->count();
            $jmlSelesai     = $jadwalHariIni->filter(fn($j) => $getStatus($j->jam_mulai ?? null, $j->jam_selesai ?? null) === 'selesai')->count();
            $jmlAkan        = $jadwalHariIni->count() - $jmlBerlangsung - $jmlSelesai;
        @endphp
        <div style="padding:8px 14px;border-top:1px solid #f1f5f9;background:#f8fafc;
                     display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:6px;">
            <div style="display:flex;align-items:center;gap:10px;flex-wrap:wrap;">
                @if($jmlBerlangsung > 0)
                    <span style="font-size:.6rem;font-weight:700;color:#059669;">
                        🟢 {{ $jmlBerlangsung }} berlangsung
                    </span>
                @endif
                @if($jmlSelesai > 0)
                    <span style="font-size:.6rem;color:#94a3b8;">
                        ✓ {{ $jmlSelesai }} selesai
                    </span>
                @endif
                @if($jmlAkan > 0)
                    <span style="font-size:.6rem;color:#64748b;">
                        🕐 {{ $jmlAkan }} akan datang
                    </span>
                @endif
            </div>
            <a href="{{ route('guru.jadwal-mengajar.index') }}"
               style="font-size:.6rem;font-weight:700;color:#4f46e5;text-decoration:none;">
                Lihat jadwal minggu ini →
            </a>
        </div>
    @endif

</div>