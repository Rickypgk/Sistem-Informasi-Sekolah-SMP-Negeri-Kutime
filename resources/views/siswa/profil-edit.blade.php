@extends('layouts.app')
@section('title', 'Edit Profil Siswa')
@section('content')
{{-- Semua form edit sudah dipindahkan ke overlay modal di halaman profil --}}
<script>window.location.replace("{{ route('siswa.profil') }}")</script>
@endsection