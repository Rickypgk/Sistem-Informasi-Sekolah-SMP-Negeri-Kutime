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
                       bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-semibold">
                Import Excel
            </button>

            {{-- Tombol Tambah --}}
            <button onclick="openModal('modalTambahUser')"
                class="inline-flex items-center gap-1.5 px-3 py-2 rounded-xl
                       bg-indigo-600 text-white text-xs font-semibold">
                Tambah User
            </button>

        </div>
    </div>

    {{-- ===== SEARCH ===== --}}
    <input type="text" id="searchInput"
        class="w-full max-w-xs px-3 py-2 border rounded-xl text-xs"
        placeholder="Cari user...">

    {{-- ===== TABS ===== --}}
    <div class="flex gap-2">
        <a href="{{ route('admin.users.index', ['tab' => 'guru']) }}">Guru</a>
        <a href="{{ route('admin.users.index', ['tab' => 'siswa']) }}">Siswa</a>
    </div>

    {{-- ===== TABEL ===== --}}
    <div>
        @include('admin.users._table_guru', ['users' => $gurus])
        @include('admin.users._table_siswa', ['users' => $siswas])
    </div>

</div>

{{-- ================= MODAL IMPORT ================= --}}
<div id="modalImport" class="fixed inset-0 hidden items-center justify-center bg-black/50">

    <div class="bg-white p-5 rounded-xl w-full max-w-md">

        <h3 class="text-sm font-bold mb-3">Import Excel</h3>

        <form action="{{ route('admin.users.import') }}" method="POST" enctype="multipart/form-data">
            @csrf

            {{-- ROLE --}}
            <div class="mb-3">
                <label class="text-xs">Role</label>
                <select name="role" id="roleSelect" class="w-full border rounded p-2 text-xs">
                    <option value="guru">Guru</option>
                    <option value="siswa">Siswa</option>
                </select>
            </div>

            {{-- SISWA EXTRA --}}
            <div id="sectionSiswaImport" class="hidden space-y-2">

                <select name="grade" id="importGrade" class="w-full border p-2 text-xs">
                    <option value="">Pilih Tingkat</option>
                    <option value="7">7</option>
                    <option value="8">8</option>
                    <option value="9">9</option>
                </select>

                <select name="semester" id="importSemester" class="w-full border p-2 text-xs">
                    <option value="">Pilih Semester</option>
                    <option value="1">1</option>
                    <option value="2">2</option>
                </select>

                <select name="kelas_id" id="importKelasId" class="w-full border p-2 text-xs">
                    <option value="">Pilih Kelas</option>
                </select>

            </div>

            {{-- PASSWORD --}}
            <input type="text" name="password_import"
                class="w-full border p-2 text-xs mt-2"
                placeholder="Password default">

            {{-- FILE --}}
            <input type="file" name="import_file"
                class="w-full text-xs mt-2">

            <button type="submit"
                class="w-full bg-emerald-600 text-white py-2 mt-3 rounded">
                Import
            </button>

        </form>

    </div>

</div>

@endsection

@push('scripts')
<script>

// ================= MODAL =================
function openModal(id) {
    document.getElementById(id).classList.remove('hidden');
    document.getElementById(id).classList.add('flex');
}

function closeModal(id) {
    document.getElementById(id).classList.add('hidden');
}

// ================= SEARCH =================
document.getElementById('searchInput').addEventListener('input', function () {
    let q = this.value.toLowerCase();
    document.querySelectorAll('.searchable-row').forEach(row => {
        row.style.display = row.innerText.toLowerCase().includes(q) ? '' : 'none';
    });
});

// ================= FIX ERROR DISINI =================
const allKelas = @json(
    ($kelasList ?? collect())->map(function ($k) {
        return [
            'id'            => $k->id,
            'name'          => $k->name,
            'grade'         => (string) $k->grade,
            'semester'      => (string) $k->semester,
            'academic_year' => $k->academic_year ?? '',
        ];
    })->values()->toArray()
);

// ================= FILTER KELAS =================
const roleSelect = document.getElementById('roleSelect');
const sectionSiswa = document.getElementById('sectionSiswaImport');
const grade = document.getElementById('importGrade');
const semester = document.getElementById('importSemester');
const kelas = document.getElementById('importKelasId');

roleSelect.addEventListener('change', function () {
    sectionSiswa.classList.toggle('hidden', this.value !== 'siswa');
});

function filterKelas() {

    if (!grade.value || !semester.value) {
        kelas.innerHTML = '<option>Pilih dulu</option>';
        return;
    }

    let filtered = allKelas.filter(k =>
        k.grade === grade.value && k.semester === semester.value
    );

    let html = '<option value="">Pilih Kelas</option>';

    filtered.forEach(k => {
        html += `<option value="${k.id}">${k.name}</option>`;
    });

    kelas.innerHTML = html;
}

grade.addEventListener('change', filterKelas);
semester.addEventListener('change', filterKelas);

</script>
@endpush