<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\Pengumuman;
use App\Models\Siswa;
use App\Models\AbsensiSiswa;
use App\Models\Timetable;
use App\Models\Kelas;
use App\Models\StudyClassAssignment;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $guru = $user->guru;

        /* =====================================================
           1. WIDGET PENGUMUMAN
        ===================================================== */
        $widgetPengumuman = Pengumuman::where('is_active', 1)
            ->where('show_di_dashboard', 1)
            ->whereIn('target_audience', ['guru', 'semua'])
            ->latest()
            ->limit(4)
            ->get();

        /* =====================================================
           DEFAULT JIKA TIDAK ADA GURU
        ===================================================== */
        if (!$guru) {
            return view('guru.dashboard', compact('widgetPengumuman'))
                ->with([
                    'totalSiswa' => 0,
                    'kehadiranPct' => 0,
                    'rataNilai' => 0,
                    'siswaRisiko' => 0,
                    'absensiHariIni' => ['hadir'=>0,'sakit'=>0,'izin'=>0,'alpha'=>0],
                    'chartLabels' => [],
                    'chartHadir' => [],
                    'chartTidak' => [],
                    'siswaBerisiko' => collect(),
                    'jadwalHariIni' => collect(),
                    'isWaliKelas' => false,
                    'kelasWaliData' => null,
                    'namaKelasWali' => null,
                    'totalSiswaWali' => 0,
                    'siswaRekapDashboard'=> collect(),
                    'rekapDataDashboard'=> [],
                    'rekapBulan'=> Carbon::now()->month,
                    'rekapTahun'=> Carbon::now()->year,
                ]);
        }

        /* =====================================================
           2. WALI KELAS
        ===================================================== */
        $isWaliKelas = method_exists($user, 'isWaliKelas') ? $user->isWaliKelas() : false;

        $kelasWaliData = null;
        $namaKelasWali = null;
        $totalSiswaWali = 0;

        if ($isWaliKelas) {
            $kelasWaliData = $guru->waliKelas?->kelas
                ?? Kelas::where('wali_guru_id', $guru->id)->first();

            $namaKelasWali = $kelasWaliData?->nama;

            $totalSiswaWali = $kelasWaliData
                ? Siswa::where('kelas_id', $kelasWaliData->id)->count()
                : 0;
        }

        /* =====================================================
           3. KELAS DIAJAR
        ===================================================== */
        $kelasIds = JadwalMengajar::where('guru_id', $guru->id)
            ->pluck('kelas_id')
            ->unique()
            ->filter();

        if ($kelasIds->isEmpty() && $kelasWaliData) {
            $kelasIds = collect([$kelasWaliData->id]);
        }

        $siswaIds = Siswa::whereIn('kelas_id', $kelasIds)->pluck('id');

        $totalSiswa = $siswaIds->count();

        /* =====================================================
           4. KPI KEHADIRAN BULAN INI
        ===================================================== */
        $bulanIni = Carbon::now()->month;
        $tahunIni = Carbon::now()->year;

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

        /* =====================================================
           5. ABSENSI HARI INI
        ===================================================== */
        $today = Carbon::today();

        $absensiHariIni = [
            'hadir' => AbsensiSiswa::whereIn('siswa_id', $siswaIds)->whereDate('tanggal', $today)->where('status', 'hadir')->count(),
            'sakit' => AbsensiSiswa::whereIn('siswa_id', $siswaIds)->whereDate('tanggal', $today)->where('status', 'sakit')->count(),
            'izin'  => AbsensiSiswa::whereIn('siswa_id', $siswaIds)->whereDate('tanggal', $today)->where('status', 'izin')->count(),
            'alpha' => AbsensiSiswa::whereIn('siswa_id', $siswaIds)->whereDate('tanggal', $today)->where('status', 'alpha')->count(),
        ];

        /* =====================================================
           6. SISWA BERISIKO
        ===================================================== */
        $siswaBerisiko = collect();

        if ($siswaIds->isNotEmpty()) {
            $siswaBerisiko = Siswa::whereIn('id', $siswaIds)
                ->with('kelas')
                ->get()
                ->map(function ($s) use ($bulanIni, $tahunIni) {

                    $r = AbsensiSiswa::where('siswa_id', $s->id)
                        ->whereMonth('tanggal', $bulanIni)
                        ->whereYear('tanggal', $tahunIni)
                        ->selectRaw("
                            SUM(CASE WHEN status='hadir' THEN 1 ELSE 0 END) as hadir,
                            SUM(CASE WHEN status='alpha' THEN 1 ELSE 0 END) as alpha,
                            COUNT(*) as total
                        ")
                        ->first();

                    $tot = $r->total ?? 0;
                    $kehadiran = $tot > 0 ? round(($r->hadir / $tot) * 100, 1) : 0;

                    $s->kehadiran = $kehadiran;
                    $s->alpha = $r->alpha ?? 0;
                    $s->kelas = $s->kelas?->nama ?? '';

                    return $s;
                })
                ->filter(fn($s) => $s->kehadiran < 75)
                ->sortBy('kehadiran')
                ->take(8)
                ->values();
        }

        $siswaRisiko = $siswaBerisiko->count();

        /* =====================================================
           7. CHART 7 HARI
        ===================================================== */
        $chartLabels = [];
        $chartHadir = [];
        $chartTidak = [];

        for ($i = 6; $i >= 0; $i--) {
            $tgl = Carbon::today()->subDays($i);

            $chartLabels[] = $tgl->locale('id')->isoFormat('ddd D/M');

            $hadir = AbsensiSiswa::whereIn('siswa_id', $siswaIds)
                ->whereDate('tanggal', $tgl)
                ->where('status', 'hadir')->count();

            $total = AbsensiSiswa::whereIn('siswa_id', $siswaIds)
                ->whereDate('tanggal', $tgl)
                ->count();

            $chartHadir[] = $hadir;
            $chartTidak[] = $total - $hadir;
        }

        /* =====================================================
           8. JADWAL HARI INI
        ===================================================== */
        $hariMap = [
            'Sunday'=>'Minggu','Monday'=>'Senin','Tuesday'=>'Selasa',
            'Wednesday'=>'Rabu','Thursday'=>'Kamis','Friday'=>'Jumat','Saturday'=>'Sabtu'
        ];

        $hariIni = $hariMap[Carbon::now()->format('l')];

        $jadwalHariIni = JadwalMengajar::with(['kelas','mataPelajaran'])
            ->where('guru_id', $guru->id)
            ->where('hari', $hariIni)
            ->orderBy('jam_mulai')
            ->get();

        // fallback timetable
        if ($jadwalHariIni->isEmpty()) {
            $jadwalHariIni = Timetable::with(['studySubject','studyGroup'])
                ->where('teacher_id', $user->id)
                ->where('day_of_week', $hariIni)
                ->orderBy('start_time')
                ->get();
        }

        /* =====================================================
           9. REKAP WALI KELAS
        ===================================================== */
        $siswaRekapDashboard = collect();
        $rekapDataDashboard = [];

        $rekapBulan = $request->input('rekap_bulan', $bulanIni);
        $rekapTahun = $request->input('rekap_tahun', $tahunIni);

        if ($isWaliKelas && $kelasWaliData) {
            $siswaRekapDashboard = Siswa::where('kelas_id', $kelasWaliData->id)
                ->orderBy('nama')
                ->get();

            foreach ($siswaRekapDashboard as $siswa) {
                $r = AbsensiSiswa::where('siswa_id', $siswa->id)
                    ->whereMonth('tanggal', $rekapBulan)
                    ->whereYear('tanggal', $rekapTahun)
                    ->selectRaw("
                        SUM(CASE WHEN status='hadir' THEN 1 ELSE 0 END) as hadir,
                        SUM(CASE WHEN status='sakit' THEN 1 ELSE 0 END) as sakit,
                        SUM(CASE WHEN status='izin' THEN 1 ELSE 0 END) as izin,
                        SUM(CASE WHEN status='alpha' THEN 1 ELSE 0 END) as alpha
                    ")
                    ->first();

                $rekapDataDashboard[$siswa->id] = [
                    'hadir' => $r->hadir ?? 0,
                    'sakit' => $r->sakit ?? 0,
                    'izin'  => $r->izin ?? 0,
                    'alpha' => $r->alpha ?? 0,
                ];
            }
        }

        /* =====================================================
           RETURN VIEW
        ===================================================== */
        return view('guru.dashboard', compact(
            'totalSiswa',
            'kehadiranPct',
            'siswaRisiko',
            'absensiHariIni',
            'isWaliKelas',
            'kelasWaliData',
            'namaKelasWali',
            'totalSiswaWali',
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