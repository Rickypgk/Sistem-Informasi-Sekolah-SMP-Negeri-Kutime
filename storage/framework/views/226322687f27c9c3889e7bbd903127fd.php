

<?php
    $hariList  = ['Senin','Selasa','Rabu','Kamis','Jumat','Sabtu'];
    $jamSekarang = \Carbon\Carbon::now()->format('H:i');

    /**
     * Warna aksen per mapel — fallback ke indigo jika tidak ada warna.
     * Helper closure untuk menentukan apakah jadwal sedang berlangsung.
     */
    $isOngoing = function($tt) use ($jamSekarang) {
        return $jamSekarang >= substr($tt->start_time, 0, 5)
            && $jamSekarang <= substr($tt->end_time, 0, 5);
    };
?>


<div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200
            dark:border-slate-700 shadow-sm overflow-hidden">

    
    <div class="flex items-center justify-between px-4 py-3.5 border-b
                border-slate-100 dark:border-slate-700/60
                bg-gradient-to-r from-indigo-50 to-violet-50
                dark:from-indigo-900/10 dark:to-violet-900/10">
        <div class="flex items-center gap-2.5">
            <div class="w-8 h-8 rounded-xl bg-gradient-to-br from-indigo-500 to-violet-600
                        flex items-center justify-center shadow-sm">
                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <div>
                <h3 class="text-sm font-bold text-slate-800 dark:text-slate-100">Jadwal Hari Ini</h3>
                <p class="text-[10px] text-slate-400 leading-none mt-0.5">
                    <?php echo e($hariIni ?? '-'); ?> · <?php echo e(\Carbon\Carbon::now()->isoFormat('D MMM Y')); ?>

                </p>
            </div>
        </div>
        <?php if(Route::has('siswa.jadwal')): ?>
        <a href="<?php echo e(route('siswa.jadwal')); ?>"
           class="text-[11px] font-semibold text-indigo-600 hover:text-indigo-700
                  dark:text-indigo-400 flex items-center gap-0.5 transition-colors">
            Minggu ini
            <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
            </svg>
        </a>
        <?php endif; ?>
    </div>

    <?php if(!$studyGroup): ?>
    
    <div class="flex flex-col items-center justify-center py-10 text-center px-4">
        <div class="w-12 h-12 rounded-2xl bg-amber-100 dark:bg-amber-900/30
                    flex items-center justify-center mb-3">
            <svg class="w-6 h-6 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                      d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2
                         0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/>
            </svg>
        </div>
        <p class="text-xs font-semibold text-slate-600 dark:text-slate-300">Belum terdaftar di kelas</p>
        <p class="text-[10px] text-slate-400 mt-1">Hubungi admin atau wali kelas.</p>
    </div>

    <?php elseif($jadwalHariIni->isEmpty()): ?>
    
    <div class="flex flex-col items-center justify-center py-10 text-center px-4">
        <div class="text-4xl mb-2">🎉</div>
        <p class="text-xs font-semibold text-slate-600 dark:text-slate-300">
            Tidak ada pelajaran hari ini!
        </p>
        <?php if($hariBerikutnya && $jadwalBerikutnya->isNotEmpty()): ?>
        <p class="text-[10px] text-slate-400 mt-1">
            Jadwal berikutnya: <span class="font-semibold text-indigo-500"><?php echo e($hariBerikutnya); ?></span>
        </p>
        <?php endif; ?>
    </div>

    <?php else: ?>
    
    <div class="divide-y divide-slate-50 dark:divide-slate-700/30">
        <?php $__currentLoopData = $jadwalHariIni; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tt): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <?php
            $ongoing  = $isOngoing($tt);
            $colorHex = $tt->studySubject->color ?? '#6366f1';
            $startFmt = substr($tt->start_time, 0, 5);
            $endFmt   = substr($tt->end_time,   0, 5);
            $sudahLewat = $jamSekarang > substr($tt->end_time, 0, 5);
        ?>
        <div class="flex items-stretch gap-0 transition-colors
                    <?php echo e($ongoing
                        ? 'bg-indigo-50/60 dark:bg-indigo-900/10'
                        : ($sudahLewat ? 'opacity-50' : 'hover:bg-slate-50 dark:hover:bg-slate-700/20')); ?>">

            
            <div class="flex flex-col items-center justify-center px-4 py-3.5 shrink-0 min-w-[68px]
                        border-r border-slate-100 dark:border-slate-700/50">
                <span class="text-[11px] font-black text-slate-700 dark:text-slate-200">
                    <?php echo e($startFmt); ?>

                </span>
                <div class="w-px h-3 bg-slate-200 dark:bg-slate-600 my-0.5"></div>
                <span class="text-[10px] text-slate-400"><?php echo e($endFmt); ?></span>
            </div>

            
            <div class="w-1 shrink-0" style="background:<?php echo e($colorHex); ?>"></div>

            
            <div class="flex-1 min-w-0 px-3.5 py-3">
                <div class="flex items-start justify-between gap-2">
                    <div class="flex-1 min-w-0">
                        <p class="text-xs font-bold text-slate-800 dark:text-slate-100 leading-tight">
                            <?php echo e($tt->studySubject->name); ?>

                        </p>
                        <div class="flex items-center gap-1.5 mt-1 flex-wrap">
                            
                            <span class="inline-flex items-center gap-1 text-[10px] font-medium
                                         text-slate-600 dark:text-slate-300">
                                <svg class="w-2.5 h-2.5 text-slate-400" fill="none"
                                     stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                          stroke-width="2"
                                          d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                </svg>
                                <?php echo e($tt->teacher->name ?? '-'); ?>

                            </span>
                            <?php if($tt->room): ?>
                            <span class="text-[10px] text-slate-400">·</span>
                            <span class="text-[10px] text-slate-500 dark:text-slate-400">
                                <?php echo e($tt->room); ?>

                            </span>
                            <?php endif; ?>
                        </div>
                    </div>
                    
                    <div class="flex flex-col items-end gap-1 shrink-0">
                        <?php if($ongoing): ?>
                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full
                                     text-[9px] font-bold bg-indigo-600 text-white shadow-sm">
                            <span class="w-1.5 h-1.5 rounded-full bg-white animate-pulse"></span>
                            Sedang berlangsung
                        </span>
                        <?php elseif($sudahLewat): ?>
                        <span class="text-[9px] text-slate-400 font-medium">Selesai</span>
                        <?php endif; ?>
                        <span class="inline-flex px-1.5 py-0.5 rounded-md text-[9px] font-semibold
                                     <?php echo e($tt->session_type === 'praktikum'
                                         ? 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-400'
                                         : 'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400'); ?>">
                            <?php echo e(ucfirst($tt->session_type ?? 'Teori')); ?>

                        </span>
                    </div>
                </div>
            </div>
        </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>
    <?php endif; ?>

    
    <?php if($hariBerikutnya && $jadwalBerikutnya->isNotEmpty() && !$jadwalHariIni->isEmpty()): ?>
    <div class="px-4 py-3 border-t border-slate-100 dark:border-slate-700/50
                bg-slate-50/60 dark:bg-slate-900/20">
        <p class="text-[10px] font-semibold text-slate-500 dark:text-slate-400 mb-2 uppercase tracking-wide">
            Jadwal <?php echo e($hariBerikutnya); ?>

        </p>
        <div class="space-y-1.5">
            <?php $__currentLoopData = $jadwalBerikutnya->take(3); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $bt): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="flex items-center gap-2">
                <span class="w-1.5 h-1.5 rounded-full shrink-0"
                      style="background: <?php echo e($bt->studySubject->color ?? '#6366f1'); ?>"></span>
                <span class="text-[10px] font-semibold text-slate-600 dark:text-slate-300 truncate flex-1">
                    <?php echo e($bt->studySubject->name); ?>

                </span>
                <span class="text-[10px] text-slate-400 shrink-0 font-mono">
                    <?php echo e(substr($bt->start_time,0,5)); ?>

                </span>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            <?php if($jadwalBerikutnya->count() > 3): ?>
            <p class="text-[10px] text-slate-400 pl-4">
                +<?php echo e($jadwalBerikutnya->count() - 3); ?> sesi lainnya
            </p>
            <?php endif; ?>
        </div>
    </div>
    <?php endif; ?>
</div>


<?php if($studyGroup && isset($jadwalByDay) && $jadwalByDay->isNotEmpty()): ?>
<div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200
            dark:border-slate-700 shadow-sm overflow-hidden mt-4">

    <div class="px-4 py-3.5 border-b border-slate-100 dark:border-slate-700/60
                bg-gradient-to-r from-slate-50 to-gray-50 dark:from-slate-900/30 dark:to-gray-900/20">
        <h3 class="text-sm font-bold text-slate-800 dark:text-slate-100">Rekap Mingguan</h3>
        <p class="text-[10px] text-slate-400 mt-0.5">Kelas <?php echo e($studyGroup->name); ?></p>
    </div>

    <div class="p-4 space-y-2">
        <?php $__currentLoopData = $hariList; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $hari): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <?php $jHari = $jadwalByDay[$hari] ?? collect(); ?>
        <?php if($jHari->isNotEmpty()): ?>
        <div class="flex items-start gap-3 group">
            
            <div class="shrink-0 w-14 text-right">
                <span class="text-[10px] font-bold
                             <?php echo e($hari === $hariIniDb
                                 ? 'text-indigo-600 dark:text-indigo-400'
                                 : 'text-slate-400 dark:text-slate-500'); ?>">
                    <?php echo e(substr($hari, 0, 3)); ?>

                </span>
                <?php if($hari === $hariIniDb): ?>
                <div class="w-1 h-1 rounded-full bg-indigo-500 ml-auto mt-0.5"></div>
                <?php endif; ?>
            </div>
            
            <div class="flex-1 flex flex-wrap gap-1.5">
                <?php $__currentLoopData = $jHari->sortBy('start_time'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tt): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="inline-flex items-center gap-1 px-2 py-1 rounded-xl border
                            text-[10px] font-medium transition-shadow group-hover:shadow-sm
                            bg-white dark:bg-slate-700
                            border-slate-200 dark:border-slate-600"
                     style="border-left: 3px solid <?php echo e($tt->studySubject->color ?? '#6366f1'); ?>">
                    <span class="text-slate-700 dark:text-slate-200 truncate max-w-[90px]">
                        <?php echo e($tt->studySubject->name); ?>

                    </span>
                    <span class="text-slate-400 font-mono shrink-0">
                        <?php echo e(substr($tt->start_time,0,5)); ?>

                    </span>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
            
            <span class="shrink-0 text-[10px] text-slate-400 self-center">
                <?php echo e($jHari->count()); ?>×
            </span>
        </div>
        <?php endif; ?>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>
</div>
<?php endif; ?><?php /**PATH C:\PA 3\smpn-kutime\resources\views/siswa/dashboard/schedule.blade.php ENDPATH**/ ?>