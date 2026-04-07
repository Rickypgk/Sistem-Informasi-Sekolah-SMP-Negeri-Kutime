{{-- resources/views/admin/kelas/index.blade.php --}}
@extends('layouts.app')
@section('title', 'Kelola Kelas')

@section('content')

<div class="space-y-4">

    {{-- ── Header ─────────────────────────────────────────────── --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
        <div>
            <h2 class="text-sm font-bold text-slate-800 dark:text-slate-100">Kelola Kelas</h2>
            <p class="text-xs text-slate-400 dark:text-slate-500 mt-0.5">
                Manajemen data kelas dan wali kelas pada sistem.
            </p>
        </div>
        <button onclick="openModal('modalTambahKelas')"
                class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl bg-indigo-600
                       text-white text-xs font-semibold hover:bg-indigo-700
                       active:scale-95 transition shadow-sm w-fit">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M12 4v16m8-8H4"/>
            </svg>
            Tambah Kelas
        </button>
    </div>

    {{-- ── Search ──────────────────────────────────────────────── --}}
    <div class="relative max-w-xs">
        <svg class="absolute left-2.5 top-1/2 -translate-y-1/2 w-3.5 h-3.5 text-slate-400"
             fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0"/>
        </svg>
        <input type="text" id="searchInput"
               placeholder="Cari kelas, wali kelas..."
               class="w-full pl-8 pr-4 py-2 rounded-xl border border-slate-200 dark:border-slate-600
                      bg-white dark:bg-slate-800 text-xs
                      focus:outline-none focus:ring-2 focus:ring-indigo-300
                      placeholder:text-slate-400 transition">
    </div>

    {{-- ── Tabel ───────────────────────────────────────────────── --}}
    <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200
                dark:border-slate-700 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="bg-slate-50 dark:bg-slate-900/50 border-b border-slate-200
                               dark:border-slate-700 text-left">
                        <th class="px-3 py-2.5 text-[10px] font-semibold text-slate-500
                                   dark:text-slate-400 uppercase tracking-wide w-7">#</th>
                        <th class="px-3 py-2.5 text-[10px] font-semibold text-slate-500
                                   dark:text-slate-400 uppercase tracking-wide min-w-[120px]">
                            Nama Kelas
                        </th>
                        <th class="px-3 py-2.5 text-[10px] font-semibold text-slate-500
                                   dark:text-slate-400 uppercase tracking-wide">Tingkat</th>
                        <th class="px-3 py-2.5 text-[10px] font-semibold text-slate-500
                                   dark:text-slate-400 uppercase tracking-wide min-w-[110px]">
                            Tahun Ajaran
                        </th>
                        <th class="px-3 py-2.5 text-[10px] font-semibold text-slate-500
                                   dark:text-slate-400 uppercase tracking-wide min-w-[160px]">
                            Wali Kelas
                        </th>
                        <th class="px-3 py-2.5 text-[10px] font-semibold text-slate-500
                                   dark:text-slate-400 uppercase tracking-wide text-center">
                            Siswa
                        </th>
                        <th class="px-3 py-2.5 text-[10px] font-semibold text-slate-500
                                   dark:text-slate-400 uppercase tracking-wide text-center min-w-[80px]">
                            Aksi
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-700/50">

                    @forelse($kelas as $i => $k)
                        <tr class="hover:bg-slate-50 dark:hover:bg-slate-700/20
                                   transition-colors searchable-row">

                            {{-- No --}}
                            <td class="px-3 py-2.5 text-[10px] text-slate-400">{{ $i + 1 }}</td>

                            {{-- Nama Kelas --}}
                            <td class="px-3 py-2.5">
                                <span class="text-xs font-semibold text-slate-800 dark:text-slate-100">
                                    {{ $k->nama }}
                                </span>
                            </td>

                            {{-- Tingkat --}}
                            <td class="px-3 py-2.5">
                                <span class="inline-flex px-1.5 py-0.5 rounded-lg text-[10px]
                                             font-semibold bg-indigo-50 text-indigo-700
                                             dark:bg-indigo-900/30 dark:text-indigo-300
                                             border border-indigo-100 dark:border-indigo-800">
                                    {{ $k->tingkat }}
                                </span>
                            </td>

                            {{-- Tahun Ajaran --}}
                            <td class="px-3 py-2.5 text-[10px] text-slate-600 dark:text-slate-400">
                                {{ $k->tahun_ajaran }}
                            </td>

                            {{-- Wali Kelas --}}
                            <td class="px-3 py-2.5">
                                @if($k->guru && $k->guru->nama)
                                    <div class="flex items-center gap-2">
                                        <div class="w-6 h-6 rounded-lg bg-indigo-100
                                                    dark:bg-indigo-900/40 flex items-center
                                                    justify-center text-indigo-600
                                                    dark:text-indigo-400 text-[10px] font-bold shrink-0">
                                            {{ strtoupper(substr($k->guru->nama, 0, 1)) }}
                                        </div>
                                        <span class="text-xs text-slate-700 dark:text-slate-300 truncate">
                                            {{ $k->guru->nama }}
                                        </span>
                                    </div>
                                @else
                                    <span class="text-[10px] text-slate-300 dark:text-slate-600">
                                        — Belum ditentukan
                                    </span>
                                @endif
                            </td>

                            {{-- Jumlah Siswa --}}
                            <td class="px-3 py-2.5 text-center">
                                <span class="inline-flex items-center justify-center
                                             min-w-[1.5rem] h-5 px-1.5 rounded-full
                                             text-[10px] font-semibold
                                             bg-slate-100 text-slate-600
                                             dark:bg-slate-700 dark:text-slate-400">
                                    {{ $k->siswas_count ?? $k->siswas->count() }}
                                </span>
                            </td>

                            {{-- Aksi --}}
                            <td class="px-3 py-2.5">
                                <div class="flex items-center justify-center gap-0.5">
                                    {{-- Edit --}}
                                    <button onclick="openEditModal({{ $k->id }})"
                                            title="Edit Kelas"
                                            class="p-1.5 rounded-lg text-slate-400
                                                   hover:text-indigo-600 hover:bg-indigo-50
                                                   dark:hover:text-indigo-400 dark:hover:bg-indigo-900/30
                                                   transition">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor"
                                             viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                  stroke-width="2"
                                                  d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0
                                                     002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828
                                                     15H9v-2.828l8.586-8.586z"/>
                                        </svg>
                                    </button>
                                    {{-- Hapus --}}
                                    <button onclick="openDeleteModal({{ $k->id }}, '{{ addslashes($k->nama) }}')"
                                            title="Hapus Kelas"
                                            class="p-1.5 rounded-lg text-slate-400
                                                   hover:text-red-600 hover:bg-red-50
                                                   dark:hover:text-red-400 dark:hover:bg-red-900/30
                                                   transition">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor"
                                             viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                  stroke-width="2"
                                                  d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0
                                                     01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0
                                                     00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                    </button>
                                </div>
                            </td>

                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-4 py-14 text-center">
                                <div class="flex flex-col items-center gap-2 text-slate-400">
                                    <div class="w-12 h-12 rounded-2xl bg-slate-100 dark:bg-slate-700
                                                flex items-center justify-center">
                                        <svg class="w-6 h-6 text-slate-300 dark:text-slate-600"
                                             fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                  stroke-width="1.5"
                                                  d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14
                                                     0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1
                                                     4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="text-xs font-semibold text-slate-500 dark:text-slate-400">
                                            Belum ada data kelas
                                        </p>
                                        <p class="text-[10px] mt-0.5 text-slate-400">
                                            Gunakan tombol
                                            <strong class="text-slate-600 dark:text-slate-300">
                                                + Tambah Kelas
                                            </strong>
                                            untuk menambahkan kelas.
                                        </p>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @endforelse

                </tbody>
            </table>
        </div>
    </div>

</div>

{{-- Data JSON untuk modal edit --}}
<script id="kelasData" type="application/json">
{!! json_encode($kelas->map(fn($k) => [
    'id'           => $k->id,
    'nama'         => $k->nama,
    'tingkat'      => $k->tingkat,
    'tahun_ajaran' => $k->tahun_ajaran,
    'guru_id'      => $k->guru_id,
])->keyBy('id')) !!}
</script>

@include('admin.kelas._modal_tambah', ['gurus' => $gurus])
@include('admin.kelas._modal_edit',   ['gurus' => $gurus])
@include('admin.kelas._modal_hapus')

@push('styles')
<style>
    .animate-modal {
        animation: modalPop .22s cubic-bezier(.34,1.56,.64,1);
    }
    @keyframes modalPop {
        from { opacity:0; transform:scale(.92) translateY(10px); }
        to   { opacity:1; transform:scale(1)   translateY(0); }
    }
</style>
@endpush

@push('scripts')
<script>
const KELAS_DATA = JSON.parse(document.getElementById('kelasData').textContent);

function openModal(id) {
    var el = document.getElementById(id);
    el.classList.remove('hidden'); el.classList.add('flex');
    document.body.style.overflow = 'hidden';
}
function closeModal(id) {
    var el = document.getElementById(id);
    el.classList.add('hidden'); el.classList.remove('flex');
    document.body.style.overflow = '';
}
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape')
        ['modalTambahKelas','modalEditKelas','modalHapusKelas'].forEach(closeModal);
});

function openEditModal(kelasId) {
    var d = KELAS_DATA[kelasId];
    if (!d) return;
    document.getElementById('editKelasNama').value    = d.nama;
    document.getElementById('editKelasTingkat').value = d.tingkat;
    document.getElementById('editKelasTahun').value   = d.tahun_ajaran;
    document.getElementById('editKelasGuru').value    = d.guru_id ?? '';
    document.getElementById('formEditKelas').action   = '{{ url("admin/kelas") }}/' + kelasId;
    openModal('modalEditKelas');
}

function openDeleteModal(kelasId, kelasNama) {
    document.getElementById('deleteKelasName').textContent = kelasNama;
    document.getElementById('formHapusKelas').action = '{{ url("admin/kelas") }}/' + kelasId;
    openModal('modalHapusKelas');
}

document.getElementById('searchInput').addEventListener('input', function() {
    var q = this.value.toLowerCase();
    document.querySelectorAll('.searchable-row').forEach(function(row) {
        row.style.display = row.textContent.toLowerCase().includes(q) ? '' : 'none';
    });
});

@if($errors->any())
document.addEventListener('DOMContentLoaded', function() { openModal('modalTambahKelas'); });
@endif
</script>
@endpush

@endsection