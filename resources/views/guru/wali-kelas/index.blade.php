@extends('layouts.app')

@section('title', 'Daftar Siswa Wali Kelas')

@section('content')

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 space-y-6">

    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-800 dark:text-slate-100">
                Siswa Wali Kelas
            </h1>
            @if($kelas)
                <p class="text-slate-600 dark:text-slate-400 mt-1">
                    {{ $kelas->nama }} • {{ $kelas->tingkat }} • {{ $kelas->tahun_ajaran }}
                </p>
            @else
                <p class="text-slate-500 dark:text-slate-400 mt-1 italic">
                    Anda belum ditugaskan sebagai wali kelas.
                </p>
            @endif
        </div>

        <div class="flex items-center gap-3">
            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-indigo-100 text-indigo-700 dark:bg-indigo-900/40 dark:text-indigo-300">
                {{ $siswa->count() }} Siswa
            </span>
        </div>
    </div>

    @if(!$kelas || $siswa->isEmpty())
        <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 shadow-sm p-10 text-center">
            <div class="text-6xl mb-4 text-slate-300 dark:text-slate-600">👩‍🎓👨‍🎓</div>
            <h3 class="text-xl font-semibold text-slate-700 dark:text-slate-300 mb-2">
                @if(!$kelas) Belum Menjadi Wali Kelas @else Kelas Masih Kosong @endif
            </h3>
            <p class="text-slate-500 dark:text-slate-400 max-w-md mx-auto">
                @if(!$kelas) Hubungi admin untuk ditugaskan sebagai wali kelas.
                @else Kelas ini belum memiliki siswa yang terdaftar. @endif
            </p>
        </div>
    @else
        <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left">
                    <thead class="bg-slate-50 dark:bg-slate-700/30">
                        <tr>
                            <th class="px-6 py-4 font-medium text-slate-600 dark:text-slate-300 w-14">#</th>
                            <th class="px-6 py-4 font-medium text-slate-600 dark:text-slate-300">Foto</th>
                            <th class="px-6 py-4 font-medium text-slate-600 dark:text-slate-300">Nama Lengkap</th>
                            <th class="px-6 py-4 font-medium text-slate-600 dark:text-slate-300">NIS/NIK</th>
                            <th class="px-6 py-4 font-medium text-slate-600 dark:text-slate-300 text-center">JK</th>
                            <th class="px-6 py-4 font-medium text-slate-600 dark:text-slate-300 text-center">Tgl Lahir</th>
                            <th class="px-6 py-4 font-medium text-slate-600 dark:text-slate-300 text-center">Terlambat Bulan Ini</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-700/50">
                        @foreach($siswa as $index => $s)
                            <tr class="hover:bg-slate-50 dark:hover:bg-slate-700/20 transition-colors">
                                <td class="px-6 py-4 text-slate-500 dark:text-slate-400">{{ $index + 1 }}</td>
                                <td class="px-6 py-4">
                                    @if($s->foto)
                                        <img src="{{ $s->foto }}" alt="{{ $s->nama_tampil }}" class="w-10 h-10 rounded-full object-cover border-2 border-indigo-100 dark:border-indigo-900/40">
                                    @else
                                        <div class="w-10 h-10 rounded-full bg-indigo-100 dark:bg-indigo-900/40 flex items-center justify-center text-indigo-700 dark:text-indigo-300 font-bold text-lg">
                                            {{ $s->inisial }}
                                        </div>
                                    @endif
                                </td>
                                <td class="px-6 py-4 font-medium text-slate-800 dark:text-slate-100">
                                    {{ $s->nama_tampil }}
                                </td>
                                <td class="px-6 py-4 text-slate-600 dark:text-slate-300 font-mono text-sm">
                                    {{ $s->nis ?? $s->nik ?? '—' }}
                                </td>
                                <td class="px-6 py-4 text-center">
                                    @if($s->jk === 'L')
                                        <span class="inline-flex px-2.5 py-1 text-xs font-medium rounded-full bg-blue-100 text-blue-700 dark:bg-blue-900/40 dark:text-blue-300">L</span>
                                    @elseif($s->jk === 'P')
                                        <span class="inline-flex px-2.5 py-1 text-xs font-medium rounded-full bg-pink-100 text-pink-700 dark:bg-pink-900/40 dark:text-pink-300">P</span>
                                    @else
                                        <span class="text-slate-400">—</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-center text-slate-600 dark:text-slate-300">
                                    {{ $s->tanggal_lahir ? $s->tanggal_lahir->format('d/m/Y') : '—' }}
                                </td>
                                <td class="px-6 py-4 text-center">
                                    @if($s->terlambat_count > 3)
                                        <span class="inline-flex items-center px-3 py-1.5 text-xs font-medium rounded-full bg-red-100 text-red-700 dark:bg-red-900/50 dark:text-red-300 shadow-sm">
                                            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                            Terlambat {{ $s->terlambat_count }}x
                                        </span>
                                    @else
                                        <span class="text-slate-500 dark:text-slate-400 text-xs">
                                            {{ $s->terlambat_count }}x
                                        </span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif

</div>

@endsection