{{-- resources/views/admin/users/index.blade.php --}}
@extends('layouts.app')
@section('title', 'Kelola User')

@section('content')

<div class="space-y-4">

    {{-- ===== PAGE HEADER ===== --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
        <div>
            <h2 class="text-sm font-bold text-slate-800 dark:text-slate-100">Kelola User</h2>
            <p class="text-xs text-slate-400 dark:text-slate-500 mt-0.5">
                Manajemen akun guru dan siswa pada sistem.
            </p>
        </div>

        <div class="flex flex-wrap items-center gap-2">

            {{-- Tombol Import --}}
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

            {{-- Dropdown Export --}}
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
                    <svg class="w-3 h-3 transition-transform duration-200" :class="open ? 'rotate-180' : ''"
                         fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                    </svg>
                </button>

                <div x-show="open" x-cloak x-transition:enter="transition ease-out duration-100"
                     x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
                     x-transition:leave="transition ease-in duration-75"
                     x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95"
                     class="absolute right-0 mt-1.5 w-52 bg-white dark:bg-slate-800 rounded-xl shadow-xl
                            border border-slate-200 dark:border-slate-700 py-1 z-30 origin-top-right">
                    <a href="{{ route('admin.users.export-excel', ['role' => 'guru']) }}"
                       class="flex items-center gap-2.5 px-4 py-2.5 text-xs font-medium
                              text-slate-700 hover:bg-slate-50 dark:text-slate-300 dark:hover:bg-slate-700">
                        <svg class="w-3.5 h-3.5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414A1 1 0 0120 9.414V19a2 2 0 01-2 2z"/>
                        </svg>
                        Excel — Data Guru
                    </a>
                    <a href="{{ route('admin.users.export-excel', ['role' => 'siswa']) }}"
                       class="flex items-center gap-2.5 px-4 py-2.5 text-xs font-medium
                              text-slate-700 hover:bg-slate-50 dark:text-slate-300 dark:hover:bg-slate-700">
                        <svg class="w-3.5 h-3.5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414A1 1 0 0120 9.414V19a2 2 0 01-2 2z"/>
                        </svg>
                        Excel — Data Siswa
                    </a>
                    <div class="border-t border-slate-100 dark:border-slate-700 my-1"></div>
                    <a href="{{ route('admin.users.export-pdf', ['role' => 'guru']) }}"
                       class="flex items-center gap-2.5 px-4 py-2.5 text-xs font-medium
                              text-slate-700 hover:bg-slate-50 dark:text-slate-300 dark:hover:bg-slate-700">
                        <svg class="w-3.5 h-3.5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                        </svg>
                        PDF — Data Guru
                    </a>
                    <a href="{{ route('admin.users.export-pdf', ['role' => 'siswa']) }}"
                       class="flex items-center gap-2.5 px-4 py-2.5 text-xs font-medium
                              text-slate-700 hover:bg-slate-50 dark:text-slate-300 dark:hover:bg-slate-700">
                        <svg class="w-3.5 h-3.5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                        </svg>
                        PDF — Data Siswa
                    </a>
                </div>
            </div>

            {{-- Tombol Tambah User --}}
            <button onclick="openModal('modalTambahUser')"
                    class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl
                           bg-indigo-600 text-white text-xs font-semibold
                           hover:bg-indigo-700 active:scale-95 transition shadow-sm">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Tambah User
            </button>

        </div>
    </div>

    {{-- ===== ALERT SUCCESS ===== --}}
    @if(session('success'))
    <div class="flex items-start gap-3 bg-emerald-50 dark:bg-emerald-950/40 border border-emerald-200
                dark:border-emerald-800 rounded-2xl p-4">
        <svg class="w-4 h-4 text-emerald-600 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
        </svg>
        <p class="text-xs font-semibold text-emerald-700 dark:text-emerald-400">{{ session('success') }}</p>
    </div>
    @endif

    {{-- ===== ALERT ERROR TUNGGAL ===== --}}
    @if(session('error'))
    <div class="flex items-start gap-3 bg-red-50 dark:bg-red-950/40 border border-red-200
                dark:border-red-800 rounded-2xl p-4">
        <svg class="w-4 h-4 text-red-500 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
        <p class="text-xs font-semibold text-red-600 dark:text-red-400">{{ session('error') }}</p>
    </div>
    @endif

    {{-- ===== ALERT ERROR BARIS IMPORT ===== --}}
    @if(session('import_errors'))
    <div class="bg-amber-50 dark:bg-amber-950/40 border border-amber-200 dark:border-amber-800 rounded-2xl p-4">
        <div class="flex items-center gap-2 mb-2">
            <svg class="w-4 h-4 text-amber-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
            </svg>
            <p class="text-xs font-bold text-amber-700 dark:text-amber-400">
                Beberapa baris gagal diimpor ({{ count(session('import_errors')) }} baris):
            </p>
        </div>
        <ul class="space-y-1 pl-6">
            @foreach(session('import_errors') as $err)
            <li class="text-[11px] text-amber-600 dark:text-amber-500 list-disc">{{ $err }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    {{-- ===== SEARCH ===== --}}
    <div class="relative max-w-xs">
        <svg class="absolute left-2.5 top-1/2 -translate-y-1/2 w-3.5 h-3.5 text-slate-400 pointer-events-none"
             fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0"/>
        </svg>
        <input type="text" id="searchInput" placeholder="Cari nama, email, NIP..."
               class="w-full pl-8 pr-4 py-2 rounded-xl border border-slate-200 dark:border-slate-600
                      bg-white dark:bg-slate-800 text-xs text-slate-700 dark:text-slate-300
                      placeholder:text-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-300
                      dark:focus:ring-indigo-700 transition">
    </div>

    {{-- ===== TABS ===== --}}
    <div class="flex items-center gap-1 bg-slate-100 dark:bg-slate-800 p-1 rounded-xl w-fit">
        <a href="{{ route('admin.users.index', ['tab' => 'guru']) }}"
           class="px-4 py-1.5 rounded-lg text-xs font-semibold transition
                  {{ $activeTab === 'guru'
                     ? 'bg-white dark:bg-slate-700 shadow-sm text-indigo-700 dark:text-indigo-400'
                     : 'text-slate-500 dark:text-slate-400 hover:text-slate-700 dark:hover:text-slate-300' }}">
            Guru
            <span class="ml-1 px-1.5 py-0.5 rounded-full text-[9px] font-bold
                         {{ $activeTab === 'guru' ? 'bg-indigo-100 text-indigo-700' : 'bg-slate-200 text-slate-500' }}">
                {{ $gurus->count() }}
            </span>
        </a>
        <a href="{{ route('admin.users.index', ['tab' => 'siswa']) }}"
           class="px-4 py-1.5 rounded-lg text-xs font-semibold transition
                  {{ $activeTab === 'siswa'
                     ? 'bg-white dark:bg-slate-700 shadow-sm text-indigo-700 dark:text-indigo-400'
                     : 'text-slate-500 dark:text-slate-400 hover:text-slate-700 dark:hover:text-slate-300' }}">
            Siswa
            <span class="ml-1 px-1.5 py-0.5 rounded-full text-[9px] font-bold
                         {{ $activeTab === 'siswa' ? 'bg-indigo-100 text-indigo-700' : 'bg-slate-200 text-slate-500' }}">
                {{ $siswas->count() }}
            </span>
        </a>
    </div>

    {{-- ===== TABEL KONTEN ===== --}}
    <div id="tab-guru" class="{{ $activeTab !== 'guru' ? 'hidden' : '' }}">
        @include('admin.users._table_guru', ['users' => $gurus])
    </div>
    <div id="tab-siswa" class="{{ $activeTab !== 'siswa' ? 'hidden' : '' }}">
        @include('admin.users._table_siswa', ['users' => $siswas])
    </div>

</div>

{{-- =========================================================== --}}
{{-- MODAL IMPORT                                                  --}}
{{-- =========================================================== --}}
<div id="modalImport"
     class="fixed inset-0 z-50 hidden items-center justify-center p-4 bg-slate-900/50 backdrop-blur-sm">
    <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-2xl border border-slate-200
                dark:border-slate-700 w-full max-w-md overflow-hidden">

        {{-- Header modal --}}
        <div class="px-5 py-4 border-b border-slate-100 dark:border-slate-700 flex justify-between items-center">
            <div class="flex items-center gap-2">
                <div class="w-7 h-7 rounded-lg bg-emerald-100 dark:bg-emerald-900/50 flex items-center justify-center">
                    <svg class="w-3.5 h-3.5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
                    </svg>
                </div>
                <h3 class="text-sm font-bold text-slate-800 dark:text-slate-100">Import Data via Excel</h3>
            </div>
            <button onclick="closeModal('modalImport')"
                    class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        <form action="{{ route('admin.users.import') }}" method="POST"
              enctype="multipart/form-data" class="p-5 space-y-4">
            @csrf

            {{-- 1. Pilih Role --}}
            <div>
                <label class="block text-[10px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-2">
                    Role Tujuan
                </label>
                <div class="grid grid-cols-2 gap-2" id="roleSelector">
                    <label class="cursor-pointer">
                        <input type="radio" name="role" value="guru" class="peer hidden" checked>
                        <div class="text-center py-2.5 rounded-xl border-2 border-slate-100 dark:border-slate-700
                                    peer-checked:border-indigo-500 peer-checked:bg-indigo-50 dark:peer-checked:bg-indigo-950/50
                                    text-xs font-semibold text-slate-600 dark:text-slate-400
                                    peer-checked:text-indigo-700 dark:peer-checked:text-indigo-400
                                    transition-all duration-150">
                            👨‍🏫 Guru
                        </div>
                    </label>
                    <label class="cursor-pointer">
                        <input type="radio" name="role" value="siswa" class="peer hidden">
                        <div class="text-center py-2.5 rounded-xl border-2 border-slate-100 dark:border-slate-700
                                    peer-checked:border-indigo-500 peer-checked:bg-indigo-50 dark:peer-checked:bg-indigo-950/50
                                    text-xs font-semibold text-slate-600 dark:text-slate-400
                                    peer-checked:text-indigo-700 dark:peer-checked:text-indigo-400
                                    transition-all duration-150">
                            🎒 Siswa
                        </div>
                    </label>
                </div>
            </div>

            {{-- 2. Download Template --}}
            <div class="rounded-xl border border-dashed border-slate-200 dark:border-slate-600
                        bg-slate-50 dark:bg-slate-900/50 p-3.5">
                <p class="text-[10px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-2.5">
                    Belum punya template?
                </p>
                <div class="flex gap-2">
                    <a id="btnTemplateGuru"
                       href="{{ route('admin.users.template-import', 'guru') }}"
                       class="flex-1 inline-flex items-center justify-center gap-1.5 px-3 py-2 rounded-xl
                              border border-indigo-200 dark:border-indigo-800
                              bg-indigo-50 dark:bg-indigo-950/50
                              text-indigo-700 dark:text-indigo-400
                              text-xs font-semibold hover:bg-indigo-100 dark:hover:bg-indigo-950
                              transition-colors duration-150">
                        <svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                        </svg>
                        Template Guru
                    </a>
                    <a id="btnTemplateSiswa"
                       href="{{ route('admin.users.template-import', 'siswa') }}"
                       class="flex-1 inline-flex items-center justify-center gap-1.5 px-3 py-2 rounded-xl
                              border border-emerald-200 dark:border-emerald-800
                              bg-emerald-50 dark:bg-emerald-950/50
                              text-emerald-700 dark:text-emerald-400
                              text-xs font-semibold hover:bg-emerald-100 dark:hover:bg-emerald-950
                              transition-colors duration-150">
                        <svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                        </svg>
                        Template Siswa
                    </a>
                </div>
                <p class="text-[10px] text-slate-400 dark:text-slate-500 mt-2 leading-relaxed">
                    Download template → isi data mulai baris ke-3 → simpan → upload di sini.
                </p>
            </div>

            {{-- 3. Password Default --}}
            <div>
                <label class="block text-[10px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-1.5">
                    Password Default <span class="text-red-400">*</span>
                </label>
                <div class="relative">
                    <input type="text" name="password_import" id="passwordImport" required
                           placeholder="Masukkan password untuk semua akun baru..."
                           class="w-full px-3 py-2.5 rounded-xl border border-slate-200 dark:border-slate-600
                                  bg-white dark:bg-slate-900 text-xs text-slate-700 dark:text-slate-300
                                  placeholder:text-slate-400 focus:ring-2 focus:ring-indigo-500 outline-none transition">
                </div>
                <p class="text-[10px] text-slate-400 dark:text-slate-500 mt-1">
                    Semua akun yang diimport akan menggunakan password ini. Min. 5 karakter.
                </p>
            </div>

            {{-- 4. Upload File --}}
            <div>
                <label class="block text-[10px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-1.5">
                    File Excel (.xlsx) <span class="text-red-400">*</span>
                </label>
                <div class="relative">
                    <input type="file" name="import_file" id="importFileInput" required
                           accept=".xlsx,.xls"
                           class="w-full text-xs text-slate-500 dark:text-slate-400
                                  file:mr-3 file:py-2 file:px-4 file:rounded-lg file:border-0
                                  file:text-xs file:font-semibold
                                  file:bg-indigo-50 dark:file:bg-indigo-950/50
                                  file:text-indigo-700 dark:file:text-indigo-400
                                  hover:file:bg-indigo-100 dark:hover:file:bg-indigo-950
                                  file:cursor-pointer cursor-pointer file:transition-colors">
                </div>
                {{-- Preview nama file yang dipilih --}}
                <div id="filePreview" class="hidden mt-2 flex items-center gap-2 px-3 py-2 rounded-lg
                                              bg-emerald-50 dark:bg-emerald-950/30 border border-emerald-200
                                              dark:border-emerald-800">
                    <svg class="w-3.5 h-3.5 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586
                                 a1 1 0 01.707.293l5.414 5.414A1 1 0 0120 9.414V19a2 2 0 01-2 2z"/>
                    </svg>
                    <span id="filePreviewName" class="text-[11px] text-emerald-700 dark:text-emerald-400 font-medium truncate"></span>
                </div>
            </div>

            {{-- Tombol Aksi --}}
            <div class="flex gap-2 pt-1">
                <button type="submit"
                        class="flex-1 bg-emerald-600 hover:bg-emerald-700 active:scale-95
                               text-white py-2.5 rounded-xl text-xs font-bold transition shadow-sm">
                    Mulai Proses Import
                </button>
                <button type="button" onclick="closeModal('modalImport')"
                        class="px-4 py-2.5 rounded-xl border border-slate-200 dark:border-slate-600
                               text-xs font-semibold text-slate-600 dark:text-slate-400
                               hover:bg-slate-50 dark:hover:bg-slate-700 transition">
                    Batal
                </button>
            </div>

        </form>
    </div>
</div>

{{-- ===== MODAL LAIN (tidak diubah) ===== --}}
@include('admin.users._modal_detail')
@include('admin.users._modal_tambah')
@include('admin.users._modal_hapus')
@include('admin.users._modal_reset_password')

@endsection

@push('scripts')
<script>
    // ── Buka / tutup modal ──────────────────────────────────────────────────
    function openModal(id) {
        const el = document.getElementById(id);
        el.classList.remove('hidden');
        el.classList.add('flex');
        document.body.style.overflow = 'hidden';
    }

    function closeModal(id) {
        const el = document.getElementById(id);
        el.classList.add('hidden');
        el.classList.remove('flex');
        document.body.style.overflow = '';
    }

    // Tutup modal jika klik backdrop
    document.querySelectorAll('[id^="modal"]').forEach(modal => {
        modal.addEventListener('click', function (e) {
            if (e.target === this) closeModal(this.id);
        });
    });

    // ── Live Search ──────────────────────────────────────────────────────────
    document.getElementById('searchInput').addEventListener('input', function () {
        const q = this.value.toLowerCase().trim();
        document.querySelectorAll('.searchable-row').forEach(row => {
            const match = row.innerText.toLowerCase().includes(q);
            row.style.display = match ? '' : 'none';
        });
    });

    // ── Preview nama file yang dipilih ───────────────────────────────────────
    document.getElementById('importFileInput').addEventListener('change', function () {
        const preview = document.getElementById('filePreview');
        const label   = document.getElementById('filePreviewName');
        if (this.files && this.files[0]) {
            label.textContent = this.files[0].name;
            preview.classList.remove('hidden');
        } else {
            preview.classList.add('hidden');
        }
    });

    // ── Sinkronisasi link template dengan role yang dipilih ──────────────────
    // (opsional: highlight tombol template yang relevan saat role berubah)
    document.querySelectorAll('input[name="role"]').forEach(radio => {
        radio.addEventListener('change', function () {
            const isGuru = this.value === 'guru';
            const btnGuru  = document.getElementById('btnTemplateGuru');
            const btnSiswa = document.getElementById('btnTemplateSiswa');

            if (isGuru) {
                btnGuru.classList.add('ring-2', 'ring-indigo-400');
                btnSiswa.classList.remove('ring-2', 'ring-indigo-400');
            } else {
                btnSiswa.classList.add('ring-2', 'ring-indigo-400');
                btnGuru.classList.remove('ring-2', 'ring-indigo-400');
            }
        });
    });
</script>
@endpush