<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\StudyGroup;
use App\Models\User;
use App\Models\Guru;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class KelasController extends Controller
{
    public function index()
    {
        $kelas = StudyGroup::with(['homeroomTeacher', 'homeroomTeacher.guru', 'timetables'])
            ->withCount('timetables')
            ->orderBy('grade')
            ->orderBy('name')
            ->get();

        $gurus = User::whereIn('role', ['guru', 'kepala_sekolah'])
            ->where('is_active', true)
            ->with('guru')
            ->orderBy('name')
            ->get();

        return view('admin.kelas.index', compact('kelas', 'gurus'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'                => 'required|string|max:100',
            'grade'               => 'required|integer|in:7,8,9',
            'academic_year'       => 'required|string|max:9',
            'semester'            => 'required|integer|in:1,2',
            'section'             => 'nullable|string|max:10',
            'room'                => 'nullable|string|max:50',
            'homeroom_teacher_id' => 'nullable|exists:users,id',
            'capacity'            => 'nullable|integer|min:1|max:60',
            'is_active'           => 'boolean',
        ]);

        $validated['is_active'] = $request->boolean('is_active');

        DB::transaction(function () use ($validated) {
            $group = StudyGroup::create($validated);
            $this->syncHomeroomToGuruProfile($group->homeroom_teacher_id, $group->id);
        });

        return redirect()->route('admin.kelas.index')
            ->with('success', 'Kelas berhasil ditambahkan dan tersinkron dengan Data Akademik.');
    }

    public function update(Request $request, StudyGroup $kelas)
    {
        $validated = $request->validate([
            'name'                => 'required|string|max:100',
            'grade'               => 'required|integer|in:7,8,9',
            'academic_year'       => 'required|string|max:9',
            'semester'            => 'required|integer|in:1,2',
            'section'             => 'nullable|string|max:10',
            'room'                => 'nullable|string|max:50',
            'homeroom_teacher_id' => 'nullable|exists:users,id',
            'capacity'            => 'nullable|integer|min:1|max:60',
            'is_active'           => 'boolean',
        ]);

        $validated['is_active'] = $request->boolean('is_active');

        DB::transaction(function () use ($validated, $kelas) {
            $oldTeacherId = $kelas->homeroom_teacher_id;
            $newTeacherId = $validated['homeroom_teacher_id'] ?? null;

            $kelas->update($validated);

            if ($oldTeacherId && $oldTeacherId !== $newTeacherId) {
                $this->clearHomeroomFromGuruProfile($oldTeacherId, $kelas->id);
            }
            $this->syncHomeroomToGuruProfile($newTeacherId, $kelas->id);
        });

        return redirect()->route('admin.kelas.index')
            ->with('success', 'Kelas berhasil diperbarui.');
    }

    public function destroy(StudyGroup $kelas)
    {
        DB::transaction(function () use ($kelas) {
            if ($kelas->homeroom_teacher_id) {
                $this->clearHomeroomFromGuruProfile($kelas->homeroom_teacher_id, $kelas->id);
            }
            $kelas->timetables()->delete();
            $kelas->delete();
        });

        return redirect()->route('admin.kelas.index')
            ->with('success', 'Kelas berhasil dihapus.');
    }

    /* ──────────────────────────────────────────────────────────────
     | PRIVATE HELPERS — Sinkronisasi Wali Kelas ↔ Profil Guru
     ─────────────────────────────────────────────────────────────── */
    private function syncHomeroomToGuruProfile(?int $userId, ?int $studyGroupId): void
    {
        if (!$userId || !$studyGroupId) return;

        $user = User::find($userId);
        if (!$user) return;

        $guruProfile = Guru::firstOrCreate(
            ['user_id' => $userId],
            ['nama' => $user->name ?? '']
        );

        $guruProfile->update(['study_group_id' => $studyGroupId]);
    }

    private function clearHomeroomFromGuruProfile(?int $userId, int $studyGroupId): void
    {
        if (!$userId) return;

        Guru::where('user_id', $userId)
            ->where('study_group_id', $studyGroupId)
            ->update(['study_group_id' => null]);
    }
}