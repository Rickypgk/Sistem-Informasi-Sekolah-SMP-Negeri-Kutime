<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class StudyGroup extends Model
{
    use HasFactory;

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

    // ── Relasi ───────────────────────────────────────────────────

    public function homeroomTeacher(): BelongsTo
    {
        return $this->belongsTo(User::class, 'homeroom_teacher_id');
    }

    public function timetables(): HasMany
    {
        return $this->hasMany(Timetable::class, 'study_group_id');
    }

    // ── Accessor: label nama lengkap ──────────────────────────────

    public function getFullNameAttribute(): string
    {
        return $this->name
            ?: ('Kelas ' . $this->grade . ($this->section ? ' ' . $this->section : ''));
    }
}