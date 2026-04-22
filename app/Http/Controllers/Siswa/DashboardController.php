<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\Pengumuman;
use App\Models\Absensi;         // Sesuaikan dengan nama model absensi Anda
use App\Models\JadwalMengajar;  // Sesuaikan dengan nama model jadwal Anda
use App\Models\Kelas;
use App\Models\Siswa;           // Sesuaikan dengan nama model siswa Anda
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $guru = $user->guru; // relasi ke tabel guru

        // ── Wali Kelas check ──────────────────────────────────────────────
        $isWaliKelas    = $user->isWaliKelas();
        $kelasWaliData  = null;
        $namaKelasWali  = null;
        $totalSiswaWali = 0;

        if ($isWaliKelas) {
            // Sesuaikan relasi sesuai struktur DB Anda
            $kelasWaliData  = $guru?->waliKelas?->kelas ?? Kelas::where('wali_guru_id', $guru?->id)->first();
            $namaKelasWali  = $kelasWaliData?->nama;
            $totalSiswaWali = $kelasWaliData ? Siswa::where('kelas_id', $kelasWaliData->id)->count() : 0;
        }

        // ── KPI: Total siswa yang diajar ──────────────────────────────────
        // Ambil semua kelas yang diajar guru ini (bisa dari jadwal mengajar)
        $kelasIds = JadwalMengajar::where('guru_id', $guru?->id)
            ->pluck('kelas_id')
            ->unique();

        $totalSiswa = Siswa::whereIn('kelas_id', $kelasIds)->count();

        // ── KPI: Kehadiran bulan ini ──────────────────────────────────────
        $bulanIni  = now()->month;
        $tahunIni  = now()->year;

        // Hitung kehadiran: hadir / total hari absensi * 100
        // Sesuaikan query dengan struktur tabel absensi Anda
        $absensiQuery = Absensi::whereIn('kelas_id', $kelasIds)
            ->whereMonth('tanggal', $bulanIni)
            ->whereYear('tanggal', $tahunIni);

        $totalAbsensi = (clone $absensiQuery)->count();
        $totalHadir   = (clone $absensiQuery)->where('status', 'hadir')->count();
        $kehadiranPct = $totalAbsensi > 0 ? round($totalHadir / $totalAbsensi * 100, 1) : 0;

        // ── KPI: Absensi hari ini ─────────────────────────────────────────
        $absensiHariIni = [
            'hadir' => Absensi::whereIn('kelas_id', $kelasIds)->whereDate('tanggal', today())->where('status', 'hadir')->count(),
            'sakit' => Absensi::whereIn('kelas_id', $kelasIds)->whereDate('tanggal', today())->where('status', 'sakit')->count(),
            'izin'  => Absensi::whereIn('kelas_id', $kelasIds)->whereDate('tanggal', today())->where('status', 'izin')->count(),
            'alpha' => Absensi::whereIn('kelas_id', $kelasIds)->whereDate('tanggal', today())->where('status', 'alpha')->count(),
        ];

        // ── KPI: Rata-rata nilai (jika ada tabel nilai) ───────────────────
        // Sesuaikan dengan model Nilai Anda, atau set default jika belum ada
        $rataNilai = 0;
        // Contoh jika ada model Nilai:
        // $rataNilai = \App\Models\Nilai::whereIn('kelas_id', $kelasIds)
        //     ->whereYear('created_at', $tahunIni)
        //     ->avg('nilai') ?? 0;

        // ── KPI: Siswa berisiko ───────────────────────────────────────────
        // Siswa dengan kehadiran < 75% di bulan ini
        $siswaBerisiko = Siswa::whereIn('kelas_id', $kelasIds)
            ->get()
            ->map(function ($s) use ($bulanIni, $tahunIni) {
                $r = Absensi::where('siswa_id', $s->id)
                    ->whereMonth('tanggal', $bulanIni)
                    ->whereYear('tanggal', $tahunIni)
                    ->selectRaw("
                        SUM(CASE WHEN status='hadir' THEN 1 ELSE 0 END) as hadir,
                        SUM(CASE WHEN status='sakit' THEN 1 ELSE 0 END) as sakit,
                        SUM(CASE WHEN status='izin'  THEN 1 ELSE 0 END) as izin,
                        SUM(CASE WHEN status='alpha' THEN 1 ELSE 0 END) as alpha,
                        COUNT(*) as total
                    ")->first();

                $total     = $r?->total ?? 0;
                $kehadiran = $total > 0 ? round($r->hadir / $total * 100, 1) : 0;
                $alpha     = $r?->alpha ?? 0;

                $s->kehadiran = $kehadiran;
                $s->nilai_rata = 0; // Isi dari model Nilai jika ada
                $s->alpha      = $alpha;
                $s->kelas      = $s->kelas?->nama ?? '';

                return $s;
            })
            ->filter(fn($s) => $s->kehadiran < 75 || $s->nilai_rata < 70)
            ->sortBy('kehadiran')
            ->take(8)
            ->values();

        $siswaRisiko = $siswaBerisiko->count();

        // ── Chart: Tren 7 hari terakhir ───────────────────────────────────
        $chartLabels = [];
        $chartHadir  = [];
        $chartTidak  = [];

        for ($i = 6; $i >= 0; $i--) {
            $tgl = now()->subDays($i);
            $chartLabels[] = $tgl->isoFormat('ddd D/M');

            $had  = Absensi::whereIn('kelas_id', $kelasIds)
                        ->whereDate('tanggal', $tgl->format('Y-m-d'))
                        ->where('status', 'hadir')->count();
            $tot  = Absensi::whereIn('kelas_id', $kelasIds)
                        ->whereDate('tanggal', $tgl->format('Y-m-d'))
                        ->count();

            $chartHadir[]  = $had;
            $chartTidak[]  = $tot - $had;
        }

        // ── Jadwal mengajar hari ini ──────────────────────────────────────
        $hariIni = strtolower(now()->isoFormat('dddd')); // senin, selasa, dst
        $jadwalHariIni = JadwalMengajar::with(['kelas', 'mataPelajaran'])
            ->where('guru_id', $guru?->id)
            ->where('hari', $hariIni) // sesuaikan dengan kolom hari di tabel Anda
            ->orderBy('jam_mulai')
            ->get();

        // ── Rekap absensi wali kelas (dashboard) ─────────────────────────
        $siswaRekapDashboard  = collect();
        $rekapDataDashboard   = [];
        $rekapBulan           = $request->input('rekap_bulan', $bulanIni);
        $rekapTahun           = $request->input('rekap_tahun', $tahunIni);

        if ($isWaliKelas && $kelasWaliData) {
            $siswaRekapDashboard = Siswa::where('kelas_id', $kelasWaliData->id)
                ->orderBy('nama')
                ->get();

            foreach ($siswaRekapDashboard as $siswa) {
                $r = Absensi::where('siswa_id', $siswa->id)
                    ->whereMonth('tanggal', $rekapBulan)
                    ->whereYear('tanggal', $rekapTahun)
                    ->selectRaw("
                        SUM(CASE WHEN status='hadir' THEN 1 ELSE 0 END) as hadir,
                        SUM(CASE WHEN status='sakit' THEN 1 ELSE 0 END) as sakit,
                        SUM(CASE WHEN status='izin'  THEN 1 ELSE 0 END) as izin,
                        SUM(CASE WHEN status='alpha' THEN 1 ELSE 0 END) as alpha
                    ")->first();

                $rekapDataDashboard[$siswa->id] = [
                    'hadir' => $r?->hadir ?? 0,
                    'sakit' => $r?->sakit ?? 0,
                    'izin'  => $r?->izin  ?? 0,
                    'alpha' => $r?->alpha ?? 0,
                ];
            }
        }

        // ── Pengumuman widget ─────────────────────────────────────────────
        $widgetPengumuman = Pengumuman::where('is_active', 1)
            ->where('show_di_dashboard', 1)
            ->whereIn('target_audience', ['guru', 'semua'])
            ->latest()
            ->limit(4)
            ->get();

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
            'widgetPengumuman',
        ));
    }
}