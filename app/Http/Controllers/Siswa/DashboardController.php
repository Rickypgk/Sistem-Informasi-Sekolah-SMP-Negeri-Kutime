<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\Pengumuman;
use App\Models\Timetable;   // sesuaikan namespace model jadwal Anda
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $user      = Auth::user();
        $siswa     = $user->siswa; // relasi ke model Siswa
        $studyGroup = $siswa?->studyGroup; // kelas siswa

        /* ── Widget Pengumuman ───────────────────────────── */
        $widgetPengumuman = Pengumuman::where('is_active', 1)
            ->where('show_di_dashboard', 1)
            ->whereIn('target_audience', ['siswa', 'semua'])
            ->latest()
            ->limit(4)
            ->get();

        /* ── Jadwal Hari ini ─────────────────────────────── */
        $hariIni = Carbon::now()->locale('id')->isoFormat('dddd'); // Senin, Selasa, …
        // Mapping isoFormat -> nilai day_of_week di DB
        $hariMap = [
            'Senin'  => 'Senin',  'Selasa' => 'Selasa',
            'Rabu'   => 'Rabu',   'Kamis'  => 'Kamis',
            'Jumat'  => 'Jumat',  'Sabtu'  => 'Sabtu',
        ];
        $hariIniDb = $hariMap[$hariIni] ?? null;

        /* ── Jadwal Minggu Ini (semua hari) ──────────────── */
        $allTimetables = collect();
        $jadwalByDay   = collect();
        $jadwalHariIni = collect();

        if ($studyGroup) {
            $allTimetables = Timetable::with(['studySubject', 'teacher'])
                ->where('study_group_id', $studyGroup->id)
                ->get();

            $jadwalByDay = $allTimetables
                ->groupBy('day_of_week')
                ->map(fn($items) => $items->sortBy('start_time'));

            $jadwalHariIni = $hariIniDb
                ? ($jadwalByDay[$hariIniDb] ?? collect())->sortBy('start_time')
                : collect();
        }

        /* ── KPI ──────────────────────────────────────────── */
        $totalJadwal      = $allTimetables->count();
        $totalMapel       = $allTimetables->pluck('study_subject_id')->unique()->count();
        $totalGuru        = $allTimetables->pluck('teacher_id')->unique()->count();
        $hariAktif        = $jadwalByDay->filter(fn($j) => $j->isNotEmpty())->count();
        $totalJamPerMinggu = 0;
        foreach ($allTimetables as $tt) {
            $start = Carbon::createFromTimeString($tt->start_time);
            $end   = Carbon::createFromTimeString($tt->end_time);
            $totalJamPerMinggu += $start->diffInMinutes($end);
        }
        $totalJamPerMinggu = round($totalJamPerMinggu / 60, 1);

        /* ── Jadwal hari berikutnya (besok) ─────────────── */
        $hariList = ['Senin','Selasa','Rabu','Kamis','Jumat','Sabtu'];
        $idxHariIni = array_search($hariIniDb, $hariList);
        $hariBerikutnya = null;
        $jadwalBerikutnya = collect();
        if ($idxHariIni !== false) {
            for ($i = 1; $i <= 5; $i++) {
                $kandidat = $hariList[($idxHariIni + $i) % count($hariList)] ?? null;
                if ($kandidat && isset($jadwalByDay[$kandidat]) && $jadwalByDay[$kandidat]->isNotEmpty()) {
                    $hariBerikutnya   = $kandidat;
                    $jadwalBerikutnya = $jadwalByDay[$kandidat];
                    break;
                }
            }
        }

        return view('siswa.dashboard', compact(
            'widgetPengumuman',
            'studyGroup',
            'allTimetables',
            'jadwalByDay',
            'jadwalHariIni',
            'jadwalBerikutnya',
            'hariBerikutnya',
            'hariIni',
            'hariIniDb',
            'totalJadwal',
            'totalMapel',
            'totalGuru',
            'hariAktif',
            'totalJamPerMinggu',
        ));
    }
}