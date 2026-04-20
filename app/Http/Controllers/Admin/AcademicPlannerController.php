<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kelas;
use App\Models\StudyGroup;
use App\Models\StudySubject;
use App\Models\Timetable;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AcademicPlannerController extends Controller
{
    // =========================================================================
    // DASHBOARD
    // =========================================================================

    public function index(): View
    {
        $stats = [
            'total_groups'     => StudyGroup::where('is_active', true)->count(),
            'total_subjects'   => StudySubject::where('is_active', true)->count(),
            'total_timetables' => Timetable::where('is_active', true)->count(),
            'total_teachers'   => User::where('role', 'guru')->count(),
        ];

        $groupsByGrade = StudyGroup::where('is_active', true)
            ->with('homeroomTeacher')
            ->orderBy('grade')
            ->orderBy('section')
            ->get()
            ->groupBy('grade');

        return view('admin.academic-planner.index', compact('stats', 'groupsByGrade'));
    }

    // =========================================================================
    // STUDY GROUPS — SHOW
    // Alias "show" ditambahkan agar route resource-style (show) tetap bekerja
    // sekaligus mempertahankan nama lama showStudyGroup.
    // =========================================================================

    /**
     * Alias untuk showStudyGroup — digunakan oleh route:
     *   Route::get('study-group/{id}', [AcademicPlannerController::class, 'show'])
     *       ->name('admin.academic-planner.study-group.show');
     */
    public function show(int $id): View
    {
        return $this->showStudyGroup($id);
    }

    public function showStudyGroup(int $id): View
    {
        $studyGroup = StudyGroup::with([
            'homeroomTeacher',
            'timetables.studySubject',
            'timetables.teacher',
        ])->findOrFail($id);

        $timetables = $studyGroup->timetables->where('is_active', true);

        $days           = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
        $timetableByDay = [];

        foreach ($days as $day) {
            $timetableByDay[$day] = $timetables
                ->where('day_of_week', $day)
                ->sortBy('start_time')
                ->values();
        }

        return view(
            'admin.academic-planner.show-study-group',
            compact('studyGroup', 'timetables', 'timetableByDay', 'days')
        );
    }

    // =========================================================================
    // STUDY GROUPS — STORE
    // Setelah simpan ke study_groups, otomatis sinkron ke tabel `kelas`
    // agar FK siswas.kelas_id terpenuhi.
    // =========================================================================

    public function storeStudyGroup(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name'                => 'required|string|max:50',
            'grade'               => 'required|integer|in:7,8,9',
            'section'             => 'nullable|string|max:10',
            'homeroom_teacher_id' => 'nullable|exists:users,id',
            'room'                => 'nullable|string|max:50',
            'academic_year'       => 'required|string|max:9|regex:/^\d{4}\/\d{4}$/',
            'semester'            => 'required|in:1,2',
            'is_active'           => 'sometimes|boolean',
        ]);

        $validated['is_active'] = $request->has('is_active');

        DB::transaction(function () use ($validated) {
            $group = StudyGroup::create($validated);
            $this->syncToKelasTable($group);
        });

        return redirect()
            ->route('admin.academic-planner.index')
            ->with('success', "Kelas {$validated['name']} berhasil ditambahkan.");
    }

    // =========================================================================
    // STUDY GROUPS — EDIT
    // =========================================================================

    public function editStudyGroup(int $id): View
    {
        $studyGroup = StudyGroup::findOrFail($id);
        $teachers   = User::whereIn('role', ['kepala_sekolah', 'guru'])
                          ->orderBy('name')
                          ->get();

        return view('admin.academic-planner.edit-study-group', compact('studyGroup', 'teachers'));
    }

    // =========================================================================
    // STUDY GROUPS — UPDATE
    // =========================================================================

    public function updateStudyGroup(Request $request, int $id): RedirectResponse
    {
        $studyGroup = StudyGroup::findOrFail($id);

        $validated = $request->validate([
            'name'                => 'required|string|max:50',
            'grade'               => 'required|integer|in:7,8,9',
            'section'             => 'nullable|string|max:10',
            'homeroom_teacher_id' => 'nullable|exists:users,id',
            'room'                => 'nullable|string|max:50',
            'academic_year'       => 'required|string|max:9|regex:/^\d{4}\/\d{4}$/',
            'semester'            => 'required|in:1,2',
            'is_active'           => 'sometimes|boolean',
        ]);

        $validated['is_active'] = $request->has('is_active');

        DB::transaction(function () use ($validated, $studyGroup) {
            $studyGroup->update($validated);
            $this->syncToKelasTable($studyGroup->fresh());
        });

        return redirect()
            ->route('admin.academic-planner.study-group.show', $id)
            ->with('success', "Kelas {$validated['name']} berhasil diperbarui.");
    }

    // =========================================================================
    // STUDY GROUPS — DESTROY
    // =========================================================================

    public function destroyStudyGroup(int $id): RedirectResponse
    {
        $studyGroup = StudyGroup::findOrFail($id);
        $name       = $studyGroup->name;

        DB::transaction(function () use ($studyGroup) {
            // Hapus dari kelas (siswas.kelas_id ON DELETE SET NULL)
            Kelas::where('id', $studyGroup->id)->delete();
            $studyGroup->delete();
        });

        return redirect()
            ->route('admin.academic-planner.index')
            ->with('success', "Kelas {$name} berhasil dihapus.");
    }

    // =========================================================================
    // STUDY SUBJECTS — INDEX
    // =========================================================================

    public function indexStudySubject(): View
    {
        $subjects = StudySubject::orderBy('name')->paginate(20);

        return view('admin.academic-planner.study-subjects', compact('subjects'));
    }

    // =========================================================================
    // STUDY SUBJECTS — CREATE
    // =========================================================================

    public function createStudySubject(): View
    {
        return view('admin.academic-planner.create-study-subject');
    }

    // =========================================================================
    // STUDY SUBJECTS — STORE
    // =========================================================================

    public function storeStudySubject(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name'         => 'required|string|max:100',
            'code'         => 'required|string|max:10|unique:study_subjects,code',
            'credit_hours' => 'required|integer|min:1|max:6',
            'type'         => 'required|in:core,elective',
            'description'  => 'nullable|string|max:500',
            'is_active'    => 'sometimes|boolean',
        ]);

        $validated['is_active'] = $request->has('is_active');

        StudySubject::create($validated);

        return redirect()
            ->route('admin.academic-planner.study-subjects.index')
            ->with('success', "Mata pelajaran {$validated['name']} berhasil ditambahkan.");
    }

    // =========================================================================
    // STUDY SUBJECTS — EDIT
    // =========================================================================

    public function editStudySubject(int $id): View
    {
        $subject = StudySubject::findOrFail($id);

        return view('admin.academic-planner.edit-study-subject', compact('subject'));
    }

    // =========================================================================
    // STUDY SUBJECTS — UPDATE
    // =========================================================================

    public function updateStudySubject(Request $request, int $id): RedirectResponse
    {
        $subject = StudySubject::findOrFail($id);

        $validated = $request->validate([
            'name'         => 'required|string|max:100',
            'code'         => 'required|string|max:10|unique:study_subjects,code,' . $id,
            'credit_hours' => 'required|integer|min:1|max:6',
            'type'         => 'required|in:core,elective',
            'description'  => 'nullable|string|max:500',
            'is_active'    => 'sometimes|boolean',
        ]);

        $validated['is_active'] = $request->has('is_active');

        $subject->update($validated);

        return redirect()
            ->route('admin.academic-planner.study-subjects.index')
            ->with('success', "Mata pelajaran {$validated['name']} berhasil diperbarui.");
    }

    // =========================================================================
    // STUDY SUBJECTS — DESTROY
    // =========================================================================

    public function destroyStudySubject(int $id): RedirectResponse
    {
        Log::info("=== DESTROY STUDY SUBJECT === ID: {$id}");

        try {
            $subject        = StudySubject::findOrFail($id);
            $name           = $subject->name;
            $timetableCount = Timetable::where('study_subject_id', $id)->count();

            Log::info("Subject: {$name}, Timetable count: {$timetableCount}");

            if ($timetableCount > 0) {
                return redirect()
                    ->route('admin.academic-planner.study-subjects.index')
                    ->with('error', "Mata pelajaran {$name} tidak dapat dihapus karena masih digunakan dalam jadwal.");
            }

            $subject->delete();

            return redirect()
                ->route('admin.academic-planner.study-subjects.index')
                ->with('success', "Mata pelajaran {$name} berhasil dihapus.");

        } catch (\Exception $e) {
            Log::error("Error deleting subject: " . $e->getMessage());

            return redirect()
                ->route('admin.academic-planner.study-subjects.index')
                ->with('error', "Terjadi kesalahan saat menghapus mata pelajaran: " . $e->getMessage());
        }
    }

    // =========================================================================
    // TIMETABLES — CREATE
    // =========================================================================

    public function createTimetable(Request $request): View
    {
        $studyGroups   = StudyGroup::where('is_active', true)
                                   ->orderBy('grade')
                                   ->orderBy('section')
                                   ->get();
        $studySubjects = StudySubject::where('is_active', true)->orderBy('name')->get();
        $teachers      = User::whereIn('role', ['kepala_sekolah', 'guru'])
                             ->orderBy('name')
                             ->get();
        $selectedGroup = $request->get('study_group_id');

        return view(
            'admin.academic-planner.create-timetable',
            compact('studyGroups', 'studySubjects', 'teachers', 'selectedGroup')
        );
    }

    // =========================================================================
    // TIMETABLES — STORE
    // =========================================================================

    public function storeTimetable(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'study_group_id'   => 'required|exists:study_groups,id',
            'study_subject_id' => 'required|exists:study_subjects,id',
            'teacher_id'       => 'required|exists:users,id',
            'day_of_week'      => 'required|in:Senin,Selasa,Rabu,Kamis,Jumat,Sabtu',
            'start_time'       => 'required|date_format:H:i',
            'end_time'         => 'required|date_format:H:i|after:start_time',
            'room'             => 'nullable|string|max:50',
            'session_type'     => 'required|in:teori,praktikum',
            'academic_year'    => 'required|string|max:9',
            'semester'         => 'required|in:1,2',
            'notes'            => 'nullable|string|max:500',
        ]);

        if (Timetable::hasConflict(
            $validated['study_group_id'],
            $validated['day_of_week'],
            $validated['start_time'],
            $validated['end_time']
        )) {
            return back()
                ->withInput()
                ->withErrors(['start_time' => 'Jadwal bentrok dengan jadwal lain di kelas yang sama.']);
        }

        Timetable::create($validated);

        return redirect()
            ->route('admin.academic-planner.study-group.show', $validated['study_group_id'])
            ->with('success', 'Jadwal berhasil ditambahkan.');
    }

    // =========================================================================
    // TIMETABLES — EDIT
    // =========================================================================

    public function editTimetable(int $id): View
    {
        $timetable     = Timetable::with(['studyGroup', 'studySubject', 'teacher'])->findOrFail($id);
        $studyGroups   = StudyGroup::where('is_active', true)
                                   ->orderBy('grade')
                                   ->orderBy('section')
                                   ->get();
        $studySubjects = StudySubject::where('is_active', true)->orderBy('name')->get();
        $teachers      = User::whereIn('role', ['kepala_sekolah', 'guru'])
                             ->orderBy('name')
                             ->get();

        return view(
            'admin.academic-planner.edit-timetable',
            compact('timetable', 'studyGroups', 'studySubjects', 'teachers')
        );
    }

    // =========================================================================
    // TIMETABLES — UPDATE
    // =========================================================================

    public function updateTimetable(Request $request, int $id)
    {
        Log::info('=== UPDATE TIMETABLE === ID: ' . $id);
        Log::info('Request data:', $request->all());

        try {
            $timetable = Timetable::findOrFail($id);

            $validated = $request->validate([
                'study_group_id'   => 'required|exists:study_groups,id',
                'study_subject_id' => 'required|exists:study_subjects,id',
                'teacher_id'       => 'required|exists:users,id',
                'day_of_week'      => 'required|in:Senin,Selasa,Rabu,Kamis,Jumat,Sabtu',
                'start_time'       => 'required|date_format:H:i',
                'end_time'         => 'required|date_format:H:i|after:start_time',
                'room'             => 'nullable|string|max:50',
                'session_type'     => 'required|in:teori,praktikum',
                'academic_year'    => 'required|string|max:9',
                'semester'         => 'required|in:1,2',
                'notes'            => 'nullable|string|max:500',
            ], [
                'study_group_id.required'   => 'Kelas wajib diisi.',
                'study_subject_id.required' => 'Mata pelajaran wajib diisi.',
                'teacher_id.required'       => 'Guru wajib diisi.',
                'day_of_week.required'      => 'Hari wajib diisi.',
                'start_time.required'       => 'Jam mulai wajib diisi.',
                'end_time.required'         => 'Jam selesai wajib diisi.',
                'session_type.required'     => 'Sesi wajib diisi.',
                'academic_year.required'    => 'Tahun ajaran wajib diisi.',
                'semester.required'         => 'Semester wajib diisi.',
            ]);

            Log::info('Validation passed:', $validated);

            $timetable->update($validated);

            Log::info('Timetable updated successfully');

            // ── AJAX / JSON ─────────────────────────────────────────────────
            if ($request->ajax() || $request->wantsJson() || $request->has('ajax_request')) {
                return response()->json([
                    'success' => true,
                    'message' => 'Jadwal berhasil diperbarui.',
                    'data'    => $timetable->fresh(),
                ]);
            }

            // ── iframe postMessage ───────────────────────────────────────────
            $html = '<html><body>'
                  . '<script>'
                  . 'window.parent.postMessage({success:true,message:"Jadwal berhasil diperbarui."},"*");'
                  . '</script>'
                  . '</body></html>';

            return response($html)->header('Content-Type', 'text/html');

        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Validation error:', $e->errors());

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'errors'  => $e->errors(),
                ], 422);
            }

            $errorHtml = '<html><body><script>'
                       . 'window.parent.postMessage({success:false,errors:'
                       . json_encode($e->errors()->all())
                       . '},"*");'
                       . '</script></body></html>';

            return response($errorHtml, 422)->header('Content-Type', 'text/html');

        } catch (\Exception $e) {
            Log::error('Error updating timetable: ' . $e->getMessage());
            Log::error($e->getTraceAsString());

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => $e->getMessage(),
                ], 500);
            }

            $errorHtml = '<html><body><script>'
                       . 'window.parent.postMessage({success:false,message:"'
                       . addslashes($e->getMessage())
                       . '"},"*");'
                       . '</script></body></html>';

            return response($errorHtml, 500)->header('Content-Type', 'text/html');
        }
    }

    // =========================================================================
    // TIMETABLES — DESTROY
    // =========================================================================

    public function destroyTimetable(int $id): RedirectResponse
    {
        $timetable = Timetable::findOrFail($id);
        $groupId   = $timetable->study_group_id;

        $timetable->delete();

        return redirect()
            ->route('admin.academic-planner.study-group.show', $groupId)
            ->with('success', 'Jadwal berhasil dihapus.');
    }

    // =========================================================================
    // PRIVATE HELPER: SINKRONISASI STUDY_GROUP → TABEL KELAS
    //
    // Tabel `siswas` punya FK ke tabel `kelas`, bukan ke `study_groups`.
    // Setiap kali study_group dibuat/diupdate, pastikan ada entri yang sama
    // di tabel `kelas` dengan ID yang sama agar FK tidak pernah gagal.
    // =========================================================================

    private function syncToKelasTable(StudyGroup $group): void
    {
        $kelasName = $group->name
            ?: ((string) $group->grade . ($group->section ?? ''));

        $tahunAjaran = (!empty($group->academic_year))
            ? $group->academic_year
            : $this->getDefaultAcademicYear();

        Kelas::updateOrCreate(
            ['id' => $group->id],
            [
                'nama'         => $kelasName,
                'tingkat'      => (string) $group->grade,
                'rombel'       => $group->section ?? null,
                'tahun_ajaran' => $tahunAjaran,
                'guru_id'      => $group->homeroom_teacher_id ?? null,
            ]
        );

        Log::info(
            "syncToKelasTable: study_group id={$group->id} "
            . "nama={$kelasName} berhasil disinkron ke tabel kelas."
        );
    }

    // =========================================================================
    // PRIVATE HELPER: TAHUN AJARAN DEFAULT OTOMATIS
    // - Bulan Juli–Desember → tahun ini / tahun depan
    // - Bulan Januari–Juni  → tahun lalu / tahun ini
    // =========================================================================

    private function getDefaultAcademicYear(): string
    {
        $bulan = now()->month;

        return $bulan >= 7
            ? now()->year . '/' . (now()->year + 1)
            : (now()->year - 1) . '/' . now()->year;
    }
}