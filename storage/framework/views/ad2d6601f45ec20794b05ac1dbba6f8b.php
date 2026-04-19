<?php $__env->startSection('title', 'Kelola Kelas'); ?>

<?php $__env->startSection('content'); ?>

<div class="space-y-4">

    
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
        <div>
            <h2 class="text-sm font-bold text-slate-800 dark:text-slate-100">Kelola Kelas</h2>
            <p class="text-xs text-slate-400 dark:text-slate-500 mt-0.5">
                Manajemen data kelas dan wali kelas — tersinkron dengan Data Akademik.
            </p>
        </div>
        <div class="flex items-center gap-2">
            <a href="<?php echo e(route('admin.academic-planner.index')); ?>"
               class="inline-flex items-center gap-1.5 px-3 py-2 rounded-xl border border-slate-200
                      dark:border-slate-600 text-slate-600 dark:text-slate-400 text-xs font-medium
                      hover:bg-slate-50 dark:hover:bg-slate-700 transition">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0
                             012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0
                             01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                Data Akademik
            </a>
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
    </div>

    
    <?php if(session('success')): ?>
        <div class="flex items-center gap-2 px-3.5 py-2.5 rounded-xl bg-emerald-50
                    dark:bg-emerald-900/20 border border-emerald-100 dark:border-emerald-800">
            <svg class="w-3.5 h-3.5 text-emerald-500 shrink-0" fill="none"
                 stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <p class="text-[11px] text-emerald-700 dark:text-emerald-300 font-medium">
                <?php echo e(session('success')); ?>

            </p>
        </div>
    <?php endif; ?>
    <?php if(session('error')): ?>
        <div class="flex items-center gap-2 px-3.5 py-2.5 rounded-xl bg-red-50
                    dark:bg-red-900/20 border border-red-100 dark:border-red-800">
            <svg class="w-3.5 h-3.5 text-red-500 shrink-0" fill="none"
                 stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0
                         001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/>
            </svg>
            <p class="text-[11px] text-red-700 dark:text-red-300 font-medium">
                <?php echo e(session('error')); ?>

            </p>
        </div>
    <?php endif; ?>

    
    <div class="flex items-start gap-2.5 px-3.5 py-2.5 rounded-xl bg-indigo-50
                dark:bg-indigo-900/20 border border-indigo-100 dark:border-indigo-800">
        <svg class="w-3.5 h-3.5 text-indigo-500 dark:text-indigo-400 mt-0.5 shrink-0"
             fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
        <p class="text-[10px] text-indigo-700 dark:text-indigo-300 leading-relaxed">
            Data kelas di halaman ini tersinkron langsung dengan
            <a href="<?php echo e(route('admin.academic-planner.index')); ?>"
               class="font-semibold underline underline-offset-2">Data Akademik</a>
            dan
            <a href="<?php echo e(route('admin.users.index')); ?>"
               class="font-semibold underline underline-offset-2">Kelola User</a>.
            Kelas yang ditambah, diedit, atau dihapus di sini akan otomatis tercermin di sana.
        </p>
    </div>

    
    <div class="flex flex-col sm:flex-row gap-2">
        <div class="relative flex-1 max-w-xs">
            <svg class="absolute left-2.5 top-1/2 -translate-y-1/2 w-3.5 h-3.5 text-slate-400"
                 fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0"/>
            </svg>
            <input type="text" id="searchInput"
                   placeholder="Cari kelas, wali kelas..."
                   class="w-full pl-8 pr-4 py-2 rounded-xl border border-slate-200
                          dark:border-slate-600 bg-white dark:bg-slate-800 text-xs
                          focus:outline-none focus:ring-2 focus:ring-indigo-300
                          placeholder:text-slate-400 transition">
        </div>
        <select id="filterSemester"
                class="rounded-xl border border-slate-200 dark:border-slate-600 px-3 py-2 text-xs
                       bg-white dark:bg-slate-800 text-slate-600 dark:text-slate-300
                       focus:outline-none focus:ring-2 focus:ring-indigo-300">
            <option value="">Semua Semester</option>
            <option value="1">Semester 1</option>
            <option value="2">Semester 2</option>
        </select>
        <select id="filterTingkat"
                class="rounded-xl border border-slate-200 dark:border-slate-600 px-3 py-2 text-xs
                       bg-white dark:bg-slate-800 text-slate-600 dark:text-slate-300
                       focus:outline-none focus:ring-2 focus:ring-indigo-300">
            <option value="">Semua Tingkat</option>
            <option value="7">Kelas 7</option>
            <option value="8">Kelas 8</option>
            <option value="9">Kelas 9</option>
        </select>
    </div>

    
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
                                   dark:text-slate-400 uppercase tracking-wide min-w-[100px]">
                            Nama Kelas
                        </th>
                        <th class="px-3 py-2.5 text-[10px] font-semibold text-slate-500
                                   dark:text-slate-400 uppercase tracking-wide">Tingkat</th>
                        <th class="px-3 py-2.5 text-[10px] font-semibold text-slate-500
                                   dark:text-slate-400 uppercase tracking-wide">Semester</th>
                        <th class="px-3 py-2.5 text-[10px] font-semibold text-slate-500
                                   dark:text-slate-400 uppercase tracking-wide min-w-[100px]">
                            Tahun Ajaran
                        </th>
                        <th class="px-3 py-2.5 text-[10px] font-semibold text-slate-500
                                   dark:text-slate-400 uppercase tracking-wide min-w-[160px]">
                            Wali Kelas
                        </th>
                        <th class="px-3 py-2.5 text-[10px] font-semibold text-slate-500
                                   dark:text-slate-400 uppercase tracking-wide">Ruang</th>
                        <th class="px-3 py-2.5 text-[10px] font-semibold text-slate-500
                                   dark:text-slate-400 uppercase tracking-wide text-center">
                            Jadwal
                        </th>
                        <th class="px-3 py-2.5 text-[10px] font-semibold text-slate-500
                                   dark:text-slate-400 uppercase tracking-wide text-center">
                            Status
                        </th>
                        <th class="px-3 py-2.5 text-[10px] font-semibold text-slate-500
                                   dark:text-slate-400 uppercase tracking-wide text-center min-w-[90px]">
                            Aksi
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-700/50">

                    <?php $__empty_1 = true; $__currentLoopData = $kelas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $k): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr class="hover:bg-slate-50 dark:hover:bg-slate-700/20
                                   transition-colors searchable-row"
                            data-semester="<?php echo e($k->semester); ?>"
                            data-tingkat="<?php echo e($k->grade); ?>">

                            <td class="px-3 py-2.5 text-[10px] text-slate-400"><?php echo e($i + 1); ?></td>

                            
                            <td class="px-3 py-2.5">
                                <a href="<?php echo e(route('admin.academic-planner.study-group.show', $k->id)); ?>"
                                   class="text-xs font-semibold text-indigo-600
                                          dark:text-indigo-400 hover:underline underline-offset-2">
                                    <?php echo e($k->name); ?>

                                </a>
                                <?php if($k->section): ?>
                                    <span class="text-[10px] text-slate-400 ml-1">
                                        (<?php echo e($k->section); ?>)
                                    </span>
                                <?php endif; ?>
                            </td>

                            
                            <td class="px-3 py-2.5">
                                <span class="inline-flex px-1.5 py-0.5 rounded-lg text-[10px]
                                             font-semibold bg-indigo-50 text-indigo-700
                                             dark:bg-indigo-900/30 dark:text-indigo-300
                                             border border-indigo-100 dark:border-indigo-800">
                                    <?php echo e($k->grade ? 'Kelas '.$k->grade : '—'); ?>

                                </span>
                            </td>

                            
                            <td class="px-3 py-2.5">
                                <?php if($k->semester): ?>
                                    <span class="inline-flex px-1.5 py-0.5 rounded-lg text-[10px]
                                                 font-semibold
                                                 <?php echo e($k->semester == 1
                                                    ? 'bg-emerald-50 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-300 border border-emerald-100 dark:border-emerald-800'
                                                    : 'bg-violet-50 text-violet-700 dark:bg-violet-900/30 dark:text-violet-300 border border-violet-100 dark:border-violet-800'); ?>">
                                        Sem <?php echo e($k->semester); ?>

                                    </span>
                                <?php else: ?>
                                    <span class="text-[10px] text-slate-300 dark:text-slate-600">—</span>
                                <?php endif; ?>
                            </td>

                            
                            <td class="px-3 py-2.5 text-[10px] text-slate-600 dark:text-slate-400">
                                <?php echo e($k->academic_year ?? '—'); ?>

                            </td>

                            
                            <td class="px-3 py-2.5">
                                <?php if($k->homeroomTeacher): ?>
                                    <div class="flex items-center gap-2">
                                        <div class="w-6 h-6 rounded-lg bg-indigo-100
                                                    dark:bg-indigo-900/40 flex items-center
                                                    justify-center text-indigo-600
                                                    dark:text-indigo-400 text-[10px] font-bold shrink-0">
                                            <?php echo e(strtoupper(substr($k->homeroomTeacher->name, 0, 1))); ?>

                                        </div>
                                        <span class="text-xs text-slate-700 dark:text-slate-300
                                                     truncate max-w-[120px]">
                                            <?php echo e($k->homeroomTeacher->name); ?>

                                        </span>
                                    </div>
                                <?php else: ?>
                                    <span class="text-[10px] text-slate-300 dark:text-slate-600">
                                        — Belum ditentukan
                                    </span>
                                <?php endif; ?>
                            </td>

                            
                            <td class="px-3 py-2.5 text-[10px] text-slate-600 dark:text-slate-400">
                                <?php echo e($k->room ?? '—'); ?>

                            </td>

                            
                            <td class="px-3 py-2.5 text-center">
                                <a href="<?php echo e(route('admin.academic-planner.study-group.show', $k->id)); ?>"
                                   class="inline-flex items-center justify-center
                                          min-w-[1.6rem] h-5 px-1.5 rounded-full text-[10px] font-semibold
                                          bg-indigo-50 text-indigo-600 dark:bg-indigo-900/30
                                          dark:text-indigo-300 hover:bg-indigo-100 transition">
                                    <?php echo e($k->timetables_count ?? ($k->timetables ? $k->timetables->count() : 0)); ?>

                                </a>
                            </td>

                            
                            <td class="px-3 py-2.5 text-center">
                                <?php if($k->is_active): ?>
                                    <span class="inline-flex items-center gap-0.5 px-1.5 py-0.5
                                                 rounded-full text-[10px] font-semibold
                                                 bg-emerald-50 text-emerald-700 dark:bg-emerald-900/30
                                                 dark:text-emerald-300">
                                        <span class="w-1 h-1 rounded-full bg-emerald-500 inline-block"></span>
                                        Aktif
                                    </span>
                                <?php else: ?>
                                    <span class="inline-flex items-center gap-0.5 px-1.5 py-0.5
                                                 rounded-full text-[10px] font-semibold
                                                 bg-slate-100 text-slate-500 dark:bg-slate-700
                                                 dark:text-slate-400">
                                        <span class="w-1 h-1 rounded-full bg-slate-400 inline-block"></span>
                                        Nonaktif
                                    </span>
                                <?php endif; ?>
                            </td>

                            
                            <td class="px-3 py-2.5">
                                <div class="flex items-center justify-center gap-0.5">
                                    <a href="<?php echo e(route('admin.academic-planner.study-group.show', $k->id)); ?>"
                                       title="Lihat Jadwal"
                                       class="p-1.5 rounded-lg text-slate-400
                                              hover:text-blue-600 hover:bg-blue-50
                                              dark:hover:text-blue-400 dark:hover:bg-blue-900/30
                                              transition">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor"
                                             viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                  stroke-width="2"
                                                  d="M15 12a3 3 0 11-6 0 3 3 0 016 0zM2.458
                                                     12C3.732 7.943 7.523 5 12 5c4.478 0 8.268
                                                     2.943 9.542 7-1.274 4.057-5.064 7-9.542
                                                     7-4.477 0-8.268-2.943-9.542-7z"/>
                                        </svg>
                                    </a>
                                    <button onclick="openEditKelasModal(<?php echo e($k->id); ?>)"
                                            title="Edit Kelas"
                                            class="p-1.5 rounded-lg text-slate-400
                                                   hover:text-amber-600 hover:bg-amber-50
                                                   dark:hover:text-amber-400 dark:hover:bg-amber-900/30
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
                                    <button onclick="openDeleteModal(<?php echo e($k->id); ?>, '<?php echo e(addslashes($k->name)); ?>')"
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
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="10" class="px-4 py-14 text-center">
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
                    <?php endif; ?>

                </tbody>
            </table>
        </div>
    </div>

</div>


<script id="kelasData" type="application/json">
<?php echo json_encode($kelas->map(fn($k) => [
    'id'                  => $k->id,
    'name'                => $k->name,
    'grade'               => $k->grade,
    'section'             => $k->section,
    'academic_year'       => $k->academic_year,
    'semester'            => $k->semester,
    'homeroom_teacher_id' => $k->homeroom_teacher_id,
    'room'                => $k->room,
    'is_active'           => $k->is_active,
    'capacity'            => $k->capacity,
])->keyBy('id')); ?>

</script>

<?php echo $__env->make('admin.kelas._modal_tambah', ['gurus' => $gurus], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<?php echo $__env->make('admin.kelas._modal_edit',   ['gurus' => $gurus], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<?php echo $__env->make('admin.kelas._modal_hapus', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

<?php $__env->startPush('styles'); ?>
<style>
    .animate-modal {
        animation: modalPop .22s cubic-bezier(.34,1.56,.64,1);
    }
    @keyframes modalPop {
        from { opacity:0; transform:scale(.92) translateY(10px); }
        to   { opacity:1; transform:scale(1)   translateY(0); }
    }
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
const KELAS_DATA = JSON.parse(document.getElementById('kelasData').textContent);

/* ── Modal helpers ── */
function openModal(id) {
    const el = document.getElementById(id);
    if (!el) return;
    el.classList.remove('hidden');
    el.classList.add('flex');
    document.body.style.overflow = 'hidden';
}
function closeModal(id) {
    const el = document.getElementById(id);
    if (!el) return;
    el.classList.add('hidden');
    el.classList.remove('flex');
    document.body.style.overflow = '';
}
document.addEventListener('keydown', e => {
    if (e.key === 'Escape') {
        ['modalTambahKelas','modalEditKelas','modalHapusKelas'].forEach(closeModal);
    }
});

/* ── Buka modal edit — isi semua field termasuk semester, tahun ajaran, ruang ── */
function openEditKelasModal(kelasId) {
    const d = KELAS_DATA[kelasId];
    if (!d) return;

    // Field utama
    document.getElementById('editKelasName').value        = d.name          ?? '';
    document.getElementById('editKelasGrade').value       = d.grade         ?? '';
    document.getElementById('editKelasSection').value     = d.section       ?? '';
    document.getElementById('editKelasAcademicYear').value= d.academic_year ?? '';
    document.getElementById('editKelasSemester').value    = d.semester      ?? '';
    document.getElementById('editKelasRoom').value        = d.room          ?? '';
    document.getElementById('editKelasCapacity').value    = d.capacity      ?? 30;
    document.getElementById('editKelasIsActive').checked  = !!d.is_active;

    // Wali kelas
    const waliEl = document.getElementById('editKelasHomeroomTeacher');
    if (waliEl) waliEl.value = d.homeroom_teacher_id ?? '';

    // Action form
    document.getElementById('formEditKelas').action =
        '<?php echo e(url("admin/kelas")); ?>/' + kelasId;

    openModal('modalEditKelas');
}

/* ── Buka modal hapus ── */
function openDeleteModal(kelasId, kelasNama) {
    document.getElementById('deleteKelasName').textContent = kelasNama;
    document.getElementById('formHapusKelas').action =
        '<?php echo e(url("admin/kelas")); ?>/' + kelasId;
    openModal('modalHapusKelas');
}

/* ── Search & filter ── */
function filterRows() {
    const q   = (document.getElementById('searchInput')?.value ?? '').toLowerCase();
    const sem = document.getElementById('filterSemester')?.value ?? '';
    const tkt = document.getElementById('filterTingkat')?.value  ?? '';

    document.querySelectorAll('.searchable-row').forEach(row => {
        const matchText = row.textContent.toLowerCase().includes(q);
        const matchSem  = !sem || row.dataset.semester === sem;
        const matchTkt  = !tkt || row.dataset.tingkat  === tkt;
        row.style.display = (matchText && matchSem && matchTkt) ? '' : 'none';
    });
}

document.getElementById('searchInput')?.addEventListener('input', filterRows);
document.getElementById('filterSemester')?.addEventListener('change', filterRows);
document.getElementById('filterTingkat')?.addEventListener('change', filterRows);

/* ── Buka modal tambah jika ada validation error ── */
<?php if($errors->any()): ?>
    document.addEventListener('DOMContentLoaded', () => openModal('modalTambahKelas'));
<?php endif; ?>
</script>
<?php $__env->stopPush(); ?>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\PA 3\smpn-kutime\resources\views/admin/kelas/index.blade.php ENDPATH**/ ?>