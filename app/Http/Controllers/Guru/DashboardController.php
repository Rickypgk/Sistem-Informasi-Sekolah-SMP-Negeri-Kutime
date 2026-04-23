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
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
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
        $widgetPengumuman = collect();
        try {
            $widgetPengumuman = Pengumuman::where('is_active', 1)
                ->where('show_di_dashboard', 1)
                ->whereIn('target_audience', ['guru', 'semua'])
                ->latest()
                ->limit(4)
                ->get();
        } catch (\Exception $e) {
            // kolom mungkin berbeda, coba tanpa filter tambahan
            try {
                $widgetPengumuman = Pengumuman::latest()->limit(4)->get();
            } catch (\Exception $e2) {
                $widgetPengumuman = collect();
            }
        }

        /* =====================================================
           DEFAULT JIKA TIDAK ADA GURU
        ===================================================== */
        if (!$guru) {
            return view('guru.dashboard', compact('widgetPengumuman'))
                ->with([
                    'totalSiswa'          => 0,
                    'kehadiranPct'        => 0,
                    'rataNilai'           => 0,
                    'siswaRisiko'         => 0,
                    'absensiHariIni'      => ['hadir' => 0, 'sakit' => 0, 'izin' => 0, 'alpha' => 0],
                    'chartLabels'         => [],
                    'chartHadir'          => [],
                    'chartTidak'          => [],
                    'siswaBerisiko'       => collect(),
                    'jadwalHariIni'       => collect(),
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

        /* =====================================================
           2. WALI KELAS
        ===================================================== */
        $isWaliKelas    = false;
        $kelasWaliData  = null;
        $namaKelasWali  = null;
        $totalSiswaWali = 0;

        try {
            // Cek apakah guru adalah wali kelas
            $isWaliKelas = Kelas::where('wali_guru_id', $guru->id)->exists()
                || Kelas::where('wali_kelas_id', $guru->id)->exists()
                || (method_exists($user, 'isWaliKelas') && $user->isWaliKelas());

            if ($isWaliKelas) {
                $kelasWaliData = Kelas::where('wali_guru_id', $guru->id)->first()
                    ?? Kelas::where('wali_kelas_id', $guru->id)->first()
                    ?? $guru->waliKelas?->kelas
                    ?? null;

                $namaKelasWali  = $kelasWaliData?->nama ?? $kelasWaliData?->name ?? null;
                $totalSiswaWali = $kelasWaliData
                    ? Siswa::where('kelas_id', $kelasWaliData->id)->count()
                    : 0;
            }
        } catch (\Exception $e) {
            $isWaliKelas = false;
        }

        /* =====================================================
           3. KELAS / SISWA YANG DIAJAR
           Support berbagai struktur tabel assignment
        ===================================================== */
        $kelasIds = collect();

        try {
            // Coba StudyClassAssignment dengan teacher_id = user->id
            $kelasIds = StudyClassAssignment::where('teacher_id', $user->id)
                ->pluck('study_group_id')
                ->merge(
                    StudyClassAssignment::where('teacher_id', $user->id)
                        ->pluck('kelas_id')
                        ->filter()
                )
                ->unique()
                ->filter();
        } catch (\Exception $e) {}

        // Fallback: coba dengan guru->id
        if ($kelasIds->isEmpty() && $guru) {
            try {
                $kelasIds = StudyClassAssignment::where('teacher_id', $guru->id)
                    ->pluck('study_group_id')
                    ->merge(
                        StudyClassAssignment::where('teacher_id', $guru->id)
                            ->pluck('kelas_id')
                            ->filter()
                    )
                    ->unique()
                    ->filter();
            } catch (\Exception $e) {}
        }

        // Fallback: coba dari Timetable
        if ($kelasIds->isEmpty()) {
            try {
                $kelasIds = Timetable::where('teacher_id', $user->id)
                    ->pluck('study_group_id')
                    ->merge(
                        Timetable::where('teacher_id', $user->id)->pluck('kelas_id')->filter()
                    )
                    ->unique()
                    ->filter();
            } catch (\Exception $e) {}
        }

        // Sertakan kelas wali jika ada
        if ($kelasWaliData) {
            $kelasIds = $kelasIds->push($kelasWaliData->id)->unique()->filter();
        }

        // Ambil siswa
        $siswaIds = collect();
        if ($kelasIds->isNotEmpty()) {
            try {
                $siswaIds = Siswa::whereIn('kelas_id', $kelasIds)->pluck('id');
            } catch (\Exception $e) {
                $siswaIds = collect();
            }
        }

        $totalSiswa = $siswaIds->count();

        /* =====================================================
           4. KPI KEHADIRAN BULAN INI
        ===================================================== */
        $bulanIni = Carbon::now()->month;
        $tahunIni = Carbon::now()->year;

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

        /* =====================================================
           5. ABSENSI HARI INI
        ===================================================== */
        $today         = Carbon::today();
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

        /* =====================================================
           6. SISWA BERISIKO (kehadiran < 75%)
        ===================================================== */
        $siswaBerisiko = collect();
        $siswaRisiko   = 0;

        if ($siswaIds->isNotEmpty()) {
            try {
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

                        $tot      = $r->total ?? 0;
                        $kehadiran = $tot > 0 ? round(($r->hadir / $tot) * 100, 1) : 0;

                        $s->kehadiran  = $kehadiran;
                        $s->alpha      = $r->alpha ?? 0;
                        $s->namaKelas  = $s->kelas?->nama ?? $s->kelas?->name ?? '—';

                        return $s;
                    })
                    ->filter(fn($s) => $s->kehadiran < 75)
                    ->sortBy('kehadiran')
                    ->take(8)
                    ->values();

                $siswaRisiko = $siswaBerisiko->count();
            } catch (\Exception $e) {}
        }

        /* =====================================================
           7. CHART 7 HARI TERAKHIR
        ===================================================== */
        $chartLabels = [];
        $chartHadir  = [];
        $chartTidak  = [];

        for ($i = 6; $i >= 0; $i--) {
            $tgl = Carbon::today()->subDays($i);

            $chartLabels[] = $tgl->locale('id')->isoFormat('ddd D/M');

            if ($siswaIds->isNotEmpty()) {
                try {
                    $hadir = AbsensiSiswa::whereIn('siswa_id', $siswaIds)
                        ->whereDate('tanggal', $tgl)
                        ->where('status', 'hadir')
                        ->count();

                    $total = AbsensiSiswa::whereIn('siswa_id', $siswaIds)
                        ->whereDate('tanggal', $tgl)
                        ->count();

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

        /* =====================================================
           8. JADWAL HARI INI
           Support: start_time/end_time, jam_mulai/jam_selesai
           Relasi: studySubject/studyGroup atau mataPelajaran/kelas
        ===================================================== */
        $hariMap = [
            'Sunday'    => 'Minggu',
            'Monday'    => 'Senin',
            'Tuesday'   => 'Selasa',
            'Wednesday' => 'Rabu',
            'Thursday'  => 'Kamis',
            'Friday'    => 'Jumat',
            'Saturday'  => 'Sabtu',
        ];
        $hariIni = $hariMap[Carbon::now()->format('l')];

        $jadwalHariIni = collect();

        try {
            // Coba dengan relasi yang ada di model Timetable
            $relations = [];
            $timetableInstance = new Timetable();

            // Cek relasi yang tersedia
            foreach (['studySubject', 'studyGroup', 'mataPelajaran', 'kelas', 'subject', 'class'] as $rel) {
                if (method_exists($timetableInstance, $rel)) {
                    $relations[] = $rel;
                }
            }

            $query = Timetable::query();

            if (!empty($relations)) {
                $query->with($relations);
            }

            // Filter teacher
            $jadwalHariIni = $query
                ->where('teacher_id', $user->id)
                ->where(function ($q) use ($hariIni) {
                    $q->where('day_of_week', $hariIni)
                      ->orWhere('hari', $hariIni)
                      ->orWhere('day', $hariIni);
                })
                ->orderByRaw("COALESCE(start_time, jam_mulai) ASC")
                ->get();

            // Normalisasi field agar blade bisa baca konsisten
            $jadwalHariIni = $jadwalHariIni->map(function ($j) {
                // Normalisasi jam
                $j->jam_mulai   = $j->start_time   ?? $j->jam_mulai   ?? null;
                $j->jam_selesai = $j->end_time      ?? $j->jam_selesai ?? null;

                // Normalisasi relasi mata pelajaran
                if (!isset($j->mataPelajaran) || !$j->mataPelajaran) {
                    $mapel = $j->studySubject ?? $j->subject ?? $j->pelajaran ?? null;
                    $j->mataPelajaran = $mapel;
                }

                // Normalisasi relasi kelas / study group
                if (!isset($j->kelas) || !$j->kelas) {
                    $kls = $j->studyGroup ?? $j->class ?? $j->group ?? null;
                    $j->kelas = $kls;
                }

                // Normalisasi kelas_id untuk link absensi
                if (!$j->kelas_id) {
                    $j->kelas_id = $j->study_group_id ?? ($j->kelas?->id ?? null);
                }

                return $j;
            });

        } catch (\Exception $e) {
            // Fallback query minimal
            try {
                $jadwalHariIni = Timetable::where('teacher_id', $user->id)
                    ->where(function ($q) use ($hariIni) {
                        $q->where('day_of_week', $hariIni)
                          ->orWhere('hari', $hariIni);
                    })
                    ->orderByRaw("COALESCE(start_time, jam_mulai) ASC")
                    ->get()
                    ->map(function ($j) {
                        $j->jam_mulai   = $j->start_time   ?? $j->jam_mulai   ?? null;
                        $j->jam_selesai = $j->end_time      ?? $j->jam_selesai ?? null;
                        return $j;
                    });
            } catch (\Exception $e2) {
                $jadwalHariIni = collect();
            }
        }

        /* =====================================================
           9. REKAP WALI KELAS
        ===================================================== */
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

                    $rekapDataDashboard[$siswa->id] = [
                        'hadir' => $r->hadir ?? 0,
                        'sakit' => $r->sakit ?? 0,
                        'izin'  => $r->izin  ?? 0,
                        'alpha' => $r->alpha ?? 0,
                    ];
                }
            } catch (\Exception $e) {
                $siswaRekapDashboard = collect();
                $rekapDataDashboard  = [];
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