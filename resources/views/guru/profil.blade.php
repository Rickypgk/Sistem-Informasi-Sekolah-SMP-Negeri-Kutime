@extends('layouts.app')

@section('title', 'Data Diri Guru')

@section('content')
<div class="max-w-4xl space-y-6">

    {{-- Flash Messages --}}
    @if(session('success'))
        <div class="px-4 py-3 bg-green-50 border border-green-200 text-green-700 rounded-lg text-sm">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error') || $errors->any())
        <div class="px-4 py-3 bg-red-50 border border-red-200 text-red-700 rounded-lg text-sm">
            {{ session('error') }}
            @if($errors->any())
                <ul class="list-disc list-inside mt-1">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            @endif
        </div>
    @endif

    {{-- ======================== PROFIL GURU ======================== --}}
    <div class="bg-white rounded-xl shadow border border-slate-200 p-6">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-xl font-semibold text-slate-800">Profil Guru</h2>
            <a href="{{ route('guru.profil.edit') }}"
               class="inline-flex items-center gap-1.5 px-4 py-2 rounded-lg bg-indigo-600 text-white
                      text-sm font-medium hover:bg-indigo-700 transition shadow-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>
                </svg>
                Edit Profil
            </a>
        </div>

        <div class="flex flex-col sm:flex-row gap-8 items-start">
            {{-- Foto Profil --}}
            <div class="shrink-0">
                @if($user->photo)
                    <img src="{{ Storage::url($user->photo) }}" alt="Foto Profil Guru"
                         class="w-40 h-[213px] object-cover border-2 border-slate-300 rounded-xl shadow-md">
                @else
                    <div class="w-40 h-[213px] rounded-xl bg-indigo-100 flex items-center justify-center
                                text-indigo-700 text-6xl font-bold shadow-md">
                        {{ strtoupper(substr($user->name, 0, 1)) }}
                    </div>
                @endif
            </div>

            {{-- Informasi Akun --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-x-10 gap-y-5 flex-1">
                <div>
                    <p class="text-sm text-slate-500 mb-1">Nama Akun</p>
                    <p class="font-medium text-slate-800 text-base">{{ $user->name }}</p>
                </div>

                <div>
                    <p class="text-sm text-slate-500 mb-1">Email</p>
                    <p class="text-slate-800 text-base">{{ $user->email }}</p>
                </div>

                <div>
                    <p class="text-sm text-slate-500 mb-1">NIP</p>
                    <p class="text-slate-800 text-base">{{ $user->guru?->nip ?? '—' }}</p>
                </div>

                <div>
                    <p class="text-sm text-slate-500 mb-1">Wali Kelas</p>
                    @if($user->guru?->kelas)
                        <p class="text-slate-800 text-base font-medium">
                            {{ $user->guru->kelas->nama }}
                            <span class="text-sm text-slate-500 ml-2">
                                ({{ $user->guru->kelas->tingkat }} • {{ $user->guru->kelas->tahun_ajaran }})
                            </span>
                        </p>
                    @else
                        <p class="text-slate-500 text-base italic">— Belum menjadi wali kelas —</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- Data Pribadi & Kepegawaian (tetap sama seperti sebelumnya) --}}
    @php $g = $user->guru; @endphp

    <div class="bg-white rounded-xl shadow border border-slate-200 p-6">
        <h3 class="text-base font-semibold text-slate-700 mb-4 pb-2 border-b border-slate-100">
            Data Pribadi
        </h3>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-x-8 gap-y-4">
            <x-profil-field label="Nama Lengkap"      :value="$g?->nama ?? '—'" />
            <x-profil-field label="Jenis Kelamin"
                :value="$g?->jk === 'L' ? 'Laki-laki' : ($g?->jk === 'P' ? 'Perempuan' : '—')" />
            <x-profil-field label="Tempat Lahir"      :value="$g?->tempat_lahir ?? '—'" />
            <x-profil-field label="Tanggal Lahir"
                :value="$g?->tanggal_lahir?->translatedFormat('d F Y') ?? '—'" />
            <x-profil-field label="Pendidikan Terakhir" :value="$g?->pendidikan_terakhir ?? '—'" />
        </div>
    </div>

    <div class="bg-white rounded-xl shadow border border-slate-200 p-6">
        <h3 class="text-base font-semibold text-slate-700 mb-4 pb-2 border-b border-slate-100">
            Data Kepegawaian
        </h3>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-x-8 gap-y-4">
            <x-profil-field label="Status Pegawai"       :value="$g?->status_pegawai ?? '—'" />
            <x-profil-field label="Pangkat / Gol. Ruang" :value="$g?->pangkat_gol_ruang ?? '—'" />
            <x-profil-field label="No. SK Pertama"       :value="$g?->no_sk_pertama ?? '—'" />
            <x-profil-field label="No. SK Terakhir"      :value="$g?->no_sk_terakhir ?? '—'" />
        </div>
    </div>

</div>
@endsection