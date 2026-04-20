<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\Timetable;   // alias StudyTimetable — sesuaikan nama model Anda
use App\Models\StudySubject;
use App\Models\StudyGroup;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class JadwalMengajarController extends Controller
{
    /**
     * Tampilkan jadwal mengajar guru yang login.
     */
    public function index()
    {
        $guru = Auth::user();

        // Semua jadwal milik guru ini
        $allTimetables = Timetable::with(['studySubject', 'studyGroup'])
            ->where('teacher_id', $guru->id)
            ->orderBy('day_of_week')
            ->orderBy('start_time')
            ->get();

        // Kelompokkan per hari
        $jadwalByDay = $allTimetables->groupBy('day_of_week');

        // KPI
        $totalJadwal      = $allTimetables->count();
        $totalKelas       = $allTimetables->unique('study_group_id')->count();
        $totalMapel       = $allTimetables->unique('study_subject_id')->count();

        // Hitung total jam per minggu (durasi dalam menit → jam)
        $totalJamPerMinggu = $allTimetables->sum(function ($t) {
            $start = \Carbon\Carbon::createFromFormat('H:i:s', $t->start_time);
            $end   = \Carbon\Carbon::createFromFormat('H:i:s', $t->end_time);
            return $end->diffInMinutes($start);
        });
        $totalJamPerMinggu = round($totalJamPerMinggu / 60, 1);

        // Data untuk dropdown form
        $studySubjects = StudySubject::where('is_active', true)->orderBy('name')->get();
        $studyGroups   = StudyGroup::where('is_active', true)->orderBy('grade')->orderBy('name')->get();

        return view('guru.jadwal-mengajar.index', compact(
            'allTimetables',
            'jadwalByDay',
            'totalJadwal',
            'totalKelas',
            'totalMapel',
            'totalJamPerMinggu',
            'studySubjects',
            'studyGroups'
        ));
    }

    /**
     * Simpan jadwal baru oleh guru.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'study_subject_id' => 'required|exists:study_subjects,id',
            'study_group_id'   => 'required|exists:study_groups,id',
            'day_of_week'      => 'required|in:Senin,Selasa,Rabu,Kamis,Jumat,Sabtu',
            'start_time'       => 'required|date_format:H:i',
            'end_time'         => 'required|date_format:H:i|after:start_time',
            'room'             => 'nullable|string|max:50',
            'session_type'     => 'required|in:teori,praktikum',
            'academic_year'    => ['required', 'regex:/^\d{4}\/\d{4}$/'],
            'semester'         => 'required|in:1,2',
            'notes'            => 'nullable|string|max:500',
            'name'        => 'required|string|max:100',
            'code'        => 'required|string|max:20',
            'color'       => 'nullable|string|max:7',
            'description' => 'nullable|string|max:200',
        ]);

        Timetable::create(array_merge($validated, [
            'teacher_id' => Auth::id(),
        ]));

                // Opsional: kaitkan ke guru yang sedang login
        $data['teacher_id'] = Auth::id(); // sesuaikan dengan kolom di tabel Anda

        StudySubject::create($data);
        
        return redirect()->route('guru.jadwal-mengajar')
            ->with('success', 'Jadwal berhasil ditambahkan.');
    }

    /**
     * Update jadwal (hanya milik guru sendiri).
     */
    public function update(Request $request, Timetable $jadwalMengajar)
    {
        // Pastikan guru hanya bisa edit jadwalnya sendiri
        abort_unless($jadwalMengajar->teacher_id === Auth::id(), 403);

        $validated = $request->validate([
            'study_subject_id' => 'required|exists:study_subjects,id',
            'study_group_id'   => 'required|exists:study_groups,id',
            'day_of_week'      => 'required|in:Senin,Selasa,Rabu,Kamis,Jumat,Sabtu',
            'start_time'       => 'required|date_format:H:i',
            'end_time'         => 'required|date_format:H:i|after:start_time',
            'room'             => 'nullable|string|max:50',
            'session_type'     => 'required|in:teori,praktikum',
            'academic_year'    => ['required', 'regex:/^\d{4}\/\d{4}$/'],
            'semester'         => 'required|in:1,2',
            'notes'            => 'nullable|string|max:500',
        ]);

        $jadwalMengajar->update($validated);

        return redirect()->route('guru.jadwal-mengajar')
            ->with('success', 'Jadwal berhasil diperbarui.');
    }

    /**
     * Hapus jadwal (hanya milik guru sendiri).
     */
    public function destroy(Timetable $jadwalMengajar)
    {
        abort_unless($jadwalMengajar->teacher_id === Auth::id(), 403);
        $jadwalMengajar->delete();

        return redirect()->route('guru.jadwal-mengajar')
            ->with('success', 'Jadwal berhasil dihapus.');
    }
}
