<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\StudyClassAssignment;

class StudyGroup extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'grade', 'section', 'capacity',
        'homeroom_teacher_id', 'room', 'description', 'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'capacity'  => 'integer',
        'grade'     => 'integer',
    ];

        protected $table = 'study_groups';
 
    /**
     * PENTING: Semua kolom yang bisa diisi harus ada di sini.
     * Inilah penyebab utama field tidak tersimpan — jika tidak ada di $fillable,
     * Laravel akan diam-diam mengabaikan field tersebut.
     */
    protected $fillable = [
        'name',
        'grade',
        'section',
        'homeroom_teacher_id',
        'room',           // ← ruang kelas
        'academic_year',  // ← tahun ajaran
        'semester',       // ← semester
        'capacity',
        'is_active',
        'description',
        'notes',
    ];
 
    protected $casts = [
        'grade'     => 'integer',
        'semester'  => 'integer',
        'capacity'  => 'integer',
        'is_active' => 'boolean',
    ];

    // Relasi ke wali kelas
    public function homeroomTeacher(): BelongsTo
    {
        return $this->belongsTo(User::class, 'homeroom_teacher_id');
    }

    // Relasi ke jadwal
    public function timetables(): HasMany
    {
        return $this->hasMany(Timetable::class);
    }

    // Relasi ke assignment guru
    public function assignments(): HasMany
    {
        return $this->hasMany(StudyClassAssignment::class);
    }

    // Jadwal dikelompokkan per hari
    public function timetablesByDay(): array
    {
        $days = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
        $grouped = [];
        foreach ($days as $day) {
            $grouped[$day] = $this->timetables()
                ->where('day_of_week', $day)
                ->where('is_active', true)
                ->orderBy('start_time')
                ->with(['studySubject', 'teacher'])
                ->get();
        }
        return $grouped;
    }
}
