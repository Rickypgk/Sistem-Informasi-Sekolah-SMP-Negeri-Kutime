<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\Pengumuman;
use App\Models\Siswa;
use App\Models\AbsensiSiswa;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // 1. Pengumuman untuk widget dashboard (tetap sama)
        $widgetPengumuman = Pengumuman::where('is_active', 1)
            ->where('show_di_dashboard', 1)
            ->whereIn('target_audience', ['guru', 'semua'])
            ->latest()
            ->limit(4)
            ->get();

        // Ambil data guru yang sedang login
        $guru = Auth::user()->guru;

        // Jika guru belum ditugaskan sebagai wali kelas
        if (!$guru || !$guru->kelas_id) {
            return view('guru.dashboard', compact('widgetPengumuman'))
                ->with([
                    'totalSiswa'       => 0,
                    'kehadiranPct'     => 0,
                    'rataNilai'        => 0,
                    'siswaRisiko'      => 0,
                    'chartKehadiran'   => [0, 0, 0, 0, 0, 0, 0],
                    'siswaBerisiko'    => collect(),
                ]);
        }

        $kelasId = $guru->kelas_id;

        // Ambil semua ID siswa di kelas wali tersebut
        $siswaIds = Siswa::where('kelas_id', $kelasId)->pluck('id');

        // 2. Total siswa di kelas
        $totalSiswa = $siswaIds->count();

        // 3. Bulan berjalan
        $bulanIni = Carbon::now()->startOfMonth();

        // 4. Hitung persentase kehadiran bulan ini
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

        // 5. Rata-rata nilai kelas (placeholder — sesuaikan dengan tabel nilai Anda)
        $rataNilai = 78.5; // GANTI DENGAN QUERY REAL, contoh:
        // $rataNilai = Nilai::whereIn('siswa_id', $siswaIds)->avg('nilai_akhir') ?? 0;

        // 6. Siswa berisiko: kehadiran <75% ATAU terlambat >3 kali
        $siswaBerisiko = collect();
        $siswaRisiko = 0;

        if ($totalSiswa > 0) {
            $siswaBerisiko = Siswa::whereIn('id', $siswaIds)
                ->with(['user'])
                ->get()
                ->map(function ($siswa) use ($bulanIni) {
                    // Hitung persentase kehadiran
                    $hadir = AbsensiSiswa::where('siswa_id', $siswa->id)
                        ->whereDate('tanggal', '>=', $bulanIni)
                        ->where('status', 'hadir')
                        ->count();

                    $totalHariSiswa = AbsensiSiswa::where('siswa_id', $siswa->id)
                        ->whereDate('tanggal', '>=', $bulanIni)
                        ->distinct('tanggal')
                        ->count('tanggal') ?: 1;

                    $siswa->kehadiran = round(($hadir / $totalHariSiswa) * 100, 1);

                    // Hitung jumlah TERLAMBAT bulan ini
                    $siswa->terlambat_count = AbsensiSiswa::where('siswa_id', $siswa->id)
                        ->whereDate('tanggal', '>=', $bulanIni)
                        ->where('status', 'terlambat') // asumsi kolom status sudah punya 'terlambat'
                        ->count();

                    // Nilai rata-rata (placeholder — ganti dengan query real jika ada tabel nilai)
                    $siswa->nilai_rata = 75.0;

                    return $siswa;
                })
                ->filter(function ($s) {
                    // Siswa dianggap berisiko jika:
                    // - Kehadiran < 75% ATAU
                    // - Terlambat lebih dari 3 kali
                    return $s->kehadiran < 75 || $s->terlambat_count > 3;
                })
                ->sortByDesc('terlambat_count') // Prioritas: yang paling sering terlambat di atas
                ->take(8); // Maksimal 8 siswa ditampilkan di dashboard

            $siswaRisiko = $siswaBerisiko->count();
        }

        // 7. Chart kehadiran 7 hari terakhir
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

        // Kirim semua data ke view dashboard
        return view('guru.dashboard', compact(
            'widgetPengumuman',
            'totalSiswa',
            'kehadiranPct',
            'rataNilai',
            'siswaRisiko',
            'chartKehadiran',
            'siswaBerisiko'
        ));
    }
}