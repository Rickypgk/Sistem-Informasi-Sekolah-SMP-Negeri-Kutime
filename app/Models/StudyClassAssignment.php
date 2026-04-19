<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StudyClassAssignment extends Model
{
    use HasFactory;

    protected $fillable = [
        'teacher_id',
        'study_subject_id',
        'study_group_id',
        'academic_year',
        'semester',
        'notes',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    // Relasi ke guru
    public function teacher(): BelongsTo
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }

    // Relasi ke mata pelajaran
    public function studySubject(): BelongsTo
    {
        return $this->belongsTo(StudySubject::class);
    }

    // Relasi ke kelas
    public function studyGroup(): BelongsTo
    {
        return $this->belongsTo(StudyGroup::class);
    }
}
