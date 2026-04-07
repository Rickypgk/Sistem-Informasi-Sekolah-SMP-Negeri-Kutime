<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\Kelas;
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
        $request->user()->load('siswa.kelas');
        return view('siswa.profil', ['user' => $request->user()]);
    }

    public function edit(Request $request): View
    {
        $request->user()->load('siswa.kelas');
        return view('siswa.profil-edit', [
            'user'      => $request->user(),
            'kelasList' => Kelas::orderBy('nama')->get(),
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        $user  = $request->user();

        // FIX: saat create profil baru, pastikan kolom NOT NULL terisi default
        $siswa = $user->siswa ?? $user->siswa()->create([
            'penerima_kps' => 0,
            'jk'           => 'L',
        ]);

        $rules = [
            // Akun
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'photo'    => ['nullable', 'image', 'max:2048'],

            // Identitas akademik
            'nidn'     => ['nullable', 'string', 'max:30', 'unique:siswas,nidn,' . $siswa->id],
            'kelas_id' => ['nullable', 'exists:kelas,id'],

            // Data diri
            'nik'                 => ['nullable', 'string', 'max:20'],
            'nama'                => ['nullable', 'string', 'max:255'],
            'jk'                  => ['nullable', 'in:L,P'],
            'tempat_lahir'        => ['nullable', 'string', 'max:100'],
            'tgl_lahir'           => ['nullable', 'date'],
            'agama'               => ['nullable', 'string', 'max:20'],
            'alamat'              => ['nullable', 'string'],
            'rt'                  => ['nullable', 'string', 'max:10'],
            'rw'                  => ['nullable', 'string', 'max:10'],
            'dusun'               => ['nullable', 'string', 'max:100'],
            'kecamatan'           => ['nullable', 'string', 'max:100'],
            'kode_pos'            => ['nullable', 'string', 'max:10'],
            'jenis_tinggal'       => ['nullable', 'string', 'max:50'],
            'jalan_transportasi'  => ['nullable', 'string', 'max:100'],
            'no_telp'             => ['nullable', 'string', 'max:20'],
            'shkun'               => ['nullable', 'string', 'max:50'],
            // FIX: validasi tetap pakai 'Ya'/'Tidak' untuk UX form,
            //      konversi ke 0/1 dilakukan sebelum disimpan ke DB
            'penerima_kps'        => ['nullable', 'in:Ya,Tidak'],
            'no_kps'              => ['nullable', 'string', 'max:50'],
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

        // FIX: konversi 'Ya'/'Tidak' → 1/0 sebelum disimpan (kolom TINYINT)
        $penerimarKps = match($data['penerima_kps'] ?? 'Tidak') {
            'Ya'    => 1,
            default => 0,
        };

        // Update data siswa
        $siswa->update([
            'nidn'                => $data['nidn']               ?? null,
            'kelas_id'            => $data['kelas_id']           ?? null,
            'nik'                 => $data['nik']                ?? null,
            'nama'                => $data['nama']               ?? null,
            'jk'                  => $data['jk']                 ?? null,
            'tempat_lahir'        => $data['tempat_lahir']       ?? null,
            'tgl_lahir'           => $data['tgl_lahir']          ?? null,
            'agama'               => $data['agama']              ?? null,
            'alamat'              => $data['alamat']             ?? null,
            'rt'                  => $data['rt']                 ?? null,
            'rw'                  => $data['rw']                 ?? null,
            'dusun'               => $data['dusun']              ?? null,
            'kecamatan'           => $data['kecamatan']          ?? null,
            'kode_pos'            => $data['kode_pos']           ?? null,
            'jenis_tinggal'       => $data['jenis_tinggal']      ?? null,
            'jalan_transportasi'  => $data['jalan_transportasi'] ?? null,
            'no_telp'             => $data['no_telp']            ?? null,
            'shkun'               => $data['shkun']              ?? null,
            'penerima_kps'        => $penerimarKps,              // integer 0/1
            'no_kps'              => $data['no_kps']             ?? null,
        ]);

        return redirect()->route('siswa.profil')->with('success', 'Profil berhasil diperbarui.');
    }
}