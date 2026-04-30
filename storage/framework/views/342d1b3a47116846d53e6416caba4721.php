

<?php $__env->startSection('title', 'Jadwal Pelajaran'); ?>

<?php $__env->startSection('content'); ?>
<div class="space-y-4">

    
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
        <div>
            <h2 class="text-sm font-bold text-slate-800 dark:text-slate-100">Jadwal Pelajaran</h2>
            <p class="text-xs text-slate-400 dark:text-slate-500 mt-0.5">
                Jadwal pembelajaran kelas Anda minggu ini.
            </p>
        </div>
        
        <?php if($studyGroup): ?>
        <div class="flex items-center gap-2 px-3 py-2 rounded-xl bg-indigo-50
                        dark:bg-indigo-900/20 border border-indigo-100 dark:border-indigo-800">
            <svg class="w-3.5 h-3.5 text-indigo-500 shrink-0" fill="none"
                stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9
                             0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1
                             1 0 011 1v5m-4 0h4" />
            </svg>
            <div>
                <p class="text-[10px] font-bold text-indigo-700 dark:text-indigo-300">
                    Kelas <?php echo e($studyGroup->name); ?>

                </p>
                <p class="text-[9px] text-indigo-500 dark:text-indigo-400">
                    <?php echo e($studyGroup->academic_year); ?> · Sem <?php echo e($studyGroup->semester); ?>

                    <?php if($studyGroup->homeroomTeacher): ?>
                    · Wali: <?php echo e($studyGroup->homeroomTeacher->name); ?>

                    <?php endif; ?>
                </p>
            </div>
        </div>
        <?php endif; ?>
    </div>

    <?php if(!$studyGroup): ?>
    
    <div class="flex flex-col items-center justify-center py-16 text-center">
        <div class="w-14 h-14 rounded-2xl bg-amber-100 dark:bg-amber-900/30
                        flex items-center justify-center mb-3">
            <svg class="w-7 h-7 text-amber-500" fill="none" stroke="currentColor"
                viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                    d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2
                             0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z" />
            </svg>
        </div>
        <p class="text-xs font-semibold text-slate-600 dark:text-slate-300">
            Anda belum terdaftar di kelas apapun
        </p>
        <p class="text-[10px] text-slate-400 mt-1">
            Hubungi admin atau wali kelas untuk mendaftarkan Anda ke kelas.
        </p>
    </div>
    <?php else: ?>

    
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
                <?php echo e($totalMapel); ?>

            </p>
            <p class="text-[10px] text-slate-400 mt-0.5">Mata Pelajaran</p>
        </div>
        <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200
                        dark:border-slate-700 shadow-sm px-4 py-3">
            <p class="text-lg font-black text-amber-600 dark:text-amber-400 leading-none">
                <?php echo e($totalGuru); ?>

            </p>
            <p class="text-[10px] text-slate-400 mt-0.5">Guru Pengajar</p>
        </div>
        <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200
                        dark:border-slate-700 shadow-sm px-4 py-3">
            <p class="text-lg font-black text-sky-600 dark:text-sky-400 leading-none">
                <?php echo e($hariAktif); ?>

            </p>
            <p class="text-[10px] text-slate-400 mt-0.5">Hari Belajar</p>
        </div>
    </div>

    
    <?php $hariList = ['Senin','Selasa','Rabu','Kamis','Jumat','Sabtu']; ?>

    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-4">
        <?php $__currentLoopData = $hariList; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $hari): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <?php $jadwalHari = $jadwalByDay[$hari] ?? collect(); ?>

        
        <?php if($jadwalHari->isEmpty()): ?>
        <?php continue; ?>
        <?php endif; ?>

        <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200
                            dark:border-slate-700 shadow-sm overflow-hidden flex flex-col">

            
            <div class="flex items-center justify-between px-3.5 py-3 border-b
                                border-slate-100 dark:border-slate-700
                                bg-gradient-to-r from-indigo-50 to-slate-50
                                dark:from-indigo-900/20 dark:to-slate-900/10">
                <div class="flex items-center gap-2">
                    <div class="w-1.5 h-5 rounded-full bg-indigo-500"></div>
                    <span class="text-xs font-bold text-slate-800 dark:text-slate-100">
                        <?php echo e($hari); ?>

                    </span>
                </div>
                <span class="text-[10px] font-semibold px-2 py-0.5 rounded-full
                                     bg-indigo-100 text-indigo-700 dark:bg-indigo-900/40
                                     dark:text-indigo-300">
                    <?php echo e($jadwalHari->count()); ?> sesi
                </span>
            </div>

            
            <div class="divide-y divide-slate-50 dark:divide-slate-700/30 flex-1">
                <?php $__currentLoopData = $jadwalHari->sortBy('start_time'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tt): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="flex items-start gap-3 px-3.5 py-3">

                    
                    <div class="flex flex-col items-center shrink-0 pt-0.5">
                        <span class="text-[10px] font-bold text-slate-600
                                                 dark:text-slate-300">
                            <?php echo e(substr($tt->start_time,0,5)); ?>

                        </span>
                        <div class="w-px h-4 bg-slate-200 dark:bg-slate-600 my-0.5"></div>
                        <span class="text-[9px] text-slate-400">
                            <?php echo e(substr($tt->end_time,0,5)); ?>

                        </span>
                    </div>

                    
                    <div class="w-1 self-stretch rounded-full shrink-0"
                        style="background: <?php echo e($tt->studySubject->color ?? '#6366f1'); ?>">
                    </div>

                    
                    <div class="flex-1 min-w-0">
                        <p class="text-xs font-bold text-slate-800 dark:text-slate-100
                                              leading-tight">
                            <?php echo e($tt->studySubject->name); ?>

                        </p>
                        <p class="text-[10px] text-slate-400 mt-0.5">
                            <span class="font-medium text-slate-600 dark:text-slate-300">
                                <?php echo e($tt->teacher->name); ?>

                            </span>
                            <?php if($tt->room): ?>
                            · <?php echo e($tt->room); ?>

                            <?php endif; ?>
                        </p>
                        <div class="flex items-center gap-1.5 mt-1 flex-wrap">
                            <span class="inline-flex px-1.5 py-0.5 rounded-md text-[9px]
                                                     font-semibold
                                                     <?php echo e($tt->session_type === 'praktikum'
                                                        ? 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-400'
                                                        : 'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400'); ?>">
                                <?php echo e(ucfirst($tt->session_type ?? 'Teori')); ?>

                            </span>
                            <span class="text-[9px] text-slate-400">
                                <?php echo e($tt->studySubject->code); ?>

                            </span>
                        </div>
                    </div>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

        
        <?php if($totalJadwal === 0): ?>
        <div class="col-span-full flex flex-col items-center justify-center py-14 text-center">
            <div class="w-14 h-14 rounded-2xl bg-slate-100 dark:bg-slate-700
                                flex items-center justify-center mb-3">
                <svg class="w-7 h-7 text-slate-300 dark:text-slate-600" fill="none"
                    stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                        d="M8 7V3m8 4V3M5 11h14M5 19h14M5 5h2m10 0h2" />
                </svg>
            </div>
            <p class="text-xs font-semibold text-slate-500 dark:text-slate-400">
                Jadwal pelajaran belum tersedia
            </p>
            <p class="text-[10px] text-slate-400 mt-0.5">
                Guru belum mengatur jadwal untuk kelas Anda.
            </p>
        </div>
        <?php endif; ?>
    </div>

    
    <?php if($mataPelajaran->isNotEmpty()): ?>
    <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200
                        dark:border-slate-700 shadow-sm overflow-hidden">

        <div class="px-4 py-3 border-b border-slate-100 dark:border-slate-700
                            bg-slate-50 dark:bg-slate-900/30">
            <h3 class="text-xs font-bold text-slate-700 dark:text-slate-200">
                Mata Pelajaran Kelas <?php echo e($studyGroup->name); ?>

            </h3>
            <p class="text-[10px] text-slate-400 mt-0.5">
                <?php echo e($mataPelajaran->count()); ?> mata pelajaran · <?php echo e($studyGroup->academic_year); ?>

            </p>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="bg-slate-50 dark:bg-slate-900/40 border-b border-slate-100
                                       dark:border-slate-700">
                        <th class="px-3 py-2 text-[10px] font-semibold text-slate-500
                                           uppercase tracking-wide text-left">#</th>
                        <th class="px-3 py-2 text-[10px] font-semibold text-slate-500
                                           uppercase tracking-wide text-left min-w-[140px]">
                            Mata Pelajaran
                        </th>
                        <th class="px-3 py-2 text-[10px] font-semibold text-slate-500
                                           uppercase tracking-wide text-left">Kode</th>
                        <th class="px-3 py-2 text-[10px] font-semibold text-slate-500
                                           uppercase tracking-wide text-left min-w-[140px]">
                            Guru Pengajar
                        </th>
                        <th class="px-3 py-2 text-[10px] font-semibold text-slate-500
                                           uppercase tracking-wide text-left">Sesi / Minggu</th>
                        <th class="px-3 py-2 text-[10px] font-semibold text-slate-500
                                           uppercase tracking-wide text-left">Tipe</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50 dark:divide-slate-700/30">
                    <?php $__currentLoopData = $mataPelajaran; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $idx => $mp): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr class="hover:bg-slate-50 dark:hover:bg-slate-700/20 transition">
                        <td class="px-3 py-2.5 text-[10px] text-slate-400"><?php echo e($idx + 1); ?></td>
                        <td class="px-3 py-2.5">
                            <div class="flex items-center gap-2">
                                <div class="w-2 h-2 rounded-full shrink-0"
                                    style="background: <?php echo e($mp->color ?? '#6366f1'); ?>"></div>
                                <span class="text-xs font-semibold text-slate-800
                                                         dark:text-slate-100">
                                    <?php echo e($mp->name); ?>

                                </span>
                            </div>
                        </td>
                        <td class="px-3 py-2.5">
                            <span class="font-mono text-[10px] text-slate-500
                                                     bg-slate-100 dark:bg-slate-700 px-1.5 py-0.5
                                                     rounded-md">
                                <?php echo e($mp->code); ?>

                            </span>
                        </td>
                        <td class="px-3 py-2.5">
                            <?php
                            $guruMapel = $allTimetables
                            ->where('study_subject_id', $mp->id)
                            ->pluck('teacher.name')
                            ->unique()
                            ->filter();
                            ?>
                            <?php $__currentLoopData = $guruMapel; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $namaGuru): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <p class="text-[10px] text-slate-600 dark:text-slate-400
                                                      leading-tight">
                                <?php echo e($namaGuru); ?>

                            </p>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            <?php if($guruMapel->isEmpty()): ?>
                            <span class="text-slate-300 dark:text-slate-600 text-[10px]">—</span>
                            <?php endif; ?>
                        </td>
                        <td class="px-3 py-2.5">
                            <?php
                            $sesiCount = $allTimetables
                            ->where('study_subject_id', $mp->id)
                            ->count();
                            ?>
                            <span class="inline-flex items-center justify-center w-6 h-5
                                                     rounded-full text-[10px] font-bold
                                                     bg-indigo-100 text-indigo-700 dark:bg-indigo-900/40
                                                     dark:text-indigo-300">
                                <?php echo e($sesiCount); ?>

                            </span>
                        </td>
                        <td class="px-3 py-2.5">
                            <span class="inline-flex px-1.5 py-0.5 rounded-md text-[9px]
                                                     font-semibold
                                                     <?php echo e($mp->type === 'core'
                                                        ? 'bg-indigo-100 text-indigo-700 dark:bg-indigo-900/30 dark:text-indigo-400'
                                                        : 'bg-slate-100 text-slate-600 dark:bg-slate-700 dark:text-slate-400'); ?>">
                                <?php echo e($mp->type === 'core' ? 'Wajib' : 'Pilihan'); ?>

                            </span>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
            </table>
        </div>
    </div>
    <?php endif; ?>

    <?php endif; ?> 

</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\PA 3\smpn-kutime\resources\views/siswa/jadwal-pelajaran/index.blade.php ENDPATH**/ ?>