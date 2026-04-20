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
    // =========================================================================

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'                => 'required|string|max:50',
            'grade'               => 'required|in:7,8,9',
            'section'             => 'nullable|string|max:10',
            'academic_year'       => ['nullable', 'string', 'max:9', 'regex:/^\d{4}\/\d{4}$/'],
            'semester'            => 'nullable|in:1,2',
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
                'homeroom_teacher_id' => $validated['homeroom_teacher_id'] ?? null,
                'room'                => $validated['room']                ?? null,
                'capacity'            => $validated['capacity']            ?? 30,
                'is_active'           => $request->boolean('is_active'),
            ]);

            // Sinkron ke tabel `kelas` agar FK siswas.kelas_id terpenuhi
            $this->syncToKelasTable(
                $group,
                $validated['academic_year'] ?? null,
                $validated['semester']      ?? null,
                $validated['room']          ?? null
            );

            // Sinkron wali kelas ke profil guru
            $this->syncHomeroomToGuruProfile($group->homeroom_teacher_id, $group->id);
        });

        return redirect()->route('admin.kelas.index')
            ->with('success', 'Kelas berhasil ditambahkan.');
    }

    // =========================================================================
    // UPDATE — perbarui kelas
    // FIX: Tidak pakai ->fresh() karena bisa null jika route param typo.
    //      Langsung reload manual dengan StudyGroup::find().
    // =========================================================================

    public function update(Request $request, StudyGroup $kelas)
    {
        $validated = $request->validate([
            'name'                => 'required|string|max:50',
            'grade'               => 'required|in:7,8,9',
            'section'             => 'nullable|string|max:10',
            'academic_year'       => ['nullable', 'string', 'max:9', 'regex:/^\d{4}\/\d{4}$/'],
            'semester'            => 'nullable|in:1,2',
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
                'homeroom_teacher_id' => $newTeacherId,
                'room'                => $validated['room']     ?? null,
                'capacity'            => $validated['capacity'] ?? 30,
                'is_active'           => $request->boolean('is_active'),
            ]);

            // FIX: Reload manual, jangan pakai fresh() karena bisa null
            $freshGroup = StudyGroup::find($kelas->id);

            if ($freshGroup) {
                $this->syncToKelasTable(
                    $freshGroup,
                    $validated['academic_year'] ?? null,
                    $validated['semester']      ?? null,
                    $validated['room']          ?? null
                );
            }

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

            // Hapus entri sinkron di tabel kelas juga
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
    // Tabel `siswas.kelas_id` FK ke tabel `kelas`.
    // Tabel `study_groups` tidak punya kolom academic_year & semester,
    // sehingga nilai tersebut diambil dari parameter form atau default otomatis.
    // Kolom `room` di study_groups disimpan sebagai `rombel` di kelas (opsional).
    // =========================================================================

    private function syncToKelasTable(
        StudyGroup $group,
        ?string $academicYear = null,
        ?string $semester = null,
        ?string $room = null
    ): void {
        $kelasName   = $group->name ?: ($group->grade . ($group->section ?? ''));
        $tahunAjaran = $academicYear ?? $this->getDefaultAcademicYear();

        // Cek apakah sudah ada entri dengan id ini
        $existingKelas = Kelas::find($group->id);

        if ($existingKelas) {
            // Update, tapi jangan timpa semester/tahun_ajaran jika tidak dikirim
            $updateData = [
                'nama'    => $kelasName,
                'tingkat' => (string) $group->grade,
                'rombel'  => $group->section ?? null,
                'guru_id' => $group->homeroom_teacher_id ?? null,
            ];

            if ($academicYear !== null) {
                $updateData['tahun_ajaran'] = $tahunAjaran;
            }

            $existingKelas->update($updateData);
        } else {
            // Buat baru
            Kelas::create([
                'id'           => $group->id,
                'nama'         => $kelasName,
                'tingkat'      => (string) $group->grade,
                'rombel'       => $group->section ?? null,
                'tahun_ajaran' => $tahunAjaran,
                'guru_id'      => $group->homeroom_teacher_id ?? null,
            ]);
        }

        Log::info("syncToKelasTable: id={$group->id} nama={$kelasName} tahun={$tahunAjaran}");
    }

    // =========================================================================
    // PRIVATE HELPER: TAHUN AJARAN DEFAULT (otomatis per kalender sekolah)
    // Juli–Desember → tahun ini / tahun depan
    // Januari–Juni  → tahun lalu / tahun ini
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
            'kelas_id'       => $studyGroupId,
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