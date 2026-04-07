<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Guru;
use App\Models\Kelas;
use Illuminate\Http\Request;

class KelasController extends Controller
{
    /* ── INDEX ──────────────────────────────────────────────── */
    public function index()
    {
        $kelas = Kelas::with('guru')->orderBy('tingkat')->orderBy('nama')->get();
        $gurus = Guru::orderBy('nama')->get();

        return view('admin.kelas.index', compact('kelas', 'gurus'));
    }

    /* ── STORE ──────────────────────────────────────────────── */
    public function store(Request $request)
    {
        $data = $request->validate([
            'nama'         => 'required|string|max:100',
            'tingkat'      => 'required|string|max:20',
            'tahun_ajaran' => 'required|string|max:20',
            'guru_id'      => 'nullable|exists:gurus,id',
        ]);

        Kelas::create($data);

        return redirect()->route('admin.kelas.index')
                         ->with('success', 'Kelas berhasil ditambahkan.');
    }

    /* ── UPDATE ─────────────────────────────────────────────── */
    public function update(Request $request, Kelas $kela)
    {
        // Laravel resource singular: "kela" untuk model Kelas
        $data = $request->validate([
            'nama'         => 'required|string|max:100',
            'tingkat'      => 'required|string|max:20',
            'tahun_ajaran' => 'required|string|max:20',
            'guru_id'      => 'nullable|exists:gurus,id',
        ]);

        $kela->update($data);

        return redirect()->route('admin.kelas.index')
                         ->with('success', 'Kelas berhasil diperbarui.');
    }

    /* ── DESTROY ─────────────────────────────────────────────── */
    public function destroy(Kelas $kela)
    {
        $kela->delete();

        return redirect()->route('admin.kelas.index')
                         ->with('success', 'Kelas berhasil dihapus.');
    }
}