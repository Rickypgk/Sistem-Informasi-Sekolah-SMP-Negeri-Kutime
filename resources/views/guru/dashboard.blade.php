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

    {{-- HEADER SELAMAT DATANG --}}
    @include('guru.dashboard.header')

    {{-- GRID UTAMA --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">

        {{-- Kolom kiri: Konten utama --}}
        <div class="lg:col-span-2 space-y-4">

            {{-- KPI Cards --}}
            @include('guru.dashboard.performance-summary')

            {{-- Tren Kehadiran 7 Hari Terakhir --}}
            @include('guru.dashboard.attendance-trend')

            {{-- Rekap Absensi Bulanan (hanya wali kelas) --}}
            @if(isset($isWaliKelas) && $isWaliKelas)
                @include('guru.dashboard.rekap-absensi')
            @endif

            {{-- Siswa Berisiko --}}
            @include('guru.dashboard.at-risk-students')

            {{-- Jadwal Mengajar Hari Ini --}}
            @include('guru.dashboard.jadwal-mengajar')

        </div>

        {{-- Kolom kanan: Widget --}}
        <div class="lg:col-span-1 space-y-4">
            @include('guru.dashboard.announcements')
            @if(isset($isWaliKelas) && $isWaliKelas)
                @include('guru.dashboard.wali-kelas-summary')
            @endif
        </div>

    </div>

</div>

{{-- Modal Pengumuman --}}
@include('guru.dashboard.modal-pengumuman')

@endsection

@push('scripts')
@include('guru.dashboard.scripts-modal')
@include('guru.dashboard.scripts-chart')
@endpush