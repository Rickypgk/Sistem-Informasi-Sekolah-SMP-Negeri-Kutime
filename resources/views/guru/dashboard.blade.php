{{-- resources/views/guru/dashboard.blade.php --}}
@extends('layouts.app')
@section('title', 'Dashboard Guru')

@push('styles')
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<style>
    #mainContent {
        font-family: 'Plus Jakarta Sans', sans-serif !important;
    }
</style>
@endpush

@section('content')

<div class="space-y-4">

    {{-- ── HEADER SELAMAT DATANG ── --}}
    @include('guru.dashboard.header')

    {{-- ── GRID UTAMA ── --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">

        {{-- ════════════════════════════════════════════
             Kolom KIRI — konten utama (2/3 lebar)
        ════════════════════════════════════════════ --}}
        <div class="lg:col-span-2 space-y-4">

            {{-- 1. KPI Cards + Absensi Hari Ini --}}
            @include('guru.dashboard.performance-summary')

            {{-- 2. Jadwal Mengajar Hari Ini --}}
            @include('guru.dashboard.jadwal-mengajar')

            {{-- 3. Tren Kehadiran 7 Hari --}}
            @include('guru.dashboard.attendance-trend')

            {{-- 4. Rekap Absensi Bulanan (hanya wali kelas) --}}
            @if(isset($isWaliKelas) && $isWaliKelas)
            @include('guru.dashboard.rekap-absensi')
            @endif

            {{-- 5. Siswa Berisiko --}}
            @include('guru.dashboard.at-risk-students')

        </div>

        {{-- ════════════════════════════════════════════
             Kolom KANAN — widget sidebar (1/3 lebar)
        ════════════════════════════════════════════ --}}
        <div class="lg:col-span-1 space-y-4">

            {{-- Pengumuman
                 announcements.blade.php sudah include modal pgModal via @once
                 dan memakai pgBuka() yang konsisten dengan halaman pengumuman penuh.
            --}}
            @include('guru.dashboard.announcements')

            {{-- Ringkasan Wali Kelas
                 Ditampilkan untuk semua guru wali kelas.
                 Berisi: distribusi kehadiran kelas, quick links absensi & rekap.
            --}}
            @if(isset($isWaliKelas) && $isWaliKelas)
            @include('guru.dashboard.wali-kelas-summary')
            @endif

        </div>

    </div>

</div>

@endsection

@push('scripts')
{{--
   scripts-modal: definisikan pgBuka() / pgTutup() / pgImgError()
   (sama dengan yang dipakai halaman pengumuman penuh).
   @endonce di dalam file mencegah double-include jika halaman
   pengumuman sudah memuatnya.
--}}
@include('guru.dashboard.scripts-modal')

{{-- Chart ApexCharts tren kehadiran 7 hari --}}
@include('guru.dashboard.scripts-chart')
@endpush