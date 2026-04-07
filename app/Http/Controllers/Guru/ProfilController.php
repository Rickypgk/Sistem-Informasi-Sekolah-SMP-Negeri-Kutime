<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\Kelas;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

class ProfilController extends Controller
{
    public function show(Request $request): View
    {
        $request->user()->load('guru.kelas');
        return view('guru.profil', ['user' => $request->user()]);
    }

    public function edit()
    {
        $user = Auth::user();
        
        $kelasList = \App\Models\Kelas::query()
            ->select('id', 'nama', 'tingkat', 'tahun_ajaran')
            ->orderBy('tahun_ajaran', 'desc')
            ->orderBy('tingkat')
            ->orderBy('nama')
            ->get();

        return view('guru.profil-edit', compact('user', 'kelasList'));
    }

    public function update(Request $request): RedirectResponse
    {
        $user = $request->user();
        $guru = $user->guru ?? $user->guru()->create([]);

        $rules = [
            // Akun
            'name'  => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'photo' => ['nullable', 'image', 'max:2048'],

            // Identitas tugas
            'nip'       => ['nullable', 'string', 'max:30', 'unique:gurus,nip,' . $guru->id],
            'wali_kelas' => ['nullable', 'exists:kelas,id'],

            // Data diri
            'nama'               => ['nullable', 'string', 'max:255'],
            'tempat_lahir'       => ['nullable', 'string', 'max:100'],
            'tanggal_lahir'      => ['nullable', 'date'],
            'jk'                 => ['nullable', 'in:L,P'],
            'status_pegawai'     => ['nullable', 'string', 'max:100'],
            'pangkat_gol_ruang'  => ['nullable', 'string', 'max:100'],
            'no_sk_pertama'      => ['nullable', 'string', 'max:150'],
            'no_sk_terakhir'     => ['nullable', 'string', 'max:150'],
            'pendidikan_terakhir'=> ['nullable', 'string', 'max:100'],
        ];

        if ($request->filled('password')) {
            $rules['password'] = ['required', 'string', 'confirmed', Password::defaults()];
        }

        $data = $request->validate($rules);

        // Upload foto
        if ($request->hasFile('photo')) {
            if ($user->photo) {
                Storage::disk('public')->delete($user->photo);
            }
            $user->photo = $request->file('photo')->store('photos', 'public');
            $user->save();
        }

        // Update password
        if (! empty($data['password'])) {
            $user->password = Hash::make($data['password']);
            $user->save();
        }

        // Update akun user
        $user->update([
            'name'  => $data['name'],
            'email' => $data['email'],
        ]);

        // Update data guru
        $guru->update([
            'nip'                => $data['nip'] ?? null,
            'kelas_id'           => $data['wali_kelas'] ?? null,
            'nama'               => $data['nama'] ?? null,
            'tempat_lahir'       => $data['tempat_lahir'] ?? null,
            'tanggal_lahir'      => $data['tanggal_lahir'] ?? null,
            'jk'                 => $data['jk'] ?? null,
            'status_pegawai'     => $data['status_pegawai'] ?? null,
            'pangkat_gol_ruang'  => $data['pangkat_gol_ruang'] ?? null,
            'no_sk_pertama'      => $data['no_sk_pertama'] ?? null,
            'no_sk_terakhir'     => $data['no_sk_terakhir'] ?? null,
            'pendidikan_terakhir'=> $data['pendidikan_terakhir'] ?? null,
        ]);

        return redirect()->route('guru.profil')->with('success', 'Profil berhasil diperbarui.');
    }
}