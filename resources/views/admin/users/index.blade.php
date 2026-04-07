{{-- resources/views/admin/users/index.blade.php --}}
@extends('layouts.app')
@section('title', 'Kelola User')

@section('content')

<div class="space-y-4">

    {{-- PAGE HEADER --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
        <div>
            <h2 class="text-sm font-bold text-slate-800 dark:text-slate-100">Kelola User</h2>
            <p class="text-xs text-slate-400 dark:text-slate-500 mt-0.5">
                Manajemen akun guru dan siswa pada sistem.
            </p>
        </div>

        {{-- Tombol aksi utama --}}
        <div class="flex flex-wrap items-center gap-2">

            {{-- Import --}}
            <button onclick="openModal('modalImport')"
                    class="inline-flex items-center gap-1.5 px-3 py-2 rounded-xl
                           bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-semibold
                           transition shadow-sm active:scale-95">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
                </svg>
                Import Excel
            </button>

            {{-- Export dropdown --}}
            <div class="relative" x-data="{ open: false }">
                <button @click="open = !open" @click.outside="open = false"
                        class="inline-flex items-center gap-1.5 px-3 py-2 rounded-xl
                               bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-600
                               text-slate-700 dark:text-slate-300 text-xs font-semibold
                               hover:bg-slate-50 dark:hover:bg-slate-700 transition shadow-sm active:scale-95">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                    </svg>
                    Export
                    <svg class="w-3 h-3 transition-transform" :class="open ? 'rotate-180' : ''"
                         fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                    </svg>
                </button>

                <div x-show="open"
                     x-transition:enter="transition ease-out duration-100"
                     x-transition:enter-start="opacity-0 scale-95"
                     x-transition:enter-end="opacity-100 scale-100"
                     x-transition:leave="transition ease-in duration-75"
                     x-transition:leave-start="opacity-100 scale-100"
                     x-transition:leave-end="opacity-0 scale-95"
                     x-cloak
                     class="absolute right-0 mt-1.5 w-52 bg-white dark:bg-slate-800 rounded-xl
                            shadow-xl border border-slate-200 dark:border-slate-700 py-1 z-30">

                    <a href="{{ route('admin.users.export-excel', ['role' => 'guru']) }}"
                       class="flex items-center gap-2.5 px-4 py-2.5 text-xs font-medium
                              text-slate-700 dark:text-slate-300
                              hover:bg-slate-50 dark:hover:bg-slate-700/60 transition">
                        <span class="w-6 h-6 rounded-lg bg-emerald-50 dark:bg-emerald-900/30
                                     flex items-center justify-center shrink-0">
                            <svg class="w-3.5 h-3.5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1
                                         1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                        </span>
                        Excel — Data Guru
                    </a>

                    <a href="{{ route('admin.users.export-excel', ['role' => 'siswa']) }}"
                       class="flex items-center gap-2.5 px-4 py-2.5 text-xs font-medium
                              text-slate-700 dark:text-slate-300
                              hover:bg-slate-50 dark:hover:bg-slate-700/60 transition">
                        <span class="w-6 h-6 rounded-lg bg-emerald-50 dark:bg-emerald-900/30
                                     flex items-center justify-center shrink-0">
                            <svg class="w-3.5 h-3.5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1
                                         1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                        </span>
                        Excel — Data Siswa
                    </a>

                    <div class="border-t border-slate-100 dark:border-slate-700 my-1"></div>

                    <a href="{{ route('admin.users.export-pdf', ['role' => 'guru']) }}"
                       class="flex items-center gap-2.5 px-4 py-2.5 text-xs font-medium
                              text-slate-700 dark:text-slate-300
                              hover:bg-slate-50 dark:hover:bg-slate-700/60 transition">
                        <span class="w-6 h-6 rounded-lg bg-red-50 dark:bg-red-900/30
                                     flex items-center justify-center shrink-0">
                            <svg class="w-3.5 h-3.5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1
                                         1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                            </svg>
                        </span>
                        PDF — Data Guru
                    </a>

                    <a href="{{ route('admin.users.export-pdf', ['role' => 'siswa']) }}"
                       class="flex items-center gap-2.5 px-4 py-2.5 text-xs font-medium
                              text-slate-700 dark:text-slate-300
                              hover:bg-slate-50 dark:hover:bg-slate-700/60 transition">
                        <span class="w-6 h-6 rounded-lg bg-red-50 dark:bg-red-900/30
                                     flex items-center justify-center shrink-0">
                            <svg class="w-3.5 h-3.5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1
                                         1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                            </svg>
                        </span>
                        PDF — Data Siswa
                    </a>
                </div>
            </div>

            {{-- Tambah User --}}
            <button onclick="openModal('modalTambahUser')"
                    class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl bg-indigo-600 text-white
                           text-xs font-semibold hover:bg-indigo-700 active:scale-95 transition shadow-sm">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Tambah User
            </button>
        </div>
    </div>

    {{-- Import errors flash --}}
    @if(session('import_errors'))
    <div class="bg-amber-50 dark:bg-amber-900/20 border border-amber-200 dark:border-amber-700 rounded-2xl p-4">
        <div class="flex items-start gap-2.5">
            <svg class="w-4 h-4 text-amber-600 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/>
            </svg>
            <div>
                <p class="text-xs font-semibold text-amber-700 dark:text-amber-400 mb-1">
                    Beberapa baris yang dilewati saat impor:
                </p>
                <ul class="space-y-0.5">
                    @foreach(session('import_errors') as $err)
                    <li class="text-[10px] text-amber-600 dark:text-amber-400">• {{ $err }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
    @endif

    {{-- TAB NAVIGATION --}}
    <div class="flex items-center gap-1 bg-slate-100 dark:bg-slate-800 p-1 rounded-xl w-fit">
        <a href="{{ route('admin.users.index', ['tab' => 'guru']) }}"
           class="flex items-center gap-1.5 px-4 py-1.5 rounded-lg text-xs font-semibold
                  transition-all duration-200
                  {{ $activeTab === 'guru'
                        ? 'bg-white dark:bg-slate-700 text-indigo-700 dark:text-indigo-300 shadow-sm'
                        : 'text-slate-500 hover:text-slate-700 dark:text-slate-400 dark:hover:text-slate-200' }}">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
            </svg>
            Guru
            <span class="inline-flex items-center justify-center min-w-[1.1rem] h-4 px-1 rounded-full
                         text-[10px] font-semibold
                         {{ $activeTab === 'guru'
                                ? 'bg-indigo-100 text-indigo-700 dark:bg-indigo-900/40 dark:text-indigo-300'
                                : 'bg-slate-200 text-slate-500 dark:bg-slate-700 dark:text-slate-400' }}">
                {{ $gurus->count() }}
            </span>
        </a>
        <a href="{{ route('admin.users.index', ['tab' => 'siswa']) }}"
           class="flex items-center gap-1.5 px-4 py-1.5 rounded-lg text-xs font-semibold
                  transition-all duration-200
                  {{ $activeTab === 'siswa'
                        ? 'bg-white dark:bg-slate-700 text-indigo-700 dark:text-indigo-300 shadow-sm'
                        : 'text-slate-500 hover:text-slate-700 dark:text-slate-400 dark:hover:text-slate-200' }}">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13
                         C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477
                         14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247
                         18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
            </svg>
            Siswa
            <span class="inline-flex items-center justify-center min-w-[1.1rem] h-4 px-1 rounded-full
                         text-[10px] font-semibold
                         {{ $activeTab === 'siswa'
                                ? 'bg-indigo-100 text-indigo-700 dark:bg-indigo-900/40 dark:text-indigo-300'
                                : 'bg-slate-200 text-slate-500 dark:bg-slate-700 dark:text-slate-400' }}">
                {{ $siswas->count() }}
            </span>
        </a>
    </div>

    {{-- SEARCH --}}
    <div class="relative max-w-xs">
        <svg class="absolute left-2.5 top-1/2 -translate-y-1/2 w-3.5 h-3.5 text-slate-400"
             fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0"/>
        </svg>
        <input type="text" id="searchInput"
               placeholder="Cari nama, email, NIP / NIS..."
               class="w-full pl-8 pr-4 py-2 rounded-xl border border-slate-200 dark:border-slate-600
                      bg-white dark:bg-slate-800 text-xs
                      focus:outline-none focus:ring-2 focus:ring-indigo-300
                      placeholder:text-slate-400 transition">
    </div>

    {{-- TAB CONTENT --}}
    <div id="tab-guru" class="{{ $activeTab !== 'guru' ? 'hidden' : '' }}">
        @include('admin.users._table_guru', ['users' => $gurus])
    </div>
    <div id="tab-siswa" class="{{ $activeTab !== 'siswa' ? 'hidden' : '' }}">
        @include('admin.users._table_siswa', ['users' => $siswas])
    </div>

</div>


{{-- ══════════════════════════════════════════════════════════════
     MODAL IMPORT EXCEL
══════════════════════════════════════════════════════════════ --}}
<div id="modalImport"
     class="fixed inset-0 z-50 hidden items-center justify-center p-4"
     style="background:rgba(0,0,0,.5);backdrop-filter:blur(4px)">
    <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-2xl border border-slate-200
                dark:border-slate-700 w-full max-w-md animate-modal">

        <div class="flex items-center justify-between px-5 py-4 border-b border-slate-100 dark:border-slate-700">
            <div class="flex items-center gap-2.5">
                <div class="w-7 h-7 rounded-lg bg-emerald-100 dark:bg-emerald-900/30 flex items-center justify-center shrink-0">
                    <svg class="w-4 h-4 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
                    </svg>
                </div>
                <div>
                    <h3 class="text-sm font-bold text-slate-800 dark:text-slate-100 leading-tight">Import dari Excel</h3>
                    <p class="text-[10px] text-slate-400">Upload file .xlsx atau .xls</p>
                </div>
            </div>
            <button onclick="closeModal('modalImport')"
                    class="p-1.5 rounded-lg text-slate-400 hover:text-slate-600 hover:bg-slate-100 dark:hover:bg-slate-700 transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        <form method="POST" action="{{ route('admin.users.import') }}" enctype="multipart/form-data" class="p-5 space-y-4">
            @csrf
            <div>
                <label class="block text-[10px] font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wide mb-2">Import sebagai</label>
                <div class="grid grid-cols-2 gap-2" id="importRoleCards">
                    <label class="import-role-card flex items-center gap-2 px-3 py-2.5 rounded-xl border-2
                                  cursor-pointer transition-all border-indigo-500 bg-indigo-50 dark:bg-indigo-900/20 dark:border-indigo-500">
                        <input type="radio" name="role" value="guru" class="sr-only" checked>
                        <svg class="w-4 h-4 text-indigo-600 dark:text-indigo-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                        <span class="text-xs font-semibold text-indigo-700 dark:text-indigo-300">Guru</span>
                    </label>
                    <label class="import-role-card flex items-center gap-2 px-3 py-2.5 rounded-xl border-2
                                  cursor-pointer transition-all border-slate-200 bg-white dark:border-slate-600 dark:bg-slate-700/30">
                        <input type="radio" name="role" value="siswa" class="sr-only">
                        <svg class="w-4 h-4 text-slate-500 dark:text-slate-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13
                                     C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477
                                     14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247
                                     18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                        </svg>
                        <span class="text-xs font-semibold text-slate-600 dark:text-slate-400">Siswa</span>
                    </label>
                </div>
            </div>

            <div>
                <label class="block text-[10px] font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wide mb-1.5">File Excel</label>
                <div class="border-2 border-dashed border-slate-300 dark:border-slate-600 rounded-xl p-5 text-center hover:border-emerald-400 transition-colors cursor-pointer"
                     onclick="document.getElementById('importFileInput').click()">
                    <svg class="w-8 h-8 text-slate-400 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                              d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1
                                 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    <p class="text-xs text-slate-500 dark:text-slate-400 font-medium">Klik untuk pilih file Excel</p>
                    <p class="text-[10px] text-slate-400 mt-0.5">.xlsx atau .xls — maks. 5 MB</p>
                    <input type="file" id="importFileInput" name="import_file" accept=".xlsx,.xls" class="hidden" onchange="showImportFile(this)">
                </div>
                <div id="importFileBox" class="mt-2 hidden">
                    <div class="flex items-center gap-2 px-3 py-2.5 bg-emerald-50 dark:bg-emerald-900/20 rounded-xl border border-emerald-200 dark:border-emerald-700">
                        <svg class="w-4 h-4 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1
                                     1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        <div class="min-w-0">
                            <p id="importFileName" class="text-xs font-semibold text-emerald-700 dark:text-emerald-300 truncate"></p>
                            <p id="importFileSize" class="text-[10px] text-emerald-500"></p>
                        </div>
                        <button type="button" onclick="clearImportFile()"
                                class="shrink-0 p-0.5 text-emerald-500 hover:text-red-500 transition">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>
                </div>
                @error('import_file')
                <p class="text-[10px] text-red-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex items-start gap-2 p-3 bg-sky-50 dark:bg-sky-900/20 rounded-xl border border-sky-200 dark:border-sky-700">
                <svg class="w-3.5 h-3.5 text-sky-600 dark:text-sky-400 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <div>
                    <p class="text-[10px] text-sky-700 dark:text-sky-400 font-medium mb-1">Belum punya template? Unduh terlebih dahulu:</p>
                    <div class="flex gap-2">
                        <a href="{{ route('admin.users.template-import', ['role' => 'guru']) }}" target="_blank"
                           class="inline-flex items-center gap-1 px-2.5 py-1 bg-sky-100 hover:bg-sky-200
                                  dark:bg-sky-900/30 dark:hover:bg-sky-900/50 text-sky-700 dark:text-sky-300
                                  text-[10px] font-semibold rounded-lg transition">⬇ Template Guru</a>
                        <a href="{{ route('admin.users.template-import', ['role' => 'siswa']) }}" target="_blank"
                           class="inline-flex items-center gap-1 px-2.5 py-1 bg-sky-100 hover:bg-sky-200
                                  dark:bg-sky-900/30 dark:hover:bg-sky-900/50 text-sky-700 dark:text-sky-300
                                  text-[10px] font-semibold rounded-lg transition">⬇ Template Siswa</a>
                    </div>
                </div>
            </div>

            <div class="flex gap-2 pt-1">
                <button type="submit"
                        class="flex-1 py-2 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white
                               text-xs font-bold transition flex items-center justify-center gap-1.5">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
                    </svg>
                    Proses Import
                </button>
                <button type="button" onclick="closeModal('modalImport')"
                        class="px-4 py-2 rounded-xl border border-slate-200 dark:border-slate-600
                               text-slate-600 dark:text-slate-400 text-xs font-semibold
                               hover:bg-slate-50 dark:hover:bg-slate-700 transition">Batal</button>
            </div>
        </form>
    </div>
</div>


{{-- ══════════════════════════════════════════════════════════════
     MODAL EDIT USER
     FIX: field guru & siswa dipisah dengan name yang unik,
          field yang tidak aktif di-disable saat submit
══════════════════════════════════════════════════════════════ --}}
<div id="modalEditUser"
     class="fixed inset-0 z-50 hidden items-center justify-center p-4"
     style="background:rgba(0,0,0,.5);backdrop-filter:blur(4px)">
    <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-2xl border border-slate-200
                dark:border-slate-700 w-full max-w-lg animate-modal max-h-[90vh] overflow-y-auto">

        {{-- Header --}}
        <div class="flex items-center justify-between px-5 py-4 border-b border-slate-100 dark:border-slate-700 sticky top-0 bg-white dark:bg-slate-800 z-10">
            <div class="flex items-center gap-2.5">
                <div class="w-7 h-7 rounded-lg bg-indigo-100 dark:bg-indigo-900/30
                            flex items-center justify-center shrink-0">
                    <svg class="w-4 h-4 text-indigo-600 dark:text-indigo-400" fill="none"
                         stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0
                                 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828
                                 15H9v-2.828l8.586-8.586z"/>
                    </svg>
                </div>
                <div>
                    <h3 class="text-sm font-bold text-slate-800 dark:text-slate-100 leading-tight">
                        Edit User
                    </h3>
                    <p class="text-[10px] text-slate-400" id="editUserSubtitle">
                        Ubah data akun pengguna
                    </p>
                </div>
            </div>
            <button onclick="closeModal('modalEditUser')"
                    class="p-1.5 rounded-lg text-slate-400 hover:text-slate-600
                           hover:bg-slate-100 dark:hover:bg-slate-700 transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        {{-- Body --}}
        <form id="formEditUser" method="POST" action="" class="p-5">
            @csrf
            @method('PUT')

            {{-- Role badge (readonly) --}}
            <div class="flex items-center gap-2 mb-4 pb-4 border-b border-slate-100 dark:border-slate-700">
                <span id="editRoleBadge"
                      class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-xs
                             font-semibold bg-indigo-50 text-indigo-700 dark:bg-indigo-900/30
                             dark:text-indigo-300">
                </span>
                <span class="text-[10px] text-slate-400">Role tidak dapat diubah</span>
            </div>

            {{-- 2 kolom: Nama Akun & Email --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-xs font-semibold text-slate-600 dark:text-slate-400 mb-1.5">
                        Nama Akun <span class="text-red-500">*</span>
                    </label>
                    <input type="text" id="editName" name="name" required
                           placeholder="Nama lengkap"
                           class="w-full rounded-xl border border-slate-200 dark:border-slate-600
                                  px-3 py-2.5 text-sm bg-white dark:bg-slate-700
                                  text-slate-800 dark:text-slate-100
                                  focus:outline-none focus:ring-2 focus:ring-indigo-300 transition">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-600 dark:text-slate-400 mb-1.5">
                        Email <span class="text-red-500">*</span>
                    </label>
                    <input type="email" id="editEmail" name="email" required
                           placeholder="email@sekolah.sch.id"
                           class="w-full rounded-xl border border-slate-200 dark:border-slate-600
                                  px-3 py-2.5 text-sm bg-white dark:bg-slate-700
                                  text-slate-800 dark:text-slate-100
                                  focus:outline-none focus:ring-2 focus:ring-indigo-300 transition">
                </div>
            </div>

            {{-- ══════════════════════════════════════════════
                 FIELD GURU
                 Semua input di sini diberi data-group="guru"
                 → akan di-disable otomatis saat mode siswa
            ══════════════════════════════════════════════ --}}
            <div id="editGuruFields" class="hidden space-y-4 mb-4">
                <div class="text-[10px] font-bold text-slate-500 dark:text-slate-400
                             uppercase tracking-wider pb-1 border-b border-slate-100 dark:border-slate-700">
                    Profil Guru
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 dark:text-slate-400 mb-1.5">
                            Nama Lengkap
                        </label>
                        <input type="text" id="editGuruNama" name="nama"
                               data-group="guru"
                               placeholder="Nama lengkap guru"
                               class="w-full rounded-xl border border-slate-200 dark:border-slate-600
                                      px-3 py-2.5 text-sm bg-white dark:bg-slate-700
                                      text-slate-800 dark:text-slate-100
                                      focus:outline-none focus:ring-2 focus:ring-indigo-300 transition">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 dark:text-slate-400 mb-1.5">
                            NIP
                        </label>
                        <input type="text" id="editGuruNip" name="nip"
                               data-group="guru"
                               placeholder="Nomor Induk Pegawai"
                               class="w-full rounded-xl border border-slate-200 dark:border-slate-600
                                      px-3 py-2.5 text-sm bg-white dark:bg-slate-700
                                      text-slate-800 dark:text-slate-100 font-mono
                                      focus:outline-none focus:ring-2 focus:ring-indigo-300 transition">
                    </div>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 dark:text-slate-400 mb-1.5">
                            Jenis Kelamin
                        </label>
                        <select id="editGuruJk" name="jk"
                                data-group="guru"
                                class="w-full rounded-xl border border-slate-200 dark:border-slate-600
                                       px-3 py-2.5 text-sm bg-white dark:bg-slate-700
                                       text-slate-800 dark:text-slate-100
                                       focus:outline-none focus:ring-2 focus:ring-indigo-300 transition">
                            <option value="">— Pilih —</option>
                            <option value="L">Laki-laki</option>
                            <option value="P">Perempuan</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 dark:text-slate-400 mb-1.5">
                            Status Pegawai
                        </label>
                        <select id="editGuruStatus" name="status_pegawai"
                                data-group="guru"
                                class="w-full rounded-xl border border-slate-200 dark:border-slate-600
                                       px-3 py-2.5 text-sm bg-white dark:bg-slate-700
                                       text-slate-800 dark:text-slate-100
                                       focus:outline-none focus:ring-2 focus:ring-indigo-300 transition">
                            <option value="">— Pilih —</option>
                            <option value="PNS">PNS</option>
                            <option value="PPPK">PPPK</option>
                            <option value="Honorer">Honorer</option>
                            <option value="GTT">GTT</option>
                        </select>
                    </div>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 dark:text-slate-400 mb-1.5">
                            Pendidikan Terakhir
                        </label>
                        <input type="text" id="editGuruPendidikan" name="pendidikan_terakhir"
                               data-group="guru"
                               placeholder="S1, S2, dll"
                               class="w-full rounded-xl border border-slate-200 dark:border-slate-600
                                      px-3 py-2.5 text-sm bg-white dark:bg-slate-700
                                      text-slate-800 dark:text-slate-100
                                      focus:outline-none focus:ring-2 focus:ring-indigo-300 transition">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 dark:text-slate-400 mb-1.5">
                            Wali Kelas
                        </label>
                        {{-- FIX: gunakan name="kelas_id" — field siswa akan di-disable saat guru aktif --}}
                        <select id="editGuruWaliKelas" name="kelas_id"
                                data-group="guru"
                                class="w-full rounded-xl border border-slate-200 dark:border-slate-600
                                       px-3 py-2.5 text-sm bg-white dark:bg-slate-700
                                       text-slate-800 dark:text-slate-100
                                       focus:outline-none focus:ring-2 focus:ring-indigo-300 transition">
                            <option value="">— Tidak ada —</option>
                            @foreach($kelasList ?? [] as $k)
                                <option value="{{ $k->id }}">{{ $k->nama }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            {{-- ══════════════════════════════════════════════
                 FIELD SISWA
                 Semua input di sini diberi data-group="siswa"
                 → akan di-disable otomatis saat mode guru
            ══════════════════════════════════════════════ --}}
            <div id="editSiswaFields" class="hidden space-y-4 mb-4">
                <div class="text-[10px] font-bold text-slate-500 dark:text-slate-400
                             uppercase tracking-wider pb-1 border-b border-slate-100 dark:border-slate-700">
                    Profil Siswa
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 dark:text-slate-400 mb-1.5">
                            Nama Lengkap
                        </label>
                        <input type="text" id="editSiswaNama" name="nama"
                               data-group="siswa"
                               placeholder="Nama lengkap siswa"
                               class="w-full rounded-xl border border-slate-200 dark:border-slate-600
                                      px-3 py-2.5 text-sm bg-white dark:bg-slate-700
                                      text-slate-800 dark:text-slate-100
                                      focus:outline-none focus:ring-2 focus:ring-indigo-300 transition">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 dark:text-slate-400 mb-1.5">
                            NIS
                        </label>
                        <input type="text" id="editSiswaNis" name="nidn"
                               data-group="siswa"
                               placeholder="Nomor Induk Siswa"
                               class="w-full rounded-xl border border-slate-200 dark:border-slate-600
                                      px-3 py-2.5 text-sm bg-white dark:bg-slate-700
                                      text-slate-800 dark:text-slate-100 font-mono
                                      focus:outline-none focus:ring-2 focus:ring-indigo-300 transition">
                    </div>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 dark:text-slate-400 mb-1.5">
                            Jenis Kelamin
                        </label>
                        <select id="editSiswaJk" name="jk"
                                data-group="siswa"
                                class="w-full rounded-xl border border-slate-200 dark:border-slate-600
                                       px-3 py-2.5 text-sm bg-white dark:bg-slate-700
                                       text-slate-800 dark:text-slate-100
                                       focus:outline-none focus:ring-2 focus:ring-indigo-300 transition">
                            <option value="">— Pilih —</option>
                            <option value="L">Laki-laki</option>
                            <option value="P">Perempuan</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 dark:text-slate-400 mb-1.5">
                            Kelas
                        </label>
                        {{-- FIX: name="kelas_id" sama, tapi field guru akan di-disable --}}
                        <select id="editSiswaKelas" name="kelas_id"
                                data-group="siswa"
                                class="w-full rounded-xl border border-slate-200 dark:border-slate-600
                                       px-3 py-2.5 text-sm bg-white dark:bg-slate-700
                                       text-slate-800 dark:text-slate-100
                                       focus:outline-none focus:ring-2 focus:ring-indigo-300 transition">
                            <option value="">— Pilih Kelas —</option>
                            @foreach($kelasList ?? [] as $k)
                                <option value="{{ $k->id }}">{{ $k->nama }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 dark:text-slate-400 mb-1.5">
                            Agama
                        </label>
                        <select id="editSiswaAgama" name="agama"
                                data-group="siswa"
                                class="w-full rounded-xl border border-slate-200 dark:border-slate-600
                                       px-3 py-2.5 text-sm bg-white dark:bg-slate-700
                                       text-slate-800 dark:text-slate-100
                                       focus:outline-none focus:ring-2 focus:ring-indigo-300 transition">
                            <option value="">— Pilih —</option>
                            @foreach(['Islam','Kristen','Katolik','Hindu','Buddha','Konghucu'] as $ag)
                                <option value="{{ $ag }}">{{ $ag }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 dark:text-slate-400 mb-1.5">
                            No. Telepon
                        </label>
                        <input type="text" id="editSiswaTelp" name="no_telp"
                               data-group="siswa"
                               placeholder="08xxxxxxxxxx"
                               class="w-full rounded-xl border border-slate-200 dark:border-slate-600
                                      px-3 py-2.5 text-sm bg-white dark:bg-slate-700
                                      text-slate-800 dark:text-slate-100
                                      focus:outline-none focus:ring-2 focus:ring-indigo-300 transition">
                    </div>
                </div>
            </div>

            {{-- Footer --}}
            <div class="flex gap-2 pt-2 border-t border-slate-100 dark:border-slate-700">
                <button type="button" onclick="closeModal('modalEditUser')"
                        class="flex-1 px-4 py-2.5 rounded-xl border border-slate-200 dark:border-slate-600
                               text-slate-600 dark:text-slate-400 text-sm font-medium
                               hover:bg-slate-50 dark:hover:bg-slate-700 transition">
                    Batal
                </button>
                <button type="submit"
                        class="flex-1 px-4 py-2.5 rounded-xl bg-indigo-600 text-white text-sm
                               font-semibold hover:bg-indigo-700 active:scale-95 transition
                               flex items-center justify-center gap-1.5">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M5 13l4 4L19 7"/>
                    </svg>
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>


{{-- DATA JSON untuk modal detail & edit --}}
<script id="guruData" type="application/json">
{!! json_encode($gurus->map(function ($u) {
    $g = $u->guru;
    return [
        'id'                  => $u->id,
        'role'                => 'guru',
        'name'                => $u->name,
        'email'               => $u->email,
        'photo'               => $u->photo ? Storage::url($u->photo) : null,
        'nama'                => $g?->nama,
        'nip'                 => $g?->nip,
        'jk'                  => $g?->jk,
        'tempat_lahir'        => $g?->tempat_lahir,
        'tanggal_lahir'       => $g?->tanggal_lahir?->format('d F Y'),
        'pendidikan_terakhir' => $g?->pendidikan_terakhir,
        'status_pegawai'      => $g?->status_pegawai,
        'pangkat_gol_ruang'   => $g?->pangkat_gol_ruang,
        'no_sk_pertama'       => $g?->no_sk_pertama,
        'no_sk_terakhir'      => $g?->no_sk_terakhir,
        'kelas'               => $g?->kelas?->nama,
        'kelas_id'            => $g?->kelas_id,
    ];
})->keyBy('id')) !!}
</script>

<script id="siswaData" type="application/json">
{!! json_encode($siswas->map(function ($u) {
    $s = $u->siswa;
    return [
        'id'            => $u->id,
        'role'          => 'siswa',
        'name'          => $u->name,
        'email'         => $u->email,
        'photo'         => $u->photo ? Storage::url($u->photo) : null,
        'nama'          => $s?->nama,
        'nidn'          => $s?->nidn,
        'nik'           => $s?->nik,
        'jk'            => $s?->jk,
        'tempat_lahir'  => $s?->tempat_lahir,
        'tgl_lahir'     => $s?->tgl_lahir?->format('d F Y'),
        'agama'         => $s?->agama,
        'no_telp'       => $s?->no_telp,
        'shkun'         => $s?->shkun,
        'kelas'         => $s?->kelas?->nama,
        'kelas_id'      => $s?->kelas_id,
        'alamat'        => $s?->alamat,
        'rt'            => $s?->rt,
        'rw'            => $s?->rw,
        'dusun'         => $s?->dusun,
        'kecamatan'     => $s?->kecamatan,
        'kode_pos'      => $s?->kode_pos,
        'jenis_tinggal' => $s?->jenis_tinggal,
        'transportasi'  => $s?->jalan_transportasi,
        'penerima_kps'  => $s?->penerima_kps,
        'no_kps'        => $s?->no_kps,
    ];
})->keyBy('id')) !!}
</script>


{{-- MODALS --}}
@include('admin.users._modal_tambah')
@include('admin.users._modal_detail')
@include('admin.users._modal_reset_password')
@include('admin.users._modal_hapus')


@push('styles')
<style>
    .animate-modal {
        animation: modalPop .22s cubic-bezier(.34,1.56,.64,1);
    }
    @keyframes modalPop {
        from { opacity:0; transform:scale(.92) translateY(10px); }
        to   { opacity:1; transform:scale(1) translateY(0); }
    }
    .detail-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: .65rem 1.75rem;
    }
    .detail-grid .full { grid-column: 1 / -1; }
</style>
@endpush


@push('scripts')
<script>
const GURU_DATA  = JSON.parse(document.getElementById('guruData').textContent);
const SISWA_DATA = JSON.parse(document.getElementById('siswaData').textContent);

/* ── Modal helpers ──────────────────────────────────────────── */
function openModal(id)  {
    var el = document.getElementById(id);
    el.classList.remove('hidden'); el.classList.add('flex');
    document.body.style.overflow = 'hidden';
}
function closeModal(id) {
    var el = document.getElementById(id);
    el.classList.add('hidden'); el.classList.remove('flex');
    document.body.style.overflow = '';
}
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        ['modalTambahUser','modalDetail','modalResetPwd','modalHapus',
         'modalImport','modalEditUser'].forEach(closeModal);
    }
});

/* ── Import role card selector ──────────────────────────────── */
document.querySelectorAll('.import-role-card').forEach(function(card) {
    card.addEventListener('click', function() {
        document.querySelectorAll('.import-role-card').forEach(function(c) {
            c.classList.remove('border-indigo-500','bg-indigo-50','dark:bg-indigo-900/20','dark:border-indigo-500');
            c.classList.add('border-slate-200','bg-white','dark:border-slate-600','dark:bg-slate-700/30');
            c.querySelector('svg').classList.remove('text-indigo-600','dark:text-indigo-400');
            c.querySelector('svg').classList.add('text-slate-500','dark:text-slate-400');
            c.querySelector('span').classList.remove('text-indigo-700','dark:text-indigo-300');
            c.querySelector('span').classList.add('text-slate-600','dark:text-slate-400');
        });
        card.classList.add('border-indigo-500','bg-indigo-50','dark:bg-indigo-900/20','dark:border-indigo-500');
        card.classList.remove('border-slate-200','bg-white','dark:border-slate-600','dark:bg-slate-700/30');
        card.querySelector('svg').classList.add('text-indigo-600','dark:text-indigo-400');
        card.querySelector('svg').classList.remove('text-slate-500','dark:text-slate-400');
        card.querySelector('span').classList.add('text-indigo-700','dark:text-indigo-300');
        card.querySelector('span').classList.remove('text-slate-600','dark:text-slate-400');
        card.querySelector('input[type=radio]').checked = true;
    });
});

/* ── Import file preview ────────────────────────────────────── */
function showImportFile(input) {
    if (input.files && input.files[0]) {
        var file = input.files[0];
        document.getElementById('importFileName').textContent = file.name;
        document.getElementById('importFileSize').textContent = (file.size / 1024 / 1024).toFixed(2) + ' MB';
        document.getElementById('importFileBox').classList.remove('hidden');
    }
}
function clearImportFile() {
    document.getElementById('importFileInput').value = '';
    document.getElementById('importFileBox').classList.add('hidden');
}

/* ── Role card selector (modal tambah) ─────────────────────── */
document.querySelectorAll('.role-card').forEach(function(card) {
    card.addEventListener('click', function() {
        document.querySelectorAll('.role-card').forEach(function(c) {
            c.classList.remove('border-indigo-500','bg-indigo-50');
            c.classList.add('border-slate-200','bg-white');
            c.querySelector('.rc-title').className = 'text-sm font-semibold text-slate-600 rc-title';
            c.querySelector('.rc-sub').className   = 'text-xs text-slate-400 rc-sub';
            var iw = c.querySelector('.rc-icon');
            iw.classList.remove('bg-indigo-100'); iw.classList.add('bg-slate-100');
            iw.querySelector('svg').classList.remove('text-indigo-600'); iw.querySelector('svg').classList.add('text-slate-500');
        });
        card.classList.add('border-indigo-500','bg-indigo-50');
        card.classList.remove('border-slate-200','bg-white');
        card.querySelector('.rc-title').className = 'text-sm font-semibold text-indigo-700 rc-title';
        card.querySelector('.rc-sub').className   = 'text-xs text-indigo-400 rc-sub';
        var iw = card.querySelector('.rc-icon');
        iw.classList.add('bg-indigo-100'); iw.classList.remove('bg-slate-100');
        iw.querySelector('svg').classList.add('text-indigo-600'); iw.querySelector('svg').classList.remove('text-slate-500');
        card.querySelector('input[type=radio]').checked = true;
    });
});

/* ── Render field helper ────────────────────────────────────── */
function renderField(label, val, full) {
    var cls     = full ? 'full' : '';
    var content = val
        ? '<p class="text-xs font-medium text-slate-700 dark:text-slate-300">' + val + '</p>'
        : '<p class="text-xs text-slate-300">—</p>';
    return '<div class="' + cls + '"><p class="text-[10px] text-slate-400 mb-0.5">' + label + '</p>' + content + '</div>';
}
function renderSection(title, fieldsHtml) {
    return '<div class="bg-slate-50 dark:bg-slate-700/30 rounded-xl border border-slate-200 dark:border-slate-600 p-3">'
         + '<h3 class="text-[10px] font-bold text-slate-600 dark:text-slate-400 uppercase tracking-wide mb-2.5">' + title + '</h3>'
         + '<div class="detail-grid">' + fieldsHtml + '</div></div>';
}

/* ── Modal Detail ──────────────────────────────────────────── */
function openDetailModal(userId) {
    var d = GURU_DATA[userId] || SISWA_DATA[userId];
    if (!d) return;
    var name   = d.nama || d.name;
    var isGuru = d.role === 'guru';
    var wrap   = document.getElementById('detailAvatarWrap');
    wrap.innerHTML = d.photo
        ? '<img src="' + d.photo + '" class="w-10 h-10 rounded-full object-cover border-2 border-slate-200 shrink-0" alt="">'
        : '<div class="w-10 h-10 rounded-full bg-indigo-100 dark:bg-indigo-900/40 flex items-center justify-center text-indigo-600 font-bold text-base shrink-0">' + name.charAt(0).toUpperCase() + '</div>';
    document.getElementById('detailName').textContent  = name;
    document.getElementById('detailEmail').textContent = d.email;
    var badge = '<span class="inline-flex px-2 py-0.5 rounded-full text-[10px] font-semibold bg-indigo-50 text-indigo-700 dark:bg-indigo-900/30 dark:text-indigo-300">' + (isGuru ? 'Guru' : 'Siswa') + '</span>';
    var body = '';
    if (isGuru) {
        body += renderSection('Informasi Akun', renderField('Nama Akun', d.name) + renderField('Email', d.email) + renderField('NIP', d.nip) + renderField('Wali Kelas', d.kelas));
        var jk = d.jk === 'L' ? 'Laki-laki' : (d.jk === 'P' ? 'Perempuan' : null);
        body += renderSection('Data Pribadi', renderField('Nama Lengkap', d.nama, true) + renderField('Jenis Kelamin', jk) + renderField('Tempat Lahir', d.tempat_lahir) + renderField('Tanggal Lahir', d.tanggal_lahir) + renderField('Pendidikan', d.pendidikan_terakhir));
        body += renderSection('Kepegawaian', renderField('Status', d.status_pegawai) + renderField('Pangkat/Gol', d.pangkat_gol_ruang) + renderField('No. SK Pertama', d.no_sk_pertama, true) + renderField('No. SK Terakhir', d.no_sk_terakhir, true));
    } else {
        var jks = d.jk === 'L' ? 'Laki-laki' : (d.jk === 'P' ? 'Perempuan' : null);
        body += renderSection('Informasi Akun', renderField('Nama Akun', d.name) + renderField('Email', d.email) + renderField('NIS/NIDN', d.nidn) + renderField('Kelas', d.kelas));
        body += renderSection('Data Pribadi', renderField('Nama Lengkap', d.nama, true) + renderField('NIK', d.nik) + renderField('Jenis Kelamin', jks) + renderField('Tempat Lahir', d.tempat_lahir) + renderField('Tanggal Lahir', d.tgl_lahir) + renderField('Agama', d.agama) + renderField('No. Telepon', d.no_telp) + renderField('SKHUN', d.shkun));
        body += renderSection('Alamat', renderField('Alamat', d.alamat, true) + renderField('RT', d.rt) + renderField('RW', d.rw) + renderField('Dusun', d.dusun) + renderField('Kecamatan', d.kecamatan) + renderField('Kode Pos', d.kode_pos) + renderField('Jenis Tinggal', d.jenis_tinggal) + renderField('Transportasi', d.transportasi));
        // FIX: penerima_kps sekarang integer (0/1), bukan string
        var kps = d.penerima_kps
            ? '<span class="inline-flex px-1.5 py-0.5 rounded-full text-[10px] font-semibold bg-green-100 text-green-700">Ya</span>'
            : '<span class="inline-flex px-1.5 py-0.5 rounded-full text-[10px] font-semibold bg-slate-100 text-slate-600">Tidak</span>';
        body += renderSection('Bantuan', '<div><p class="text-[10px] text-slate-400 mb-0.5">Penerima KPS</p>' + kps + '</div>' + renderField('No. KPS', d.no_kps));
    }
    document.getElementById('detailBody').innerHTML = '<div class="flex items-center gap-2 mb-3">' + badge + '</div>' + body;
    openModal('modalDetail');
}

/* ═══════════════════════════════════════════════════════════════
   Modal Edit User
   FIX UTAMA:
   - Field grup yang tidak aktif di-DISABLE sebelum submit
     sehingga tidak ikut terkirim ke server
   - Re-enable setelah form disubmit (untuk berjaga-jaga)
═══════════════════════════════════════════════════════════════ */
function setGroupDisabled(group, disabled) {
    document.querySelectorAll('[data-group="' + group + '"]').forEach(function(el) {
        el.disabled = disabled;
    });
}

function openEditModal(userId) {
    var d = GURU_DATA[userId] || SISWA_DATA[userId];
    if (!d) return;

    var isGuru = d.role === 'guru';

    // Set action form → PUT /admin/users/{id}
    document.getElementById('formEditUser').action = '/admin/users/' + userId;

    // Subtitle
    document.getElementById('editUserSubtitle').textContent =
        'Mengedit: ' + (d.nama || d.name);

    // Role badge
    document.getElementById('editRoleBadge').textContent = isGuru ? '👤 Guru' : '📚 Siswa';

    // Isi field akun
    document.getElementById('editName').value  = d.name  || '';
    document.getElementById('editEmail').value = d.email || '';

    if (isGuru) {
        // Tampilkan guru, sembunyikan siswa
        document.getElementById('editGuruFields').classList.remove('hidden');
        document.getElementById('editSiswaFields').classList.add('hidden');

        // FIX: enable guru, disable siswa agar tidak ikut terkirim
        setGroupDisabled('guru', false);
        setGroupDisabled('siswa', true);

        // Isi field guru
        document.getElementById('editGuruNama').value       = d.nama                || '';
        document.getElementById('editGuruNip').value        = d.nip                 || '';
        document.getElementById('editGuruJk').value         = d.jk                  || '';
        document.getElementById('editGuruStatus').value     = d.status_pegawai      || '';
        document.getElementById('editGuruPendidikan').value = d.pendidikan_terakhir || '';
        document.getElementById('editGuruWaliKelas').value  = d.kelas_id            || '';

    } else {
        // Tampilkan siswa, sembunyikan guru
        document.getElementById('editSiswaFields').classList.remove('hidden');
        document.getElementById('editGuruFields').classList.add('hidden');

        // FIX: enable siswa, disable guru agar tidak ikut terkirim
        setGroupDisabled('siswa', false);
        setGroupDisabled('guru', true);

        // Isi field siswa
        document.getElementById('editSiswaNama').value  = d.nama     || '';
        document.getElementById('editSiswaNis').value   = d.nidn     || '';
        document.getElementById('editSiswaJk').value    = d.jk       || '';
        document.getElementById('editSiswaKelas').value = d.kelas_id || '';
        document.getElementById('editSiswaAgama').value = d.agama    || '';
        document.getElementById('editSiswaTelp').value  = d.no_telp  || '';
    }

    openModal('modalEditUser');
}

// FIX: re-enable semua field setelah form disubmit
// (mencegah disabled field tersimpan di state browser saat back)
document.getElementById('formEditUser').addEventListener('submit', function() {
    setGroupDisabled('guru', false);
    setGroupDisabled('siswa', false);
});

/* ── Modal Reset Password ──────────────────────────────────── */
function openResetModal(userId, userName) {
    document.getElementById('resetUserName').textContent = userName;
    document.getElementById('formResetPwd').action = '/admin/users/' + userId + '/reset-password';
    document.getElementById('resetPwdInput').value = '';
    openModal('modalResetPwd');
}

/* ── Modal Hapus ───────────────────────────────────────────── */
function openDeleteModal(userId, userName) {
    document.getElementById('deleteUserName').textContent = userName;
    document.getElementById('formHapus').action = '/admin/users/' + userId;
    openModal('modalHapus');
}

function togglePwd(inputId) {
    var inp = document.getElementById(inputId);
    inp.type = inp.type === 'password' ? 'text' : 'password';
}

/* ── Live search ────────────────────────────────────────────── */
document.getElementById('searchInput').addEventListener('input', function() {
    var q   = this.value.toLowerCase();
    var tab = document.querySelector('[id^="tab-"]:not(.hidden)');
    if (!tab) return;
    tab.querySelectorAll('.searchable-row').forEach(function(row) {
        row.style.display = row.textContent.toLowerCase().includes(q) ? '' : 'none';
    });
});

@if($errors->any())
document.addEventListener('DOMContentLoaded', function() { openModal('modalTambahUser'); });
@endif

@if(session('show_import_modal'))
document.addEventListener('DOMContentLoaded', function() { openModal('modalImport'); });
@endif
</script>
@endpush

@endsection