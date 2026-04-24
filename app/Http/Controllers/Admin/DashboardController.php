<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Guru;
use App\Models\Pengumuman;
use App\Models\StudyGroup;
use App\Models\TeacherSchedule;   // sesuaikan dengan nama model jadwal Anda
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // ── 1. Pengumuman Widget ─────────────────────────────────
        $widgetPengumuman = Pengumuman::where('is_active', 1)
            ->where('show_di_dashboard', 1)
            ->whereIn('target_audience', ['guru', 'siswa', 'semua'])
            ->latest()
            ->limit(5)
            ->get();

        // ── 2. Statistik Ringkasan ───────────────────────────────
        $stats = [
            'total_guru'   => User::where('role', 'guru')->count(),
            'total_siswa'  => User::where('role', 'siswa')->count(),
            'total_kelas'  => StudyGroup::count(),
            'guru_hadir'   => $this->guruHadirHariIni(),
        ];

        // ── 3. Jadwal Hari Ini ───────────────────────────────────
        // Sesuaikan query dengan nama tabel/model jadwal Anda.
        // Contoh menggunakan tabel teacher_schedules dengan kolom:
        //   day_of_week, start_time, end_time, guru_id, study_group_id
        $jadwalHariIni = $this->getJadwalHariIni();

        // ── 4. Activity Log (12 jam terakhir) ───────────────────
        $activityLogs = ActivityLog::with('user')
            ->where('created_at', '>=', now()->subHours(12))
            ->orderByDesc('created_at')
            ->limit(50)
            ->get();

        // ── 5. Absensi Ringkasan Minggu Ini ─────────────────────
        $absensiMinggu = $this->getAbsensiMingguIni();

        // ── 6. Guru Berulang Tahun Bulan Ini ────────────────────
        $guruUltah = $this->getGuruUltahBulanIni();

        // ── 7. Kelas tanpa wali kelas ────────────────────────────
        $kelasTanpaWali = StudyGroup::whereNull('homeroom_teacher_id')
            ->orWhere('homeroom_teacher_id', 0)
            ->count();

        return view('admin.dashboard', compact(
            'widgetPengumuman',
            'stats',
            'jadwalHariIni',
            'activityLogs',
            'absensiMinggu',
            'guruUltah',
            'kelasTanpaWali',
        ));
    }

    // ── Private helpers ──────────────────────────────────────────

    private function guruHadirHariIni(): int
    {
        // Sesuaikan nama tabel absensi Anda
        // Contoh: tabel guru_absensis dengan kolom tanggal & status
        try {
            return DB::table('guru_absensis')
                ->where('tanggal', today())
                ->where('status', 'P')
                ->distinct('guru_id')
                ->count('guru_id');
        } catch (\Throwable) {
            return 0;
        }
    }

    private function getJadwalHariIni(): \Illuminate\Support\Collection
    {
        // day_of_week: 0=Minggu,1=Senin,...,6=Sabtu
        $dayOfWeek = Carbon::now()->dayOfWeek;

        try {
            /*
             * Sesuaikan nama tabel & kolom dengan skema database Anda.
             * Contoh skema tabel teacher_schedules:
             *   id, guru_id, study_group_id, subject, day_of_week,
             *   start_time, end_time, room, created_at, updated_at
             */
            return DB::table('teacher_schedules as ts')
                ->join('gurus as g', 'g.id', '=', 'ts.guru_id')
                ->join('study_groups as sg', 'sg.id', '=', 'ts.study_group_id')
                ->where('ts.day_of_week', $dayOfWeek)
                ->select(
                    'ts.id',
                    'ts.start_time',
                    'ts.end_time',
                    'ts.subject',
                    'ts.room',
                    'g.nama as guru_nama',
                    'g.nip  as guru_nip',
                    'sg.name as kelas_nama',
                )
                ->orderBy('ts.start_time')
                ->get();
        } catch (\Throwable) {
            return collect();
        }
    }

    private function getAbsensiMingguIni(): array
    {
        $start = Carbon::now()->startOfWeek();
        $end   = Carbon::now()->endOfWeek();

        try {
            $rows = DB::table('guru_absensis')
                ->whereBetween('tanggal', [$start->toDateString(), $end->toDateString()])
                ->select('status', DB::raw('count(*) as total'))
                ->groupBy('status')
                ->pluck('total', 'status')
                ->toArray();

            return [
                'hadir' => $rows['P'] ?? 0,
                'sakit' => $rows['S'] ?? 0,
                'izin'  => $rows['I'] ?? 0,
                'alpha' => $rows['A'] ?? 0,
                'telat' => $rows['L'] ?? 0,
            ];
        } catch (\Throwable) {
            return ['hadir' => 0, 'sakit' => 0, 'izin' => 0, 'alpha' => 0, 'telat' => 0];
        }
    }

    private function getGuruUltahBulanIni(): \Illuminate\Support\Collection
    {
        $bulanIni = Carbon::now()->month;

        try {
            return DB::table('gurus')
                ->whereNotNull('tanggal_lahir')
                ->whereMonth('tanggal_lahir', $bulanIni)
                ->orderByRaw('DAY(tanggal_lahir) ASC')
                ->select('id', 'nama', 'tanggal_lahir', 'nip')
                ->get()
                ->map(function ($g) {
                    $g->ultah_hari_ini = Carbon::parse($g->tanggal_lahir)->day === now()->day;
                    $g->hari_ke        = Carbon::parse($g->tanggal_lahir)->day;
                    return $g;
                });
        } catch (\Throwable) {
            return collect();
        }
    }

        // ── GET /admin/dashboard/jadwal-hari-ini  (JSON) ──────────────
    public function jadwalHariIni(): \Illuminate\Http\JsonResponse
    {
        $jadwal = $this->getJadwalHariIni()->map(fn($j) => [
            'guru_nama'  => $j->guru_nama,
            'kelas_nama' => $j->kelas_nama,
            'subject'    => $j->subject ?? '',
            'room'       => $j->room ?? '',
            'start_time' => \Carbon\Carbon::parse($j->start_time)->format('H:i'),
            'end_time'   => \Carbon\Carbon::parse($j->end_time)->format('H:i'),
            'is_now'     => now()->format('H:i') >= \Carbon\Carbon::parse($j->start_time)->format('H:i')
                         && now()->format('H:i') <= \Carbon\Carbon::parse($j->end_time)->format('H:i'),
        ]);

        return response()->json(['success' => true, 'data' => $jadwal]);
    }

    // ── GET /admin/dashboard/stats  (JSON) ────────────────────────
    public function stats(): \Illuminate\Http\JsonResponse
    {
        return response()->json([
            'success' => true,
            'data'    => [
                'total_guru'  => \App\Models\User::where('role', 'guru')->count(),
                'total_siswa' => \App\Models\User::where('role', 'siswa')->count(),
                'total_kelas' => \App\Models\StudyGroup::count(),
                'guru_hadir'  => $this->guruHadirHariIni(),
            ],
        ]);
    }
}


    