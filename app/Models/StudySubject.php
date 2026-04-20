<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\StudyClassAssignment;

class StudySubject extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'code', 'credit_hours', 'type', 'color', 'session', 'description', 'is_active',
    ];

    protected $casts = [
        'is_active'    => 'boolean',
        'credit_hours' => 'integer',
    ];

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

    // Warna default jika tidak diset
    public function getColorAttribute($value): string
    {
        return $value ?? '#3B82F6';
    }

    // Label tipe dalam bahasa Indonesia
    public function getTypeLabelAttribute(): string
    {
        return $this->type === 'core' ? 'Wajib' : 'Pilihan';
    }
}
