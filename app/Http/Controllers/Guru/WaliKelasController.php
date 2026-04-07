<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Models\AbsensiSiswa;
use Illuminate\Support\Facades\Storage;

class WaliKelasController extends Controller
{
    public function index()
    {
        $guru = Auth::user()->guru;

        if (!$guru || !$guru->kelas_id) {
            return view('guru.wali-kelas.index', [
                'kelas' => null,
                'siswa' => collect(),
            ]);
        }

        $kelas = $guru->kelas;

        $bulanIni = Carbon::now()->startOfMonth();

        $siswa = $kelas->siswas()
            ->with('user')
            ->get()
            ->map(function ($s) use ($bulanIni) {
                $nama = $s->nama ?? $s->user?->name ?? '—';
                $inisial = strtoupper(substr($nama, 0, 1));
                $foto = $s->user?->photo ? Storage::url($s->user->photo) : null;

                // Hitung keterlambatan di sini (di controller)
                $terlambatCount = AbsensiSiswa::where('siswa_id', $s->id)
                    ->whereDate('tanggal', '>=', $bulanIni)
                    ->where('status', 'terlambat')
                    ->count();

                $s->nama_tampil   = $nama;
                $s->inisial       = $inisial;
                $s->foto          = $foto;
                $s->terlambat_count = $terlambatCount;

                return $s;
            });

        return view('guru.wali-kelas.index', compact('kelas', 'siswa'));
    }
}