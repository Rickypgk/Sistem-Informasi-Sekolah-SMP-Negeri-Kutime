<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\Pengumuman;
use App\Models\Siswa;
use App\Models\AbsensiSiswa;
use App\Models\Timetable;
use App\Models\Kelas; // Tambahkan jika belum ada
use App\Models\JadwalMengajar; // Tambahkan jika ada tabel jadwal_mengajar
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        // Pengumuman untuk widget
        $widgetPengumuman = Pengumuman::where('is_active', 1)
            ->where('show_di_dashboard', 1)
            ->whereIn('target_audience', ['guru', 'semua'])
            ->latest()
            ->limit(4)
            ->get();

        $user = Auth::user();
        $guru = $user->guru; // relasi dari User ke model Guru

        // Default values jika belum ada data guru
        if (!$guru) {
            return view('guru.dashboard', compact('widgetPengumuman'))
                ->with([
                    'totalSiswa'         => 0,
                    'kehadiranPct'       => 0,
                    'rataNilai'          => 0,
                    'siswaRisiko'        => 0,
                    'absensiHariIni'     => ['hadir' => 0, 'sakit' => 0, 'izin' => 0, 'alpha' => 0, 'terlambat' => 0],
                    'chartLabels'        => [],
                    'chartHadir'         => [],
                    'chartTidak'         => [],
                    'siswaBerisiko'      => collect(),
                    'jadwalHariIni'      => collect(),
                    'isWaliKelas'        => false,
                    'kelasWaliData'      => null,
                    'namaKelasWali'      => null,
                    'totalSiswaWali'     => 0,
                    'siswaRekapDashboard'=> collect(),
                    'rekapDataDashboard' => [],
                    'rekapBulan'         => Carbon::now()->month,
                    'rekapTahun'         => Carbon::now()->year,
                ]);
        }

        // ── Wali Kelas check ──────────────────────────────────────────────
        $isWaliKelas = method_exists($user, 'isWaliKelas') ? $user->isWaliKelas() : false;
        $kelasWaliData = null;
        $namaKelasWali = null;
        $totalSiswaWali = 0;

        if ($isWaliKelas) {
            // Cek dari relasi waliKelas atau tabel kelas
            $kelasWaliData = $guru->waliKelas?->kelas ?? Kelas::where('wali_guru_id', $guru->id)->first();
            $namaKelasWali = $kelasWaliData?->nama ?? null;
            $totalSiswaWali = $kelasWaliData ? Siswa::where('kelas_id', $kelasWaliData->id)->count() : 0;
        }

        // ── Ambil kelas yang diajar guru ini ──────────────────────────────
        $kelasIds = [];
        if (class_exists('App\Models\JadwalMengajar')) {
            $kelasIds = JadwalMengajar::where('guru_id', $guru->id)
                ->pluck('kelas_id')
                ->unique()
                ->toArray();
        } else {
            // Fallback ke kelas wali kelas atau kelas_id guru
            $kelasIds = $kelasWaliData ? [$kelasWaliData->id] : ($guru->kelas_id ? [$guru->kelas_id] : []);
        }

        $siswaIds = Siswa::whereIn('kelas_id', $kelasIds)->pluck('id');

        // ── KPI: Total siswa yang diajar ──────────────────────────────────
        $totalSiswa = $siswaIds->count();

        // ── KPI: Kehadiran bulan ini ──────────────────────────────────────
        $bulanIni = Carbon::now()->startOfMonth();
        $bulanIniMonth = Carbon::now()->month;
        $tahunIni = Carbon::now()->year;

        $totalHariEfektif = AbsensiSiswa::whereIn('siswa_id', $siswaIds)
            ->whereDate('tanggal', '>=', $bulanIni)
            ->distinct('tanggal')->count('tanggal');

        $hadirBulanIni = AbsensiSiswa::whereIn('siswa_id', $siswaIds)
            ->whereDate('tanggal', '>=', $bulanIni)
            ->where('status', 'hadir')->count();

        $kehadiranPct = ($totalHariEfektif > 0 && $totalSiswa > 0) 
            ? round(($hadirBulanIni / ($totalHariEfektif * $totalSiswa)) * 100, 1) 
            : 0;

        // ── KPI: Absensi hari ini ─────────────────────────────────────────
        $absensiHariIni = [
            'hadir'     => AbsensiSiswa::whereIn('siswa_id', $siswaIds)
                ->whereDate('tanggal', Carbon::today())
                ->where('status', 'hadir')->count(),
            'sakit'     => AbsensiSiswa::whereIn('siswa_id', $siswaIds)
                ->whereDate('tanggal', Carbon::today())
                ->where('status', 'sakit')->count(),
            'izin'      => AbsensiSiswa::whereIn('siswa_id', $siswaIds)
                ->whereDate('tanggal', Carbon::today())
                ->where('status', 'izin')->count(),
            'alpha'     => AbsensiSiswa::whereIn('siswa_id', $siswaIds)
                ->whereDate('tanggal', Carbon::today())
                ->where('status', 'alpha')->count(),
            'terlambat' => AbsensiSiswa::whereIn('siswa_id', $siswaIds)
                ->whereDate('tanggal', Carbon::today())
                ->where('status', 'terlambat')->count(),
        ];

        // ── KPI: Rata-rata nilai (placeholder) ───────────────────────────
        $rataNilai = 78.5; // Ganti dengan query Nilai jika ada

        // ── KPI: Siswa berisiko ───────────────────────────────────────────
        $siswaBerisiko = collect();
        $siswaRisiko = 0;

        if ($totalSiswa > 0) {
            $siswaBerisiko = Siswa::whereIn('id', $siswaIds)
                ->with('kelas', 'user')
                ->get()
                ->map(function ($siswa) use ($bulanIni) {
                    $absensi = AbsensiSiswa::where('siswa_id', $siswa->id)
                        ->whereDate('tanggal', '>=', $bulanIni)
                        ->selectRaw("
                            SUM(CASE WHEN status='hadir' THEN 1 ELSE 0 END) as hadir,
                            SUM(CASE WHEN status='sakit' THEN 1 ELSE 0 END) as sakit,
                            SUM(CASE WHEN status='izin' THEN 1 ELSE 0 END) as izin,
                            SUM(CASE WHEN status='alpha' THEN 1 ELSE 0 END) as alpha,
                            SUM(CASE WHEN status='terlambat' THEN 1 ELSE 0 END) as terlambat,
                            COUNT(*) as total
                        ")
                        ->first();

                    $total = $absensi->total ?? 0;
                    $kehadiran = $total > 0 ? round(($absensi->hadir / $total) * 100, 1) : 0;
                    
                    $siswa->kehadiran = $kehadiran;
                    $siswa->alpha = $absensi->alpha ?? 0;
                    $siswa->terlambat_count = $absensi->terlambat ?? 0;
                    $siswa->nilai_rata = 0; // Ganti dengan query Nilai jika ada
                    $siswa->kelas_nama = $siswa->kelas?->nama ?? '';

                    return $siswa;
                })
                ->filter(fn($s) => $s->kehadiran < 75 || $s->alpha > 3 || $s->terlambat_count > 3)
                ->sortBy('kehadiran')
                ->take(8)
                ->values();

            $siswaRisiko = $siswaBerisiko->count();
        }

        // ── Chart: Tren 7 hari terakhir ───────────────────────────────────
        $chartLabels = [];
        $chartHadir = [];
        $chartTidak = [];
        for ($i = 6; $i >= 0; $i--) {
            $tgl = Carbon::today()->subDays($i);
            $chartLabels[] = $tgl->locale('id')->isoFormat('ddd, D/M');
            
            $hadir = AbsensiSiswa::whereIn('siswa_id', $siswaIds)
                ->whereDate('tanggal', $tgl)
                ->where('status', 'hadir')->count();
            $total = AbsensiSiswa::whereIn('siswa_id', $siswaIds)
                ->whereDate('tanggal', $tgl)->count();
            
            $chartHadir[] = $hadir;
            $chartTidak[] = $total - $hadir;
        }

        // ── Jadwal mengajar hari ini ──────────────────────────────────────
        $hariIniNama = Carbon::now()->locale('id')->translatedFormat('l'); // Senin, Selasa, dst.
        
        $jadwalHariIni = Timetable::with(['studySubject', 'studyGroup'])
            ->where('teacher_id', $user->id)
            ->where('day_of_week', $hariIniNama)
            ->orderBy('start_time')
            ->get();

        // Fallback jika ada JadwalMengajar
        if (class_exists('App\Models\JadwalMengajar') && $jadwalHariIni->isEmpty()) {
            $hariIniLower = strtolower(Carbon::now()->isoFormat('dddd'));
            $jadwalHariIni = JadwalMengajar::with(['kelas', 'mataPelajaran'])
                ->where('guru_id', $guru->id)
                ->where('hari', $hariIniLower)
                ->orderBy('jam_mulai')
                ->get();
        }

        // ── Rekap absensi wali kelas (dashboard) ─────────────────────────
        $siswaRekapDashboard = collect();
        $rekapDataDashboard = [];
        $rekapBulan = $request->input('rekap_bulan', $bulanIniMonth);
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
                        SUM(CASE WHEN status='alpha' THEN 1 ELSE 0 END) as alpha,
                        SUM(CASE WHEN status='terlambat' THEN 1 ELSE 0 END) as terlambat
                    ")
                    ->first();
                
                $rekapDataDashboard[$siswa->id] = [
                    'hadir'     => $r->hadir ?? 0,
                    'sakit'     => $r->sakit ?? 0,
                    'izin'      => $r->izin ?? 0,
                    'alpha'     => $r->alpha ?? 0,
                    'terlambat' => $r->terlambat ?? 0,
                ];
            }
        }

        return view('guru.dashboard', compact(
            // KPI
            'totalSiswa',
            'kehadiranPct',
            'rataNilai',
            'siswaRisiko',
            'absensiHariIni',
            // Wali kelas
            'isWaliKelas',
            'kelasWaliData',
            'namaKelasWali',
            'totalSiswaWali',
            // Siswa berisiko
            'siswaBerisiko',
            // Chart
            'chartLabels',
            'chartHadir',
            'chartTidak',
            // Jadwal
            'jadwalHariIni',
            // Rekap absensi wali kelas
            'siswaRekapDashboard',
            'rekapDataDashboard',
            'rekapBulan',
            'rekapTahun',
            // Widget
            'widgetPengumuman'
        ));
    }
}