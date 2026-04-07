@extends('layouts.app')

@section('title', 'Data Diri Siswa')

@section('content')
<div class="max-w-4xl space-y-6">

    {{-- Header --}}
    <div class="bg-white rounded-xl shadow border border-slate-200 p-6">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-xl font-semibold text-slate-800">Profil Siswa</h2>
            <a href="{{ route('siswa.profil.edit') }}"
               class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg bg-indigo-600 text-white text-sm font-medium hover:bg-indigo-700 transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>
                </svg>
                Edit Profil
            </a>
        </div>

        {{-- Foto & Info Akun --}}
        <div class="flex flex-col sm:flex-row gap-6 items-start">
            <div class="shrink-0">
                @if($user->photo)
                    <img src="{{ Storage::url($user->photo) }}" alt="Foto Profil"
                         class="w-32 h-44 object-cover border-2 border-slate-300 rounded-lg shadow-md">
                @else
                    <div class="w-32 h-44 rounded-lg bg-indigo-100 flex items-center justify-center
                                text-indigo-600 text-5xl font-bold shadow-md">
                        {{ strtoupper(substr($user->name, 0, 1)) }}
                    </div>
                @endif
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-x-8 gap-y-4 flex-1">
                <div>
                    <p class="text-sm text-slate-500 mb-1">Nama Akun</p>
                    <p class="font-medium text-slate-800 text-base">{{ $user->name }}</p>
                </div>
                <div>
                    <p class="text-sm text-slate-500 mb-1">Email</p>
                    <p class="text-slate-800 text-base">{{ $user->email }}</p>
                </div>
                <div>
                    <p class="text-sm text-slate-500 mb-1">NIS / NIDN</p>
                    <p class="text-slate-800 text-base">{{ $user->siswa?->nidn ?? '-' }}</p>
                </div>
                <div>
                    <p class="text-sm text-slate-500 mb-1">Kelas</p>
                    <p class="text-slate-800 text-base">{{ $user->siswa?->kelas?->nama ?? '-' }}</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Section: Data Pribadi --}}
    <div class="bg-white rounded-xl shadow border border-slate-200 p-6">
        <h3 class="text-base font-semibold text-slate-700 mb-4 pb-2 border-b border-slate-100">
            Data Pribadi
        </h3>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-x-8 gap-y-4">
            @php $s = $user->siswa; @endphp

            <x-profil-field label="Nama Lengkap"  :value="$s?->nama" />
            <x-profil-field label="NIK"            :value="$s?->nik" />
            <x-profil-field label="Jenis Kelamin"
                :value="$s?->jk === 'L' ? 'Laki-laki' : ($s?->jk === 'P' ? 'Perempuan' : null)" />
            <x-profil-field label="Tempat Lahir"   :value="$s?->tempat_lahir" />
            <x-profil-field label="Tanggal Lahir"
                :value="$s?->tgl_lahir?->translatedFormat('d F Y')" />
            <x-profil-field label="Agama"          :value="$s?->agama" />
            <x-profil-field label="No. Telepon"    :value="$s?->no_telp" />
            <x-profil-field label="SKHUN"          :value="$s?->shkun" />
        </div>
    </div>

    {{-- Section: Alamat --}}
    <div class="bg-white rounded-xl shadow border border-slate-200 p-6">
        <h3 class="text-base font-semibold text-slate-700 mb-4 pb-2 border-b border-slate-100">
            Alamat
        </h3>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-x-8 gap-y-4">
            <div class="sm:col-span-2">
                <p class="text-xs text-slate-500 mb-0.5">Alamat</p>
                <p class="text-slate-800">{{ $s?->alamat ?? '-' }}</p>
            </div>
            <x-profil-field label="RT"           :value="$s?->rt" />
            <x-profil-field label="RW"           :value="$s?->rw" />
            <x-profil-field label="Dusun"        :value="$s?->dusun" />
            <x-profil-field label="Kecamatan"    :value="$s?->kecamatan" />
            <x-profil-field label="Kode Pos"     :value="$s?->kode_pos" />
            <x-profil-field label="Jenis Tinggal" :value="$s?->jenis_tinggal" />
            <x-profil-field label="Transportasi" :value="$s?->jalan_transportasi" />
        </div>
    </div>

    {{-- Section: Bantuan / KPS --}}
    <div class="bg-white rounded-xl shadow border border-slate-200 p-6">
        <h3 class="text-base font-semibold text-slate-700 mb-4 pb-2 border-b border-slate-100">
            Informasi Bantuan
        </h3>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-x-8 gap-y-4">
            <div>
                <p class="text-xs text-slate-500 mb-0.5">Penerima KPS</p>
                @if($s?->penerima_kps === 'Ya')
                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium
                                 bg-green-100 text-green-700">Ya</span>
                @else
                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium
                                 bg-slate-100 text-slate-600">Tidak</span>
                @endif
            </div>
            <x-profil-field label="No. KPS" :value="$s?->no_kps" />
        </div>
    </div>

</div>
@endsection