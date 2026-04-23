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

    {{-- ── HEADER SELAMAT DATANG ── --}}
    @include('guru.dashboard.header')

    {{-- ── GRID UTAMA ── --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">

        {{-- ════ Kolom kiri: Konten utama ════ --}}
        <div class="lg:col-span-2 space-y-4">

            {{-- 1. KPI Cards + Absensi Hari Ini --}}
            @include('guru.dashboard.performance-summary')

            {{-- 2. JADWAL MENGAJAR HARI INI (naik ke sini) --}}
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

        {{-- ════ Kolom kanan: Widget sidebar ════ --}}
        <div class="lg:col-span-1 space-y-4">

            {{-- Pengumuman --}}
            @include('guru.dashboard.announcements')

            {{-- Ringkasan Wali Kelas (hanya wali kelas) --}}
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