@extends('layouts.app')
@section('title', 'Dashboard Guru')

@section('content')

<div class="space-y-4 max-w-7xl mx-auto">

    {{-- HEADER SELAMAT DATANG --}}
    @include('guru.dashboard.header')

    {{-- GRID UTAMA --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">

        {{-- Kolom kiri: Konten utama (performa & chart) --}}
        <div class="lg:col-span-2 space-y-4">

            {{-- Ringkasan Performa (KPI Cards) --}}
            @include('guru.dashboard.performance-summary')

            {{-- Tren Kehadiran 7 Hari Terakhir --}}
            @include('guru.dashboard.attendance-trend')

            {{-- Siswa Berisiko --}}
            @include('guru.dashboard.at-risk-students')

            {{-- JADWAL MENGAJAR HARI INI (Baru) --}}
            @include('guru.dashboard.jadwal-mengajar')

        </div>

        {{-- Kolom kanan: Widget Pengumuman --}}
        <div class="lg:col-span-1">
            @include('guru.dashboard.announcements')
        </div>

    </div>

</div>

{{-- Modal Pengumuman --}}
@include('guru.dashboard.modal-pengumuman')

@endsection

@push('scripts')
{{-- JS Modal Pengumuman --}}
@include('guru.dashboard.scripts-modal')
@endpush