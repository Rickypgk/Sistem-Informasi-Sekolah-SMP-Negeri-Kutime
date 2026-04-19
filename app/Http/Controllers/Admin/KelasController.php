<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\StudyGroup;
use App\Models\User;
use App\Models\Guru;          // model profil guru (tabel gurus)
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class KelasController extends Controller
{
    /**
     * Tampilkan daftar kelas dari study_groups.
     */
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
    /**
     * Simpan kelas baru. Jika wali kelas dipilih, update profil guru.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'                => 'required|string|max:50',
            'grade'               => 'required|in:7,8,9',
            'section'             => 'nullable|string|max:10',
            'academic_year'       => ['required', 'string', 'max:9', 'regex:/^\d{4}\/\d{4}$/'],
            'semester'            => 'required|in:1,2',
            'homeroom_teacher_id' => 'nullable|exists:users,id',
            'room'                => 'nullable|string|max:50',
            'capacity'            => 'nullable|integer|min:1|max:60',
            'is_active'           => 'nullable|boolean',
        ]);

        DB::transaction(function () use ($validated, $request) {

            // Jika ada guru yang sebelumnya menjadi wali kelas di kelas lain
            // dengan guru_id yang sama, lepaskan dulu (opsional — uncomment jika diperlukan)
            // if (!empty($validated['homeroom_teacher_id'])) {
            //     StudyGroup::where('homeroom_teacher_id', $validated['homeroom_teacher_id'])
            //               ->update(['homeroom_teacher_id' => null]);
            // }

            $group = StudyGroup::create([
                'name'                => $validated['name'],
                'grade'               => $validated['grade'],
                'section'             => $validated['section']             ?? null,
                'academic_year'       => $validated['academic_year'],
                'semester'            => $validated['semester'],
                'homeroom_teacher_id' => $validated['homeroom_teacher_id'] ?? null,
                'room'                => $validated['room']                ?? null,
                'capacity'            => $validated['capacity']            ?? 30,
                'is_active'           => $request->boolean('is_active'),
            ]);

            // Sinkronisasi ke profil guru: set kelas_id
            $this->syncHomeroomToGuruProfile($group->homeroom_teacher_id, $group->id);
        });

        return redirect()->route('admin.kelas.index')
            ->with('success', 'Kelas berhasil ditambahkan dan tersinkron dengan Data Akademik.');
    }

    /**
     * Update kelas. Sinkronisasi wali kelas ke profil guru.
     */
    public function update(Request $request, StudyGroup $kelas)
    {
        $validated = $request->validate([
            'name'                => 'required|string|max:50',
            'grade'               => 'required|in:7,8,9',
            'section'             => 'nullable|string|max:10',
            'academic_year'       => ['required', 'string', 'max:9', 'regex:/^\d{4}\/\d{4}$/'],
            'semester'            => 'required|in:1,2',
            'homeroom_teacher_id' => 'nullable|exists:users,id',
            'room'                => 'nullable|string|max:50',
            'capacity'            => 'nullable|integer|min:1|max:60',
            'is_active'           => 'nullable|boolean',
        ]);

        DB::transaction(function () use ($validated, $request, $kelas) {

            $oldTeacherId = $kelas->homeroom_teacher_id;
            $newTeacherId = $validated['homeroom_teacher_id'] ?? null;

            $kelas->update([
                'name'                => $validated['name'],
                'grade'               => $validated['grade'],
                'section'             => $validated['section']  ?? null,
                'academic_year'       => $validated['academic_year'],
                'semester'            => $validated['semester'],
                'homeroom_teacher_id' => $newTeacherId,
                'room'                => $validated['room']     ?? null,
                'capacity'            => $validated['capacity'] ?? 30,
                'is_active'           => $request->boolean('is_active'),
            ]);

            // Jika wali kelas berubah: lepas yang lama, set yang baru
            if ($oldTeacherId && $oldTeacherId !== $newTeacherId) {
                $this->clearHomeroomFromGuruProfile($oldTeacherId, $kelas->id);
            }
            $this->syncHomeroomToGuruProfile($newTeacherId, $kelas->id);
        });

        return redirect()->route('admin.kelas.index')
            ->with('success', 'Kelas berhasil diperbarui.');
    }

    /**
     * Hapus kelas dan timetable-nya. Lepas wali kelas dari profil guru.
     */
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
     | PRIVATE HELPERS
     | Sinkronisasi wali kelas ↔ profil guru (tabel gurus)
     ─────────────────────────────────────────────────────────────── */

    /**
     * Set kelas_id pada profil guru yang menjadi wali kelas.
     *
     * @param int|null $userId      ID user (tabel users) yang menjadi wali
     * @param int      $studyGroupId ID study_groups
     */
    private function syncHomeroomToGuruProfile(?int $userId, ?int $studyGroupId): void
    {
        // 🚫 Stop kalau data tidak valid
        if (!$userId || !$studyGroupId) {
            return;
        }

        // 🔍 Ambil user (optional safety)
        $user = User::find($userId);
        if (!$user) {
            return;
        }

        // ✅ Pastikan profil guru ada
        $guruProfile = Guru::firstOrCreate(
            ['user_id' => $userId],
            ['nama' => $user->name ?? '']
        );

        // ✅ Update relasi ke study group
        $guruProfile->update([
            'study_group_id' => $studyGroupId
        ]);
    }

    /**
     * Hapus kelas_id dari profil guru lama jika masih menunjuk kelas ini.
     */
    private function clearHomeroomFromGuruProfile(?int $userId, int $studyGroupId): void
    {
        if (!$userId) return;

        Guru::where('user_id', $userId)
            ->where('kelas_id', $studyGroupId)
            ->update(['kelas_id' => null]);
    }
}
