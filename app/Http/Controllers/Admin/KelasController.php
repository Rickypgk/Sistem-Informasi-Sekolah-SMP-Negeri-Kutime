<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kelas;
use App\Models\StudyGroup;
use App\Models\User;
use App\Models\Guru;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class KelasController extends Controller
{
    // =========================================================================
    // INDEX — tampilkan daftar kelas
    // =========================================================================

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

    // =========================================================================
    // STORE — simpan kelas baru
    // Setelah simpan ke study_groups, otomatis sinkron ke tabel `kelas`
    // agar FK siswas.kelas_id terpenuhi
    // =========================================================================

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

            // Sinkron ke tabel `kelas` agar FK siswas.kelas_id terpenuhi
            $this->syncToKelasTable($group);

            // Sinkron wali kelas ke profil guru
            $this->syncHomeroomToGuruProfile($group->homeroom_teacher_id, $group->id);
        });

        return redirect()->route('admin.kelas.index')
            ->with('success', 'Kelas berhasil ditambahkan dan tersinkron dengan Data Akademik.');
    }

    // =========================================================================
    // UPDATE — perbarui kelas
    // =========================================================================

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

            // Sinkron perubahan ke tabel `kelas`
            $this->syncToKelasTable($kelas->fresh());

            // Update wali kelas di profil guru
            if ($oldTeacherId && $oldTeacherId !== $newTeacherId) {
                $this->clearHomeroomFromGuruProfile($oldTeacherId, $kelas->id);
            }
            $this->syncHomeroomToGuruProfile($newTeacherId, $kelas->id);
        });

        return redirect()->route('admin.kelas.index')
            ->with('success', 'Kelas berhasil diperbarui.');
    }

    // =========================================================================
    // DESTROY — hapus kelas
    // =========================================================================

    public function destroy(StudyGroup $kelas)
    {
        DB::transaction(function () use ($kelas) {
            if ($kelas->homeroom_teacher_id) {
                $this->clearHomeroomFromGuruProfile($kelas->homeroom_teacher_id, $kelas->id);
            }

            // Lepas referensi siswa ke kelas ini agar tidak orphan
            // (ON DELETE SET NULL sudah di-handle DB, tapi pastikan kelas juga dihapus)
            Kelas::where('id', $kelas->id)->delete();

            $kelas->timetables()->delete();
            $kelas->delete();
        });

        return redirect()->route('admin.kelas.index')
            ->with('success', 'Kelas berhasil dihapus.');
    }

    // =========================================================================
    // PRIVATE HELPER: SINKRONISASI STUDY_GROUP → TABEL KELAS
    //
    // Ini adalah inti perbaikan masalah FK.
    // Tabel `siswas` punya FK ke tabel `kelas`, bukan ke `study_groups`.
    // Setiap kali study_group dibuat/diupdate, pastikan ada entri yang sama
    // di tabel `kelas` dengan ID yang sama.
    // =========================================================================

    private function syncToKelasTable(StudyGroup $group): void
    {
        $kelasName = $group->name
            ?: ($group->grade . ($group->section ?? ''));

        // Gunakan updateOrCreate dengan ID yang sama agar konsisten
        Kelas::updateOrCreate(
            ['id' => $group->id],
            [
                'nama'         => $kelasName,
                'tingkat'      => (string) $group->grade,
                'rombel'       => $group->section       ?? null,
                'tahun_ajaran' => $group->academic_year ?? $this->getDefaultAcademicYear(),
                'guru_id'      => $group->homeroom_teacher_id ?? null,
            ]
        );

        Log::info("syncToKelasTable: Kelas id={$group->id} nama={$kelasName} berhasil disinkron.");
    }

    // =========================================================================
    // PRIVATE HELPER: HITUNG TAHUN AJARAN DEFAULT
    // =========================================================================

    private function getDefaultAcademicYear(): string
    {
        $bulan = now()->month;
        return $bulan >= 7
            ? now()->year . '/' . (now()->year + 1)
            : (now()->year - 1) . '/' . now()->year;
    }

    // =========================================================================
    // PRIVATE HELPER: SINKRON WALI KELAS → PROFIL GURU
    // =========================================================================

    private function syncHomeroomToGuruProfile(?int $userId, ?int $studyGroupId): void
    {
        if (!$userId || !$studyGroupId) return;

        $user = User::find($userId);
        if (!$user) return;

        $guruProfile = Guru::firstOrCreate(
            ['user_id' => $userId],
            ['nama'    => $user->name ?? '']
        );

        $guruProfile->update([
            'study_group_id' => $studyGroupId,
            'kelas_id'       => $studyGroupId, // kompatibilitas kode lama
        ]);
    }

    // =========================================================================
    // PRIVATE HELPER: LEPAS WALI KELAS LAMA DARI PROFIL GURU
    // =========================================================================

    private function clearHomeroomFromGuruProfile(?int $userId, int $studyGroupId): void
    {
        if (!$userId) return;

        Guru::where('user_id', $userId)
            ->where(function ($q) use ($studyGroupId) {
                $q->where('kelas_id', $studyGroupId)
                  ->orWhere('study_group_id', $studyGroupId);
            })
            ->update([
                'kelas_id'       => null,
                'study_group_id' => null,
            ]);
    }

    // =========================================================================
    // PRIVATE HELPER: STRING KOSONG → NULL
    // =========================================================================

    private function nullIfEmptyOrNullString(?string $str): ?string
    {
        return ($str === '' || $str === 'null') ? null : $str;
    }
}