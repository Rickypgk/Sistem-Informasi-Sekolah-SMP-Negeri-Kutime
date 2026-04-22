
<div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 shadow-sm overflow-hidden">
    
    
    <div class="px-5 py-4 border-b border-slate-100 dark:border-slate-700 bg-slate-50 dark:bg-slate-900/50 flex items-center justify-between">
        <div class="flex items-center gap-3">
            <div class="w-8 h-8 rounded-xl bg-indigo-100 dark:bg-indigo-900/40 flex items-center justify-center">
                <span class="text-indigo-600 dark:text-indigo-400 text-lg">📅</span>
            </div>
            <div>
                <h3 class="font-semibold text-slate-800 dark:text-slate-100">Jadwal Mengajar Hari Ini</h3>
                <p class="text-xs text-slate-500 dark:text-slate-400">
                    <?php echo e(now()->locale('id')->translatedFormat('l, d F Y')); ?>

                </p>
            </div>
        </div>
        
        <a href="<?php echo e(route('guru.jadwal-mengajar.index')); ?>" 
           class="text-xs font-medium text-indigo-600 hover:text-indigo-700 dark:text-indigo-400 flex items-center gap-1">
            Lihat Semua <span class="text-lg leading-none">→</span>
        </a>
    </div>

    
    <div class="divide-y divide-slate-100 dark:divide-slate-700">
        <?php $__empty_1 = true; $__currentLoopData = $jadwalHariIni; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $jadwal): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
        <div class="px-5 py-4 flex gap-4 items-start hover:bg-slate-50 dark:hover:bg-slate-700/30 transition">
            
            
            <div class="text-right w-16 shrink-0">
                <div class="text-xs font-mono text-slate-500 dark:text-slate-400">
                    <?php echo e(substr($jadwal->start_time ?? '', 0, 5)); ?>

                </div>
                <div class="text-xs text-slate-400 mt-0.5">—</div>
                <div class="text-xs font-mono text-slate-500 dark:text-slate-400">
                    <?php echo e(substr($jadwal->end_time ?? '', 0, 5)); ?>

                </div>
            </div>

            
            <div class="flex-1 min-w-0">
                <div class="flex items-center gap-2">
                    <div class="w-3 h-3 rounded-full shrink-0" 
                         style="background-color: <?php echo e($jadwal->studySubject->color ?? '#6366f1'); ?>"></div>
                    <p class="font-medium text-slate-800 dark:text-slate-100 truncate">
                        <?php echo e($jadwal->studySubject->name ?? 'Mata Pelajaran Tidak Diketahui'); ?>

                    </p>
                </div>
                
                <p class="text-sm text-slate-600 dark:text-slate-300 mt-1">
                    <?php echo e($jadwal->studyGroup->name ?? 'Kelas Tidak Diketahui'); ?>

                    <?php if($jadwal->room): ?>
                        <span class="text-slate-400"> • Ruang <?php echo e($jadwal->room); ?></span>
                    <?php endif; ?>
                </p>

                <div class="mt-2">
                    <span class="inline-block px-2.5 py-0.5 text-[10px] font-medium rounded-full 
                                 <?php echo e($jadwal->session_type === 'praktikum' 
                                    ? 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-400' 
                                    : 'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400'); ?>">
                        <?php echo e(ucfirst($jadwal->session_type ?? 'teori')); ?>

                    </span>
                </div>
            </div>
        </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
        <div class="px-5 py-12 text-center">
            <p class="text-slate-400 dark:text-slate-500 text-sm">
                Tidak ada jadwal mengajar hari ini.
            </p>
            <a href="<?php echo e(route('guru.jadwal-mengajar.index')); ?>" 
               class="mt-4 inline-block text-indigo-600 hover:underline text-xs font-medium">
                Kelola Jadwal Mengajar →
            </a>
        </div>
        <?php endif; ?>
    </div>
</div><?php /**PATH C:\PA 3\smpn-kutime\resources\views/guru/dashboard/jadwal-mengajar.blade.php ENDPATH**/ ?>