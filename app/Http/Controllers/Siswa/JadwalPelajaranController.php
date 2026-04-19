<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\Timetable;
use App\Models\StudySubject;
use App\Models\StudyGroup;
use App\Models\Siswa;
use Illuminate\Support\Facades\Auth;

class JadwalPelajaranController extends Controller
{
    public function index()
    {
        $user  = Auth::user();
        $siswa = $user->siswa; // relasi ke profil siswa

        // Ambil kelas siswa via profil (kelas_id) atau via homeroomGroups
        // Sesuaikan dengan relasi di model Siswa Anda
        $studyGroupId = $siswa?->kelas_id ?? null;
        $studyGroup   = $studyGroupId
            ? StudyGroup::with('homeroomTeacher')->find($studyGroupId)
            : null;

        if (!$studyGroup) {
            return view('siswa.jadwal-pelajaran.index', [
                'studyGroup'    => null,
                'jadwalByDay'   => collect(),
                'allTimetables' => collect(),
                'mataPelajaran' => collect(),
                'totalJadwal'   => 0,
                'totalMapel'    => 0,
                'totalGuru'     => 0,
                'hariAktif'     => 0,
            ]);
        }

        // Semua jadwal kelas ini
        $allTimetables = Timetable::with(['studySubject', 'teacher'])
            ->where('study_group_id', $studyGroup->id)
            ->orderBy('day_of_week')
            ->orderBy('start_time')
            ->get();

        $jadwalByDay = $allTimetables->groupBy('day_of_week');

        // Mata pelajaran unik di kelas ini
        $mataPelajaran = StudySubject::whereIn(
            'id',
            $allTimetables->pluck('study_subject_id')->unique()
        )->get();

        // KPI
        $totalJadwal = $allTimetables->count();
        $totalMapel  = $mataPelajaran->count();
        $totalGuru   = $allTimetables->pluck('teacher_id')->unique()->count();
        $hariAktif   = $jadwalByDay->keys()->count();

        return view('siswa.jadwal-pelajaran.index', compact(
            'studyGroup',
            'jadwalByDay',
            'allTimetables',
            'mataPelajaran',
            'totalJadwal',
            'totalMapel',
            'totalGuru',
            'hariAktif'
        ));
    }
}
