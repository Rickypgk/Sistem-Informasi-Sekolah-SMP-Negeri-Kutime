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

<div class="space-y-4">

    {{-- ── HEADER ── --}}
    @include('guru.dashboard.header')

    {{-- ── GRID UTAMA ── --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">

        {{-- ═══ Kolom KIRI 2/3 ═══ --}}
        <div class="lg:col-span-2 space-y-4">

            {{-- KPI + Absensi Hari Ini --}}
            @include('guru.dashboard.performance-summary')

            {{-- Jadwal Mengajar --}}
            @include('guru.dashboard.jadwal-mengajar')

            {{-- Tren Kehadiran --}}
            @include('guru.dashboard.attendance-trend')

            {{-- Rekap Absensi Bulanan Wali Kelas --}}
            @if(!empty($isWaliKelas))
                @include('guru.dashboard.rekap-absensi')
            @endif

            {{-- Siswa Berisiko --}}
            @include('guru.dashboard.at-risk-students')

        </div>

        {{-- ═══ Kolom KANAN 1/3 ═══ --}}
        <div class="lg:col-span-1 space-y-4">

            {{-- Pengumuman (modal pgBuka sudah @once di dalam file ini) --}}
            @include('guru.dashboard.announcements')

            {{--
                Wali Kelas Summary:
                - Selalu di-include tanpa kondisi di sini
                - Guard @if(isWaliKelas) ada DI DALAM file wali-kelas-summary itu sendiri
                - Jika bukan wali kelas → file tersebut tidak merender apapun (aman)
            --}}
            @include('guru.dashboard.wali-kelas-summary')

        </div>

    </div>

</div>

@endsection

@push('scripts')
@include('guru.dashboard.scripts-modal')
@include('guru.dashboard.scripts-chart')
@endpush