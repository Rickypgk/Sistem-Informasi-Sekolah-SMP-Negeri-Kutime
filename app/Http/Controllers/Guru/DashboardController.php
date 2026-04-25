<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\Pengumuman;
use App\Models\Siswa;
use App\Models\AbsensiSiswa;
use App\Models\Timetable;
use App\Models\Kelas;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $guru = $user->guru;

        /* ─────────────────────────────────────────────────────────
           1. WIDGET PENGUMUMAN
        ───────────────────────────────────────────────────────── */
        $widgetPengumuman = collect();
        try {
            $widgetPengumuman = Pengumuman::where('is_active', 1)
                ->where('show_di_dashboard', 1)
                ->whereIn('target_audience', ['guru', 'semua'])
                ->latest()
                ->limit(4)
                ->get();
        } catch (\Exception $e) {
            try {
                $widgetPengumuman = Pengumuman::latest()->limit(4)->get();
            } catch (\Exception $e2) {
                $widgetPengumuman = collect();
            }
        }

        /* ─────────────────────────────────────────────────────────
           DEFAULT jika guru belum terdaftar
        ───────────────────────────────────────────────────────── */
        if (!$guru) {
            return view('guru.dashboard', [
                'widgetPengumuman'    => $widgetPengumuman,
                'totalSiswa'          => 0,
                'kehadiranPct'        => 0,
                'siswaRisiko'         => 0,
                'absensiHariIni'      => ['hadir' => 0, 'sakit' => 0, 'izin' => 0, 'alpha' => 0],
                'chartLabels'         => [],
                'chartHadir'          => [],
                'chartTidak'          => [],
                'siswaBerisiko'       => collect(),
                'jadwalHariIni'       => collect(),
                // ── Wali Kelas: selalu ada meski false ──
                'isWaliKelas'         => false,
                'kelasWaliData'       => null,
                'namaKelasWali'       => null,
                'totalSiswaWali'      => 0,
                'siswaRekapDashboard' => collect(),
                'rekapDataDashboard'  => [],
                'rekapBulan'          => Carbon::now()->month,
                'rekapTahun'          => Carbon::now()->year,
            ]);
        }

        /* ─────────────────────────────────────────────────────────
           2. WALI KELAS CHECK — PALING KRITIS
              Urutan pengecekan:
              A) Kelas::where('wali_guru_id', $guru->id)
              B) Kelas::where('wali_kelas_id', $guru->id)
              C) method isWaliKelas() di model User
              D) relasi guru->waliKelas
           Sesuaikan dengan struktur DB Anda.
        ───────────────────────────────────────────────────────── */
        $isWaliKelas    = false;
        $kelasWaliData  = null;
        $namaKelasWali  = null;
        $totalSiswaWali = 0;

        try {
            // ── Cek via kolom di tabel kelas ──
            $kelasWaliData = Kelas::where('wali_guru_id', $guru->id)->first()
                          ?? Kelas::where('wali_kelas_id', $guru->id)->first();

            // ── Cek via method di model User (jika ada) ──
            if (!$kelasWaliData && method_exists($user, 'isWaliKelas') && $user->isWaliKelas()) {
                // Coba dapat kelas dari relasi guru
                $kelasWaliData = $guru->waliKelas?->kelas ?? null;
            }

            // ── Cek via relasi langsung guru->waliKelas ──
            if (!$kelasWaliData && $guru->waliKelas) {
                $kelasWaliData = $guru->waliKelas->kelas ?? $guru->waliKelas ?? null;
            }

            // ── Set flag berdasarkan hasil cek ──
            $isWaliKelas = !is_null($kelasWaliData);

            if ($isWaliKelas && $kelasWaliData) {
                $namaKelasWali  = $kelasWaliData->nama ?? $kelasWaliData->name ?? null;
                $totalSiswaWali = Siswa::where('kelas_id', $kelasWaliData->id)->count();
            }

        } catch (\Exception $e) {
            // Pastikan tetap false jika error — JANGAN biarkan undefined
            $isWaliKelas    = false;
            $kelasWaliData  = null;
            $namaKelasWali  = null;
            $totalSiswaWali = 0;
        }

        /* ─────────────────────────────────────────────────────────
           3. KUMPULKAN study_group_id dari Timetable
        ───────────────────────────────────────────────────────── */
        $studyGroupIds = collect();

        try {
            $studyGroupIds = Timetable::where('teacher_id', $user->id)
                ->whereNotNull('study_group_id')
                ->pluck('study_group_id')
                ->unique()
                ->filter()
                ->values();
        } catch (\Exception $e) {}

        // Sertakan kelas wali kelas jika ada
        if ($kelasWaliData) {
            $studyGroupIds = $studyGroupIds->push($kelasWaliData->id)->unique()->filter()->values();
        }

        $siswaIds = collect();
        if ($studyGroupIds->isNotEmpty()) {
            try {
                $siswaIds = Siswa::whereIn('kelas_id', $studyGroupIds)->pluck('id');
            } catch (\Exception $e) {
                try {
                    $siswaIds = Siswa::whereIn('study_group_id', $studyGroupIds)->pluck('id');
                } catch (\Exception $e2) {
                    $siswaIds = collect();
                }
            }
        }

        /* ─────────────────────────────────────────────────────────
           4. TOTAL SISWA
        ───────────────────────────────────────────────────────── */
        $totalSiswa = $siswaIds->count();

        /* ─────────────────────────────────────────────────────────
           5. KPI KEHADIRAN BULAN INI
        ───────────────────────────────────────────────────────── */
        $bulanIni     = Carbon::now()->month;
        $tahunIni     = Carbon::now()->year;
        $kehadiranPct = 0;

        if ($siswaIds->isNotEmpty()) {
            try {
                $totalAbsensi = AbsensiSiswa::whereIn('siswa_id', $siswaIds)
                    ->whereMonth('tanggal', $bulanIni)
                    ->whereYear('tanggal', $tahunIni)
                    ->count();

                $totalHadir = AbsensiSiswa::whereIn('siswa_id', $siswaIds)
                    ->whereMonth('tanggal', $bulanIni)
                    ->whereYear('tanggal', $tahunIni)
                    ->where('status', 'hadir')
                    ->count();

                $kehadiranPct = $totalAbsensi > 0
                    ? round(($totalHadir / $totalAbsensi) * 100, 1)
                    : 0;
            } catch (\Exception $e) {}
        }

        /* ─────────────────────────────────────────────────────────
           6. ABSENSI HARI INI
        ───────────────────────────────────────────────────────── */
        $today          = Carbon::today();
        $absensiHariIni = ['hadir' => 0, 'sakit' => 0, 'izin' => 0, 'alpha' => 0];

        if ($siswaIds->isNotEmpty()) {
            try {
                $absensiHariIni = [
                    'hadir' => AbsensiSiswa::whereIn('siswa_id', $siswaIds)->whereDate('tanggal', $today)->where('status', 'hadir')->count(),
                    'sakit' => AbsensiSiswa::whereIn('siswa_id', $siswaIds)->whereDate('tanggal', $today)->where('status', 'sakit')->count(),
                    'izin'  => AbsensiSiswa::whereIn('siswa_id', $siswaIds)->whereDate('tanggal', $today)->where('status', 'izin')->count(),
                    'alpha' => AbsensiSiswa::whereIn('siswa_id', $siswaIds)->whereDate('tanggal', $today)->where('status', 'alpha')->count(),
                ];
            } catch (\Exception $e) {}
        }

        /* ─────────────────────────────────────────────────────────
           7. SISWA BERISIKO (kehadiran < 75% bulan ini)
        ───────────────────────────────────────────────────────── */
        $siswaBerisiko = collect();
        $siswaRisiko   = 0;

        if ($siswaIds->isNotEmpty()) {
            try {
                $siswaBerisiko = Siswa::whereIn('id', $siswaIds)
                    ->with('kelas')
                    ->get()
                    ->map(function ($s) use ($bulanIni, $tahunIni) {
                        try {
                            $r = AbsensiSiswa::where('siswa_id', $s->id)
                                ->whereMonth('tanggal', $bulanIni)
                                ->whereYear('tanggal', $tahunIni)
                                ->selectRaw("
                                    SUM(CASE WHEN status='hadir' THEN 1 ELSE 0 END) as hadir,
                                    SUM(CASE WHEN status='alpha' THEN 1 ELSE 0 END) as alpha,
                                    COUNT(*) as total
                                ")
                                ->first();
                        } catch (\Exception $e) {
                            $r = null;
                        }

                        $tot       = $r?->total ?? 0;
                        $kehadiran = $tot > 0 ? round(($r->hadir / $tot) * 100, 1) : 0;

                        $s->kehadiran = $kehadiran;
                        $s->alpha     = $r?->alpha ?? 0;
                        $s->namaKelas = $s->kelas?->nama ?? $s->kelas?->name ?? '—';

                        return $s;
                    })
                    ->filter(fn($s) => $s->kehadiran < 75)
                    ->sortBy('kehadiran')
                    ->take(8)
                    ->values();

                $siswaRisiko = $siswaBerisiko->count();
            } catch (\Exception $e) {}
        }

        /* ─────────────────────────────────────────────────────────
           8. CHART TREN 7 HARI TERAKHIR
        ───────────────────────────────────────────────────────── */
        $chartLabels = [];
        $chartHadir  = [];
        $chartTidak  = [];

        for ($i = 6; $i >= 0; $i--) {
            $tgl           = Carbon::today()->subDays($i);
            $chartLabels[] = $tgl->locale('id')->isoFormat('ddd D/M');

            if ($siswaIds->isNotEmpty()) {
                try {
                    $hadir        = AbsensiSiswa::whereIn('siswa_id', $siswaIds)->whereDate('tanggal', $tgl)->where('status', 'hadir')->count();
                    $total        = AbsensiSiswa::whereIn('siswa_id', $siswaIds)->whereDate('tanggal', $tgl)->count();
                    $chartHadir[] = $hadir;
                    $chartTidak[] = $total - $hadir;
                } catch (\Exception $e) {
                    $chartHadir[] = 0;
                    $chartTidak[] = 0;
                }
            } else {
                $chartHadir[] = 0;
                $chartTidak[] = 0;
            }
        }

        /* ─────────────────────────────────────────────────────────
           9. JADWAL MENGAJAR HARI INI
        ───────────────────────────────────────────────────────── */
        $hariMap = [
            'Sunday'    => 'Minggu',
            'Monday'    => 'Senin',
            'Tuesday'   => 'Selasa',
            'Wednesday' => 'Rabu',
            'Thursday'  => 'Kamis',
            'Friday'    => 'Jumat',
            'Saturday'  => 'Sabtu',
        ];
        $hariHariIni   = $hariMap[Carbon::now()->format('l')];
        $jadwalHariIni = collect();

        try {
            $jadwalHariIni = Timetable::with(['studySubject', 'studyGroup'])
                ->where('teacher_id', $user->id)
                ->where('day_of_week', $hariHariIni)
                ->orderBy('start_time')
                ->get()
                ->map(function ($j) {
                    $j->_jam_mulai   = $j->start_time ?? null;
                    $j->_jam_selesai = $j->end_time   ?? null;
                    $j->_mapel       = $j->studySubject?->name ?? $j->studySubject?->nama ?? $j->subject_name ?? '—';
                    $j->_kelas       = $j->studyGroup?->name  ?? $j->studyGroup?->nama  ?? $j->group_name   ?? '—';
                    $j->_ruangan     = $j->room ?? $j->ruangan ?? null;
                    $j->_kelas_id    = $j->study_group_id ?? null;
                    $j->_sumber      = 'Timetable';
                    $j->_warna       = $j->studySubject?->color ?? null;
                    return $j;
                });
        } catch (\Exception $e) {
            $jadwalHariIni = collect();
        }

        /* ─────────────────────────────────────────────────────────
           10. REKAP ABSENSI WALI KELAS (widget dashboard)
               Hanya dijalankan jika $isWaliKelas = true
        ───────────────────────────────────────────────────────── */
        $siswaRekapDashboard = collect();
        $rekapDataDashboard  = [];
        $rekapBulan          = $request->input('rekap_bulan', $bulanIni);
        $rekapTahun          = $request->input('rekap_tahun', $tahunIni);

        if ($isWaliKelas && $kelasWaliData) {
            try {
                $siswaRekapDashboard = Siswa::where('kelas_id', $kelasWaliData->id)
                    ->orderBy('nama')
                    ->get();

                foreach ($siswaRekapDashboard as $siswa) {
                    try {
                        $r = AbsensiSiswa::where('siswa_id', $siswa->id)
                            ->whereMonth('tanggal', $rekapBulan)
                            ->whereYear('tanggal', $rekapTahun)
                            ->selectRaw("
                                SUM(CASE WHEN status='hadir' THEN 1 ELSE 0 END) as hadir,
                                SUM(CASE WHEN status='sakit' THEN 1 ELSE 0 END) as sakit,
                                SUM(CASE WHEN status='izin'  THEN 1 ELSE 0 END) as izin,
                                SUM(CASE WHEN status='alpha' THEN 1 ELSE 0 END) as alpha
                            ")
                            ->first();
                    } catch (\Exception $e) {
                        $r = null;
                    }

                    $rekapDataDashboard[$siswa->id] = [
                        'hadir' => $r?->hadir ?? 0,
                        'sakit' => $r?->sakit ?? 0,
                        'izin'  => $r?->izin  ?? 0,
                        'alpha' => $r?->alpha ?? 0,
                    ];
                }
            } catch (\Exception $e) {
                $siswaRekapDashboard = collect();
                $rekapDataDashboard  = [];
            }
        }

        /* ─────────────────────────────────────────────────────────
           RETURN VIEW — semua variabel dijamin terdefinisi
        ───────────────────────────────────────────────────────── */
        return view('guru.dashboard', compact(
            'totalSiswa',
            'kehadiranPct',
            'siswaRisiko',
            'absensiHariIni',
            'isWaliKelas',        // ← boolean, selalu ada
            'kelasWaliData',      // ← object|null
            'namaKelasWali',      // ← string|null
            'totalSiswaWali',     // ← int
            'siswaBerisiko',
            'chartLabels',
            'chartHadir',
            'chartTidak',
            'jadwalHariIni',
            'siswaRekapDashboard',
            'rekapDataDashboard',
            'rekapBulan',
            'rekapTahun',
            'widgetPengumuman'
        ));
    }
}