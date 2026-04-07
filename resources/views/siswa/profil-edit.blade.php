@extends('layouts.app')

@section('title', 'Edit Profil Siswa')

@section('content')
<div class="max-w-4xl">

    @if(session('success'))
        <div class="mb-4 px-4 py-3 bg-green-50 border border-green-200 text-green-700 rounded-lg text-sm">
            {{ session('success') }}
        </div>
    @endif

    <form action="{{ route('siswa.profil.update') }}" method="POST" enctype="multipart/form-data"
          class="space-y-6">
        @csrf
        @method('PUT')

        {{-- ======================== AKUN ======================== --}}
        <div class="bg-white rounded-xl shadow border border-slate-200 p-6">
            <h3 class="text-base font-semibold text-slate-700 mb-5 pb-2 border-b border-slate-100">
                Informasi Akun
            </h3>

            {{-- Foto --}}
            <div class="flex items-center gap-5 mb-6">
                <div id="photoPreviewWrap">
                    @if($user->photo)
                        <img id="photoPreview" src="{{ Storage::url($user->photo) }}" alt="Foto"
                             class="w-20 h-20 rounded-full object-cover border-2 border-slate-200">
                    @else
                        <div id="photoPreview"
                             class="w-20 h-20 rounded-full bg-indigo-100 flex items-center justify-center
                                    text-indigo-600 text-2xl font-bold">
                            {{ strtoupper(substr($user->name, 0, 1)) }}
                        </div>
                    @endif
                </div>
                <div>
                    <label class="inline-flex items-center gap-1.5 cursor-pointer px-3 py-1.5 rounded-lg
                                  border border-slate-300 text-sm text-slate-700 hover:bg-slate-50 transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        Ganti Foto
                        <input type="file" name="photo" id="photoInput" accept="image/*" class="hidden">
                    </label>
                    <p class="text-xs text-slate-400 mt-1">Max 2MB. Format: JPG, PNG</p>
                    @error('photo') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <x-form-group label="Nama Akun" name="name" required>
                    <x-form-input name="name" :value="old('name', $user->name)" required />
                </x-form-group>

                <x-form-group label="Email" name="email" required>
                    <x-form-input type="email" name="email" :value="old('email', $user->email)" required />
                </x-form-group>

                <x-form-group label="Password Baru" name="password">
                    <x-form-input type="password" name="password" placeholder="Kosongkan jika tidak diubah" />
                </x-form-group>

                <x-form-group label="Konfirmasi Password" name="password_confirmation">
                    <x-form-input type="password" name="password_confirmation" />
                </x-form-group>
            </div>
        </div>

        {{-- ======================== IDENTITAS AKADEMIK ======================== --}}
        <div class="bg-white rounded-xl shadow border border-slate-200 p-6">
            <h3 class="text-base font-semibold text-slate-700 mb-5 pb-2 border-b border-slate-100">
                Identitas Akademik
            </h3>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <x-form-group label="NIS / NIDN" name="nidn">
                    <x-form-input name="nidn" :value="old('nidn', $user->siswa?->nidn)" placeholder="Nomor Induk Siswa" />
                </x-form-group>

                <x-form-group label="Kelas" name="kelas_id">
                    <select name="kelas_id" id="kelas_id"
                            class="w-full rounded-lg border @error('kelas_id') border-red-400 @else border-slate-300 @enderror
                                   px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400">
                        <option value="">— Pilih Kelas —</option>
                        @foreach($kelasList as $kelas)
                            <option value="{{ $kelas->id }}"
                                {{ old('kelas_id', $user->siswa?->kelas_id) == $kelas->id ? 'selected' : '' }}>
                                {{ $kelas->nama }}
                            </option>
                        @endforeach
                    </select>
                    @error('kelas_id') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                </x-form-group>
            </div>
        </div>

        {{-- ======================== DATA PRIBADI ======================== --}}
        <div class="bg-white rounded-xl shadow border border-slate-200 p-6">
            <h3 class="text-base font-semibold text-slate-700 mb-5 pb-2 border-b border-slate-100">
                Data Pribadi
            </h3>
            @php $s = $user->siswa; @endphp
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">

                <x-form-group label="Nama Lengkap" name="nama" class="sm:col-span-2">
                    <x-form-input name="nama" :value="old('nama', $s?->nama)" />
                </x-form-group>

                <x-form-group label="NIK" name="nik">
                    <x-form-input name="nik" :value="old('nik', $s?->nik)" maxlength="20" />
                </x-form-group>

                <x-form-group label="Jenis Kelamin" name="jk">
                    <select name="jk"
                            class="w-full rounded-lg border @error('jk') border-red-400 @else border-slate-300 @enderror
                                   px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400">
                        <option value="">— Pilih —</option>
                        <option value="L" {{ old('jk', $s?->jk) === 'L' ? 'selected' : '' }}>Laki-laki</option>
                        <option value="P" {{ old('jk', $s?->jk) === 'P' ? 'selected' : '' }}>Perempuan</option>
                    </select>
                    @error('jk') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                </x-form-group>

                <x-form-group label="Tempat Lahir" name="tempat_lahir">
                    <x-form-input name="tempat_lahir" :value="old('tempat_lahir', $s?->tempat_lahir)" />
                </x-form-group>

                <x-form-group label="Tanggal Lahir" name="tgl_lahir">
                    <x-form-input type="date" name="tgl_lahir"
                        :value="old('tgl_lahir', $s?->tgl_lahir?->format('Y-m-d'))" />
                </x-form-group>

                <x-form-group label="Agama" name="agama">
                    <select name="agama"
                            class="w-full rounded-lg border @error('agama') border-red-400 @else border-slate-300 @enderror
                                   px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400">
                        <option value="">— Pilih —</option>
                        @foreach(['Islam','Kristen','Katholik','Hindu','Buddha','Konghucu'] as $ag)
                            <option value="{{ $ag }}" {{ old('agama', $s?->agama) === $ag ? 'selected' : '' }}>
                                {{ $ag }}
                            </option>
                        @endforeach
                    </select>
                    @error('agama') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                </x-form-group>

                <x-form-group label="No. Telepon" name="no_telp">
                    <x-form-input name="no_telp" :value="old('no_telp', $s?->no_telp)" maxlength="20" />
                </x-form-group>

                <x-form-group label="SKHUN" name="shkun">
                    <x-form-input name="shkun" :value="old('shkun', $s?->shkun)" maxlength="50" />
                </x-form-group>

            </div>
        </div>

        {{-- ======================== ALAMAT ======================== --}}
        <div class="bg-white rounded-xl shadow border border-slate-200 p-6">
            <h3 class="text-base font-semibold text-slate-700 mb-5 pb-2 border-b border-slate-100">
                Alamat
            </h3>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">

                <div class="sm:col-span-2">
                    <label class="block text-sm font-medium text-slate-700 mb-1">Alamat</label>
                    <textarea name="alamat" rows="3"
                              class="w-full rounded-lg border @error('alamat') border-red-400 @else border-slate-300 @enderror
                                     px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400 resize-none">{{ old('alamat', $s?->alamat) }}</textarea>
                    @error('alamat') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>

                <x-form-group label="RT" name="rt">
                    <x-form-input name="rt" :value="old('rt', $s?->rt)" maxlength="10" />
                </x-form-group>

                <x-form-group label="RW" name="rw">
                    <x-form-input name="rw" :value="old('rw', $s?->rw)" maxlength="10" />
                </x-form-group>

                <x-form-group label="Dusun" name="dusun">
                    <x-form-input name="dusun" :value="old('dusun', $s?->dusun)" />
                </x-form-group>

                <x-form-group label="Kecamatan" name="kecamatan">
                    <x-form-input name="kecamatan" :value="old('kecamatan', $s?->kecamatan)" />
                </x-form-group>

                <x-form-group label="Kode Pos" name="kode_pos">
                    <x-form-input name="kode_pos" :value="old('kode_pos', $s?->kode_pos)" maxlength="10" />
                </x-form-group>

                <x-form-group label="Jenis Tinggal" name="jenis_tinggal">
                    <select name="jenis_tinggal"
                            class="w-full rounded-lg border @error('jenis_tinggal') border-red-400 @else border-slate-300 @enderror
                                   px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400">
                        <option value="">— Pilih —</option>
                        @foreach(['Bersama Orang Tua','Wali','Kos','Asrama','Panti Asuhan','Lainnya'] as $jt)
                            <option value="{{ $jt }}" {{ old('jenis_tinggal', $s?->jenis_tinggal) === $jt ? 'selected' : '' }}>
                                {{ $jt }}
                            </option>
                        @endforeach
                    </select>
                    @error('jenis_tinggal') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                </x-form-group>

                <x-form-group label="Alat Transportasi" name="jalan_transportasi" class="sm:col-span-2">
                    <select name="jalan_transportasi"
                            class="w-full rounded-lg border @error('jalan_transportasi') border-red-400 @else border-slate-300 @enderror
                                   px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400">
                        <option value="">— Pilih —</option>
                        @foreach(['Jalan Kaki','Sepeda','Sepeda Motor','Mobil Pribadi','Angkutan Umum','Antar Jemput','Lainnya'] as $tr)
                            <option value="{{ $tr }}" {{ old('jalan_transportasi', $s?->jalan_transportasi) === $tr ? 'selected' : '' }}>
                                {{ $tr }}
                            </option>
                        @endforeach
                    </select>
                    @error('jalan_transportasi') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                </x-form-group>

            </div>
        </div>

        {{-- ======================== INFORMASI BANTUAN ======================== --}}
        <div class="bg-white rounded-xl shadow border border-slate-200 p-6">
            <h3 class="text-base font-semibold text-slate-700 mb-5 pb-2 border-b border-slate-100">
                Informasi Bantuan
            </h3>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">

                <x-form-group label="Penerima KPS" name="penerima_kps">
                    <select name="penerima_kps"
                            class="w-full rounded-lg border @error('penerima_kps') border-red-400 @else border-slate-300 @enderror
                                   px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400"
                            id="penerimaKps">
                        <option value="Tidak" {{ old('penerima_kps', $s?->penerima_kps ?? 'Tidak') === 'Tidak' ? 'selected' : '' }}>Tidak</option>
                        <option value="Ya"    {{ old('penerima_kps', $s?->penerima_kps) === 'Ya' ? 'selected' : '' }}>Ya</option>
                    </select>
                    @error('penerima_kps') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                </x-form-group>

                <div id="noKpsGroup" class="{{ old('penerima_kps', $s?->penerima_kps) !== 'Ya' ? 'opacity-40 pointer-events-none' : '' }}">
                    <x-form-group label="No. KPS" name="no_kps">
                        <x-form-input name="no_kps" :value="old('no_kps', $s?->no_kps)" maxlength="50" />
                    </x-form-group>
                </div>

            </div>
        </div>

        {{-- ======================== TOMBOL ======================== --}}
        <div class="flex items-center gap-3 justify-end">
            <a href="{{ route('siswa.profil') }}"
               class="px-4 py-2 rounded-lg border border-slate-300 text-slate-700 text-sm hover:bg-slate-50 transition">
                Batal
            </a>
            <button type="submit"
                    class="px-5 py-2 rounded-lg bg-indigo-600 text-white text-sm font-medium hover:bg-indigo-700 transition">
                Simpan Perubahan
            </button>
        </div>

    </form>
</div>

@push('scripts')
<script>
    // Preview foto sebelum upload
    document.getElementById('photoInput').addEventListener('change', function (e) {
        const file = e.target.files[0];
        if (!file) return;
        const reader = new FileReader();
        reader.onload = (ev) => {
            const wrap = document.getElementById('photoPreviewWrap');
            wrap.innerHTML = `<img src="${ev.target.result}" alt="Preview"
                class="w-20 h-20 rounded-full object-cover border-2 border-slate-200">`;
        };
        reader.readAsDataURL(file);
    });

    // Toggle No. KPS berdasarkan penerima_kps
    const kpsSelect = document.getElementById('penerimaKps');
    const noKpsGroup = document.getElementById('noKpsGroup');
    kpsSelect.addEventListener('change', function () {
        if (this.value === 'Ya') {
            noKpsGroup.classList.remove('opacity-40', 'pointer-events-none');
        } else {
            noKpsGroup.classList.add('opacity-40', 'pointer-events-none');
        }
    });
</script>
@endpush
@endsection