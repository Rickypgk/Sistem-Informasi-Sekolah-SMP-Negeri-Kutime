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
        $guru = $user->guru;

        $bulanIni = Carbon::now()->month;
        $tahunIni = Carbon::now()->year;

        /* ===================== DEFAULT VARIABLE ===================== */
        $isWaliKelas = false;
        $kelasWaliData = null;
        $namaKelasWali = null;
        $totalSiswaWali = 0;

        $siswaBerisiko = collect();
        $chartLabels = [];
        $chartHadir = [];
        $chartTidak = [];
        $jadwalHariIni = collect();
        $siswaRekapDashboard = collect();
        $rekapDataDashboard = [];

        /* ===================== PENGUMUMAN ===================== */
        $widgetPengumuman = Pengumuman::latest()->limit(4)->get();

        /* ===================== WALI KELAS ===================== */
        try {
            $kelasWaliData = StudyGroup::where('homeroom_teacher_id', $user->id)
                ->where('is_active', 1)
                ->first();

            $isWaliKelas = !is_null($kelasWaliData);

            if ($isWaliKelas) {
                $namaKelasWali = $kelasWaliData->name ?? null;

                $totalSiswaWali = Siswa::where('study_group_id', $kelasWaliData->id)->count();
                if ($totalSiswaWali === 0) {
                    $totalSiswaWali = Siswa::where('kelas_id', $kelasWaliData->id)->count();
                }
            }
        } catch (\Exception $e) {}

        /* ===================== DATA UTAMA ===================== */
        $studyGroupIds = Timetable::where('teacher_id', $user->id)
            ->pluck('study_group_id')->filter()->unique();

        if ($kelasWaliData) {
            $studyGroupIds->push($kelasWaliData->id);
        }

        $siswaIds = Siswa::whereIn('study_group_id', $studyGroupIds)->pluck('id');

        if ($siswaIds->isEmpty()) {
            $siswaIds = Siswa::whereIn('kelas_id', $studyGroupIds)->pluck('id');
        }

        $totalSiswa = $siswaIds->count();

        /* ===================== KPI ===================== */
        $kehadiranPct = 0;
        if ($siswaIds->isNotEmpty()) {
            $total = AbsensiSiswa::whereIn('siswa_id', $siswaIds)
                ->whereMonth('tanggal', $bulanIni)
                ->whereYear('tanggal', $tahunIni)
                ->count();

            $hadir = AbsensiSiswa::whereIn('siswa_id', $siswaIds)
                ->whereMonth('tanggal', $bulanIni)
                ->whereYear('tanggal', $tahunIni)
                ->where('status', 'hadir')
                ->count();

            $kehadiranPct = $total > 0 ? round(($hadir / $total) * 100, 1) : 0;
        }

        /* ===================== ABSENSI HARI INI ===================== */
        $today = Carbon::today();
        $absensiHariIni = [
            'hadir' => AbsensiSiswa::whereIn('siswa_id', $siswaIds)->whereDate('tanggal', $today)->where('status','hadir')->count(),
            'sakit' => AbsensiSiswa::whereIn('siswa_id', $siswaIds)->whereDate('tanggal', $today)->where('status','sakit')->count(),
            'izin'  => AbsensiSiswa::whereIn('siswa_id', $siswaIds)->whereDate('tanggal', $today)->where('status','izin')->count(),
            'alpha' => AbsensiSiswa::whereIn('siswa_id', $siswaIds)->whereDate('tanggal', $today)->where('status','alpha')->count(),
        ];

        /* ===================== CHART ===================== */
        for ($i = 6; $i >= 0; $i--) {
            $tgl = Carbon::today()->subDays($i);
            $chartLabels[] = $tgl->format('d/m');

            $h = AbsensiSiswa::whereIn('siswa_id', $siswaIds)->whereDate('tanggal', $tgl)->where('status','hadir')->count();
            $t = AbsensiSiswa::whereIn('siswa_id', $siswaIds)->whereDate('tanggal', $tgl)->count();

            $chartHadir[] = $h;
            $chartTidak[] = $t - $h;
        }

        /* ===================== JADWAL ===================== */
        $hariMap = [
            'Sunday'=>'Minggu','Monday'=>'Senin','Tuesday'=>'Selasa',
            'Wednesday'=>'Rabu','Thursday'=>'Kamis','Friday'=>'Jumat','Saturday'=>'Sabtu',
        ];

        $hariHariIni = $hariMap[Carbon::now()->format('l')];

        $jadwalHariIni = Timetable::where('teacher_id', $user->id)
            ->where('day_of_week', $hariHariIni)
            ->get();

        /* ===================== REKAP ===================== */
        $rekapBulan = $request->input('rekap_bulan', $bulanIni);
        $rekapTahun = $request->input('rekap_tahun', $tahunIni);

        if ($isWaliKelas && $kelasWaliData) {
            $siswaRekapDashboard = Siswa::where('study_group_id', $kelasWaliData->id)->get();

            foreach ($siswaRekapDashboard as $siswa) {
                $r = AbsensiSiswa::where('siswa_id', $siswa->id)
                    ->whereMonth('tanggal', $rekapBulan)
                    ->whereYear('tanggal', $rekapTahun)
                    ->selectRaw("
                        SUM(CASE WHEN status='hadir' THEN 1 ELSE 0 END) as hadir,
                        SUM(CASE WHEN status='sakit' THEN 1 ELSE 0 END) as sakit,
                        SUM(CASE WHEN status='izin' THEN 1 ELSE 0 END) as izin,
                        SUM(CASE WHEN status='alpha' THEN 1 ELSE 0 END) as alpha
                    ")->first();

                $rekapDataDashboard[$siswa->id] = [
                    'hadir' => $r->hadir ?? 0,
                    'sakit' => $r->sakit ?? 0,
                    'izin'  => $r->izin ?? 0,
                    'alpha' => $r->alpha ?? 0,
                ];
            }
        }

        /* ===================== RETURN ===================== */
        return view('guru.dashboard', compact(
            'totalSiswa','kehadiranPct','absensiHariIni',
            'siswaBerisiko','chartLabels','chartHadir','chartTidak',
            'jadwalHariIni','siswaRekapDashboard','rekapDataDashboard',
            'rekapBulan','rekapTahun','widgetPengumuman',
            'isWaliKelas','kelasWaliData','namaKelasWali','totalSiswaWali'
        ));
    }
}