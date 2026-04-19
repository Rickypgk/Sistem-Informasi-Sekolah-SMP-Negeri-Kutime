@extends('layouts.app')

@section('title', 'Jadwal Kelas ' . $studyGroup->name)

@section('content')
<div class="container-fluid py-4">

    {{-- Breadcrumb --}}
    <nav aria-label="breadcrumb" class="mb-3">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ route('admin.academic-planner.index') }}">Academic Planner</a>
            </li>
            <li class="breadcrumb-item active">Kelas {{ $studyGroup->name }}</li>
        </ol>
    </nav>

    {{-- Header kelas --}}
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body d-flex justify-content-between align-items-center flex-wrap gap-3">
            <div class="d-flex align-items-center gap-3">
                <div class="rounded-3 bg-primary text-white d-flex align-items-center justify-content-center"
                    style="width:60px;height:60px;font-size:1.8rem;font-weight:900;">
                    {{ $studyGroup->name }}
                </div>
                <div class="card-body">
                    <div>
                        <h5 class="card-title mb-1">{{ $studyGroup->name }}</h5>
                        <div class="text-muted small">
                            {{ $studyGroup->academic_year }} &bull; Semester {{ $studyGroup->semester }}
                            @if ($studyGroup->room) &bull; Ruang {{ $studyGroup->room }} @endif
                        </div>
                        <div class="text-muted small">
                            Wali Kelas:
                            <strong>{{ $studyGroup->homeroomTeacher?->name ?? 'Belum ditetapkan' }}</strong>
                        </div>
                    </div>
                </div>
            </div>
            <div class="d-flex gap-2 flex-wrap">
                <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addJadwalModal">
                    <i class="bi bi-plus-lg me-1"></i>Tambah Jadwal
                </button>
                <a href="#" class="btn btn-sm btn-outline-warning" data-bs-toggle="modal" data-bs-target="#editKelasModal">
                    <i class="bi bi-pencil me-1"></i>Edit Kelas
                </a>
                <button type="button" class="btn btn-sm btn-outline-danger"
                    onclick="deleteKelas({{ $studyGroup->id }}, '{{ $studyGroup->name }}')">
                    <i class="bi bi-trash me-1"></i>Hapus Kelas
                </button>
            </div>
        </div>
    </div>

    {{-- Alert --}}
    @if (session('success'))
    <div class="alert alert-success alert-dismissible fade show">
        <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    {{-- Jadwal per hari --}}
    <div class="row g-3">
        @foreach ($days as $day)
        <div class="col-12 col-md-6 col-xl-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white border-bottom d-flex justify-content-between align-items-center py-3">
                    <h6 class="fw-bold mb-0">
                        <i class="bi bi-calendar-day me-2 text-primary"></i>{{ $day }}
                    </h6>
                    <span class="badge bg-primary bg-opacity-10 text-primary">
                        {{ $timetableByDay[$day]->count() }} sesi
                    </span>
                </div>
                <div class="card-body p-0">
                    @forelse ($timetableByDay[$day] as $tt)
                    <div class="timetable-item">
                        <div class="d-flex justify-content-between align-items-start">
                            {{-- Kolom Kiri - Warna dan Info Mapel --}}
                            <div class="d-flex align-items-start gap-3">
                                {{-- Strip warna mapel --}}
                                <div class="timetable-color-strip"
                                    style="background:{{ $tt->studySubject->color ?? '#3B82F6' }}"></div>

                                {{-- Info Mata Pelajaran --}}
                                <div class="timetable-info">
                                    <div class="timetable-subject">
                                        {{ $tt->studySubject->name }}
                                    </div>
                                    <div class="timetable-time">
                                        <i class="bi bi-clock me-1"></i>
                                        {{ substr($tt->start_time,0,5) }} – {{ substr($tt->end_time,0,5) }}
                                    </div>
                                    <div class="timetable-teacher">
                                        <i class="bi bi-person me-1"></i>{{ $tt->teacher->name }}
                                        @if ($tt->room)
                                        &bull; <i class="bi bi-geo-alt me-1"></i>{{ $tt->room }}
                                        @endif
                                        @if ($tt->session_type)
                                        &bull; <i class="bi bi-book me-1"></i>{{ ucfirst($tt->session_type) }}
                                        @endif
                                    </div>
                                </div>
                            </div>

                            {{-- Kolom Kanan - Aksi --}}
                            <div class="timetable-actions">
                                <a href="#" class="btn btn-sm btn-outline-primary"
                                    data-bs-toggle="modal" data-bs-target="#showJadwalModal{{ $tt->id }}">
                                    <i class="bi bi-eye"></i> Show
                                </a>
                                <a href="#" class="btn btn-sm btn-outline-warning"
                                    data-bs-toggle="modal" data-bs-target="#editJadwalModal{{ $tt->id }}">
                                    <i class="bi bi-pencil"></i> Edit
                                </a>
                                <button type="button" class="btn btn-sm btn-outline-danger"
                                    onclick="deleteTimetable({{ $tt->id }})">
                                    <i class="bi bi-trash"></i> Delete
                                </button>
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="text-center text-muted py-4">
                        <i class="bi bi-calendar-x d-block mb-1 opacity-50"></i>
                        <small>Tidak ada jadwal</small>
                    </div>
                    @endforelse
                </div>
                <div class="card-footer bg-white border-0">
                    <button type="button" class="btn btn-sm btn-light w-100 text-primary" data-bs-toggle="modal" data-bs-target="#addJadwalModal">
                        <i class="bi bi-plus me-1"></i>Tambah Jadwal {{ $day }}
                    </button>
                </div>
            </div>
        </div>
        @endforeach
    </div>

</div>

<!-- Modal Edit Kelas -->
<div class="modal fade" id="editKelasModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Kelas - {{ $studyGroup->name }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('admin.academic-planner.study-group.update', $studyGroup->id) }}" method="POST">
                @csrf @method('PUT')
                <div class="modal-body">
                    @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0 ps-3">
                            @foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach
                        </ul>
                    </div>
                    @endif

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Nama Kelas <span class="text-danger">*</span></label>
                        <input type="text" name="name" value="{{ old('name', $studyGroup->name) }}"
                            placeholder="Contoh: VII-A" class="form-control @error('name') is-invalid @enderror"
                            maxlength="50" required>
                        @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Tingkatan <span class="text-danger">*</span></label>
                                <select name="grade" class="form-select @error('grade') is-invalid @enderror" required>
                                    <option value="">-- Pilih Tingkatan --</option>
                                    <option value="7" {{ old('grade', $studyGroup->grade) == 7 ? 'selected' : '' }}>VII (Kelas 7)</option>
                                    <option value="8" {{ old('grade', $studyGroup->grade) == 8 ? 'selected' : '' }}>VIII (Kelas 8)</option>
                                    <option value="9" {{ old('grade', $studyGroup->grade) == 9 ? 'selected' : '' }}>IX (Kelas 9)</option>
                                </select>
                                @error('grade')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Jurusan</label>
                                <input type="text" name="section" value="{{ old('section', $studyGroup->section) }}"
                                    placeholder="Contoh: A, B, C" class="form-control @error('section') is-invalid @enderror"
                                    maxlength="10">
                                @error('section')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Wali Kelas</label>
                                <select name="homeroom_teacher_id" class="form-select @error('homeroom_teacher_id') is-invalid @enderror">
                                    <option value="">-- Pilih Wali Kelas --</option>
                                    @foreach (\App\Models\User::where('role', 'guru')->where('is_active', true)->orderBy('name')->get() as $teacher)
                                    <option value="{{ $teacher->id }}" {{ old('homeroom_teacher_id', $studyGroup->homeroom_teacher_id) == $teacher->id ? 'selected' : '' }}>
                                        {{ $teacher->name }}
                                    </option>
                                    @endforeach
                                </select>
                                @error('homeroom_teacher_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Ruang Kelas</label>
                                <input type="text" name="room" value="{{ old('room', $studyGroup->room) }}"
                                    placeholder="Contoh: Lab Komputer" class="form-control @error('room') is-invalid @enderror"
                                    maxlength="50">
                                @error('room')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Tahun Ajaran <span class="text-danger">*</span></label>
                        <input type="text" name="academic_year" value="{{ old('academic_year', $studyGroup->academic_year) }}"
                            placeholder="Contoh: 2024/2025" class="form-control @error('academic_year') is-invalid @enderror"
                            maxlength="9" pattern="[0-9]{4}/[0-9]{4}" required>
                        @error('academic_year')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        <small class="form-text text-muted">Format: YYYY/YYYY (contoh: 2024/2025)</small>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Semester <span class="text-danger">*</span></label>
                                <select name="semester" class="form-select @error('semester') is-invalid @enderror" required>
                                    <option value="">-- Pilih Semester --</option>
                                    <option value="1" {{ old('semester', $studyGroup->semester) == '1' ? 'selected' : '' }}>Semester 1 (Ganjil)</option>
                                    <option value="2" {{ old('semester', $studyGroup->semester) == '2' ? 'selected' : '' }}>Semester 2 (Genap)</option>
                                </select>
                                @error('semester')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <div class="form-check mt-4">
                                    <input class="form-check-input" type="checkbox" id="is_active_edit" name="is_active" value="1"
                                        {{ old('is_active', $studyGroup->is_active) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="is_active_edit">
                                        Aktif
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-warning">
                        <i class="bi bi-check-lg me-1"></i>Update Kelas
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Tambah Jadwal -->
<div class="modal fade" id="addJadwalModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tambah Jadwal Pelajaran - Kelas {{ $studyGroup->name }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('admin.academic-planner.timetables.store') }}" method="POST">
                @csrf
                <input type="hidden" name="study_group_id" value="{{ $studyGroup->id }}">
                <input type="hidden" name="ajax_request" value="1">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            {{-- Mata Pelajaran --}}
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Mata Pelajaran <span class="text-danger">*</span></label>
                                <select name="study_subject_id" class="form-select" required>
                                    <option value="">-- Pilih Mata Pelajaran --</option>
                                    @php
                                    $subjects = \App\Models\StudySubject::where('is_active', true)->orderBy('name')->get();
                                    @endphp
                                    @if($subjects->count() > 0)
                                    @foreach ($subjects as $subject)
                                    <option value="{{ $subject->id }}">{{ $subject->name }} ({{ $subject->code }})</option>
                                    @endforeach
                                    @else
                                    <option value="">Tidak ada mata pelajaran tersedia</option>
                                    @endif
                                </select>
                                @if($subjects->count() == 0)
                                <small class="text-danger">
                                    Tidak ada mata pelajaran aktif.
                                    <a href="{{ route('academic-planner.study-subjects.index') }}" class="text-decoration-none" target="_blank">
                                        Tambah mata pelajaran terlebih dahulu
                                    </a>
                                </small>
                                @endif
                            </div>

                            {{-- Guru --}}
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Guru <span class="text-danger">*</span></label>
                                <select name="teacher_id" class="form-select" required>
                                    <option value="">-- Pilih Guru --</option>
                                    @foreach (\App\Models\User::where('role', 'guru')->where('is_active', true)->orderBy('name')->get() as $teacher)
                                    <option value="{{ $teacher->id }}">{{ $teacher->name }} ({{ $teacher->username }})</option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- Hari --}}
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Hari <span class="text-danger">*</span></label>
                                <select name="day_of_week" class="form-select" required>
                                    <option value="">-- Pilih Hari --</option>
                                    @foreach (['Senin','Selasa','Rabu','Kamis','Jumat','Sabtu'] as $day)
                                    <option value="{{ $day }}">{{ $day }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-md-6">
                            {{-- Jam --}}
                            <div class="row mb-3">
                                <div class="col-6">
                                    <label class="form-label fw-semibold">Jam Mulai <span class="text-danger">*</span></label>
                                    <input type="time" name="start_time" value="07:00" class="form-control" required>
                                </div>
                                <div class="col-6">
                                    <label class="form-label fw-semibold">Jam Selesai <span class="text-danger">*</span></label>
                                    <input type="time" name="end_time" value="08:30" class="form-control" required>
                                </div>
                            </div>

                            {{-- Ruang --}}
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Ruang</label>
                                <input type="text" name="room" placeholder="Contoh: Lab Fisika" class="form-control" maxlength="50">
                            </div>

                            {{-- Sesi Praktikum/Teori --}}
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Sesi <span class="text-danger">*</span></label>
                                <select name="session_type" class="form-select" required>
                                    <option value="">-- Pilih Sesi --</option>
                                    <option value="teori">Teori</option>
                                    <option value="praktikum">Praktikum</option>
                                </select>
                            </div>

                            {{-- Tahun ajaran & semester --}}
                            <div class="row mb-3">
                                <div class="col-7">
                                    <label class="form-label fw-semibold">Tahun Ajaran <span class="text-danger">*</span></label>
                                    <input type="text" name="academic_year" value="2025/2026" placeholder="2025/2026" class="form-control" maxlength="9" required>
                                </div>
                                <div class="col-5">
                                    <label class="form-label fw-semibold">Semester <span class="text-danger">*</span></label>
                                    <select name="semester" class="form-select" required>
                                        <option value="1">Semester 1</option>
                                        <option value="2">Semester 2</option>
                                    </select>
                                </div>
                            </div>

                            {{-- Catatan --}}
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Catatan</label>
                                <textarea name="notes" rows="2" maxlength="500" class="form-control" placeholder="Catatan tambahan (opsional)"></textarea>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-check-lg me-1"></i>Simpan Jadwal
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Edit Jadwal Dinamis -->
@foreach ($timetables as $timetable)
<div class="modal fade" id="editJadwalModal{{ $timetable->id }}" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Jadwal Pelajaran - Kelas {{ $studyGroup->name }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('admin.academic-planner.timetables.update', $timetable->id) }}" method="POST" target="editFrame{{ $timetable->id }}">
                @csrf
                <input type="hidden" name="_method" value="PUT">
                <input type="hidden" name="study_group_id" value="{{ $studyGroup->id }}">
                <input type="hidden" name="ajax_request" value="1">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            {{-- Mata Pelajaran --}}
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Mata Pelajaran <span class="text-danger">*</span></label>
                                <select name="study_subject_id" class="form-select" required>
                                    <option value="">-- Pilih Mata Pelajaran --</option>
                                    @php
                                    $subjects = \App\Models\StudySubject::where('is_active', true)->orderBy('name')->get();
                                    // Debug: uncomment line below to see count
                                    // \Log::info('Total subjects found: ' . $subjects->count());
                                    @endphp
                                    @if($subjects->count() > 0)
                                    @foreach ($subjects as $subject)
                                    <option value="{{ $subject->id }}" {{ $timetable->study_subject_id == $subject->id ? 'selected' : '' }}>
                                        {{ $subject->name }} ({{ $subject->code }})
                                    </option>
                                    @endforeach
                                    @else
                                    <option value="">Tidak ada mata pelajaran tersedia</option>
                                    @endif
                                </select>
                                @if($subjects->count() == 0)
                                <small class="text-danger">
                                    Tidak ada mata pelajaran aktif.
                                    <a href="{{ route('academic-planner.study-subjects.index') }}" class="text-decoration-none" target="_blank">
                                        Tambah mata pelajaran terlebih dahulu
                                    </a>
                                </small>
                                @endif
                            </div>

                            {{-- Guru --}}
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Guru <span class="text-danger">*</span></label>
                                <select name="teacher_id" class="form-select" required>
                                    <option value="">-- Pilih Guru --</option>
                                    @foreach (\App\Models\User::whereIn('role', ['kepala_sekolah', 'guru'])->where('is_active', true)->orderBy('name')->get() as $teacher)
                                    <option value="{{ $teacher->id }}" {{ $timetable->teacher_id == $teacher->id ? 'selected' : '' }}>
                                        {{ $teacher->name }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- Hari --}}
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Hari <span class="text-danger">*</span></label>
                                <select name="day_of_week" class="form-select" required>
                                    <option value="">-- Pilih Hari --</option>
                                    @foreach (['Senin','Selasa','Rabu','Kamis','Jumat','Sabtu'] as $day)
                                    <option value="{{ $day }}" {{ $timetable->day_of_week == $day ? 'selected' : '' }}>{{ $day }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-md-6">
                            {{-- Jam --}}
                            <div class="row mb-3">
                                <div class="col-6">
                                    <label class="form-label fw-semibold">Jam Mulai <span class="text-danger">*</span></label>
                                    <input type="time" name="start_time" value="{{ $timetable->start_time }}" class="form-control" required>
                                </div>
                                <div class="col-6">
                                    <label class="form-label fw-semibold">Jam Selesai <span class="text-danger">*</span></label>
                                    <input type="time" name="end_time" value="{{ $timetable->end_time }}" class="form-control" required>
                                </div>
                            </div>

                            {{-- Ruang --}}
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Ruang</label>
                                <input type="text" name="room" value="{{ $timetable->room }}" placeholder="Contoh: Lab Fisika" class="form-control" maxlength="50">
                            </div>

                            {{-- Sesi Praktikum/Teori --}}
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Sesi <span class="text-danger">*</span></label>
                                <select name="session_type" class="form-select" required>
                                    <option value="">-- Pilih Sesi --</option>
                                    <option value="teori" {{ $timetable->session_type == 'teori' ? 'selected' : '' }}>Teori</option>
                                    <option value="praktikum" {{ $timetable->session_type == 'praktikum' ? 'selected' : '' }}>Praktikum</option>
                                </select>
                            </div>

                            {{-- Tahun ajaran & semester --}}
                            <div class="row mb-3">
                                <div class="col-7">
                                    <label class="form-label fw-semibold">Tahun Ajaran <span class="text-danger">*</span></label>
                                    <input type="text" name="academic_year" value="{{ $timetable->academic_year ?? '2025/2026' }}" placeholder="2025/2026" class="form-control" maxlength="9" required>
                                </div>
                                <div class="col-5">
                                    <label class="form-label fw-semibold">Semester <span class="text-danger">*</span></label>
                                    <select name="semester" class="form-select" required>
                                        <option value="1" {{ $timetable->semester == 1 ? 'selected' : '' }}>Semester 1</option>
                                        <option value="2" {{ $timetable->semester == 2 ? 'selected' : '' }}>Semester 2</option>
                                    </select>
                                </div>
                            </div>

                            {{-- Catatan --}}
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Catatan</label>
                                <textarea name="notes" rows="2" maxlength="500" class="form-control" placeholder="Catatan tambahan (opsional)">{{ $timetable->notes ?? '' }}</textarea>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-warning" style="pointer-events: auto !important; cursor: pointer !important;">
                        <i class="bi bi-check-lg me-1"></i>Update Jadwal
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endforeach

<!-- Modal Show Jadwal Dinamis -->
@foreach ($timetables as $timetable)
<div class="modal fade" id="showJadwalModal{{ $timetable->id }}" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Detail Jadwal Pelajaran - Kelas {{ $studyGroup->name }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <table class="table table-borderless">
                            <tr>
                                <td width="120"><strong>Mata Pelajaran:</strong></td>
                                <td>{{ $timetable->studySubject->name }} ({{ $timetable->studySubject->code }})</td>
                            </tr>
                            <tr>
                                <td><strong>Guru:</strong></td>
                                <td>{{ $timetable->teacher->name }}</td>
                            </tr>
                            <tr>
                                <td><strong>Hari:</strong></td>
                                <td>{{ $timetable->day_of_week }}</td>
                            </tr>
                            <tr>
                                <td><strong>Waktu:</strong></td>
                                <td>{{ substr($timetable->start_time,0,5) }} - {{ substr($timetable->end_time,0,5) }}</td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <table class="table table-borderless">
                            <tr>
                                <td width="120"><strong>Ruang:</strong></td>
                                <td>{{ $timetable->room ?? '-' }}</td>
                            </tr>
                            <tr>
                                <td><strong>Sesi:</strong></td>
                                <td>
                                    <span class="badge bg-{{ $timetable->session_type == 'praktikum' ? 'success' : 'primary' }}">
                                        {{ ucfirst($timetable->session_type ?? 'Teori') }}
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <td><strong>Tahun Ajaran:</strong></td>
                                <td>{{ $timetable->academic_year }}</td>
                            </tr>
                            <tr>
                                <td><strong>Semester:</strong></td>
                                <td>{{ $timetable->semester }}</td>
                            </tr>
                        </table>
                    </div>
                </div>
                @if ($timetable->notes)
                <div class="mt-3">
                    <strong>Catatan:</strong>
                    <p class="text-muted">{{ $timetable->notes }}</p>
                </div>
                @endif
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>
@endforeach

@endsection

@push('styles')
<style>
    /* Custom timetable styling */
    .timetable-item {
        border-bottom: 1px solid #e9ecef;
        padding: 1rem;
        transition: all 0.2s ease;
    }

    .timetable-item:hover {
        background-color: #f8f9fa;
    }

    .timetable-color-strip {
        width: 6px;
        height: 45px;
        border-radius: 2px;
        flex-shrink: 0;
    }

    .timetable-info {
        flex-grow: 1;
        min-width: 0;
    }

    .timetable-subject {
        font-weight: 600;
        color: #212529;
        margin-bottom: 0.25rem;
        font-size: 0.95rem;
    }

    .timetable-time {
        color: #6c757d;
        font-size: 0.8rem;
        margin-bottom: 0.25rem;
    }

    .timetable-teacher {
        color: #6c757d;
        font-size: 0.8rem;
    }

    .timetable-actions {
        flex-shrink: 0;
        display: flex;
        gap: 0.5rem;
        align-items: center;
    }

    .timetable-actions .btn {
        padding: 0.375rem 0.75rem;
        font-size: 0.8rem;
        border-radius: 0.375rem;
        min-width: 80px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 0.25rem;
        transition: all 0.2s ease;
    }

    .timetable-actions .btn:hover {
        transform: translateY(-1px);
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    /* Modal overlay effects */
    .modal.show {
        backdrop-filter: blur(5px);
        -webkit-backdrop-filter: blur(5px);
        z-index: 1050;
    }

    .modal-backdrop.show {
        background-color: rgba(0, 0, 0, 0.7) !important;
        z-index: 1040;
    }

    .modal-content {
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
        border: none;
        z-index: 1060;
    }

    .modal-dialog {
        animation: modalSlideIn 0.3s ease-out;
        z-index: 1055;
    }

    @keyframes modalSlideIn {
        from {
            opacity: 0;
            transform: translate(0, -50px) scale(0.95);
        }

        to {
            opacity: 1;
            transform: translate(0, 0) scale(1);
        }
    }

    /* Ensure submit buttons are always clickable */
    .modal-footer button[type="submit"] {
        pointer-events: auto !important;
        cursor: pointer !important;
        position: relative !important;
        z-index: 1070 !important;
    }

    .modal-footer button[type="submit"]:disabled {
        cursor: not-allowed !important;
        opacity: 0.65 !important;
    }

    /* Remove any overlay that might block clicks */
    .modal * {
        pointer-events: auto;
    }

    .modal-backdrop {
        pointer-events: none;
    }
</style>
@endpush

@push('scripts')
<script>
    // Refresh halaman setelah submit modal
    document.addEventListener('DOMContentLoaded', function() {
        console.log('JavaScript loaded');

        // Listen for postMessage from iframe
        window.addEventListener('message', function(event) {
            console.log('PostMessage received:', event.data);

            if (event.data.success) {
                // Find and close the modal
                const modals = document.querySelectorAll('.modal.show');
                modals.forEach(modal => {
                    const bootstrapModal = bootstrap.Modal.getInstance(modal);
                    if (bootstrapModal) {
                        bootstrapModal.hide();
                    }
                });

                // Show success message
                showAlert(event.data.message || 'Jadwal berhasil diperbarui.', 'success');

                // Refresh page after 1 second
                setTimeout(() => {
                    location.reload();
                }, 1000);
            } else if (event.data.error) {
                showAlert(event.data.message || 'Terjadi kesalahan.', 'danger');
            }
        });

        // Modal tambah jadwal
        const addForm = document.querySelector('#addJadwalModal form');
        if (addForm) {
            console.log('Add form found');
            addForm.addEventListener('submit', function(e) {
                console.log('Add form submit');
                e.preventDefault();
                submitFormAjax(this, 'addJadwalModal');
            });
        }

        // Modal edit jadwal (dinamis) - menggunakan iframe
        @foreach($timetables as $timetable)
        const editForm {
            {
                $timetable - > id
            }
        } = document.querySelector('#editJadwalModal{{ $timetable->id }} form');
        const editFrame {
            {
                $timetable - > id
            }
        } = document.querySelector('#editFrame{{ $timetable->id }}');

        if (editForm {
                {
                    $timetable - > id
                }
            } && editFrame {
                {
                    $timetable - > id
                }
            }) {
            console.log('Edit form {{ $timetable->id }} found with iframe');

            // Simple form submit without complex event handling
            editForm {
                {
                    $timetable - > id
                }
            }.addEventListener('submit', function(e) {
                console.log('Form {{ $timetable->id }} submitting...');

                // Show loading state
                const submitBtn = editForm {
                    {
                        $timetable - > id
                    }
                }.querySelector('button[type="submit"]');
                if (submitBtn) {
                    submitBtn.disabled = true;
                    submitBtn.innerHTML = '<i class="bi bi-hourglass-split me-1"></i>Menyimpan...';
                }

                // Simple iframe monitoring
                setTimeout(function() {
                    try {
                        const iframeContent = editFrame {
                            {
                                $timetable - > id
                            }
                        }.contentDocument.body.innerText;
                        console.log('Iframe content:', iframeContent);

                        if (iframeContent.includes('berhasil') || iframeContent.includes('success')) {
                            showAlert('Jadwal berhasil diperbarui!', 'success');
                            setTimeout(() => window.location.reload(), 1500);
                        } else {
                            showAlert('Terjadi kesalahan. Periksa kembali data.', 'danger');
                            if (submitBtn) {
                                submitBtn.disabled = false;
                                submitBtn.innerHTML = '<i class="bi bi-check-lg me-1"></i>Update Jadwal';
                            }
                        }
                    } catch (e) {
                        console.error('Error:', e);
                        showAlert('Terjadi kesalahan teknis.', 'danger');
                        if (submitBtn) {
                            submitBtn.disabled = false;
                            submitBtn.innerHTML = '<i class="bi bi-check-lg me-1"></i>Update Jadwal';
                        }
                    }
                }, 2000); // Wait 2 seconds for response
            });
        } else {
            console.error('Edit form {{ $timetable->id }} or iframe not found');
        }
        @endforeach
    });

    function submitFormAjax(form, modalId) {
        console.log('submitFormAjax called for', modalId);

        // Client-side validation
        const requiredFields = form.querySelectorAll('[required]');
        let isValid = true;

        requiredFields.forEach(field => {
            if (!field.value.trim()) {
                isValid = false;
                field.classList.add('is-invalid');
                console.log('Field validation failed:', field.name);
            } else {
                field.classList.remove('is-invalid');
            }
        });

        if (!isValid) {
            showAlert('Harap lengkapi semua field yang wajib diisi.', 'danger');
            return;
        }

        const formData = new FormData(form);
        const submitBtn = form.querySelector('button[type="submit"]');
        const originalText = submitBtn.innerHTML;

        console.log('Submit button:', submitBtn);
        console.log('Form action:', form.action);
        console.log('Form method:', form.method);

        // Log form data
        for (let [key, value] of formData.entries()) {
            console.log(key, value);
        }

        // Show loading
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="bi bi-hourglass-split me-1"></i>Menyimpan...';
        console.log('Button disabled and loading shown');

        // Try fetch with fallback
        fetch(form.action, {
                method: form.method,
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json',
                }
            })
            .then(response => {
                console.log('Response received:', response.status, response.statusText);
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                console.log('Data received:', data);
                if (data.success) {
                    // Close modal
                    const modalElement = document.getElementById(modalId);
                    const modal = bootstrap.Modal.getInstance(modalElement);
                    if (modal) {
                        modal.hide();
                        console.log('Modal closed');
                    }

                    // Show success message
                    showAlert(data.message, 'success');
                    console.log('Success alert shown');

                    // Refresh page after 1 second
                    setTimeout(() => {
                        console.log('Refreshing page');
                        location.reload();
                    }, 1000);
                } else {
                    // Show errors
                    if (data.errors) {
                        let errorHtml = '<ul class="mb-0 ps-3">';
                        for (const [field, message] of Object.entries(data.errors)) {
                            errorHtml += `<li>${message}</li>`;
                        }
                        errorHtml += '</ul>';
                        showAlert(errorHtml, 'danger');
                    } else {
                        showAlert(data.message, 'danger');
                    }
                    console.log('Error alert shown');
                }
            })
            .catch(error => {
                console.error('Fetch error:', error);

                // Fallback: try normal form submission
                console.log('Trying fallback submission...');
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalText;

                // Remove event listener and submit normally
                form.removeEventListener('submit', arguments.callee);
                form.submit();
            })
            .finally(() => {
                // Only reset button if not using fallback
                if (submitBtn.disabled) {
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = originalText;
                    console.log('Button reset');
                }
            });
    }

    function showAlert(message, type) {
        const alertHtml = `
            <div class="alert alert-${type} alert-dismissible fade show" role="alert">
                ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        `;

        // Insert alert at the top of the content
        const container = document.querySelector('.container');
        container.insertAdjacentHTML('afterbegin', alertHtml);

        // Auto remove after 5 seconds
        setTimeout(() => {
            const alert = container.querySelector('.alert');
            if (alert) {
                alert.remove();
            }
        }, 5000);
    }

    // Function untuk delete timetable
    function deleteTimetable(id) {
        if (confirm('Hapus jadwal ini?')) {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = `/academic-planner/timetables/${id}`;

            // Add CSRF token
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
            if (csrfToken) {
                const csrfInput = document.createElement('input');
                csrfInput.type = 'hidden';
                csrfInput.name = '_token';
                csrfInput.value = csrfToken;
                form.appendChild(csrfInput);
            }

            // Add method override
            const methodInput = document.createElement('input');
            methodInput.type = 'hidden';
            methodInput.name = '_method';
            methodInput.value = 'DELETE';
            form.appendChild(methodInput);

            // Submit form
            document.body.appendChild(form);
            form.submit();
        }
    }

    // Function untuk delete kelas
    function deleteKelas(id, name) {
        if (confirm(`Hapus kelas ${name}? Semua jadwalnya akan ikut terhapus.`)) {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = `/academic-planner/${id}/delete-study-group`;

            // Add CSRF token
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
            if (csrfToken) {
                const csrfInput = document.createElement('input');
                csrfInput.type = 'hidden';
                csrfInput.name = '_token';
                csrfInput.value = csrfToken;
                form.appendChild(csrfInput);
            }

            // Add method override
            const methodInput = document.createElement('input');
            methodInput.type = 'hidden';
            methodInput.name = '_method';
            methodInput.value = 'DELETE';
            form.appendChild(methodInput);

            // Submit form
            document.body.appendChild(form);
            form.submit();
        }
    }
</script>
@endpush

<!-- Hidden iframes for form submission -->
@foreach ($timetables as $timetable)
<iframe id="editFrame{{ $timetable->id }}" name="editFrame{{ $timetable->id }}" style="display:none;"></iframe>
@endforeach