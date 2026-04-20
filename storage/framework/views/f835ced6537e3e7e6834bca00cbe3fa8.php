

<?php $__env->startSection('title', 'Jadwal Mengajar'); ?>

<?php $__env->startSection('content'); ?>
<div class="space-y-4">

    
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
        <div>
            <h2 class="text-sm font-bold text-slate-800 dark:text-slate-100">Jadwal Mengajar</h2>
            <p class="text-xs text-slate-400 dark:text-slate-500 mt-0.5">
                Kelola jadwal kelas yang Anda ampu.
            </p>
        </div>
        <button onclick="openModal('modalTambahJadwal')"
            class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl bg-indigo-600
                       text-white text-xs font-semibold hover:bg-indigo-700
                       active:scale-95 transition shadow-sm w-fit">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M12 4v16m8-8H4" />
            </svg>
            Tambah Jadwal
        </button>
    </div>

    
    <?php if(session('success')): ?>
    <div class="flex items-center gap-2 px-3.5 py-2.5 rounded-xl bg-emerald-50
                    dark:bg-emerald-900/20 border border-emerald-100 dark:border-emerald-800">
        <svg class="w-3.5 h-3.5 text-emerald-500 shrink-0" fill="none" stroke="currentColor"
            viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>
        <p class="text-[11px] text-emerald-700 dark:text-emerald-300 font-medium">
            <?php echo e(session('success')); ?>

        </p>
    </div>
    <?php endif; ?>
    <?php if(session('error')): ?>
    <div class="flex items-center gap-2 px-3.5 py-2.5 rounded-xl bg-red-50
                    dark:bg-red-900/20 border border-red-100 dark:border-red-800">
        <svg class="w-3.5 h-3.5 text-red-500 shrink-0" fill="none" stroke="currentColor"
            viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71
                         3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z" />
        </svg>
        <p class="text-[11px] text-red-700 dark:text-red-300 font-medium">
            <?php echo e(session('error')); ?>

        </p>
    </div>
    <?php endif; ?>

    
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
        <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200
                    dark:border-slate-700 shadow-sm px-4 py-3">
            <p class="text-lg font-black text-indigo-600 dark:text-indigo-400 leading-none">
                <?php echo e($totalJadwal); ?>

            </p>
            <p class="text-[10px] text-slate-400 mt-0.5">Total Jadwal</p>
        </div>
        <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200
                    dark:border-slate-700 shadow-sm px-4 py-3">
            <p class="text-lg font-black text-emerald-600 dark:text-emerald-400 leading-none">
                <?php echo e($totalKelas); ?>

            </p>
            <p class="text-[10px] text-slate-400 mt-0.5">Kelas Diampu</p>
        </div>
        <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200
                    dark:border-slate-700 shadow-sm px-4 py-3">
            <p class="text-lg font-black text-amber-600 dark:text-amber-400 leading-none">
                <?php echo e($totalMapel); ?>

            </p>
            <p class="text-[10px] text-slate-400 mt-0.5">Mata Pelajaran</p>
        </div>
        <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200
                    dark:border-slate-700 shadow-sm px-4 py-3">
            <p class="text-lg font-black text-sky-600 dark:text-sky-400 leading-none">
                <?php echo e($totalJamPerMinggu); ?>

            </p>
            <p class="text-[10px] text-slate-400 mt-0.5">Jam / Minggu</p>
        </div>
    </div>

    
    <?php
    $hariList = ['Senin','Selasa','Rabu','Kamis','Jumat','Sabtu'];
    ?>

    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-4">
        <?php $__currentLoopData = $hariList; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $hari): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <?php $jadwalHari = $jadwalByDay[$hari] ?? collect(); ?>
        <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200
                        dark:border-slate-700 shadow-sm flex flex-col overflow-hidden">

            
            <div class="flex items-center justify-between px-3.5 py-3 border-b
                            border-slate-100 dark:border-slate-700 bg-slate-50/70
                            dark:bg-slate-900/30">
                <div class="flex items-center gap-2">
                    <div class="w-1.5 h-5 rounded-full
                                    <?php echo e($jadwalHari->count() > 0
                                       ? 'bg-indigo-500'
                                       : 'bg-slate-200 dark:bg-slate-700'); ?>">
                    </div>
                    <span class="text-xs font-bold text-slate-700 dark:text-slate-200">
                        <?php echo e($hari); ?>

                    </span>
                </div>
                <span class="text-[10px] font-semibold px-2 py-0.5 rounded-full
                                 <?php echo e($jadwalHari->count() > 0
                                    ? 'bg-indigo-100 text-indigo-700 dark:bg-indigo-900/40 dark:text-indigo-300'
                                    : 'bg-slate-100 text-slate-400 dark:bg-slate-700 dark:text-slate-500'); ?>">
                    <?php echo e($jadwalHari->count()); ?> sesi
                </span>
            </div>

            
            <div class="flex-1 divide-y divide-slate-50 dark:divide-slate-700/30">
                <?php $__empty_1 = true; $__currentLoopData = $jadwalHari->sortBy('start_time'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tt): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <div class="flex items-start gap-2.5 px-3.5 py-3
                                    hover:bg-slate-50 dark:hover:bg-slate-700/20 transition group">

                    
                    <div class="flex flex-col items-center gap-1 shrink-0 pt-0.5">
                        <div class="w-1 h-12 rounded-full"
                            style="background: <?php echo e($tt->studySubject->color ?? '#6366f1'); ?>"></div>
                    </div>

                    
                    <div class="flex-1 min-w-0">
                        <div class="flex items-start justify-between gap-1">
                            <p class="text-xs font-semibold text-slate-800 dark:text-slate-100
                                              leading-tight truncate">
                                <?php echo e($tt->studySubject->name); ?>

                            </p>
                            
                            <span class="text-[9px] font-semibold px-1.5 py-0.5 rounded-md shrink-0
                                                 <?php echo e($tt->session_type === 'praktikum'
                                                    ? 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-400'
                                                    : 'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400'); ?>">
                                <?php echo e(ucfirst($tt->session_type ?? 'teori')); ?>

                            </span>
                        </div>

                        <div class="flex items-center gap-1.5 mt-1">
                            <svg class="w-3 h-3 text-slate-400 shrink-0" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <span class="text-[10px] text-slate-500 dark:text-slate-400">
                                <?php echo e(substr($tt->start_time,0,5)); ?> – <?php echo e(substr($tt->end_time,0,5)); ?>

                            </span>
                        </div>

                        <div class="flex items-center gap-1.5 mt-0.5 flex-wrap">
                            
                            <span class="inline-flex items-center gap-0.5 text-[10px] font-medium
                                                 text-indigo-700 dark:text-indigo-300">
                                <svg class="w-2.5 h-2.5" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2
                                                     0h-5m-9 0H3m2 0h5" />
                                </svg>
                                <?php echo e($tt->studyGroup->name); ?>

                            </span>
                            <?php if($tt->room): ?>
                            <span class="text-[10px] text-slate-400">
                                · <?php echo e($tt->room); ?>

                            </span>
                            <?php endif; ?>
                        </div>
                    </div>

                    
                    <div class="flex items-center gap-0.5 shrink-0 opacity-0
                                        group-hover:opacity-100 transition-opacity">
                        <button onclick="openEditJadwal(<?php echo e($tt->id); ?>)"
                            title="Edit"
                            class="p-1 rounded-lg text-slate-400 hover:text-amber-600
                                               hover:bg-amber-50 dark:hover:bg-amber-900/30 transition">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0
                                                 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828
                                                 15H9v-2.828l8.586-8.586z" />
                            </svg>
                        </button>
                        <button onclick="openDeleteJadwal(<?php echo e($tt->id); ?>, '<?php echo e(addslashes($tt->studySubject->name)); ?>')"
                            title="Hapus"
                            class="p-1 rounded-lg text-slate-400 hover:text-red-600
                                               hover:bg-red-50 dark:hover:bg-red-900/30 transition">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0
                                                 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0
                                                 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                        </button>
                    </div>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <div class="flex flex-col items-center justify-center py-6 text-slate-300
                                    dark:text-slate-600">
                    <svg class="w-8 h-8 mb-1 opacity-50" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1"
                            d="M8 7V3m8 4V3M5 11h14M5 19h14M5 5h2m10 0h2" />
                    </svg>
                    <p class="text-[10px]">Tidak ada jadwal</p>
                </div>
                <?php endif; ?>
            </div>

            
            <div class="px-3.5 py-2 border-t border-slate-100 dark:border-slate-700
                            bg-slate-50/50 dark:bg-slate-900/20">
                <button onclick="openTambahHari('<?php echo e($hari); ?>')"
                    class="w-full text-[10px] font-medium text-indigo-500 dark:text-indigo-400
                                   hover:text-indigo-700 flex items-center justify-center gap-1 py-0.5
                                   hover:underline transition">
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 4v16m8-8H4" />
                    </svg>
                    Tambah Jadwal <?php echo e($hari); ?>

                </button>
            </div>
        </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>

</div>


<script id="jadwalData" type="application/json">
    {
        !!json_encode($allTimetables - > map(fn($t) => [
            'id' => $t - > id,
            'study_subject_id' => $t - > study_subject_id,
            'study_group_id' => $t - > study_group_id,
            'day_of_week' => $t - > day_of_week,
            'start_time' => substr($t - > start_time, 0, 5),
            'end_time' => substr($t - > end_time, 0, 5),
            'room' => $t - > room,
            'session_type' => $t - > session_type,
            'academic_year' => $t - > academic_year,
            'semester' => $t - > semester,
            'notes' => $t - > notes,
        ]) - > keyBy('id')) !!
    }
</script>


<div id="modalTambahJadwal"
    class="fixed inset-0 z-50 hidden items-center justify-center p-4"
    role="dialog" aria-modal="true">
    <div class="absolute inset-0 bg-slate-900/50 backdrop-blur-sm"
        onclick="closeModal('modalTambahJadwal')"></div>

    <div class="relative bg-white dark:bg-slate-800 rounded-2xl shadow-2xl w-full max-w-md
                max-h-[92vh] flex flex-col animate-modal">

        <div class="flex items-center justify-between px-5 py-4 border-b border-slate-100
                    dark:border-slate-700 shrink-0">
            <div class="flex items-center gap-2.5">
                <div class="w-7 h-7 rounded-lg bg-indigo-100 dark:bg-indigo-900/40
                            flex items-center justify-center shrink-0">
                    <svg class="w-4 h-4 text-indigo-600 dark:text-indigo-400" fill="none"
                        stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 4v16m8-8H4" />
                    </svg>
                </div>
                <div>
                    <h2 class="text-sm font-bold text-slate-800 dark:text-slate-100">
                        Tambah Jadwal Mengajar
                    </h2>
                    <p class="text-[10px] text-slate-400 mt-0.5">Pilih kelas dan mata pelajaran</p>
                </div>
            </div>
            <button onclick="closeModal('modalTambahJadwal')"
                class="p-1.5 rounded-lg text-slate-400 hover:text-slate-600
                           hover:bg-slate-100 dark:hover:bg-slate-700 transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <div class="overflow-y-auto px-5 py-4 flex-1">
            <form id="formTambahJadwal"
                action="<?php echo e(route('guru.jadwal-mengajar.store')); ?>"
                method="POST"
                class="space-y-3">
                <?php echo csrf_field(); ?>

                
                <div>
                    <label class="block text-[10px] font-semibold text-slate-500 dark:text-slate-400
                                  uppercase tracking-wide mb-1">
                        Mata Pelajaran <span class="text-red-500">*</span>
                    </label>
                    <select name="study_subject_id" required
                        class="w-full rounded-xl border border-slate-200 dark:border-slate-600
                                   px-3 py-2 text-xs focus:outline-none focus:ring-2
                                   focus:ring-indigo-300 bg-white dark:bg-slate-700
                                   dark:text-slate-200 transition">
                        <option value="">— Pilih Mata Pelajaran —</option>
                        <?php $__currentLoopData = $studySubjects; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $subj): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($subj->id); ?>">
                            <?php echo e($subj->name); ?> (<?php echo e($subj->code); ?>)
                        </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                    <?php if($studySubjects->isEmpty()): ?>
                    <p class="text-[10px] text-amber-600 mt-1">
                        Mata pelajaran belum tersedia. Hubungi admin.
                    </p>
                    <?php endif; ?>
                </div>

                
                <div>
                    <label class="block text-[10px] font-semibold text-slate-500 dark:text-slate-400
                                  uppercase tracking-wide mb-1">
                        Kelas <span class="text-red-500">*</span>
                    </label>
                    <select name="study_group_id" required
                        class="w-full rounded-xl border border-slate-200 dark:border-slate-600
                                   px-3 py-2 text-xs focus:outline-none focus:ring-2
                                   focus:ring-indigo-300 bg-white dark:bg-slate-700
                                   dark:text-slate-200 transition">
                        <option value="">— Pilih Kelas —</option>
                        <?php $__currentLoopData = $studyGroups; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $group): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($group->id); ?>">
                            <?php echo e($group->name); ?>

                            <?php if($group->academic_year): ?> (<?php echo e($group->academic_year); ?>) <?php endif; ?>
                        </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>

                
                <div>
                    <label class="block text-[10px] font-semibold text-slate-500 dark:text-slate-400
                                  uppercase tracking-wide mb-1">
                        Hari <span class="text-red-500">*</span>
                    </label>
                    <select name="day_of_week" id="tambahJadwalHari" required
                        class="w-full rounded-xl border border-slate-200 dark:border-slate-600
                                   px-3 py-2 text-xs focus:outline-none focus:ring-2
                                   focus:ring-indigo-300 bg-white dark:bg-slate-700
                                   dark:text-slate-200 transition">
                        <option value="">— Pilih Hari —</option>
                        <?php $__currentLoopData = ['Senin','Selasa','Rabu','Kamis','Jumat','Sabtu']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $h): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($h); ?>"><?php echo e($h); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>

                
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-[10px] font-semibold text-slate-500 dark:text-slate-400
                                      uppercase tracking-wide mb-1">
                            Jam Mulai <span class="text-red-500">*</span>
                        </label>
                        <input type="time" name="start_time" value="07:00" required
                            class="w-full rounded-xl border border-slate-200 dark:border-slate-600
                                      px-3 py-2 text-xs focus:outline-none focus:ring-2
                                      focus:ring-indigo-300 bg-white dark:bg-slate-700
                                      dark:text-slate-200 transition">
                    </div>
                    <div>
                        <label class="block text-[10px] font-semibold text-slate-500 dark:text-slate-400
                                      uppercase tracking-wide mb-1">
                            Jam Selesai <span class="text-red-500">*</span>
                        </label>
                        <input type="time" name="end_time" value="08:30" required
                            class="w-full rounded-xl border border-slate-200 dark:border-slate-600
                                      px-3 py-2 text-xs focus:outline-none focus:ring-2
                                      focus:ring-indigo-300 bg-white dark:bg-slate-700
                                      dark:text-slate-200 transition">
                    </div>
                </div>

                
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-[10px] font-semibold text-slate-500 dark:text-slate-400
                                      uppercase tracking-wide mb-1">
                            Ruang
                        </label>
                        <input type="text" name="room" placeholder="Lab / Kelas"
                            maxlength="50"
                            class="w-full rounded-xl border border-slate-200 dark:border-slate-600
                                      px-3 py-2 text-xs focus:outline-none focus:ring-2
                                      focus:ring-indigo-300 bg-white dark:bg-slate-700
                                      dark:text-slate-200 transition">
                    </div>
                    <div>
                        <label class="block text-[10px] font-semibold text-slate-500 dark:text-slate-400
                                      uppercase tracking-wide mb-1">
                            Sesi <span class="text-red-500">*</span>
                        </label>
                        <select name="session_type" required
                            class="w-full rounded-xl border border-slate-200 dark:border-slate-600
                                       px-3 py-2 text-xs focus:outline-none focus:ring-2
                                       focus:ring-indigo-300 bg-white dark:bg-slate-700
                                       dark:text-slate-200 transition">
                            <option value="teori">Teori</option>
                            <option value="praktikum">Praktikum</option>
                        </select>
                    </div>
                </div>

                
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-[10px] font-semibold text-slate-500 dark:text-slate-400
                                      uppercase tracking-wide mb-1">
                            Tahun Ajaran <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="academic_year"
                            value="<?php echo e(date('Y').'/'.((int)date('Y')+1)); ?>"
                            maxlength="9" required
                            class="w-full rounded-xl border border-slate-200 dark:border-slate-600
                                      px-3 py-2 text-xs focus:outline-none focus:ring-2
                                      focus:ring-indigo-300 bg-white dark:bg-slate-700
                                      dark:text-slate-200 transition">
                    </div>
                    <div>
                        <label class="block text-[10px] font-semibold text-slate-500 dark:text-slate-400
                                      uppercase tracking-wide mb-1">
                            Semester <span class="text-red-500">*</span>
                        </label>
                        <select name="semester" required
                            class="w-full rounded-xl border border-slate-200 dark:border-slate-600
                                       px-3 py-2 text-xs focus:outline-none focus:ring-2
                                       focus:ring-indigo-300 bg-white dark:bg-slate-700
                                       dark:text-slate-200 transition">
                            <option value="1">Semester 1</option>
                            <option value="2">Semester 2</option>
                        </select>
                    </div>
                </div>

                
                <div>
                    <label class="block text-[10px] font-semibold text-slate-500 dark:text-slate-400
                                  uppercase tracking-wide mb-1">Catatan</label>
                    <textarea name="notes" rows="2" maxlength="500"
                        placeholder="Catatan tambahan (opsional)"
                        class="w-full rounded-xl border border-slate-200 dark:border-slate-600
                                     px-3 py-2 text-xs focus:outline-none focus:ring-2
                                     focus:ring-indigo-300 bg-white dark:bg-slate-700
                                     dark:text-slate-200 transition resize-none"></textarea>
                </div>

            </form>
        </div>

        <div class="flex gap-2 px-5 py-3.5 border-t border-slate-100 dark:border-slate-700
                    bg-slate-50/50 dark:bg-slate-900/20 rounded-b-2xl shrink-0">
            <button type="button" onclick="closeModal('modalTambahJadwal')"
                class="flex-1 px-4 py-2 rounded-xl border border-slate-200 dark:border-slate-600
                           text-slate-600 dark:text-slate-400 text-xs font-medium
                           hover:bg-white dark:hover:bg-slate-700 transition">
                Batal
            </button>
            <button type="submit" form="formTambahJadwal"
                class="flex-1 px-4 py-2 rounded-xl bg-indigo-600 text-white text-xs font-semibold
                           hover:bg-indigo-700 active:scale-95 transition">
                Simpan Jadwal
            </button>
        </div>

    </div>
</div>


<div id="modalEditJadwal"
    class="fixed inset-0 z-50 hidden items-center justify-center p-4"
    role="dialog" aria-modal="true">
    <div class="absolute inset-0 bg-slate-900/50 backdrop-blur-sm"
        onclick="closeModal('modalEditJadwal')"></div>

    <div class="relative bg-white dark:bg-slate-800 rounded-2xl shadow-2xl w-full max-w-md
                max-h-[92vh] flex flex-col animate-modal">

        <div class="flex items-center justify-between px-5 py-4 border-b border-slate-100
                    dark:border-slate-700 shrink-0">
            <div class="flex items-center gap-2.5">
                <div class="w-7 h-7 rounded-lg bg-amber-100 dark:bg-amber-900/30
                            flex items-center justify-center shrink-0">
                    <svg class="w-4 h-4 text-amber-600 dark:text-amber-400" fill="none"
                        stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5
                                 m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                </div>
                <h2 class="text-sm font-bold text-slate-800 dark:text-slate-100">Edit Jadwal</h2>
            </div>
            <button onclick="closeModal('modalEditJadwal')"
                class="p-1.5 rounded-lg text-slate-400 hover:text-slate-600
                           hover:bg-slate-100 dark:hover:bg-slate-700 transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <div class="overflow-y-auto px-5 py-4 flex-1">
            <form id="formEditJadwal" method="POST" class="space-y-3">
                <?php echo csrf_field(); ?> <?php echo method_field('PUT'); ?>

                <div>
                    <label class="block text-[10px] font-semibold text-slate-500 dark:text-slate-400
                                  uppercase tracking-wide mb-1">
                        Mata Pelajaran <span class="text-red-500">*</span>
                    </label>
                    <select name="study_subject_id" id="editJadwalSubject" required
                        class="w-full rounded-xl border border-slate-200 dark:border-slate-600
                                   px-3 py-2 text-xs focus:outline-none focus:ring-2
                                   focus:ring-indigo-300 bg-white dark:bg-slate-700
                                   dark:text-slate-200 transition">
                        <?php $__currentLoopData = $studySubjects; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $subj): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($subj->id); ?>"><?php echo e($subj->name); ?> (<?php echo e($subj->code); ?>)</option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>

                <div>
                    <label class="block text-[10px] font-semibold text-slate-500 dark:text-slate-400
                                  uppercase tracking-wide mb-1">
                        Kelas <span class="text-red-500">*</span>
                    </label>
                    <select name="study_group_id" id="editJadwalGroup" required
                        class="w-full rounded-xl border border-slate-200 dark:border-slate-600
                                   px-3 py-2 text-xs focus:outline-none focus:ring-2
                                   focus:ring-indigo-300 bg-white dark:bg-slate-700
                                   dark:text-slate-200 transition">
                        <?php $__currentLoopData = $studyGroups; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $group): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($group->id); ?>"><?php echo e($group->name); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>

                <div>
                    <label class="block text-[10px] font-semibold text-slate-500 dark:text-slate-400
                                  uppercase tracking-wide mb-1">
                        Hari <span class="text-red-500">*</span>
                    </label>
                    <select name="day_of_week" id="editJadwalHari" required
                        class="w-full rounded-xl border border-slate-200 dark:border-slate-600
                                   px-3 py-2 text-xs focus:outline-none focus:ring-2
                                   focus:ring-indigo-300 bg-white dark:bg-slate-700
                                   dark:text-slate-200 transition">
                        <?php $__currentLoopData = ['Senin','Selasa','Rabu','Kamis','Jumat','Sabtu']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $h): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($h); ?>"><?php echo e($h); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-[10px] font-semibold text-slate-500 dark:text-slate-400
                                      uppercase tracking-wide mb-1">Jam Mulai</label>
                        <input type="time" name="start_time" id="editJadwalStart" required
                            class="w-full rounded-xl border border-slate-200 dark:border-slate-600
                                      px-3 py-2 text-xs focus:outline-none focus:ring-2
                                      focus:ring-indigo-300 bg-white dark:bg-slate-700
                                      dark:text-slate-200 transition">
                    </div>
                    <div>
                        <label class="block text-[10px] font-semibold text-slate-500 dark:text-slate-400
                                      uppercase tracking-wide mb-1">Jam Selesai</label>
                        <input type="time" name="end_time" id="editJadwalEnd" required
                            class="w-full rounded-xl border border-slate-200 dark:border-slate-600
                                      px-3 py-2 text-xs focus:outline-none focus:ring-2
                                      focus:ring-indigo-300 bg-white dark:bg-slate-700
                                      dark:text-slate-200 transition">
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-[10px] font-semibold text-slate-500 dark:text-slate-400
                                      uppercase tracking-wide mb-1">Ruang</label>
                        <input type="text" name="room" id="editJadwalRoom" maxlength="50"
                            class="w-full rounded-xl border border-slate-200 dark:border-slate-600
                                      px-3 py-2 text-xs focus:outline-none focus:ring-2
                                      focus:ring-indigo-300 bg-white dark:bg-slate-700
                                      dark:text-slate-200 transition">
                    </div>
                    <div>
                        <label class="block text-[10px] font-semibold text-slate-500 dark:text-slate-400
                                      uppercase tracking-wide mb-1">Sesi</label>
                        <select name="session_type" id="editJadwalSession" required
                            class="w-full rounded-xl border border-slate-200 dark:border-slate-600
                                       px-3 py-2 text-xs focus:outline-none focus:ring-2
                                       focus:ring-indigo-300 bg-white dark:bg-slate-700
                                       dark:text-slate-200 transition">
                            <option value="teori">Teori</option>
                            <option value="praktikum">Praktikum</option>
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-[10px] font-semibold text-slate-500 dark:text-slate-400
                                      uppercase tracking-wide mb-1">Tahun Ajaran</label>
                        <input type="text" name="academic_year" id="editJadwalYear"
                            maxlength="9" required
                            class="w-full rounded-xl border border-slate-200 dark:border-slate-600
                                      px-3 py-2 text-xs focus:outline-none focus:ring-2
                                      focus:ring-indigo-300 bg-white dark:bg-slate-700
                                      dark:text-slate-200 transition">
                    </div>
                    <div>
                        <label class="block text-[10px] font-semibold text-slate-500 dark:text-slate-400
                                      uppercase tracking-wide mb-1">Semester</label>
                        <select name="semester" id="editJadwalSemester" required
                            class="w-full rounded-xl border border-slate-200 dark:border-slate-600
                                       px-3 py-2 text-xs focus:outline-none focus:ring-2
                                       focus:ring-indigo-300 bg-white dark:bg-slate-700
                                       dark:text-slate-200 transition">
                            <option value="1">Semester 1</option>
                            <option value="2">Semester 2</option>
                        </select>
                    </div>
                </div>

                <div>
                    <label class="block text-[10px] font-semibold text-slate-500 dark:text-slate-400
                                  uppercase tracking-wide mb-1">Catatan</label>
                    <textarea name="notes" id="editJadwalNotes" rows="2" maxlength="500"
                        class="w-full rounded-xl border border-slate-200 dark:border-slate-600
                                     px-3 py-2 text-xs focus:outline-none focus:ring-2
                                     focus:ring-indigo-300 bg-white dark:bg-slate-700
                                     dark:text-slate-200 transition resize-none"></textarea>
                </div>

            </form>
        </div>

        <div class="flex gap-2 px-5 py-3.5 border-t border-slate-100 dark:border-slate-700
                    bg-slate-50/50 dark:bg-slate-900/20 rounded-b-2xl shrink-0">
            <button type="button" onclick="closeModal('modalEditJadwal')"
                class="flex-1 px-4 py-2 rounded-xl border border-slate-200 dark:border-slate-600
                           text-slate-600 dark:text-slate-400 text-xs font-medium
                           hover:bg-white dark:hover:bg-slate-700 transition">
                Batal
            </button>
            <button type="submit" form="formEditJadwal"
                class="flex-1 px-4 py-2 rounded-xl bg-amber-500 text-white text-xs font-semibold
                           hover:bg-amber-600 active:scale-95 transition">
                Simpan Perubahan
            </button>
        </div>
    </div>
</div>


<div id="modalHapusJadwal"
    class="fixed inset-0 z-50 hidden items-center justify-center p-4"
    role="dialog" aria-modal="true">
    <div class="absolute inset-0 bg-slate-900/50 backdrop-blur-sm"
        onclick="closeModal('modalHapusJadwal')"></div>
    <div class="relative bg-white dark:bg-slate-800 rounded-2xl shadow-2xl
                w-full max-w-xs p-5 animate-modal text-center">
        <div class="w-12 h-12 rounded-2xl bg-red-100 dark:bg-red-900/30
                    flex items-center justify-center mx-auto mb-3">
            <svg class="w-6 h-6 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732
                         4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
            </svg>
        </div>
        <h3 class="text-sm font-bold text-slate-800 dark:text-slate-100">Hapus Jadwal?</h3>
        <p class="text-xs text-slate-500 dark:text-slate-400 mt-1.5 mb-4 leading-relaxed">
            Jadwal <strong id="hapusJadwalName" class="text-slate-700 dark:text-slate-300"></strong>
            akan dihapus permanen.
        </p>
        <form id="formHapusJadwal" method="POST">
            <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
            <div class="flex gap-2">
                <button type="button" onclick="closeModal('modalHapusJadwal')"
                    class="flex-1 px-4 py-2 rounded-xl border border-slate-200 dark:border-slate-600
                               text-slate-600 dark:text-slate-400 text-xs font-medium
                               hover:bg-slate-50 dark:hover:bg-slate-700 transition">
                    Batal
                </button>
                <button type="submit"
                    class="flex-1 px-4 py-2 rounded-xl bg-red-600 text-white text-xs font-semibold
                               hover:bg-red-700 active:scale-95 transition">
                    Hapus
                </button>
            </div>
        </form>
    </div>
</div>

<?php $__env->startPush('styles'); ?>
<style>
    .animate-modal {
        animation: modalPop .22s cubic-bezier(.34, 1.56, .64, 1);
    }

    @keyframes modalPop {
        from {
            opacity: 0;
            transform: scale(.92) translateY(10px);
        }

        to {
            opacity: 1;
            transform: scale(1) translateY(0);
        }
    }
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
    const JADWAL_DATA = JSON.parse(document.getElementById('jadwalData').textContent);

    function openModal(id) {
        document.getElementById(id).classList.remove('hidden');
        document.getElementById(id).classList.add('flex');
        document.body.style.overflow = 'hidden';
    }

    function closeModal(id) {
        document.getElementById(id).classList.add('hidden');
        document.getElementById(id).classList.remove('flex');
        document.body.style.overflow = '';
    }
    document.addEventListener('keydown', e => {
        if (e.key === 'Escape')
            ['modalTambahJadwal', 'modalEditJadwal', 'modalHapusJadwal'].forEach(closeModal);
    });

    // Tambah jadwal dengan hari sudah terisi
    function openTambahHari(hari) {
        document.getElementById('tambahJadwalHari').value = hari;
        openModal('modalTambahJadwal');
    }

    // Edit jadwal
    function openEditJadwal(id) {
        const d = JADWAL_DATA[id];
        if (!d) return;
        document.getElementById('formEditJadwal').action =
            '<?php echo e(url("guru/jadwal-mengajar")); ?>/' + id;
        document.getElementById('editJadwalSubject').value = d.study_subject_id ?? '';
        document.getElementById('editJadwalGroup').value = d.study_group_id ?? '';
        document.getElementById('editJadwalHari').value = d.day_of_week ?? '';
        document.getElementById('editJadwalStart').value = d.start_time ?? '';
        document.getElementById('editJadwalEnd').value = d.end_time ?? '';
        document.getElementById('editJadwalRoom').value = d.room ?? '';
        document.getElementById('editJadwalSession').value = d.session_type ?? 'teori';
        document.getElementById('editJadwalYear').value = d.academic_year ?? '';
        document.getElementById('editJadwalSemester').value = d.semester ?? '1';
        document.getElementById('editJadwalNotes').value = d.notes ?? '';
        openModal('modalEditJadwal');
    }

    // Hapus jadwal
    function openDeleteJadwal(id, nama) {
        document.getElementById('hapusJadwalName').textContent = nama;
        document.getElementById('formHapusJadwal').action =
            '<?php echo e(url("guru/jadwal-mengajar")); ?>/' + id;
        openModal('modalHapusJadwal');
    }
</script>
<?php $__env->stopPush(); ?>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\PA 3\smpn-kutime\resources\views/guru/jadwal-mengajar/index.blade.php ENDPATH**/ ?>