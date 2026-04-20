<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\Pengumuman;
use App\Models\Siswa;
use App\Models\AbsensiSiswa;
use App\Models\Timetable;           // Model jadwal mengajar
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // Pengumuman untuk widget
        $widgetPengumuman = Pengumuman::where('is_active', 1)
            ->where('show_di_dashboard', 1)
            ->whereIn('target_audience', ['guru', 'semua'])
            ->latest()
            ->limit(4)
            ->get();

        $guru = Auth::user();

        // Jika tidak ada relasi guru atau belum punya kelas
        if (!$guru || !$guru->guru || !$guru->guru->kelas_id) {
            return view('guru.dashboard', compact('widgetPengumuman'))
                ->with([
                    'totalSiswa'       => 0,
                    'kehadiranPct'     => 0,
                    'rataNilai'        => 0,
                    'siswaRisiko'      => 0,
                    'chartKehadiran'   => [0, 0, 0, 0, 0, 0, 0],
                    'siswaBerisiko'    => collect(),
                    'jadwalHariIni'    => collect(),   // Tambahan baru
                ]);
        }

        $guruModel = $guru->guru;
        $kelasId = $guruModel->kelas_id;

        $siswaIds = Siswa::where('kelas_id', $kelasId)->pluck('id');

        // === Data Lama (Performance, Kehadiran, dll) ===
        $totalSiswa = $siswaIds->count();

        $bulanIni = Carbon::now()->startOfMonth();

        $totalHariEfektif = AbsensiSiswa::whereIn('siswa_id', $siswaIds)
            ->whereDate('tanggal', '>=', $bulanIni)
            ->distinct('tanggal')
            ->count('tanggal');

        $hadirBulanIni = AbsensiSiswa::whereIn('siswa_id', $siswaIds)
            ->whereDate('tanggal', '>=', $bulanIni)
            ->where('status', 'hadir')
            ->count();

        $kehadiranPct = ($totalHariEfektif > 0 && $totalSiswa > 0)
            ? round(($hadirBulanIni / ($totalHariEfektif * $totalSiswa)) * 100, 1)
            : 0;

        $rataNilai = 78.5; // Ganti dengan query real jika ada tabel nilai

        // Siswa Berisiko
        $siswaBerisiko = collect();
        $siswaRisiko = 0;

        if ($totalSiswa > 0) {
            $siswaBerisiko = Siswa::whereIn('id', $siswaIds)
                ->with(['user'])
                ->get()
                ->map(function ($siswa) use ($bulanIni) {
                    $hadir = AbsensiSiswa::where('siswa_id', $siswa->id)
                        ->whereDate('tanggal', '>=', $bulanIni)
                        ->where('status', 'hadir')
                        ->count();

                    $totalHariSiswa = AbsensiSiswa::where('siswa_id', $siswa->id)
                        ->whereDate('tanggal', '>=', $bulanIni)
                        ->distinct('tanggal')
                        ->count('tanggal') ?: 1;

                    $siswa->kehadiran = round(($hadir / $totalHariSiswa) * 100, 1);
                    $siswa->terlambat_count = AbsensiSiswa::where('siswa_id', $siswa->id)
                        ->whereDate('tanggal', '>=', $bulanIni)
                        ->where('status', 'terlambat')
                        ->count();

                    $siswa->nilai_rata = 75.0;

                    return $siswa;
                })
                ->filter(fn($s) => $s->kehadiran < 75 || $s->terlambat_count > 3)
                ->sortByDesc('terlambat_count')
                ->take(8);

            $siswaRisiko = $siswaBerisiko->count();
        }

        // Chart Kehadiran 7 Hari Terakhir
        $chartKehadiran = [];
        for ($i = 6; $i >= 0; $i--) {
            $tgl = Carbon::today()->subDays($i);
            $hadir = AbsensiSiswa::whereIn('siswa_id', $siswaIds)
                ->whereDate('tanggal', $tgl)
                ->where('status', 'hadir')
                ->count();
            $totalHari = AbsensiSiswa::whereIn('siswa_id', $siswaIds)
                ->whereDate('tanggal', $tgl)
                ->count();
            $pct = $totalHari > 0 ? round(($hadir / $totalHari) * 100, 0) : 0;
            $chartKehadiran[] = $pct;
        }

        // === JADWAL MENGAJAR HARI INI (Baru) ===
        $hariIni = Carbon::now()->locale('id')->translatedFormat('l'); // Senin, Selasa, dst.

        $jadwalHariIni = Timetable::with(['studySubject', 'studyGroup'])
            ->where('teacher_id', $guru->id)
            ->where('day_of_week', $hariIni)
            ->orderBy('start_time')
            ->get();

        // Kirim ke view
        return view('guru.dashboard', compact(
            'widgetPengumuman',
            'totalSiswa',
            'kehadiranPct',
            'rataNilai',
            'siswaRisiko',
            'chartKehadiran',
            'siswaBerisiko',
            'jadwalHariIni'          // Data jadwal hari ini
        ));
    }
}