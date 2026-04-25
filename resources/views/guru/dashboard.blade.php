{{-- resources/views/guru/dashboard.blade.php --}}
@extends('layouts.app')
@section('title', 'Dashboard Guru')

@push('styles')
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<style>
    #mainContent { font-family: 'Plus Jakarta Sans', sans-serif !important; }
</style>
@endpush

@section('content')

{{--
    ══════════════════════════════════════════════════════════
    CATATAN ARSITEKTUR VARIABEL $isWaliKelas:
    ──────────────────────────────────────────────────────────
    • DashboardController SELALU mengirim $isWaliKelas (bool)
    • Di sini kita pakai ?? false sebagai safety fallback
    • Kondisi cek ada DUA LAPIS:
        1. Di dashboard.blade.php → untuk include rekap-absensi
        2. Di wali-kelas-summary.blade.php → internal guard ($isWK)
    ══════════════════════════════════════════════════════════
--}}
@php
    // Safety cast — pastikan boolean, bukan null/undefined
    $isWaliKelas = (bool)($isWaliKelas ?? false);
@endphp

<div class="space-y-4">

    {{-- ── HEADER ── --}}
    @include('guru.dashboard.header')

    {{-- ── GRID UTAMA ── --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">

        {{-- ═══════════════════════════════════════
             Kolom KIRI — 2/3
        ═══════════════════════════════════════ --}}
        <div class="lg:col-span-2 space-y-4">

            {{-- KPI + Absensi Hari Ini --}}
            @include('guru.dashboard.performance-summary')

            {{-- Jadwal Mengajar Hari Ini --}}
            @include('guru.dashboard.jadwal-mengajar')

            {{-- Tren Kehadiran 7 Hari --}}
            @include('guru.dashboard.attendance-trend')

            {{--
                Rekap Absensi Bulanan:
                HANYA muncul jika guru adalah wali kelas.
                Guard di sini (layer 1) sebelum include
                agar Blade tidak memproses file sama sekali
                jika bukan wali kelas.
            --}}
            @if($isWaliKelas)
                @include('guru.dashboard.rekap-absensi')
            @endif

            {{-- Siswa Berisiko --}}
            @include('guru.dashboard.at-risk-students')

        </div>

        {{-- ═══════════════════════════════════════
             Kolom KANAN — 1/3
        ═══════════════════════════════════════ --}}
        <div class="lg:col-span-1 space-y-4">

            {{-- Pengumuman --}}
            @include('guru.dashboard.announcements')

            {{--
                ════════════════════════════════════════════
                WALI KELAS SUMMARY WIDGET
                ────────────────────────────────────────────
                Strategi: Double-guard untuk keamanan penuh.

                LAYER 1 (di sini):
                  @if($isWaliKelas) → cegah include jika false
                  Efisiensi: file tidak diproses Blade sama sekali
                  jika guru bukan wali kelas.

                LAYER 2 (di dalam wali-kelas-summary.blade.php):
                  @php $isWK = isset($isWaliKelas) && (bool)$isWaliKelas @endphp
                  @if($isWK) → guard internal sebagai safety net

                Dengan double-guard:
                  ✅ Guru biasa   → widget TIDAK tampil (layer 1 stop)
                  ✅ Wali kelas   → widget TAMPIL (lolos kedua layer)
                  ✅ Error/null   → widget TIDAK tampil (layer 1 stop)
                ════════════════════════════════════════════
            --}}
            @if($isWaliKelas)
                @include('guru.dashboard.wali-kelas-summary')
            @endif

        </div>

    </div>

</div>

@endsection

@push('scripts')
@include('guru.dashboard.scripts-modal')
@include('guru.dashboard.scripts-chart')
@endpush