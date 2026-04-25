<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Guru extends Model
{
    protected $fillable = [
        'user_id',
        'nip',
        'kelas_id',
        'nama',
        'tempat_lahir',
        'tanggal_lahir',
        'jk',
        'status_pegawai',
        'pangkat_gol_ruang',
        'no_sk_pertama',
        'no_sk_terakhir',
        'pendidikan_terakhir',
    ];

    protected $casts = [
        'tanggal_lahir' => 'date',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relasi ke tabel kelas lama (tidak dipakai untuk wali kelas).
     */
    public function kelas(): BelongsTo
    {
        return $this->belongsTo(Kelas::class, 'kelas_id');
    }

    /**
     * Wali kelas via study_groups — pakai user_id sebagai foreign key
     * karena homeroom_teacher_id di study_groups = users.id, bukan gurus.id
     */
    public function homeroomGroups(): HasMany
    {
        return $this->hasMany(StudyGroup::class, 'homeroom_teacher_id', 'user_id');
    }

    public function studyGroup(): BelongsTo
    {
        return $this->belongsTo(StudyGroup::class);
    }
}