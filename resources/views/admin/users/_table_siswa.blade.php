{{-- resources/views/admin/users/_table_siswa.blade.php
     Variabel masuk: $users (Collection<User> with role=siswa, with siswa.kelas)
--}}

@php
    /*
    |------------------------------------------------------------------
    | Ambil kelas unik dari data siswa yang sedang ditampilkan
    | Sumber: relasi $user->siswa->kelas->nama
    |------------------------------------------------------------------
    */
    $kelasUnik = $users
        ->map(fn($u) => $u->siswa?->kelas)
        ->filter()
        ->unique('id')
        ->sortBy('nama')
        ->values();
@endphp

<div class="space-y-3">

    {{-- ── Filter Kelas ─────────────────────────────────────────── --}}
    <div class="flex flex-wrap items-center gap-2 bg-white dark:bg-slate-800
                px-4 py-3 rounded-2xl border border-slate-200 dark:border-slate-700
                shadow-sm">

        <span class="text-[10px] font-bold text-slate-500 dark:text-slate-400
                     uppercase tracking-wider shrink-0">
            Filter Kelas:
        </span>

        {{-- Tombol "Semua" --}}
        <button data-kelas-id="all"
                class="kelas-filter-btn px-3 py-1 rounded-lg text-xs font-semibold
                       transition-all active:scale-95
                       bg-indigo-600 text-white shadow-sm">
            Semua
            <span class="ml-1 bg-white/25 rounded px-1 text-[10px]">
                {{ $users->count() }}
            </span>
        </button>

        @forelse($kelasUnik as $k)
            @php
                $jumlahDiKelas = $users->filter(
                    fn($u) => $u->siswa?->kelas_id === $k->id
                )->count();
            @endphp
            <button data-kelas-id="{{ $k->id }}"
                    class="kelas-filter-btn px-3 py-1 rounded-lg text-xs font-semibold
                           transition-all active:scale-95
                           bg-white dark:bg-slate-700
                           border border-slate-200 dark:border-slate-600
                           text-slate-700 dark:text-slate-300
                           hover:bg-slate-50 dark:hover:bg-slate-600">
                {{ $k->nama }}
                <span class="ml-1 bg-slate-100 dark:bg-slate-600 rounded px-1 text-[10px]">
                    {{ $jumlahDiKelas }}
                </span>
            </button>
        @empty
            <span class="text-[10px] text-slate-400 italic">
                Belum ada kelas — buat kelas di
                <a href="{{ route('admin.kelas.index') }}"
                   class="text-indigo-500 hover:underline font-semibold">
                    Kelola Kelas
                </a>
            </span>
        @endforelse

    </div>

    {{-- ── Tabel Siswa ───────────────────────────────────────────── --}}
    <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200
                dark:border-slate-700 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full" id="tabelSiswa">
                <thead>
                    <tr class="bg-slate-50 dark:bg-slate-900/50 border-b
                               border-slate-200 dark:border-slate-700 text-left">
                        <th class="px-3 py-2.5 text-[10px] font-semibold text-slate-500
                                   dark:text-slate-400 uppercase tracking-wide w-7">#</th>
                        <th class="px-3 py-2.5 text-[10px] font-semibold text-slate-500
                                   dark:text-slate-400 uppercase tracking-wide min-w-[180px]">
                            Siswa
                        </th>
                        <th class="px-3 py-2.5 text-[10px] font-semibold text-slate-500
                                   dark:text-slate-400 uppercase tracking-wide min-w-[80px]">
                            Kelas
                        </th>
                        <th class="px-3 py-2.5 text-[10px] font-semibold text-slate-500
                                   dark:text-slate-400 uppercase tracking-wide min-w-[100px]">
                            NIS
                        </th>
                        <th class="px-3 py-2.5 text-[10px] font-semibold text-slate-500
                                   dark:text-slate-400 uppercase tracking-wide min-w-[120px]">
                            NIK
                        </th>
                        <th class="px-3 py-2.5 text-[10px] font-semibold text-slate-500
                                   dark:text-slate-400 uppercase tracking-wide">
                            Kelamin
                        </th>
                        <th class="px-3 py-2.5 text-[10px] font-semibold text-slate-500
                                   dark:text-slate-400 uppercase tracking-wide min-w-[130px]">
                            Tempat / Tgl Lahir
                        </th>
                        <th class="px-3 py-2.5 text-[10px] font-semibold text-slate-500
                                   dark:text-slate-400 uppercase tracking-wide">
                            Agama
                        </th>
                        <th class="px-3 py-2.5 text-[10px] font-semibold text-slate-500
                                   dark:text-slate-400 uppercase tracking-wide min-w-[100px]">
                            No. Telp
                        </th>
                        <th class="px-3 py-2.5 text-[10px] font-semibold text-slate-500
                                   dark:text-slate-400 uppercase tracking-wide min-w-[100px]">
                            SKHUN
                        </th>
                        <th class="px-3 py-2.5 text-[10px] font-semibold text-slate-500
                                   dark:text-slate-400 uppercase tracking-wide min-w-[150px]">
                            Alamat
                        </th>
                        <th class="px-3 py-2.5 text-[10px] font-semibold text-slate-500
                                   dark:text-slate-400 uppercase tracking-wide">RT</th>
                        <th class="px-3 py-2.5 text-[10px] font-semibold text-slate-500
                                   dark:text-slate-400 uppercase tracking-wide">RW</th>
                        <th class="px-3 py-2.5 text-[10px] font-semibold text-slate-500
                                   dark:text-slate-400 uppercase tracking-wide min-w-[80px]">
                            Dusun
                        </th>
                        <th class="px-3 py-2.5 text-[10px] font-semibold text-slate-500
                                   dark:text-slate-400 uppercase tracking-wide min-w-[100px]">
                            Kecamatan
                        </th>
                        <th class="px-3 py-2.5 text-[10px] font-semibold text-slate-500
                                   dark:text-slate-400 uppercase tracking-wide min-w-[75px]">
                            Kode Pos
                        </th>
                        <th class="px-3 py-2.5 text-[10px] font-semibold text-slate-500
                                   dark:text-slate-400 uppercase tracking-wide min-w-[110px]">
                            Jenis Tinggal
                        </th>
                        <th class="px-3 py-2.5 text-[10px] font-semibold text-slate-500
                                   dark:text-slate-400 uppercase tracking-wide min-w-[110px]">
                            Transportasi
                        </th>
                        <th class="px-3 py-2.5 text-[10px] font-semibold text-slate-500
                                   dark:text-slate-400 uppercase tracking-wide">
                            KPS
                        </th>
                        <th class="px-3 py-2.5 text-[10px] font-semibold text-slate-500
                                   dark:text-slate-400 uppercase tracking-wide min-w-[100px]">
                            No. KPS
                        </th>
                        <th class="px-3 py-2.5 text-[10px] font-semibold text-slate-500
                                   dark:text-slate-400 uppercase tracking-wide text-center
                                   min-w-[90px]">
                            Aksi
                        </th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-slate-100 dark:divide-slate-700/50"
                       id="siswaBody">
                    @forelse($users as $i => $user)
                        @php
                            $s        = $user->siswa;
                            $kelasId  = $s?->kelas_id ?? 'none';
                            $kelasNama= $s?->kelas?->nama ?? null;
                        @endphp

                        <tr class="hover:bg-slate-50 dark:hover:bg-slate-700/20
                                   transition-colors searchable-row"
                            data-kelas-id="{{ $kelasId }}">

                            {{-- No --}}
                            <td class="px-3 py-2.5 text-[10px] text-slate-400">
                                {{ $i + 1 }}
                            </td>

                            {{-- Nama + Email --}}
                            <td class="px-3 py-2.5">
                                <div class="flex items-center gap-2">
                                    @if($user->photo)
                                        <img src="{{ Storage::url($user->photo) }}" alt=""
                                             class="w-7 h-7 rounded-lg object-cover
                                                    border border-slate-200
                                                    dark:border-slate-600 shrink-0">
                                    @else
                                        <div class="w-7 h-7 rounded-lg bg-indigo-100
                                                    dark:bg-indigo-900/40 flex items-center
                                                    justify-center text-indigo-600
                                                    dark:text-indigo-400 text-xs font-bold
                                                    shrink-0">
                                            {{ strtoupper(substr($s?->nama ?? $user->name, 0, 1)) }}
                                        </div>
                                    @endif
                                    <div class="min-w-0">
                                        <p class="text-xs font-semibold text-slate-800
                                                  dark:text-slate-100 leading-tight truncate">
                                            {{ $s?->nama ?? $user->name }}
                                        </p>
                                        <p class="text-[10px] text-slate-400 truncate">
                                            {{ $user->email }}
                                        </p>
                                    </div>
                                </div>
                            </td>

                            {{-- Kelas — tampil jelas di kolom kedua --}}
                            <td class="px-3 py-2.5">
                                @if($kelasNama)
                                    <span class="inline-flex px-1.5 py-0.5 rounded-lg
                                                 text-[10px] font-semibold
                                                 bg-violet-50 text-violet-700
                                                 dark:bg-violet-900/30 dark:text-violet-400
                                                 border border-violet-100
                                                 dark:border-violet-800 whitespace-nowrap">
                                        {{ $kelasNama }}
                                    </span>
                                @else
                                    <span class="text-[10px] text-slate-300
                                                 dark:text-slate-600 italic">
                                        Belum ada
                                    </span>
                                @endif
                            </td>

                            {{-- NIS --}}
                            <td class="px-3 py-2.5">
                                @if($s?->nidn)
                                    <span class="font-mono text-[10px] text-slate-600
                                                 dark:text-slate-400 bg-slate-100
                                                 dark:bg-slate-700 px-1.5 py-0.5 rounded-md">
                                        {{ $s->nidn }}
                                    </span>
                                @else
                                    <span class="text-slate-300 dark:text-slate-600
                                                 text-[10px]">—</span>
                                @endif
                            </td>

                            {{-- NIK --}}
                            <td class="px-3 py-2.5">
                                @if($s?->nik)
                                    <span class="font-mono text-[10px] text-slate-600
                                                 dark:text-slate-400">{{ $s->nik }}</span>
                                @else
                                    <span class="text-slate-300 dark:text-slate-600
                                                 text-[10px]">—</span>
                                @endif
                            </td>

                            {{-- JK --}}
                            <td class="px-3 py-2.5">
                                @if($s?->jk)
                                    <span class="inline-flex px-1.5 py-0.5 rounded-full
                                                 text-[10px] font-semibold
                                                 {{ $s->jk==='L'
                                                     ? 'bg-blue-50 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400'
                                                     : 'bg-pink-50 text-pink-700 dark:bg-pink-900/30 dark:text-pink-400' }}">
                                        {{ $s->jk==='L' ? '♂ L' : '♀ P' }}
                                    </span>
                                @else
                                    <span class="text-slate-300 dark:text-slate-600
                                                 text-[10px]">—</span>
                                @endif
                            </td>

                            {{-- Tempat/Tgl Lahir --}}
                            <td class="px-3 py-2.5">
                                @if($s?->tempat_lahir || $s?->tgl_lahir)
                                    <p class="text-[10px] text-slate-700 dark:text-slate-300
                                              leading-tight">
                                        {{ $s->tempat_lahir ?? '-' }}
                                    </p>
                                    <p class="text-[10px] text-slate-400">
                                        {{ $s?->tgl_lahir?->translatedFormat('d F Y') ?? '-' }}
                                    </p>
                                @else
                                    <span class="text-slate-300 dark:text-slate-600
                                                 text-[10px]">—</span>
                                @endif
                            </td>

                            {{-- Kolom sederhana --}}
                            <td class="px-3 py-2.5 text-[10px] text-slate-600
                                       dark:text-slate-400">{{ $s?->agama ?? '—' }}</td>
                            <td class="px-3 py-2.5 text-[10px] text-slate-600
                                       dark:text-slate-400">{{ $s?->no_telp ?? '—' }}</td>

                            {{-- SKHUN --}}
                            <td class="px-3 py-2.5">
                                @if($s?->shkun)
                                    <span class="font-mono text-[10px] text-slate-600
                                                 dark:text-slate-400">{{ $s->shkun }}</span>
                                @else
                                    <span class="text-slate-300 dark:text-slate-600
                                                 text-[10px]">—</span>
                                @endif
                            </td>

                            {{-- Alamat --}}
                            <td class="px-3 py-2.5">
                                @if($s?->alamat)
                                    <p class="text-[10px] text-slate-600 dark:text-slate-400
                                              leading-relaxed line-clamp-2"
                                       title="{{ $s->alamat }}">{{ $s->alamat }}</p>
                                @else
                                    <span class="text-slate-300 dark:text-slate-600
                                                 text-[10px]">—</span>
                                @endif
                            </td>

                            <td class="px-3 py-2.5 text-[10px] text-slate-600
                                       dark:text-slate-400">{{ $s?->rt ?? '—' }}</td>
                            <td class="px-3 py-2.5 text-[10px] text-slate-600
                                       dark:text-slate-400">{{ $s?->rw ?? '—' }}</td>
                            <td class="px-3 py-2.5 text-[10px] text-slate-600
                                       dark:text-slate-400">{{ $s?->dusun ?? '—' }}</td>
                            <td class="px-3 py-2.5 text-[10px] text-slate-600
                                       dark:text-slate-400">{{ $s?->kecamatan ?? '—' }}</td>
                            <td class="px-3 py-2.5 text-[10px] text-slate-600
                                       dark:text-slate-400">{{ $s?->kode_pos ?? '—' }}</td>
                            <td class="px-3 py-2.5 text-[10px] text-slate-600
                                       dark:text-slate-400">{{ $s?->jenis_tinggal ?? '—' }}</td>
                            <td class="px-3 py-2.5 text-[10px] text-slate-600
                                       dark:text-slate-400">{{ $s?->jalan_transportasi ?? '—' }}</td>

                            {{-- KPS --}}
                            <td class="px-3 py-2.5">
                                @if($s?->penerima_kps === 'Ya')
                                    <span class="inline-flex px-1.5 py-0.5 rounded-full
                                                 text-[10px] font-semibold bg-green-100
                                                 text-green-700 dark:bg-green-900/30
                                                 dark:text-green-400">Ya</span>
                                @else
                                    <span class="inline-flex px-1.5 py-0.5 rounded-full
                                                 text-[10px] font-semibold bg-slate-100
                                                 text-slate-600 dark:bg-slate-700
                                                 dark:text-slate-400">Tdk</span>
                                @endif
                            </td>

                            {{-- No. KPS --}}
                            <td class="px-3 py-2.5">
                                @if($s?->no_kps)
                                    <span class="font-mono text-[10px] text-slate-600
                                                 dark:text-slate-400">{{ $s->no_kps }}</span>
                                @else
                                    <span class="text-slate-300 dark:text-slate-600
                                                 text-[10px]">—</span>
                                @endif
                            </td>

                            {{-- Aksi --}}
                            <td class="px-3 py-2.5">
                                <div class="flex items-center justify-center gap-0.5">
                                    <button onclick="openDetailModal({{ $user->id }})"
                                            title="Lihat Detail"
                                            class="p-1.5 rounded-lg text-slate-400
                                                   hover:text-indigo-600 hover:bg-indigo-50
                                                   dark:hover:bg-indigo-900/30 transition">
                                        <svg class="w-3.5 h-3.5" fill="none"
                                             stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round"
                                                  stroke-linejoin="round" stroke-width="2"
                                                  d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            <path stroke-linecap="round"
                                                  stroke-linejoin="round" stroke-width="2"
                                                  d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478
                                                     0 8.268 2.943 9.542 7-1.274 4.057-5.064
                                                     7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                        </svg>
                                    </button>
                                    {{-- ── Tombol Edit ── letakkan PERTAMA di blok aksi ── --}}
                                    <button onclick="openEditModal({{ $user->id }})"
                                            title="Edit User"
                                            class="p-1.5 rounded-lg text-slate-400 hover:text-indigo-600
                                                hover:bg-indigo-50 dark:hover:bg-indigo-900/30 transition">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0
                                                    002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828
                                                    15H9v-2.828l8.586-8.586z"/>
                                        </svg>
                                    </button>
                                    <button onclick="openResetModal({{ $user->id }},
                                                '{{ addslashes($s?->nama ?? $user->name) }}')"
                                            title="Reset Password"
                                            class="p-1.5 rounded-lg text-slate-400
                                                   hover:text-amber-600 hover:bg-amber-50
                                                   dark:hover:bg-amber-900/30 transition">
                                        <svg class="w-3.5 h-3.5" fill="none"
                                             stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round"
                                                  stroke-linejoin="round" stroke-width="2"
                                                  d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743
                                                     5.743L11 17H9v2H7v2H4a1 1 0
                                                     01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964
                                                     A6 6 0 1121 9z"/>
                                        </svg>
                                    </button>
                                    <button onclick="openDeleteModal({{ $user->id }},
                                                '{{ addslashes($s?->nama ?? $user->name) }}')"
                                            title="Hapus User"
                                            class="p-1.5 rounded-lg text-slate-400
                                                   hover:text-red-600 hover:bg-red-50
                                                   dark:hover:bg-red-900/30 transition">
                                        <svg class="w-3.5 h-3.5" fill="none"
                                             stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round"
                                                  stroke-linejoin="round" stroke-width="2"
                                                  d="M19 7l-.867 12.142A2 2 0 0116.138
                                                     21H7.862a2 2 0 01-1.995-1.858L5 7m5
                                                     4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1
                                                     0 00-1 1v3M4 7h16"/>
                                        </svg>
                                    </button>
                                </div>
                            </td>
                        </tr>

                    @empty
                        <tr>
                            <td colspan="21" class="px-4 py-12 text-center">
                                <div class="flex flex-col items-center gap-2 text-slate-400">
                                    <div class="w-12 h-12 rounded-2xl bg-slate-100
                                                dark:bg-slate-700 flex items-center
                                                justify-center">
                                        <svg class="w-6 h-6 text-slate-300 dark:text-slate-600"
                                             fill="none" stroke="currentColor"
                                             viewBox="0 0 24 24">
                                            <path stroke-linecap="round"
                                                  stroke-linejoin="round" stroke-width="1.5"
                                                  d="M12 6.253v13m0-13C10.832 5.477 9.246 5
                                                     7.5 5S4.168 5.477 3 6.253v13C4.168
                                                     18.477 5.754 18 7.5 18s3.332.477 4.5
                                                     1.253m0-13C13.168 5.477 14.754 5 16.5
                                                     5c1.747 0 3.332.477 4.5 1.253v13C19.832
                                                     18.477 18.247 18 16.5 18c-1.746
                                                     0-3.332.477-4.5 1.253"/>
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="text-xs font-semibold text-slate-500
                                                  dark:text-slate-400">
                                            Belum ada data siswa
                                        </p>
                                        <p class="text-[10px] mt-0.5 text-slate-400">
                                            Gunakan tombol
                                            <strong>+ Tambah User</strong>
                                            untuk menambahkan akun siswa.
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

</div>{{-- /space-y-3 --}}

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {

    const filterBtns = document.querySelectorAll('.kelas-filter-btn');
    const rows       = document.querySelectorAll('#siswaBody tr[data-kelas-id]');
    const searchInput = document.getElementById('searchInput');

    // ── Terapkan filter kelas + search sekaligus ────────────────
    function applyFilters() {
        const activeBtn    = document.querySelector('.kelas-filter-btn.bg-indigo-600');
        const selectedKelas = activeBtn ? activeBtn.dataset.kelasId : 'all';
        const query         = searchInput ? searchInput.value.toLowerCase().trim() : '';

        rows.forEach(row => {
            const kelasMatch  = (selectedKelas === 'all' || row.dataset.kelasId === selectedKelas);
            const searchMatch = !query || row.textContent.toLowerCase().includes(query);
            row.style.display = (kelasMatch && searchMatch) ? '' : 'none';
        });
    }

    // ── Klik tombol filter kelas ─────────────────────────────────
    filterBtns.forEach(btn => {
        btn.addEventListener('click', function () {
            // Reset semua tombol ke style non-aktif
            filterBtns.forEach(b => {
                b.classList.remove(
                    'bg-indigo-600', 'text-white', 'shadow-sm',
                    'hover:bg-indigo-700'
                );
                b.classList.add(
                    'bg-white', 'dark:bg-slate-700',
                    'border', 'border-slate-200', 'dark:border-slate-600',
                    'text-slate-700', 'dark:text-slate-300',
                    'hover:bg-slate-50', 'dark:hover:bg-slate-600'
                );
                // Update warna badge di dalam tombol
                const badge = b.querySelector('span');
                if (badge) {
                    badge.classList.remove('bg-white/25');
                    badge.classList.add('bg-slate-100', 'dark:bg-slate-600');
                }
            });

            // Set tombol ini sebagai aktif
            this.classList.add(
                'bg-indigo-600', 'text-white', 'shadow-sm', 'hover:bg-indigo-700'
            );
            this.classList.remove(
                'bg-white', 'dark:bg-slate-700',
                'border', 'border-slate-200', 'dark:border-slate-600',
                'text-slate-700', 'dark:text-slate-300',
                'hover:bg-slate-50', 'dark:hover:bg-slate-600'
            );
            const activeBadge = this.querySelector('span');
            if (activeBadge) {
                activeBadge.classList.add('bg-white/25');
                activeBadge.classList.remove('bg-slate-100', 'dark:bg-slate-600');
            }

            applyFilters();
        });
    });

    // ── Sinkronisasi dengan search box ──────────────────────────
    if (searchInput) {
        searchInput.addEventListener('input', applyFilters);
    }

    // ── Default: tampilkan semua ─────────────────────────────────
    applyFilters();
});
</script>
@endpush