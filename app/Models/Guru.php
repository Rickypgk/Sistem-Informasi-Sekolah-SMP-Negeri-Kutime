<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Kelas;

class Guru extends Model
{
    protected $fillable = [
        'user_id',
        'nip',
        'kelas_id',
        // Data diri lengkap
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
     * Relasi ke Kelas sebagai Wali Kelas.
     */
    public function kelas()
    {
        return $this->belongsTo(Kelas::class, 'kelas_id');
    }
    public function studyGroup()
    {
        return $this->belongsTo(StudyGroup::class);
    }

    public function isWaliKelas()
    {
        return $this->guru && $this->guru->waliKelas()->exists();
    }
}
