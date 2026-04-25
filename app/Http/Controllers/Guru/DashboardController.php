<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\Pengumuman;
use App\Models\Siswa;
use App\Models\AbsensiSiswa;
use App\Models\Timetable;
use App\Models\StudyGroup;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $guru = $user->guru; // null jika belum ada di tabel gurus — tidak masalah

        $bulanIni = Carbon::now()->month;
        $tahunIni = Carbon::now()->year;

        /* ═══════════════════════════════════════════════════════
           1. WIDGET PENGUMUMAN
        ═══════════════════════════════════════════════════════ */
        $widgetPengumuman = collect();
        try {
            $widgetPengumuman = Pengumuman::where('is_active', 1)
                ->where('show_di_dashboard', 1)
                ->whereIn('target_audience', ['guru', 'semua'])
                ->latest()->limit(4)->get();
        } catch (\Exception $e) {
            try {
                $widgetPengumuman = Pengumuman::latest()->limit(4)->get();
            } catch (\Exception $e2) {
                $widgetPengumuman = collect();
            }
        }

        /* ═══════════════════════════════════════════════════════
           2. DETEKSI WALI KELAS
           ───────────────────────────────────────────────────────
           Tabel : study_groups
           Kolom : homeroom_teacher_id = users.id  ← CONFIRMED
           User  : id=2, Kelas 8 A (study_group id=2)
        ═══════════════════════════════════════════════════════ */
        $isWaliKelas    = false;
        $kelasWaliData  = null;
        $namaKelasWali  = null;
        $totalSiswaWali = 0;

        try {
            // Query langsung — homeroom_teacher_id = users.id (sudah dikonfirmasi)
            $kelasWaliData = StudyGroup::where('homeroom_teacher_id', $user->id)
                ->where('is_active', 1)
                ->first();

            $isWaliKelas = !is_null($kelasWaliData);

        } catch (\Exception $e) {
            $isWaliKelas   = false;
            $kelasWaliData = null;
        }

        if ($isWaliKelas && $kelasWaliData) {
            $namaKelasWali = $kelasWaliData->name ?? null;

            // Hitung siswa — coba study_group_id dulu, fallback kelas_id
            try {
                $totalSiswaWali = Siswa::where('study_group_id', $kelasWaliData->id)->count();
                if ($totalSiswaWali === 0) {
                    $totalSiswaWali = Siswa::where('kelas_id', $kelasWaliData->id)->count();
                }
            } catch (\Exception $e) {
                $totalSiswaWali = 0;
            }
        }

        /* ═══════════════════════════════════════════════════════
           DEFAULT — guru belum ada di tabel gurus
           (deteksi wali kelas tetap dikirim karena pakai users.id)
        ═══════════════════════════════════════════════════════ */
        if (!$guru) {
            return view('guru.dashboard', [
                'widgetPengumuman'    => $widgetPengumuman,
                'totalSiswa'          => 0,
                'kehadiranPct'        => 0,
                'siswaRisiko'         => 0,
                'absensiHariIni'      => ['hadir'=>0,'sakit'=>0,'izin'=>0,'alpha'=>0],
                'chartLabels'         => [],
                'chartHadir'          => [],
                'chartTidak'          => [],
                'siswaBerisiko'       => collect(),
                'jadwalHariIni'       => collect(),
                'isWaliKelas'         => $isWaliKelas,
                'kelasWaliData'       => $kelasWaliData,
                'namaKelasWali'       => $namaKelasWali,
                'totalSiswaWali'      => $totalSiswaWali,
                'siswaRekapDashboard' => collect(),
                'rekapDataDashboard'  => [],
                'rekapBulan'          => $bulanIni,
                'rekapTahun'          => $tahunIni,
            ]);
        }

        /* ═══════════════════════════════════════════════════════
           3. STUDY GROUP IDs dari Timetable
        ═══════════════════════════════════════════════════════ */
        $studyGroupIds = collect();
        try {
            $studyGroupIds = Timetable::where('teacher_id', $user->id)
                ->whereNotNull('study_group_id')
                ->pluck('study_group_id')
                ->unique()->filter()->values();
        } catch (\Exception $e) {}

        // Tambahkan study_group wali kelas
        if ($kelasWaliData) {
            $studyGroupIds = $studyGroupIds
                ->push($kelasWaliData->id)
                ->unique()->filter()->values();
        }

        // Ambil siswa_id
        $siswaIds = collect();
        if ($studyGroupIds->isNotEmpty()) {
            try {
                $siswaIds = Siswa::whereIn('study_group_id', $studyGroupIds)->pluck('id');
            } catch (\Exception $e) {
                try {
                    $siswaIds = Siswa::whereIn('kelas_id', $studyGroupIds)->pluck('id');
                } catch (\Exception $e2) {
                    $siswaIds = collect();
                }
            }
        }

        /* ═══════════════════════════════════════════════════════
           4. TOTAL SISWA
        ═══════════════════════════════════════════════════════ */
        $totalSiswa = $siswaIds->count();

        /* ═══════════════════════════════════════════════════════
           5. KPI KEHADIRAN BULAN INI
        ═══════════════════════════════════════════════════════ */
        $kehadiranPct = 0;
        if ($siswaIds->isNotEmpty()) {
            try {
                $total  = AbsensiSiswa::whereIn('siswa_id', $siswaIds)
                    ->whereMonth('tanggal', $bulanIni)->whereYear('tanggal', $tahunIni)->count();
                $hadir  = AbsensiSiswa::whereIn('siswa_id', $siswaIds)
                    ->whereMonth('tanggal', $bulanIni)->whereYear('tanggal', $tahunIni)
                    ->where('status', 'hadir')->count();
                $kehadiranPct = $total > 0 ? round(($hadir / $total) * 100, 1) : 0;
            } catch (\Exception $e) {}
        }

        /* ═══════════════════════════════════════════════════════
           6. ABSENSI HARI INI
        ═══════════════════════════════════════════════════════ */
        $today          = Carbon::today();
        $absensiHariIni = ['hadir'=>0,'sakit'=>0,'izin'=>0,'alpha'=>0];
        if ($siswaIds->isNotEmpty()) {
            try {
                $absensiHariIni = [
                    'hadir' => AbsensiSiswa::whereIn('siswa_id', $siswaIds)->whereDate('tanggal', $today)->where('status','hadir')->count(),
                    'sakit' => AbsensiSiswa::whereIn('siswa_id', $siswaIds)->whereDate('tanggal', $today)->where('status','sakit')->count(),
                    'izin'  => AbsensiSiswa::whereIn('siswa_id', $siswaIds)->whereDate('tanggal', $today)->where('status','izin')->count(),
                    'alpha' => AbsensiSiswa::whereIn('siswa_id', $siswaIds)->whereDate('tanggal', $today)->where('status','alpha')->count(),
                ];
            } catch (\Exception $e) {}
        }

        /* ═══════════════════════════════════════════════════════
           7. SISWA BERISIKO (kehadiran < 75%)
        ═══════════════════════════════════════════════════════ */
        $siswaBerisiko = collect();
        $siswaRisiko   = 0;
        if ($siswaIds->isNotEmpty()) {
            try {
                $siswaBerisiko = Siswa::whereIn('id', $siswaIds)
                    ->with('kelas')->get()
                    ->map(function ($s) use ($bulanIni, $tahunIni) {
                        try {
                            $r = AbsensiSiswa::where('siswa_id', $s->id)
                                ->whereMonth('tanggal', $bulanIni)
                                ->whereYear('tanggal', $tahunIni)
                                ->selectRaw("
                                    SUM(CASE WHEN status='hadir' THEN 1 ELSE 0 END) as hadir,
                                    SUM(CASE WHEN status='alpha' THEN 1 ELSE 0 END) as alpha,
                                    COUNT(*) as total
                                ")->first();
                        } catch (\Exception $e) { $r = null; }
                        $tot = $r?->total ?? 0;
                        $s->kehadiran = $tot > 0 ? round(($r->hadir / $tot) * 100, 1) : 0;
                        $s->alpha     = $r?->alpha ?? 0;
                        $s->namaKelas = $s->kelas?->nama ?? $s->kelas?->name ?? '—';
                        return $s;
                    })
                    ->filter(fn($s) => $s->kehadiran < 75)
                    ->sortBy('kehadiran')->take(8)->values();
                $siswaRisiko = $siswaBerisiko->count();
            } catch (\Exception $e) {}
        }

        /* ═══════════════════════════════════════════════════════
           8. CHART TREN 7 HARI TERAKHIR
        ═══════════════════════════════════════════════════════ */
        $chartLabels = [];
        $chartHadir  = [];
        $chartTidak  = [];
        for ($i = 6; $i >= 0; $i--) {
            $tgl           = Carbon::today()->subDays($i);
            $chartLabels[] = $tgl->locale('id')->isoFormat('ddd D/M');
            if ($siswaIds->isNotEmpty()) {
                try {
                    $h = AbsensiSiswa::whereIn('siswa_id', $siswaIds)->whereDate('tanggal', $tgl)->where('status','hadir')->count();
                    $t = AbsensiSiswa::whereIn('siswa_id', $siswaIds)->whereDate('tanggal', $tgl)->count();
                    $chartHadir[] = $h;
                    $chartTidak[] = $t - $h;
                } catch (\Exception $e) {
                    $chartHadir[] = 0;
                    $chartTidak[] = 0;
                }
            } else {
                $chartHadir[] = 0;
                $chartTidak[] = 0;
            }
        }

        /* ═══════════════════════════════════════════════════════
           9. JADWAL MENGAJAR HARI INI
        ═══════════════════════════════════════════════════════ */
        $hariMap = [
            'Sunday'=>'Minggu','Monday'=>'Senin','Tuesday'=>'Selasa',
            'Wednesday'=>'Rabu','Thursday'=>'Kamis','Friday'=>'Jumat','Saturday'=>'Sabtu',
        ];
        $hariHariIni   = $hariMap[Carbon::now()->format('l')];
        $jadwalHariIni = collect();
        try {
            $jadwalHariIni = Timetable::with(['studySubject','studyGroup'])
                ->where('teacher_id', $user->id)
                ->where('day_of_week', $hariHariIni)
                ->orderBy('start_time')->get()
                ->map(function ($j) {
                    $j->_jam_mulai   = $j->start_time ?? null;
                    $j->_jam_selesai = $j->end_time   ?? null;
                    $j->_mapel       = $j->studySubject?->name ?? $j->studySubject?->nama ?? '—';
                    $j->_kelas       = $j->studyGroup?->name  ?? $j->studyGroup?->nama  ?? '—';
                    $j->_ruangan     = $j->room ?? $j->ruangan ?? null;
                    $j->_kelas_id    = $j->study_group_id ?? null;
                    $j->_sumber      = 'Timetable';
                    $j->_warna       = $j->studySubject?->color ?? null;
                    return $j;
                });
        } catch (\Exception $e) {
            $jadwalHariIni = collect();
        }

        /* ═══════════════════════════════════════════════════════
           10. REKAP ABSENSI WALI KELAS
        ═══════════════════════════════════════════════════════ */
        $siswaRekapDashboard = collect();
        $rekapDataDashboard  = [];
        $rekapBulan = $request->input('rekap_bulan', $bulanIni);
        $rekapTahun = $request->input('rekap_tahun', $tahunIni);

        if ($isWaliKelas && $kelasWaliData) {
            try {
                // Ambil siswa wali kelas
                $siswaRekapDashboard = Siswa::where('study_group_id', $kelasWaliData->id)
                    ->orderBy('nama')->get();

                // Fallback jika kolom berbeda
                if ($siswaRekapDashboard->isEmpty()) {
                    $siswaRekapDashboard = Siswa::where('kelas_id', $kelasWaliData->id)
                        ->orderBy('nama')->get();
                }

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
                            ")->first();
                    } catch (\Exception $e) { $r = null; }

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

        /* ═══════════════════════════════════════════════════════
           RETURN VIEW
        ═══════════════════════════════════════════════════════ */
    return view('guru.dashboard', compact(
        'siswaBerisiko', 'chartLabels', 'chartHadir', 'chartTidak',
        'jadwalHariIni', 'siswaRekapDashboard', 'rekapDataDashboard',
        'rekapBulan', 'rekapTahun', 'widgetPengumuman'
    ));
    }
}